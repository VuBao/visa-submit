const MAX_FILE_SIZE = 10 * 1024 * 1024;
const PIN_HASH_ITERATIONS = 100000;
const ALLOWED = new Map([
  ["image/jpeg", ["jpg", "jpeg"]],
  ["image/png", ["png"]],
  ["image/webp", ["webp"]],
  ["application/pdf", ["pdf"]],
]);
const DOCUMENTS = {
  residence_card_front: [
    "在留カード・表面",
    "Thẻ ngoại kiều - mặt trước",
    false,
  ],
  residence_card_back: ["在留カード・裏面", "Thẻ ngoại kiều - mặt sau", false],
  passport_vietnam: [
    "パスポート・身分事項ページ",
    "Hộ chiếu Việt Nam - trang thông tin",
    false,
  ],
  passport_residence_status: [
    "パスポート・在留資格ページ",
    "Hộ chiếu - trang tư cách lưu trú Nhật Bản",
    false,
  ],
  insurance_front: ["保険証・表面", "Thẻ bảo hiểm - mặt trước", false],
  insurance_back: ["保険証・裏面", "Thẻ bảo hiểm - mặt sau", false],
  sankyu_senmonkyu: ["三級・専門級", "Chứng chỉ SANKYU hoặc SENMONKYU", false],
  tokutei_certificate: [
    "特定技能合格証",
    "Chứng chỉ Tokutei chuyên ngành",
    false,
    true,
  ],
  jlpt_certificate: ["日本語能力試験（JLPT）", "Chứng chỉ tiếng Nhật JLPT", false],
  gensen: ["源泉徴収票", "Phiếu khấu trừ thuế Gensen", false],
  tax_certificate: ["課税証明書", "Giấy chứng nhận thuế năm gần nhất", false],
  tax_payment_certificate: ["納税証明書", "Giấy chứng nhận đã đóng thuế", false],
  juminhyo_mynumber: ["マイナンバー付の住民票", "Juminhyo có MyNumber", false],
  nenkin_record: ["年金記録照会", "Giấy tra cứu lịch sử Nenkin", false],
  insured_record_nofu2: [
    "被保険者記録照会（納付II）",
    "Lịch sử đóng Nenkin",
    false,
  ],
  health_check: ["健康診断書", "Giấy khám sức khỏe", false, true],
  photo_3x4: ["証明写真 3×4", "Ảnh thẻ 3×4", false],
  kokumin_payment: [
    "国民健康保険料納付証明書",
    "Giấy đóng bảo hiểm quốc dân",
    false,
  ],
  student_documents: [
    "留学生の追加書類",
    "Giấy tờ bổ sung cho du học sinh",
    false,
  ],
  student_graduation: [
    "卒業証明書（見込み可）",
    "Bằng tốt nghiệp (có thể dùng giấy dự kiến tốt nghiệp)",
    false,
  ],
  student_transcript: [
    "成績・出席証明書 / 推薦状",
    "Bảng điểm, chuyên cần hoặc thư giới thiệu",
    false,
  ],
};

const json = (body, status = 200) =>
  new Response(JSON.stringify(body), {
    status,
    headers: {
      "content-type": "application/json; charset=utf-8",
      "access-control-allow-origin": "*",
      "access-control-allow-methods": "GET, PATCH, POST, DELETE, OPTIONS",
      "access-control-allow-headers": "content-type",
    },
  });

function b64url(bytes) {
  let s = "";
  for (const b of bytes) s += String.fromCharCode(b);
  return btoa(s).replace(/\+/g, "-").replace(/\//g, "_").replace(/=+$/, "");
}

function randomPin() {
  const bytes = new Uint32Array(1);
  crypto.getRandomValues(bytes);
  return String(bytes[0] % 1000000).padStart(6, "0");
}

function randomSalt() {
  const bytes = new Uint8Array(16);
  crypto.getRandomValues(bytes);
  return b64url(bytes);
}

async function pinHash(pin, salt) {
  const material = await crypto.subtle.importKey(
    "raw",
    new TextEncoder().encode(pin),
    "PBKDF2",
    false,
    ["deriveBits"],
  );
  const bits = await crypto.subtle.deriveBits(
    {
      name: "PBKDF2",
      hash: "SHA-256",
      salt: new TextEncoder().encode(salt),
      iterations: PIN_HASH_ITERATIONS,
    },
    material,
    256,
  );
  return b64url(new Uint8Array(bits));
}

function sameText(left, right) {
  const a = new TextEncoder().encode(String(left));
  const b = new TextEncoder().encode(String(right));
  if (a.length !== b.length) return false;
  let different = 0;
  for (let index = 0; index < a.length; index++) different |= a[index] ^ b[index];
  return different === 0;
}

async function authenticateApplication(env, applicationCode, pin) {
  const application = await env.DB.prepare(
    "SELECT * FROM applications WHERE application_code = ?",
  )
    .bind(applicationCode)
    .first();
  if (!application) return { error: "Mã hồ sơ hoặc PIN không đúng.", status: 401 };
  const now = Date.now();
  if (Number(application.locked_until || 0) > now)
    return {
      error: "Đã nhập sai quá nhiều lần. Vui lòng thử lại sau 15 phút.",
      status: 429,
    };
  const valid = sameText(await pinHash(pin, application.pin_salt), application.pin_hash);
  if (!valid) {
    const attempts = Number(application.failed_attempts || 0) + 1;
    const lockedUntil = attempts >= 5 ? now + 15 * 60 * 1000 : null;
    await env.DB.prepare(
      "UPDATE applications SET failed_attempts = ?, locked_until = ? WHERE application_code = ?",
    )
      .bind(attempts >= 5 ? 0 : attempts, lockedUntil, applicationCode)
      .run();
    return { error: "Mã hồ sơ hoặc PIN không đúng.", status: 401 };
  }
  if (application.failed_attempts || application.locked_until)
    await env.DB.prepare(
      "UPDATE applications SET failed_attempts = 0, locked_until = NULL WHERE application_code = ?",
    )
      .bind(applicationCode)
      .run();
  return { application };
}

function savedDocuments(application) {
  try {
    const documents = JSON.parse(application.documents_json || "[]");
    return Array.isArray(documents) ? documents : [];
  } catch (_) {
    return [];
  }
}

function publicApplication(application) {
  return {
    application_code: application.application_code,
    full_name: application.full_name,
    company_name: application.company_name,
    updated_at: application.updated_at,
    documents: savedDocuments(application).map((item) => ({
      type: item.type,
      name: item.name,
      mime_type: item.mimeType,
    })),
  };
}

function pemBytes(pem) {
  let normalized = String(pem).trim();
  if (normalized.startsWith("{")) {
    try {
      normalized = JSON.parse(normalized).private_key || normalized;
    } catch (_) {
      /* Validate below. */
    }
  }
  normalized = normalized
    .replace(/^['"]|['"]$/g, "")
    .replace(/\\n/g, "\n")
    .replace(/\\r/g, "");
  const raw = atob(
    normalized.replace(/-----[^-]+-----/g, "").replace(/\s/g, ""),
  );
  return Uint8Array.from(raw, (c) => c.charCodeAt(0));
}

async function serviceAccountAccessToken(env) {
  const now = Math.floor(Date.now() / 1000);
  const head = b64url(
    new TextEncoder().encode(JSON.stringify({ alg: "RS256", typ: "JWT" })),
  );
  const claim = b64url(
    new TextEncoder().encode(
      JSON.stringify({
        iss: env.GOOGLE_SERVICE_ACCOUNT_EMAIL,
        scope:
          "https://www.googleapis.com/auth/drive https://www.googleapis.com/auth/spreadsheets",
        aud: "https://oauth2.googleapis.com/token",
        iat: now,
        exp: now + 3600,
      }),
    ),
  );
  const key = await crypto.subtle.importKey(
    "pkcs8",
    pemBytes(env.GOOGLE_PRIVATE_KEY),
    { name: "RSASSA-PKCS1-v1_5", hash: "SHA-256" },
    false,
    ["sign"],
  );
  const sig = await crypto.subtle.sign(
    "RSASSA-PKCS1-v1_5",
    key,
    new TextEncoder().encode(`${head}.${claim}`),
  );
  const response = await fetch("https://oauth2.googleapis.com/token", {
    method: "POST",
    headers: { "content-type": "application/x-www-form-urlencoded" },
    body: `grant_type=urn%3Aietf%3Aparams%3Aoauth%3Agrant-type%3Ajwt-bearer&assertion=${head}.${claim}.${b64url(new Uint8Array(sig))}`,
  });
  if (!response.ok) throw new Error("Google authentication failed");
  return (await response.json()).access_token;
}

async function oauthAccessToken(env) {
  const body = new URLSearchParams({
    client_id: env.GOOGLE_OAUTH_CLIENT_ID,
    client_secret: env.GOOGLE_OAUTH_CLIENT_SECRET,
    refresh_token: env.GOOGLE_OAUTH_REFRESH_TOKEN,
    grant_type: "refresh_token",
  });
  const response = await fetch("https://oauth2.googleapis.com/token", {
    method: "POST",
    headers: { "content-type": "application/x-www-form-urlencoded" },
    body,
  });
  if (!response.ok) {
    const detail = await response.json().catch(() => null);
    throw new Error(
      `Google OAuth authentication failed (${response.status}): ${detail?.error || "Unknown error"}`,
    );
  }
  return (await response.json()).access_token;
}

async function accessToken(env) {
  if (
    env.GOOGLE_OAUTH_CLIENT_ID &&
    env.GOOGLE_OAUTH_CLIENT_SECRET &&
    env.GOOGLE_OAUTH_REFRESH_TOKEN
  ) {
    try {
      return await oauthAccessToken(env);
    } catch (error) {
      // The OAuth refresh token may be revoked or expire. The service account
      // is deliberately configured as the durable integration identity and
      // has access only to the shared Sheet and private Drive folder.
      if (env.GOOGLE_SERVICE_ACCOUNT_EMAIL && env.GOOGLE_PRIVATE_KEY) {
        console.warn("Google OAuth refresh failed; using service account.");
        return serviceAccountAccessToken(env);
      }
      throw error;
    }
  }
  return serviceAccountAccessToken(env);
}

async function uploadAccessToken(env) {
  if (
    env.GOOGLE_OAUTH_CLIENT_ID &&
    env.GOOGLE_OAUTH_CLIENT_SECRET &&
    env.GOOGLE_OAUTH_REFRESH_TOKEN
  )
    return oauthAccessToken(env);
  return serviceAccountAccessToken(env);
}

async function driveUpload(token, env, item, name, folder) {
  const boundary = `visa-${crypto.randomUUID()}`;
  const meta = JSON.stringify({ name, parents: [folder] });
  const pre = new TextEncoder().encode(
    `--${boundary}\r\nContent-Type: application/json; charset=UTF-8\r\n\r\n${meta}\r\n--${boundary}\r\nContent-Type: ${item.file.type}\r\n\r\n`,
  );
  const post = new TextEncoder().encode(`\r\n--${boundary}--`);
  const body = new Uint8Array(pre.length + item.bytes.length + post.length);
  body.set(pre);
  body.set(item.bytes, pre.length);
  body.set(post, pre.length + item.bytes.length);
  const response = await fetch(
    "https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart&supportsAllDrives=true&fields=id,name,mimeType,size",
    {
      method: "POST",
      headers: {
        Authorization: `Bearer ${token}`,
        "content-type": `multipart/related; boundary=${boundary}`,
      },
      body,
    },
  );
  if (!response.ok) {
    const detail = await response.json().catch(() => null);
    throw new Error(
      `Google Drive upload failed (${response.status}): ${detail?.error?.message || "Unknown error"}`,
    );
  }
  return response.json();
}

async function createDriveFolder(token, name, parent) {
  const response = await fetch(
    "https://www.googleapis.com/drive/v3/files?supportsAllDrives=true&fields=id,name,mimeType,webViewLink",
    {
      method: "POST",
      headers: {
        Authorization: `Bearer ${token}`,
        "content-type": "application/json",
      },
      body: JSON.stringify({
        name,
        mimeType: "application/vnd.google-apps.folder",
        parents: [parent],
      }),
    },
  );
  if (!response.ok) {
    const detail = await response.json().catch(() => null);
    throw new Error(
      `Google Drive folder creation failed (${response.status}): ${detail?.error?.message || "Unknown error"}`,
    );
  }
  return response.json();
}

async function appendSheet(token, env, row) {
  const range = "'Dashboard'!A:BA";
  const url = `https://sheets.googleapis.com/v4/spreadsheets/${encodeURIComponent(env.GOOGLE_SHEET_ID)}/values/${encodeURIComponent(range)}:append?valueInputOption=USER_ENTERED&insertDataOption=INSERT_ROWS`;
  const response = await fetch(url, {
    method: "POST",
    headers: {
      Authorization: `Bearer ${token}`,
      "content-type": "application/json",
    },
    body: JSON.stringify({ values: [row] }),
  });
  if (!response.ok) throw new Error("Google Sheets write failed");
}

async function sheetsRequest(token, env, path, init = {}) {
  const response = await fetch(
    `https://sheets.googleapis.com/v4/spreadsheets/${encodeURIComponent(env.GOOGLE_SHEET_ID)}${path}`,
    {
      ...init,
      headers: {
        Authorization: `Bearer ${token}`,
        "content-type": "application/json",
        ...(init.headers || {}),
      },
    },
  );
  if (!response.ok)
    throw new Error(`Google Sheets API failed (${response.status})`);
  return response.status === 204 ? null : response.json();
}

function sheetTitle(value) {
  return (
    String(value || "Ứng viên")
      .replace(/[\\/*?:\[\]]/g, "-")
      .trim()
      .slice(0, 80) || "Ứng viên"
  );
}

async function createApplicantTab(token, env, profile, uploaded) {
  const meta = await sheetsRequest(
    token,
    env,
    "?fields=sheets(properties(sheetId,title,index))",
  );
  const sheets = meta.sheets || [];
  let dashboard = sheets.find((item) => item.properties?.title === "Dashboard");
  const requests = [];
  if (!dashboard) {
    requests.push({
      addSheet: { properties: { title: "Dashboard", index: 0 } },
    });
  } else if (dashboard.properties.index !== 0) {
    requests.push({
      updateSheetProperties: {
        properties: { sheetId: dashboard.properties.sheetId, index: 0 },
        fields: "index",
      },
    });
  }
  const base = sheetTitle(
    `${profile.fullName} - ${profile.submissionId.slice(0, 8)}`,
  );
  const used = new Set(sheets.map((item) => item.properties?.title));
  let title = base;
  let n = 2;
  while (used.has(title)) title = sheetTitle(`${base.slice(0, 74)} (${n++})`);
  requests.push({ addSheet: { properties: { title, index: 1 } } });
  const result = await sheetsRequest(token, env, ":batchUpdate", {
    method: "POST",
    body: JSON.stringify({ requests }),
  });
  const added = (result.replies || [])
    .slice()
    .reverse()
    .find((reply) => reply.addSheet)?.addSheet?.properties;
  if (!added) throw new Error("Applicant tab creation failed");
  const tabId = added.sheetId;
  const rows = [
    ["Hồ sơ visa / ビザ申請", ""],
    ["Mã hồ sơ", profile.submissionId],
    ["Họ và tên", profile.fullName],
    ["Công ty", profile.companyName],
    ["Ngày gửi", profile.submittedAt],
    ["Trạng thái", "Mới / New"],
    ["", ""],
    ["Tài liệu", "Tên file", "Preview / Xem", "Download / Tải", "Ảnh"],
  ];
  for (const item of uploaded) {
    const preview = `https://drive.google.com/file/d/${encodeURIComponent(item.file.id)}/view`;
    const download = `https://drive.google.com/uc?export=download&id=${encodeURIComponent(item.file.id)}`;
    rows.push([
      item.def[1],
      item.file.name,
      `=HYPERLINK("${preview}","Mở preview")`,
      `=HYPERLINK("${download}","Download")`,
      item.file.mimeType.startsWith("image/")
        ? `=IMAGE("https://drive.google.com/uc?export=view&id=${encodeURIComponent(item.file.id)}")`
        : "",
    ]);
  }
  const quotedTitle = `'${title.replace(/'/g, "''")}'`;
  await sheetsRequest(
    token,
    env,
    `/values/${encodeURIComponent(`${quotedTitle}!A1:E${rows.length}`)}?valueInputOption=USER_ENTERED`,
    { method: "PUT", body: JSON.stringify({ values: rows }) },
  );
  return { title, tabId };
}

async function deleteDriveFile(token, fileId) {
  const response = await fetch(
    `https://www.googleapis.com/drive/v3/files/${encodeURIComponent(fileId)}?supportsAllDrives=true`,
    { method: "DELETE", headers: { Authorization: `Bearer ${token}` } },
  );
  if (!response.ok && response.status !== 404)
    throw new Error(`Google Drive delete failed (${response.status})`);
}

async function driveParentFolder(token, fileId) {
  const response = await fetch(
    `https://www.googleapis.com/drive/v3/files/${encodeURIComponent(fileId)}?fields=parents&supportsAllDrives=true`,
    { headers: { Authorization: `Bearer ${token}` } },
  );
  if (!response.ok) return null;
  const data = await response.json();
  return data.parents?.[0] || null;
}

async function clearDashboardRow(token, env, rowNumber) {
  const url = `https://sheets.googleapis.com/v4/spreadsheets/${encodeURIComponent(env.GOOGLE_SHEET_ID)}/values/${encodeURIComponent(`'Dashboard'!A${rowNumber}:BA${rowNumber}`)}:clear`;
  const response = await fetch(url, {
    method: "POST",
    headers: { Authorization: `Bearer ${token}`, "content-type": "application/json" },
    body: "{}",
  });
  if (!response.ok) throw new Error("Google Sheets delete failed");
}

async function deleteApplicantTab(token, env, applicationCode) {
  const meta = await sheetsRequest(
    token,
    env,
    "?fields=sheets(properties(sheetId,title))",
  );
  const suffix = applicationCode.slice(0, 8);
  const tab = (meta.sheets || []).find(
    (sheet) =>
      sheet.properties?.title !== "Dashboard" &&
      sheet.properties?.title?.endsWith(suffix),
  );
  if (!tab?.properties?.sheetId) return;
  await sheetsRequest(token, env, ":batchUpdate", {
    method: "POST",
    body: JSON.stringify({
      requests: [{ deleteSheet: { sheetId: tab.properties.sheetId } }],
    }),
  });
}

function adminIdentity(request, env) {
  const email = request.headers.get("Cf-Access-Authenticated-User-Email") || "";
  const allow = String(env.ADMIN_EMAILS || "")
    .split(",")
    .map((value) => value.trim().toLowerCase())
    .filter(Boolean);
  if (!email || (allow.length && !allow.includes(email.toLowerCase())))
    return null;
  return email;
}

async function dashboardRows(token, env) {
  const url = `https://sheets.googleapis.com/v4/spreadsheets/${encodeURIComponent(env.GOOGLE_SHEET_ID)}/values/${encodeURIComponent("'Dashboard'!A1:BA1000")}?valueRenderOption=FORMATTED_VALUE`;
  const response = await fetch(url, {
    headers: { Authorization: `Bearer ${token}` },
  });
  if (!response.ok) throw new Error("Google Sheets read failed");
  return (await response.json()).values || [];
}

function dashboardDocumentCells(submissionId, folderId, documents) {
  const metadata = [`submission_id:${submissionId}`]
    .concat(
      documents.map(
        (item) => `${item.type}:${item.id}:${item.name}:${item.mimeType}`,
      ),
    )
    .join(" | ");
  const folderUrl = `https://drive.google.com/drive/folders/${encodeURIComponent(folderId)}`;
  const fileColumns = [];
  for (const [type] of Object.entries(DOCUMENTS)) {
    const item = documents.find((document) => document.type === type);
    if (!item) {
      fileColumns.push("", "");
      continue;
    }
    const previewUrl = `https://drive.google.com/file/d/${encodeURIComponent(item.id)}/view`;
    const downloadUrl = `https://drive.google.com/uc?export=download&id=${encodeURIComponent(item.id)}`;
    fileColumns.push(
      `=HYPERLINK("${previewUrl}","Preview / Xem")`,
      `=HYPERLINK("${downloadUrl}","Download / Tải")`,
    );
  }
  return [
    metadata,
    `=HYPERLINK("${folderUrl}","Folder hồ sơ")`,
    ...fileColumns,
  ];
}

async function updateDashboardDocuments(
  token,
  env,
  applicationCode,
  submissionId,
  folderId,
  documents,
) {
  const rows = await dashboardRows(token, env);
  const index = rows.findIndex((row) => row[0] === applicationCode);
  if (index < 0) throw new Error("Application row not found");
  const rowNumber = index + 1;
  await sheetsRequest(
    token,
    env,
    `/values/${encodeURIComponent(`'Dashboard'!F${rowNumber}:AW${rowNumber}`)}?valueInputOption=USER_ENTERED`,
    {
      method: "PUT",
      body: JSON.stringify({
        values: [dashboardDocumentCells(submissionId, folderId, documents)],
      }),
    },
  );
}

function parseDocuments(row) {
  const text =
    row.find(
      (cell) =>
        String(cell || "").includes(":image/") ||
        String(cell || "").includes(":application/pdf"),
    ) || "";
  return String(text)
    .split(" | ")
    .filter(Boolean)
    .map((part) => {
      const bits = part.split(":");
      return {
        document_type: DOCUMENTS[bits[0]]?.[1] || bits[0] || "",
        document_type_key: bits[0] || "",
        id: bits[1] || "",
        original_name: bits[2] || "",
        mime_type: bits.slice(3).join(":") || "",
      };
    })
    .filter(
      (item) =>
        item.id &&
        (item.mime_type === "image/jpeg" ||
          item.mime_type === "image/png" ||
          item.mime_type === "image/webp" ||
          item.mime_type === "application/pdf"),
    );
}

function applicationFromRow(row, index) {
  return {
    application_code: row[0] || "",
    submitted_at: row[1] || "",
    full_name: row[2] || "",
    company_name: row[3] || "",
    status: row[4] && !String(row[4]).includes(":") ? row[4] : "Mới / New",
    admin_note: row[52] || "",
    document_count: parseDocuments(row).length,
    row_number: index + 1,
  };
}

async function existingSubmission(token, env, submissionId) {
  const url = `https://sheets.googleapis.com/v4/spreadsheets/${encodeURIComponent(env.GOOGLE_SHEET_ID)}/values/${encodeURIComponent("'Dashboard'!F:F")}`;
  const response = await fetch(url, {
    headers: { Authorization: `Bearer ${token}` },
  });
  if (!response.ok) return false;
  const data = await response.json();
  return (data.values || []).some((row) =>
    String(row[0] || "").includes(`submission_id:${submissionId}`),
  );
}

async function applicationCodeExists(token, env, applicationCode) {
  const url = `https://sheets.googleapis.com/v4/spreadsheets/${encodeURIComponent(env.GOOGLE_SHEET_ID)}/values/${encodeURIComponent("'Dashboard'!A:A")}`;
  const response = await fetch(url, {
    headers: { Authorization: `Bearer ${token}` },
  });
  if (!response.ok) return false;
  const data = await response.json();
  return (data.values || []).some((row) => row[0] === applicationCode);
}

function applicationCodeBase(companyName, date = new Date()) {
  const company =
    String(companyName)
      .normalize("NFKC")
      .trim()
      .replace(/\s+/g, "_")
      .replace(/[^\p{L}\p{N}_-]/gu, "")
      .slice(0, 50) || "CONG_TY";
  const dateText = new Intl.DateTimeFormat("en-GB", {
    timeZone: "Asia/Tokyo",
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
  }).format(date);
  return `${company}_${dateText}`;
}

async function uniqueApplicationCode(token, env, base) {
  if (!(await applicationCodeExists(token, env, base))) return base;
  let suffix = 2;
  while (await applicationCodeExists(token, env, `${base}_${suffix}`)) suffix += 1;
  return `${base}_${suffix}`;
}

function extension(name) {
  return (name.split(".").pop() || "").toLowerCase();
}

function hasSignature(mime, bytes) {
  if (mime === "application/pdf")
    return new TextDecoder().decode(bytes.slice(0, 5)) === "%PDF-";
  if (mime === "image/png")
    return (
      bytes.length >= 8 &&
      bytes
        .slice(0, 8)
        .every((v, i) => v === [137, 80, 78, 71, 13, 10, 26, 10][i])
    );
  if (mime === "image/jpeg")
    return (
      bytes.length >= 3 &&
      bytes[0] === 255 &&
      bytes[1] === 216 &&
      bytes[2] === 255
    );
  if (mime === "image/webp")
    return (
      bytes.length >= 12 &&
      new TextDecoder().decode(bytes.slice(0, 4)) === "RIFF" &&
      new TextDecoder().decode(bytes.slice(8, 12)) === "WEBP"
    );
  return false;
}

function submissionErrorMessage(error) {
  const detail = error instanceof Error ? error.message : String(error);
  if (/OAuth authentication failed|Google authentication failed/i.test(detail))
    return "Không thể xác thực với Google Drive/Sheets. Quản trị viên cần kiểm tra lại kết nối Google.";
  if (/folder creation failed/i.test(detail))
    return "Không thể tạo thư mục hồ sơ trên Google Drive. Quản trị viên cần kiểm tra quyền ghi của thư mục Drive.";
  if (/Drive upload failed/i.test(detail))
    return "Không thể tải tài liệu lên Google Drive. Vui lòng kiểm tra file và thử lại.";
  if (/Sheets write failed|Sheets API failed/i.test(detail))
    return "Không thể ghi hồ sơ vào Google Sheets. Quản trị viên cần kiểm tra quyền truy cập bảng tính.";
  if (/Application row not found/i.test(detail))
    return "Không tìm thấy hồ sơ tương ứng trong bảng quản lý. Vui lòng liên hệ quản trị viên.";
  if (/D1|database|SQLITE/i.test(detail))
    return "Không thể lưu mã hồ sơ và PIN vào cơ sở dữ liệu. Vui lòng thử lại sau.";
  const safeDetail = detail
    .replace(/https?:\/\/\S+/gi, "dịch vụ bên ngoài")
    .replace(/[\r\n]+/g, " ")
    .slice(0, 180);
  return `Không thể xử lý hồ sơ. Lý do kỹ thuật: ${safeDetail || "không xác định"}.`;
}

export default {
  async fetch(request, env) {
    const origin = request.headers.get("Origin");
    const headers = origin
      ? {
          "access-control-allow-origin": origin,
          "access-control-allow-methods": "GET, PATCH, POST, DELETE, OPTIONS",
          "access-control-allow-headers": "content-type",
        }
      : {};
    if (request.method === "OPTIONS") return new Response(null, { headers });
    const url = new URL(request.url);
    if (url.pathname === "/api/visa/login" && request.method === "POST") {
      try {
        const body = await request.json();
        const applicationCode = String(body.application_code || "").trim();
        const pin = String(body.pin || "").trim();
        if (!applicationCode || !/^\d{6}$/.test(pin))
          return json({ ok: false, error: "Vui lòng nhập mã hồ sơ và PIN 6 số." }, 422);
        const authentication = await authenticateApplication(
          env,
          applicationCode,
          pin,
        );
        if (!authentication.application)
          return json(
            { ok: false, error: authentication.error },
            authentication.status,
          );
        return json({
          ok: true,
          application: publicApplication(authentication.application),
        });
      } catch (error) {
        console.error(
          "Applicant login failed:",
          error instanceof Error ? error.message : String(error),
        );
        return json({ ok: false, error: "Không thể mở hồ sơ. Vui lòng thử lại." }, 500);
      }
    }
    if (url.pathname.startsWith("/api/admin/")) {
      const identity = adminIdentity(request, env);
      if (!identity)
        return json(
          { ok: false, error: "Admin authentication required." },
          401,
        );
      try {
        const token = await accessToken(env);
        const rows = await dashboardRows(token, env);
        if (
          url.pathname === "/api/admin/applications" &&
          request.method === "GET"
        )
          return json({
            ok: true,
            identity,
            applications: rows
              .map((row, index) => applicationFromRow(row, index))
              .filter((item) => item.application_code),
          });
        const code = decodeURIComponent(url.pathname.split("/")[4] || "");
        const found = rows
          .map((row, index) => ({ row, index }))
          .find((item) => item.row[0] === code);
        if (
          url.pathname.startsWith("/api/admin/applications/") &&
          request.method === "GET"
        ) {
          if (!found)
            return json({ ok: false, error: "Application not found." }, 404);
          return json({
            ok: true,
            application: applicationFromRow(found.row, found.index),
            documents: parseDocuments(found.row),
          });
        }
        if (
          url.pathname.startsWith("/api/admin/applications/") &&
          request.method === "PATCH"
        ) {
          if (!found)
            return json({ ok: false, error: "Application not found." }, 404);
          const body = await request.json();
          const rowNumber = found.index + 1;
          const fullName = String(body.full_name ?? found.row[2] ?? "").trim();
          const companyName = String(body.company_name ?? found.row[3] ?? "").trim();
          const status = String(body.status ?? found.row[4] ?? "Mới / New").trim();
          const note = String(body.admin_note ?? found.row[52] ?? "").trim();
          if (!fullName || !companyName)
            return json(
              { ok: false, error: "Họ tên và công ty là bắt buộc." },
              400,
            );
          const updateUrl = `https://sheets.googleapis.com/v4/spreadsheets/${encodeURIComponent(env.GOOGLE_SHEET_ID)}/values:batchUpdate`;
          const update = await fetch(updateUrl, {
            method: "POST",
            headers: {
              Authorization: `Bearer ${token}`,
              "content-type": "application/json",
            },
            body: JSON.stringify({
              valueInputOption: "USER_ENTERED",
              data: [
                {
                  range: `'Dashboard'!C${rowNumber}:E${rowNumber}`,
                  values: [[fullName, companyName, status]],
                },
                {
                  range: `'Dashboard'!BA${rowNumber}`,
                  values: [[note]],
                },
              ],
            }),
          });
          if (!update.ok) throw new Error("Google Sheets update failed");
          await env.DB.prepare(
            "UPDATE applications SET full_name = ?, company_name = ?, updated_at = ? WHERE application_code = ?",
          )
            .bind(fullName, companyName, new Date().toISOString(), code)
            .run();
          return json({ ok: true });
        }
        if (
          url.pathname.startsWith("/api/admin/applications/") &&
          request.method === "DELETE"
        ) {
          if (!found)
            return json({ ok: false, error: "Application not found." }, 404);
          const documents = parseDocuments(found.row);
          const storedApplication = await env.DB.prepare(
            "SELECT folder_id FROM applications WHERE application_code = ?",
          )
            .bind(code)
            .first();
          const folderId =
            storedApplication?.folder_id ||
            (documents[0] ? await driveParentFolder(token, documents[0].id) : null);
          await Promise.all(
            documents.map((document) => deleteDriveFile(token, document.id)),
          );
          // Only remove a generated subfolder. Never delete the configured root folder.
          if (folderId && folderId !== env.GOOGLE_DRIVE_FOLDER_ID)
            await deleteDriveFile(token, folderId);
          await deleteApplicantTab(token, env, code);
          await clearDashboardRow(token, env, found.index + 1);
          await env.DB.prepare(
            "DELETE FROM applications WHERE application_code = ?",
          )
            .bind(code)
            .run();
          return json({ ok: true });
        }
        if (
          url.pathname.startsWith("/api/admin/files/") &&
          request.method === "GET"
        ) {
          const fileId = decodeURIComponent(url.pathname.split("/")[4] || "");
          const file = await fetch(
            `https://www.googleapis.com/drive/v3/files/${encodeURIComponent(fileId)}?alt=media&supportsAllDrives=true`,
            { headers: { Authorization: `Bearer ${token}` } },
          );
          if (!file.ok)
            return json({ ok: false, error: "File not found." }, 404);
          return new Response(file.body, {
            headers: {
              ...headers,
              "content-type":
                file.headers.get("content-type") || "application/octet-stream",
              "content-disposition":
                url.searchParams.get("mode") === "download"
                  ? "attachment"
                  : "inline",
            },
          });
        }
        return json({ ok: false, error: "Not found" }, 404);
      } catch (error) {
        console.error(
          "Admin API failed:",
          error instanceof Error ? error.message : String(error),
        );
        return json({ ok: false, error: "Admin API error." }, 500);
      }
    }
    if (url.pathname !== "/api/visa/submit" || request.method !== "POST")
      return json({ ok: false, error: "Not found" }, 404);
    try {
      const form = await request.formData();
      const resumeCode = String(form.get("resume_code") || "").trim();
      const resumePin = String(form.get("resume_pin") || "").trim();
      let storedApplication = null;
      if (resumeCode || resumePin) {
        if (!resumeCode || !/^\d{6}$/.test(resumePin))
          return json({ ok: false, error: "Mã hồ sơ hoặc PIN không hợp lệ." }, 422);
        const authentication = await authenticateApplication(
          env,
          resumeCode,
          resumePin,
        );
        if (!authentication.application)
          return json(
            { ok: false, error: authentication.error },
            authentication.status,
          );
        storedApplication = authentication.application;
      }
      const fullName = storedApplication
        ? storedApplication.full_name
        : String(form.get("full_name") || "").trim();
      const companyName = storedApplication
        ? storedApplication.company_name
        : String(form.get("company_name") || "").trim();
      const submissionId = storedApplication
        ? storedApplication.submission_id
        : String(form.get("submission_id") || "").trim();
      if (!fullName || !companyName || !submissionId)
        return json(
          {
            ok: false,
            error: "Vui lòng nhập họ tên và tên công ty ứng tuyển.",
          },
          422,
        );
      if (
        fullName.length > 191 ||
        companyName.length > 191 ||
        !/^[a-zA-Z0-9-]{16,80}$/.test(submissionId)
      )
        return json({ ok: false, error: "Dữ liệu không hợp lệ." }, 422);
      if (!storedApplication) {
        const previous = await env.DB.prepare(
          "SELECT * FROM applications WHERE submission_id = ?",
        )
          .bind(submissionId)
          .first();
        if (previous) {
          if (previous.full_name !== fullName || previous.company_name !== companyName)
            return json({ ok: false, error: "Thông tin lần gửi trước không khớp." }, 409);
          if (
            Array.from(form.entries()).some(
              ([key, value]) =>
                key.startsWith("documents[") && value instanceof File && value.size > 0,
            )
          )
            return json(
              { ok: false, error: "Hồ sơ đã lưu trước đó. Vui lòng mở lại hồ sơ để kiểm tra tài liệu trước khi gửi tiếp." },
              409,
            );
          // A mobile connection can drop after the row is committed but before
          // the client receives its PIN. The unguessable submission ID allows
          // that same browser session to recover the existing application.
          const pin = randomPin();
          const salt = randomSalt();
          await env.DB.prepare(
            "UPDATE applications SET pin_salt = ?, pin_hash = ?, failed_attempts = 0, locked_until = NULL WHERE submission_id = ?",
          )
            .bind(salt, await pinHash(pin, salt), submissionId)
            .run();
          const publicOrigin =
            origin && /^https?:\/\//i.test(origin)
              ? origin
              : "https://k-anhjobs-visa.pages.dev";
          return json({
            ok: true,
            recovered: true,
            application_code: previous.application_code,
            pin,
            resume_url: `${publicOrigin}/apply-visa/?resume=${encodeURIComponent(previous.application_code)}`,
            application: publicApplication(previous),
            message: "Đã khôi phục hồ sơ của lần gửi trước.",
          });
        }
      }
      const files = [];
      for (const [key, value] of form.entries()) {
        if (!(value instanceof File) || !value.size) continue;
        const match = /^documents\[([^\]]+)\](?:\[\])?$/.exec(key);
        if (key.startsWith("documents[") && (!match || !DOCUMENTS[match[1]]))
          return json({ ok: false, error: "Loại giấy tờ không được hỗ trợ." }, 422);
      }
      for (const [type, def] of Object.entries(DOCUMENTS)) {
        const inputFiles = [
          ...form.getAll(`documents[${type}]`),
          ...form.getAll(`documents[${type}][]`),
        ].filter((file) => file instanceof File && file.size > 0);
        if (!inputFiles.length) {
          if (def[2])
            return json(
              { ok: false, error: `${def[1]}: thiếu file bắt buộc.` },
              422,
            );
          continue;
        }
        for (const file of inputFiles) {
          const ext = extension(file.name);
          const bytes = new Uint8Array(await file.arrayBuffer());
          if (
            file.size > MAX_FILE_SIZE ||
            !ALLOWED.has(file.type) ||
            !ALLOWED.get(file.type).includes(ext) ||
            !hasSignature(file.type, bytes) ||
            /\.(php\d*|phtml|phar|htaccess)$/i.test(file.name)
          )
            return json(
              {
                ok: false,
                error: `${def[1]}: file không hợp lệ hoặc vượt quá 10 MB.`,
              },
              422,
            );
          files.push({ type, file, bytes, def });
        }
      }
      const token = await uploadAccessToken(env);
      if (storedApplication) {
        const uploaded = [];
        for (const item of files)
          uploaded.push({
            type: item.type,
            file: await driveUpload(
              token,
              env,
              item,
              `${item.type}-${item.file.name.replace(/[^a-zA-Z0-9._-]/g, "_")}`,
              storedApplication.folder_id,
            ),
          });
        const addedDocuments = uploaded.map((item) => ({
          type: item.type,
          id: item.file.id,
          name: item.file.name,
          mimeType: item.file.mimeType,
        }));
        const documents = savedDocuments(storedApplication).concat(addedDocuments);
        const now = new Date().toISOString();
        try {
          await updateDashboardDocuments(
            token,
            env,
            storedApplication.application_code,
            storedApplication.submission_id,
            storedApplication.folder_id,
            documents,
          );
          await env.DB.prepare(
            "UPDATE applications SET documents_json = ?, updated_at = ? WHERE application_code = ?",
          )
            .bind(
              JSON.stringify(documents),
              now,
              storedApplication.application_code,
            )
            .run();
        } catch (error) {
          await Promise.all(
            uploaded.map((item) => deleteDriveFile(token, item.file.id)),
          );
          throw error;
        }
        const publicOrigin =
          origin && /^https?:\/\//i.test(origin)
            ? origin
            : "https://k-anhjobs-visa.pages.dev";
        return json({
          ok: true,
          resumed: true,
          application_code: storedApplication.application_code,
          resume_url: `${publicOrigin}/apply-visa/?resume=${encodeURIComponent(storedApplication.application_code)}`,
          application: {
            ...publicApplication({
              ...storedApplication,
              documents_json: JSON.stringify(documents),
              updated_at: now,
            }),
          },
          message: "Đã lưu tài liệu bổ sung.",
        });
      }
      if (await existingSubmission(token, env, submissionId))
        return json(
          { ok: false, error: "Hồ sơ đã được nhận trước đó. Vui lòng liên hệ quản trị viên để mở lại." },
          409,
        );
      const applicationCode = await uniqueApplicationCode(
        token,
        env,
        applicationCodeBase(companyName),
      );
      const folderName = `${fullName} - ${applicationCode}`
        .replace(/[\\/*?:\[\]]/g, "-")
        .slice(0, 100);
      const applicantFolder = await createDriveFolder(
        token,
        folderName,
        env.GOOGLE_DRIVE_FOLDER_ID,
      );
      const uploaded = [];
      for (const item of files)
        uploaded.push({
          type: item.type,
          file: await driveUpload(
            token,
            env,
            item,
            `${item.type}-${item.file.name.replace(/[^a-zA-Z0-9._-]/g, "_")}`,
            applicantFolder.id,
          ),
        });
      const documents = uploaded.map((item) => ({
        type: item.type,
        id: item.file.id,
        name: item.file.name,
        mimeType: item.file.mimeType,
      }));
      const now = new Date().toISOString();
      const pin = randomPin();
      const salt = randomSalt();
      const hashedPin = await pinHash(pin, salt);
      let applicationInserted = false;
      try {
        await env.DB.prepare(
          `INSERT INTO applications
            (application_code, submission_id, pin_salt, pin_hash, full_name,
             company_name, folder_id, documents_json, created_at, updated_at)
           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
        )
          .bind(
            applicationCode,
            submissionId,
            salt,
            hashedPin,
            fullName,
            companyName,
            applicantFolder.id,
            JSON.stringify(documents),
            now,
            now,
          )
          .run();
        applicationInserted = true;
        await appendSheet(token, env, [
          applicationCode,
          now,
          fullName,
          companyName,
          "Mới / New",
          ...dashboardDocumentCells(submissionId, applicantFolder.id, documents),
        ]);
      } catch (error) {
        if (applicationInserted)
          await env.DB.prepare(
            "DELETE FROM applications WHERE application_code = ? AND submission_id = ?",
          )
            .bind(applicationCode, submissionId)
            .run();
        await Promise.all(
          uploaded.map((item) => deleteDriveFile(token, item.file.id)),
        );
        await deleteDriveFile(token, applicantFolder.id);
        throw error;
      }
      try {
        await createApplicantTab(
          token,
          env,
          { submissionId: applicationCode, fullName, companyName, submittedAt: now },
          uploaded,
        );
      } catch (error) {
        console.error(
          "Applicant tab creation failed:",
          error instanceof Error ? error.message : String(error),
        );
      }
      return new Response(
        JSON.stringify({
          ok: true,
          application_code: applicationCode,
          pin,
          resume_url: `${origin && /^https?:\/\//i.test(origin) ? origin : "https://k-anhjobs-visa.pages.dev"}/apply-visa/?resume=${encodeURIComponent(applicationCode)}`,
          application: {
            application_code: applicationCode,
            full_name: fullName,
            company_name: companyName,
            updated_at: now,
            documents,
          },
          message: "Đã gửi hồ sơ thành công.",
        }),
        {
          headers: {
            ...headers,
            "content-type": "application/json; charset=utf-8",
          },
        },
      );
    } catch (error) {
      const errorCode = crypto.randomUUID().slice(0, 8).toUpperCase();
      console.error(
        `Visa submission failed [${errorCode}]:`,
        error instanceof Error ? error.message : String(error),
      );
      return new Response(
        JSON.stringify({
          ok: false,
          error: `${submissionErrorMessage(error)} Mã lỗi: ${errorCode}.`,
        }),
        {
          status: 500,
          headers: {
            ...headers,
            "content-type": "application/json; charset=utf-8",
          },
        },
      );
    }
  },
};
