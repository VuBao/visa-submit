(() => {
  // API is proxied by the Admin Worker on the same protected origin.
  // This lets Cloudflare Access authenticate the browser once for the Admin app.
  const API = "";
  const $ = (s) => document.querySelector(s);
  let apps = [];
  let currentDocumentGroup = 0;
  const documentGroups = [
    { name: "Thông tin cá nhân", ja: "本人確認・個人情報", types: ["residence_card_front", "residence_card_back", "passport_vietnam", "passport_residence_status", "insurance_front", "insurance_back", "photo_3x4"] },
    { name: "Chứng chỉ", ja: "資格・証明書", types: ["sankyu_senmonkyu", "tokutei_certificate", "jlpt_certificate", "student_graduation", "student_transcript", "student_documents"] },
    { name: "Hồ sơ thuế", ja: "税務書類", types: ["gensen", "tax_certificate", "tax_payment_certificate", "kokumin_payment"] },
    { name: "Nenkin", ja: "年金・住民票", types: ["juminhyo_mynumber", "nenkin_record", "insured_record_nofu2", "health_check", "other_documents"] },
  ];
  const groupIndex = (type) => {
    const index = documentGroups.findIndex((group) => group.types.includes(type));
    return index < 0 ? 3 : index;
  };
  function showDocumentGroup(index, scroll = false) {
    if (index < 0 || index >= documentGroups.length) return;
    currentDocumentGroup = index;
    document.querySelectorAll("#documentGroups [data-group]").forEach((button) => {
      const active = Number(button.dataset.group) === index;
      button.classList.toggle("is-current", active);
      button.setAttribute("aria-selected", String(active));
    });
    document.querySelectorAll("#documents .document-section").forEach((section) => {
      const active = Number(section.dataset.group) === index;
      section.hidden = !active;
      if (active) section.querySelectorAll("img[data-src]").forEach((image) => {
        image.src = image.dataset.src;
        delete image.dataset.src;
      });
    });
    $("#documentPrevious").hidden = index === 0;
    $("#documentNext").hidden = index === documentGroups.length - 1;
    if (scroll) requestAnimationFrame(() =>
      $("#documentGroups").scrollIntoView({ behavior: "smooth", block: "start" }),
    );
  }
  const esc = (s) =>
    String(s ?? "").replace(
      /[&<>"']/g,
      (c) =>
        ({
          "&": "&amp;",
          "<": "&lt;",
          ">": "&gt;",
          '"': "&quot;",
          "'": "&#39;",
        })[c],
    );
  const displayDate = (value) => {
    const date = new Date(value);
    return Number.isNaN(date.getTime())
      ? String(value || "—")
      : `${date.toLocaleString("vi-VN", {
        timeZone: "Asia/Tokyo", day: "2-digit", month: "2-digit", year: "numeric",
        hour: "2-digit", minute: "2-digit", hour12: false,
      })} JST`;
  };
  async function api(path, init) {
    const r = await fetch(API + path, init);
    const d = await r.json().catch(() => ({}));
    if (!r.ok) throw new Error(d.error || `HTTP ${r.status}`);
    return d;
  }
  async function resetApplicationPin(code) {
    const value = $("#pinValue");
    const hint = $("#pinHint");
    const reset = $("#resetPin");
    const copy = $("#copyNewPin");
    reset.disabled = true;
    copy.hidden = true;
    value.classList.remove("is-generated");
    value.textContent = "Đang tạo PIN mới…";
    hint.textContent = "Vui lòng chờ, không đóng trang.";
    try {
      const result = await api(
        `/api/admin/applications/${encodeURIComponent(code)}/reset-pin`,
        { method: "POST", headers: { "content-type": "application/json" }, body: "{}" },
      );
      if (!/^\d{6}$/.test(result.pin || "")) throw new Error("Máy chủ chưa trả PIN hợp lệ.");
      value.textContent = result.pin;
      value.classList.add("is-generated");
      hint.textContent = "PIN cũ đã hết hiệu lực. Hãy sao chép PIN mới trước khi rời trang.";
      copy.textContent = "Sao chép mã hồ sơ + PIN";
      copy.hidden = false;
    } catch (error) {
      value.textContent = "Chưa xác nhận PIN mới";
      hint.textContent = "Nếu mạng bị gián đoạn, PIN có thể đã đổi. Hãy đặt lại lần nữa.";
      alert(error.message || "Không thể đặt lại PIN.");
    } finally {
      reset.disabled = false;
    }
  }
  function render() {
    const q = ($("#search").value || "").toLowerCase();
    const st = $("#status").value;
    const list = apps.filter(
      (a) =>
        (!st || a.status === st) &&
        [a.application_code, a.full_name, a.company_name]
          .join(" ")
          .toLowerCase()
          .includes(q),
    );
    $("#rows").innerHTML = list.length
      ? list
          .map(
            (a) =>
              `<tr><td><b>${esc(a.application_code)}</b></td><td>${esc(a.full_name)}</td><td>${esc(a.company_name)}</td><td><span class="badge">${esc(a.status)}</span></td><td>${a.document_count}</td><td>${esc(a.submitted_at)}</td><td><button class="open" data-id="${encodeURIComponent(a.application_code)}">Chi tiết</button></td></tr>`,
          )
          .join("")
      : '<tr><td colspan="7" class="loading">Không có hồ sơ</td></tr>';
    $("#total").textContent = apps.length;
    $("#newCount").textContent = apps.filter(
      (a) => a.status === "Mới / New",
    ).length;
    $("#docsCount").textContent = apps.reduce(
      (n, a) => n + a.document_count,
      0,
    );
    document
      .querySelectorAll(".open")
      .forEach(
        (b) => (b.onclick = () => openDetail(decodeURIComponent(b.dataset.id))),
      );
  }
  async function load() {
    try {
      $("#error").hidden = true;
      const d = await api("/api/admin/applications");
      apps = d.applications || [];
      render();
      $("#identity").textContent = d.identity || "Admin";
    } catch (e) {
      $("#error").textContent = e.message;
      $("#error").hidden = false;
      $("#rows").innerHTML =
        '<tr><td colspan="7" class="loading">Không thể tải dữ liệu</td></tr>';
    }
  }
  async function openDetail(code) {
    location.hash = "detail=" + encodeURIComponent(code);
    $("#dashboard").hidden = true;
    $("#detail").hidden = false;
    $("#profile").replaceChildren();
    $("#documentGroups").innerHTML = "";
    $("#documents").innerHTML = '<p class="document-empty">Đang tải tài liệu…</p>';
    $("#documentNavigation").hidden = true;
    try {
      const d = await api(
        "/api/admin/applications/" + encodeURIComponent(code),
      );
      $("#detailName").textContent = d.application.full_name;
      $("#detailCode").textContent = d.application.application_code;
      $("#detailStatus").textContent = d.application.status;
      $("#editName").value = d.application.full_name || "";
      $("#editCompany").value = d.application.company_name || "";
      $("#editStatus").value = d.application.status;
      $("#note").value = d.application.admin_note || "";
      $("#profile").innerHTML =
        `<div class="profile-field"><dt>Họ và tên</dt><dd>${esc(d.application.full_name)}</dd></div>` +
        `<div class="profile-field"><dt>Công ty ứng tuyển</dt><dd>${esc(d.application.company_name)}</dd></div>` +
        `<div class="profile-field profile-field-code"><dt>Mã hồ sơ</dt><dd>${esc(d.application.application_code)}</dd></div>` +
        `<div class="profile-field profile-field-pin"><dt>PIN</dt><dd id="pinValue" aria-live="polite">Không thể xem PIN cũ</dd><p id="pinHint" class="pin-help">Đặt lại để tạo PIN mới cho ứng viên.</p><div class="pin-actions"><button id="resetPin" type="button">Đặt lại PIN</button><button id="copyNewPin" type="button" hidden>Sao chép mã hồ sơ + PIN</button></div></div>` +
        `<div class="profile-field profile-field-date"><dt>Ngày nộp</dt><dd>${esc(displayDate(d.application.submitted_at))}</dd></div>`;
      $("#resetPin").onclick = () => resetApplicationPin(code);
      $("#copyNewPin").onclick = async () => {
        try {
          await navigator.clipboard.writeText(`Mã hồ sơ: ${code}\nPIN: ${$("#pinValue").textContent}`);
          $("#copyNewPin").textContent = "Đã sao chép ✓";
        } catch (_) {
          alert("Không thể sao chép tự động. Vui lòng chọn và sao chép mã hiển thị.");
        }
      };
      const grouped = documentGroups.map((_, index) =>
        d.documents.filter((file) => groupIndex(file.document_type_key) === index),
      );
      $("#documentGroups").innerHTML = documentGroups
        .map((group, index) =>
          `<button type="button" role="tab" aria-controls="documents-group-${index}" aria-selected="false" data-group="${index}" class="document-group-status ${grouped[index].length ? "is-complete" : ""}"><b>${index + 1}. ${esc(group.name)}</b><small>${esc(group.ja)}</small><span>${grouped[index].length ? `${grouped[index].length} tài liệu` : "Chưa có tài liệu"}</span></button>`,
        )
        .join("");
      $("#documents").innerHTML = documentGroups
        .map((group, index) => {
          const files = grouped[index].sort((a, b) =>
            group.types.indexOf(a.document_type_key) - group.types.indexOf(b.document_type_key),
          );
          return `<section class="document-section" id="documents-group-${index}" data-group="${index}" role="tabpanel" hidden><h3>${index + 1}. ${esc(group.name)} / ${esc(group.ja)}</h3>${files.length
            ? files.map((x) =>
              `<article class="document"><div><h3>${esc(x.document_type)}</h3><p>${esc(x.original_name)} · ${esc(x.mime_type)}</p></div><div class="actions"><button data-preview="${esc(x.id)}">Preview</button><button data-download="${esc(x.id)}">Download</button><button data-copy="${esc(x.id)}">Copy</button></div>${x.mime_type.startsWith("image/") ? `<img class="preview show" id="preview-${esc(x.id)}" loading="lazy" data-src="${API}/api/admin/files/${encodeURIComponent(x.id)}?mode=preview" alt="${esc(x.original_name)}">` : ""}</article>`,
            ).join("")
            : '<p class="document-empty">Chưa có tài liệu</p>'}</section>`;
        })
        .join("");
      d.documents.forEach((x) => {
        const image = document.querySelector(
          `#preview-${CSS.escape(x.id)}`,
        );
        if (image) {
          image.onerror = () => {
            image.remove();
            const warning = document.createElement("p");
            warning.className = "file-warning";
            warning.textContent = "Ảnh không còn khả dụng trên Drive.";
            document
              .querySelector(`[data-preview="${CSS.escape(x.id)}"]`)
              .closest(".document")
              .append(warning);
          };
        }
        const p = document.querySelector(
          `[data-preview="${CSS.escape(x.id)}"]`,
        );
        p.onclick = () => openFile(x, "preview");
        document.querySelector(`[data-download="${CSS.escape(x.id)}"]`).onclick =
          () => openFile(x, "download");
        document.querySelector(`[data-copy="${CSS.escape(x.id)}"]`).onclick =
          () =>
            navigator.clipboard.writeText(
              `${API}/api/admin/files/${encodeURIComponent(x.id)}?mode=download`,
            );
      });
      $("#documentNavigation").hidden = false;
      showDocumentGroup(0);
    } catch (e) {
      $("#documentGroups").innerHTML = "";
      $("#documents").innerHTML = `<div class="error">${esc(e.message)}</div>`;
    }
  }
  $("#documentGroups").onclick = (event) => {
    const button = event.target.closest("button[data-group]");
    if (button) showDocumentGroup(Number(button.dataset.group), true);
  };
  $("#documentPrevious").onclick = () => showDocumentGroup(currentDocumentGroup - 1, true);
  $("#documentNext").onclick = () => showDocumentGroup(currentDocumentGroup + 1, true);
  $("#search").oninput = render;
  $("#status").onchange = render;
  $("#refresh").onclick = load;
  $("#back").onclick = () => {
    location.hash = "dashboard";
    $("#detail").hidden = true;
    $("#dashboard").hidden = false;
    $("#profile").replaceChildren();
  };
  $("#save").onclick = async () => {
    const code = $("#detailCode").textContent;
    try {
      await api("/api/admin/applications/" + encodeURIComponent(code), {
        method: "PATCH",
        headers: { "content-type": "application/json" },
        body: JSON.stringify({
          full_name: $("#editName").value,
          company_name: $("#editCompany").value,
          status: $("#editStatus").value,
          admin_note: $("#note").value,
        }),
      });
      $("#detailStatus").textContent = $("#editStatus").value;
      $("#detailName").textContent = $("#editName").value;
      alert("Đã lưu thay đổi");
    } catch (e) {
      alert(e.message);
    }
  };
  async function openFile(file, mode) {
    const popup = mode === "preview" ? window.open("", "_blank") : null;
    try {
      const response = await fetch(
        `${API}/api/admin/files/${encodeURIComponent(file.id)}?mode=${mode}`,
      );
      if (!response.ok) {
        const data = await response.json().catch(() => ({}));
        throw new Error(data.error || "Không thể mở file.");
      }
      const blobUrl = URL.createObjectURL(await response.blob());
      if (mode === "download") {
        const link = document.createElement("a");
        link.href = blobUrl;
        link.download = file.original_name || "document";
        link.click();
        setTimeout(() => URL.revokeObjectURL(blobUrl), 1000);
      } else if (popup) {
        popup.location.href = blobUrl;
      }
    } catch (error) {
      popup?.close();
      alert(error.message || "Không thể mở file.");
    }
  }
  $("#delete").onclick = async () => {
    const code = $("#detailCode").textContent;
    if (!confirm(`Xóa hồ sơ ${code} và toàn bộ tài liệu Drive? Hành động này không thể hoàn tác.`)) return;
    try {
      await api("/api/admin/applications/" + encodeURIComponent(code), {
        method: "DELETE",
      });
      alert("Đã xóa hồ sơ.");
      location.hash = "dashboard";
      $("#detail").hidden = true;
      $("#dashboard").hidden = false;
      $("#profile").replaceChildren();
      await load();
    } catch (error) {
      alert(error.message || "Không thể xóa hồ sơ.");
    }
  };
  load();
})();
