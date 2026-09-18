(() => {
  // API is proxied by the Admin Worker on the same protected origin.
  // This lets Cloudflare Access authenticate the browser once for the Admin app.
  const API = "";
  const $ = (s) => document.querySelector(s);
  let apps = [];
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
  async function api(path, init) {
    const r = await fetch(API + path, init);
    const d = await r.json().catch(() => ({}));
    if (!r.ok) throw new Error(d.error || `HTTP ${r.status}`);
    return d;
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
        `<dt>Họ và tên</dt><dd>${esc(d.application.full_name)}</dd><dt>Công ty</dt><dd>${esc(d.application.company_name)}</dd><dt>Ngày nộp</dt><dd>${esc(d.application.submitted_at)}</dd>`;
      $("#documents").innerHTML = d.documents
        .map(
          (x) =>
            `<article class="document"><div><h3>${esc(x.document_type)}</h3><p>${esc(x.original_name)} · ${esc(x.mime_type)}</p></div><div class="actions"><button data-preview="${esc(x.id)}">Preview</button><button data-download="${esc(x.id)}">Download</button><button data-copy="${esc(x.id)}">Copy</button></div>${x.mime_type.startsWith("image/") ? `<img class="preview show" id="preview-${esc(x.id)}" loading="lazy" src="${API}/api/admin/files/${encodeURIComponent(x.id)}?mode=preview" alt="${esc(x.original_name)}">` : ""}</article>`,
        )
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
    } catch (e) {
      $("#documents").innerHTML = `<div class="error">${esc(e.message)}</div>`;
    }
  }
  $("#search").oninput = render;
  $("#status").onchange = render;
  $("#refresh").onclick = load;
  $("#back").onclick = () => {
    location.hash = "dashboard";
    $("#detail").hidden = true;
    $("#dashboard").hidden = false;
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
      await load();
    } catch (error) {
      alert(error.message || "Không thể xóa hồ sơ.");
    }
  };
  load();
})();
