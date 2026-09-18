(function () {
  const form = document.getElementById("visa-application-form");
  if (!form) return;
  const docs = {
    residence_card_front: ["在留カード・表面", "Thẻ ngoại kiều - mặt trước", 1],
    residence_card_back: ["在留カード・裏面", "Thẻ ngoại kiều - mặt sau", 1],
    passport_vietnam: ["パスポート・身分事項ページ", "Hộ chiếu Việt Nam - mặt thông tin", 1],
    passport_residence_status: ["パスポート・在留資格ページ", "Hộ chiếu - tư cách lưu trú Nhật Bản", 1],
    insurance_front: ["保険証・表面", "Thẻ bảo hiểm - mặt trước", 1],
    insurance_back: ["保険証・裏面", "Thẻ bảo hiểm - mặt sau", 1],
    sankyu_senmonkyu: ["三級・専門級", "Chứng chỉ SANKYU hoặc SENMONKYU", 0],
    tokutei_certificate: ["特定技能合格証", "Chứng chỉ Tokutei chuyên ngành", 1, 1],
    jlpt_certificate: ["日本語能力試験（JLPT）", "Chứng chỉ tiếng Nhật JLPT", 0],
    gensen: ["源泉徴収票", "Phiếu khấu trừ thuế Gensen", 1],
    tax_certificate: ["課税証明書", "Giấy chứng nhận thuế năm gần nhất", 1],
    tax_payment_certificate: ["納税証明書", "Giấy chứng nhận đã đóng thuế", 1],
    juminhyo_mynumber: ["マイナンバー付の住民票", "Juminhyo có MyNumber", 1],
    nenkin_record: ["年金記録照会", "Giấy tra cứu lịch sử Nenkin", 1],
    insured_record_nofu2: ["被保険者記録照会（納付II）", "Lịch sử đóng Nenkin", 1],
    health_check: ["健康診断書", "Giấy khám sức khỏe", 1, 1],
    photo_3x4: ["証明写真 3×4", "Ảnh thẻ 3×4", 1],
    kokumin_payment: ["国民健康保険料納付証明書", "Giấy đóng bảo hiểm quốc dân", 0],
    student_graduation: ["卒業証明書（見込み可）", "Bằng tốt nghiệp (có thể dùng giấy dự kiến tốt nghiệp)", 0],
    student_transcript: ["成績・出席証明書 / 推薦状", "Bảng điểm, chuyên cần hoặc thư giới thiệu", 0],
  };
  const categories = [
    {
      ja: "本人確認・個人情報",
      vi: "Thông tin cá nhân",
      items: [
        "residence_card_front",
        "residence_card_back",
        "passport_vietnam",
        "passport_residence_status",
        "insurance_front",
        "insurance_back",
        "health_check",
        "photo_3x4",
      ],
    },
    {
      ja: "資格・証明書",
      vi: "Chứng chỉ",
      items: [
        "sankyu_senmonkyu",
        "tokutei_certificate",
        "jlpt_certificate",
        "student_graduation",
        "student_transcript",
      ],
    },
    {
      ja: "税務書類",
      vi: "Hồ sơ thuế",
      items: [
        "gensen",
        "tax_certificate",
        "tax_payment_certificate",
        "kokumin_payment",
      ],
    },
    {
      ja: "年金・住民票",
      vi: "Nenkin",
      items: ["juminhyo_mynumber", "nenkin_record", "insured_record_nofu2"],
    },
  ];
  const documentGrid = document.getElementById("visa-documents");
  const documentCard = (key, value) =>
    `<article class="visa-document-card ${value[2] ? "is-required" : "is-optional"}"><div class="visa-document-title"><h3>${value[0]}</h3><p>${value[1]}</p></div><div class="visa-required">${value[2] ? "必須 / Bắt buộc" : "該当者のみ / Nếu có"}</div><label class="visa-file-picker"><input type="file" name="documents[${key}]${value[3] ? "[]" : ""}" accept=".jpg,.jpeg,.png,.webp,.pdf,image/jpeg,image/png,image/webp,application/pdf" ${value[2] ? "required" : ""} ${value[3] ? "multiple" : ""}><b>ファイルを選ぶ</b><span>Kéo thả, Ctrl+V hoặc chọn file</span><small>JPG, PNG, WebP, PDF · 最大 10 MB</small></label><div class="visa-file-name">未選択 / Chưa chọn</div></article>`;
  documentGrid.innerHTML = categories
    .map(
      (category, index) =>
        `<section class="visa-document-category"><header><span>${String(index + 1).padStart(2, "0")}</span><div><h3>${category.ja}</h3><p>${category.vi}</p></div></header><div class="visa-category-grid">${category.items.map((key) => documentCard(key, docs[key])).join("")}</div></section>`,
    )
    .join("");
  const allowed = ["image/jpeg", "image/png", "image/webp", "application/pdf"];
  let lastFocusedCard = null;

  const filesFromClipboard = (clipboard) => {
    if (!clipboard) return [];
    if (clipboard.files?.length) return Array.from(clipboard.files);
    const files = [];
    for (const item of clipboard.items || []) {
      if (item.kind === "file") files.push(item.getAsFile());
    }
    return files.filter(Boolean);
  };

  const assignFiles = (input, files) => {
    const selected = Array.from(files || []).filter(Boolean);
    if (!selected.length) return;
    const transfer = new DataTransfer();
    (input.multiple ? selected : selected.slice(0, 1)).forEach((file) =>
      transfer.items.add(file),
    );
    input.files = transfer.files;
    input.dispatchEvent(new Event("change", { bubbles: true }));
  };

  const fileInputs = form.querySelectorAll("input[type=file]");
  fileInputs.forEach((input) => {
    const card = input.closest(".visa-document-card");
    const dropZone = input.closest(".visa-file-picker");
    card.addEventListener("click", () => {
      lastFocusedCard = card;
    });
    dropZone.addEventListener("dragover", (event) => {
      event.preventDefault();
      dropZone.classList.add("is-dragging");
    });
    dropZone.addEventListener("dragleave", () => {
      dropZone.classList.remove("is-dragging");
    });
    dropZone.addEventListener("drop", (event) => {
      event.preventDefault();
      dropZone.classList.remove("is-dragging");
      assignFiles(input, event.dataTransfer?.files);
    });
  });
  document.addEventListener("focusin", (event) => {
    if (!event.target.closest(".visa-document-card")) lastFocusedCard = null;
  });
  document.addEventListener("paste", (event) => {
    if (!lastFocusedCard) return;
    const files = filesFromClipboard(event.clipboardData);
    if (!files.length) return;
    event.preventDefault();
    assignFiles(lastFocusedCard.querySelector('input[type="file"]'), files);
  });
  form.querySelectorAll("input[type=file]").forEach((input) =>
    input.addEventListener("change", () => {
      const output = input
        .closest(".visa-document-card")
        .querySelector(".visa-file-name");
      const card = input.closest(".visa-document-card");
      const previousUrls = JSON.parse(card.dataset.previewUrls || "[]");
      previousUrls.forEach((url) => URL.revokeObjectURL(url));
      card.querySelector(".visa-file-review")?.remove();
      const files = Array.from(input.files || []);
      output.classList.remove("has-file");
      if (!files.length) {
        output.textContent = "未選択 / Chưa chọn";
        return;
      }
      if (files.some((file) => file.size > 10 * 1024 * 1024 || !allowed.includes(file.type))) {
        input.value = "";
        output.textContent = "JPG, PNG, WebP, PDF（最大10 MB）";
        return;
      }
      output.textContent = "✓ " + files.map((file) => file.name).join(", ");
      output.classList.add("has-file");
      const review = document.createElement("div");
      review.className = "visa-file-review";
      const previewUrls = [];
      files.forEach((file) => {
        const url = URL.createObjectURL(file);
        previewUrls.push(url);
        const item = document.createElement("div");
        item.className = "visa-file-preview-item";
        if (file.type === "application/pdf") {
          item.innerHTML = `<iframe title="${file.name}" src="${url}"></iframe>`;
        } else {
          const image = document.createElement("img");
          image.alt = file.name;
          image.src = url;
          item.appendChild(image);
        }
        const actions = document.createElement("div");
        actions.className = "visa-file-actions";
        actions.innerHTML =
          '<button type="button" data-action="preview">Preview / Xem</button>';
        actions.querySelector('[data-action="preview"]').addEventListener("click", () =>
          window.open(url, "_blank", "noopener"),
        );
        item.appendChild(actions);
        review.appendChild(item);
      });
      card.dataset.previewUrls = JSON.stringify(previewUrls);
      card.appendChild(review);
    }),
  );
  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    if (!form.reportValidity()) return;
    const button = form.querySelector("button[type=submit]");
    button.disabled = true;
    button.textContent = "送信中… / Đang gửi…";
    const data = new FormData(form);
    data.set("submission_id", crypto.randomUUID().replaceAll("-", ""));
    try {
      const response = await fetch(form.dataset.endpoint, {
        method: "POST",
        body: data,
      });
      const result = await response.json();
      if (!response.ok || !result.ok)
        throw new Error(result.error || "Submit failed");
      form.outerHTML =
        `<section class="visa-result" role="status"><div class="visa-result-icon">✓</div><h2>提出が完了しました<br><span>Đã gửi hồ sơ thành công</span></h2><p>Mã hồ sơ / 申請番号: <b>${result.application_code || ""}</b></p></section>`;
    } catch (error) {
      const alert = document.getElementById("visa-alert");
      alert.textContent = error.message;
      alert.hidden = false;
      button.disabled = false;
      button.textContent = "書類を送信する / Gửi hồ sơ";
    }
  });
})();
