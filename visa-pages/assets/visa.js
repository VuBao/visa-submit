(function () {
  const form = document.getElementById("visa-application-form");
  if (!form) return;
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
