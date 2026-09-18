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
    tokutei_certificate: ["特定技能合格証", "Chứng chỉ Tokutei chuyên ngành", 1],
    gensen: ["源泉徴収票", "Phiếu khấu trừ thuế Gensen", 1],
    tax_certificate: ["課税証明書", "Giấy chứng nhận thuế năm gần nhất", 1],
    tax_payment_certificate: ["納税証明書", "Giấy chứng nhận đã đóng thuế", 1],
    juminhyo_mynumber: ["マイナンバー付の住民票", "Juminhyo có MyNumber", 1],
    nenkin_record: ["年金記録照会", "Giấy tra cứu lịch sử Nenkin", 1],
    insured_record_nofu2: ["被保険者記録照会（納付II）", "Lịch sử đóng Nenkin", 1],
    health_check: ["健康診断書", "Giấy khám sức khỏe", 1],
    photo_3x4: ["証明写真 3×4", "Ảnh thẻ 3×4", 1],
    kokumin_payment: ["国民健康保険料納付証明書", "Giấy đóng bảo hiểm quốc dân", 0],
    student_graduation: ["卒業証明書（見込み可）", "Bằng tốt nghiệp (có thể dùng giấy dự kiến tốt nghiệp)", 0],
    student_transcript: ["成績・出席証明書 / 推薦状", "Bảng điểm, chuyên cần hoặc thư giới thiệu", 0],
  };
  const documentGrid = document.getElementById("visa-documents");
  documentGrid.innerHTML = Object.entries(docs).map(([key, value]) =>
    `<article class="visa-document-card ${value[2] ? "is-required" : "is-optional"}"><div class="visa-document-title"><h3>${value[0]}</h3><p>${value[1]}</p></div><div class="visa-required">${value[2] ? "必須 / Bắt buộc" : "該当者のみ / Nếu có"}</div><label class="visa-file-picker"><input type="file" name="documents[${key}]" accept=".jpg,.jpeg,.png,.webp,.pdf,image/jpeg,image/png,image/webp,application/pdf" ${value[2] ? "required" : ""}><b>ファイルを選ぶ</b><span>Chọn ảnh hoặc PDF</span><small>JPG, PNG, WebP, PDF · 最大 10 MB</small></label><div class="visa-file-name">未選択 / Chưa chọn</div></article>`,
  ).join("");
  const allowed = ["image/jpeg", "image/png", "image/webp", "application/pdf"];
  form.querySelectorAll("input[type=file]").forEach((input) =>
    input.addEventListener("change", () => {
      const output = input
        .closest(".visa-document-card")
        .querySelector(".visa-file-name");
      const card = input.closest(".visa-document-card");
      const previousUrl = card.dataset.previewUrl;
      if (previousUrl) URL.revokeObjectURL(previousUrl);
      card.querySelector(".visa-file-review")?.remove();
      const file = input.files[0];
      output.classList.remove("has-file");
      if (!file) {
        output.textContent = "未選択 / Chưa chọn";
        return;
      }
      if (file.size > 10 * 1024 * 1024 || !allowed.includes(file.type)) {
        input.value = "";
        output.textContent = "JPG, PNG, WebP, PDF（最大10 MB）";
        return;
      }
      output.textContent = "✓ " + file.name;
      output.classList.add("has-file");
      const url = URL.createObjectURL(file);
      card.dataset.previewUrl = url;
      const review = document.createElement("div");
      review.className = "visa-file-review";
      if (file.type === "application/pdf") {
        review.innerHTML = `<iframe title="${file.name}" src="${url}"></iframe>`;
      } else {
        const image = document.createElement("img");
        image.alt = file.name;
        image.src = url;
        review.appendChild(image);
      }
      const actions = document.createElement("div");
      actions.className = "visa-file-actions";
      actions.innerHTML =
        '<button type="button" data-action="preview">Preview / Xem</button>';
      actions
        .querySelector('[data-action="preview"]')
        .addEventListener("click", () =>
          window.open(url, "_blank", "noopener"),
        );
      review.appendChild(actions);
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
