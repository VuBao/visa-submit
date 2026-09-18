const MAX_FILE_SIZE = 10 * 1024 * 1024;
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
    true,
  ],
  residence_card_back: ["在留カード・裏面", "Thẻ ngoại kiều - mặt sau", true],
  passport_vietnam: [
    "パスポート・身分事項ページ",
    "Hộ chiếu Việt Nam - trang thông tin",
    true,
  ],
  passport_residence_status: [
    "パスポート・在留資格ページ",
    "Hộ chiếu - trang tư cách lưu trú Nhật Bản",
    true,
  ],
  insurance_front: ["保険証・表面", "Thẻ bảo hiểm - mặt trước", true],
  insurance_back: ["保険証・裏面", "Thẻ bảo hiểm - mặt sau", true],
  sankyu_senmonkyu: ["三級・専門級", "Chứng chỉ SANKYU hoặc SENMONKYU", false],
  tokutei_certificate: [
    "特定技能合格証",
    "Chứng chỉ Tokutei chuyên ngành",
    true,
  ],
  gensen: ["源泉徴収票", "Phiếu khấu trừ thuế Gensen", true],
  tax_certificate: ["課税証明書", "Giấy chứng nhận thuế năm gần nhất", true],
  tax_payment_certificate: ["納税証明書", "Giấy chứng nhận đã đóng thuế", true],
  juminhyo_mynumber: ["マイナンバー付の住民票", "Juminhyo có MyNumber", true],
  nenkin_record: ["年金記録照会", "Giấy tra cứu lịch sử Nenkin", true],
  insured_record_nofu2: [
    "被保険者記録照会（納付II）",
    "Lịch sử đóng Nenkin",
    true,
  ],
  health_check: ["健康診断書", "Giấy khám sức khỏe", true],
  photo_3x4: ["証明写真 3×4", "Ảnh thẻ 3×4", true],
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
    return oauthAccessToken(env);
  }
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
      item.def[0],
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
        document_type: bits[0] || "",
        id: bits[1] || "",
        original_name: bits[2] || "",
        mime_type: bits.slice(3).join(":") || "",
      };
    })
    .filter((item) => item.id);
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
  const url = `https://sheets.googleapis.com/v4/spreadsheets/${encodeURIComponent(env.GOOGLE_SHEET_ID)}/values/A:A`;
  const response = await fetch(url, {
    headers: { Authorization: `Bearer ${token}` },
  });
  if (!response.ok) return false;
  const data = await response.json();
  return (data.values || []).some((row) => row[0] === submissionId);
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
          return json({ ok: true });
        }
        if (
          url.pathname.startsWith("/api/admin/applications/") &&
          request.method === "DELETE"
        ) {
          if (!found)
            return json({ ok: false, error: "Application not found." }, 404);
          const documents = parseDocuments(found.row);
          const folderId = documents[0]
            ? await driveParentFolder(token, documents[0].id)
            : null;
          await Promise.all(
            documents.map((document) => deleteDriveFile(token, document.id)),
          );
          // Only remove a generated subfolder. Never delete the configured root folder.
          if (folderId && folderId !== env.GOOGLE_DRIVE_FOLDER_ID)
            await deleteDriveFile(token, folderId);
          await deleteApplicantTab(token, env, code);
          await clearDashboardRow(token, env, found.index + 1);
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
      const fullName = String(form.get("full_name") || "").trim();
      const companyName = String(form.get("company_name") || "").trim();
      const submissionId = String(form.get("submission_id") || "").trim();
      if (
        !fullName ||
        !companyName ||
        !submissionId ||
        form.get("consent") !== "1"
      )
        return json(
          {
            ok: false,
            error: "Vui lòng nhập đủ thông tin và đồng ý cung cấp thông tin.",
          },
          422,
        );
      if (
        fullName.length > 191 ||
        companyName.length > 191 ||
        !/^[a-zA-Z0-9-]{16,80}$/.test(submissionId)
      )
        return json({ ok: false, error: "Dữ liệu không hợp lệ." }, 422);
      const files = [];
      for (const [type, def] of Object.entries(DOCUMENTS)) {
        const file = form.get(`documents[${type}]`);
        if (!(file instanceof File) || file.size === 0) {
          if (def[2])
            return json(
              { ok: false, error: `${def[1]}: thiếu file bắt buộc.` },
              422,
            );
          continue;
        }
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
      const token = await accessToken(env);
      if (await existingSubmission(token, env, submissionId))
        return json({ ok: true, duplicate: true });
      const folderName = `${fullName} - ${submissionId.slice(0, 8)}`
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
      const now = new Date().toISOString();
      const metadata = uploaded
        .map(
          (item) =>
            `${item.type}:${item.file.id}:${item.file.name}:${item.file.mimeType}`,
        )
        .join(" | ");
      const folderUrl = `https://drive.google.com/drive/folders/${encodeURIComponent(applicantFolder.id)}`;
      const fileColumns = [];
      for (const [type] of Object.entries(DOCUMENTS)) {
        const item = uploaded.find((entry) => entry.type === type);
        if (!item) {
          fileColumns.push("", "");
          continue;
        }
        const fileId = item.file.id;
        const previewUrl = `https://drive.google.com/file/d/${encodeURIComponent(fileId)}/view`;
        const downloadUrl = `https://drive.google.com/uc?export=download&id=${encodeURIComponent(fileId)}`;
        fileColumns.push(
          `=HYPERLINK("${previewUrl}","Preview / Xem")`,
          `=HYPERLINK("${downloadUrl}","Download / Tải")`,
        );
      }
      try {
        await appendSheet(token, env, [
          submissionId,
          now,
          fullName,
          companyName,
          "Mới / New",
          metadata,
          `=HYPERLINK("${folderUrl}","Folder hồ sơ")`,
          ...fileColumns,
        ]);
      } catch (error) {
        await Promise.all(
          uploaded.map((item) => deleteDriveFile(token, item.file.id)),
        );
        throw error;
      }
      try {
        await createApplicantTab(
          token,
          env,
          { submissionId, fullName, companyName, submittedAt: now },
          uploaded,
        );
      } catch (error) {
        console.error(
          "Applicant tab creation failed:",
          error instanceof Error ? error.message : String(error),
        );
      }
      return new Response(
        JSON.stringify({ ok: true, message: "Đã gửi hồ sơ thành công." }),
        {
          headers: {
            ...headers,
            "content-type": "application/json; charset=utf-8",
          },
        },
      );
    } catch (error) {
      console.error(
        "Visa submission failed:",
        error instanceof Error ? error.message : String(error),
      );
      return new Response(
        JSON.stringify({
          ok: false,
          error: "Không thể gửi hồ sơ. Vui lòng thử lại sau.",
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
