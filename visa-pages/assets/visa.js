(function () {
  const form = document.getElementById("visa-application-form");
  if (!form) return;
  const docs = {
    residence_card_front: ["在留カード・表面", "Thẻ ngoại kiều - mặt trước", 0],
    residence_card_back: ["在留カード・裏面", "Thẻ ngoại kiều - mặt sau", 0],
    passport_vietnam: ["パスポート・身分事項ページ", "Hộ chiếu Việt Nam - mặt thông tin", 0],
    passport_residence_status: ["パスポート・在留資格ページ", "Hộ chiếu - tư cách lưu trú Nhật Bản", 0],
    insurance_front: ["保険証・表面", "Thẻ bảo hiểm - mặt trước", 0],
    insurance_back: ["保険証・裏面", "Thẻ bảo hiểm - mặt sau", 0],
    sankyu_senmonkyu: ["三級・専門級", "Chứng chỉ SANKYU hoặc SENMONKYU", 0],
    tokutei_certificate: ["特定技能合格証", "Chứng chỉ Tokutei chuyên ngành", 0, 1],
    jlpt_certificate: ["日本語能力試験（JLPT）", "Chứng chỉ tiếng Nhật JLPT", 0],
    gensen: ["源泉徴収票", "Phiếu khấu trừ thuế Gensen", 0],
    tax_certificate: ["課税証明書", "Giấy chứng nhận thuế năm gần nhất", 0],
    tax_payment_certificate: ["納税証明書", "Giấy chứng nhận đã đóng thuế", 0],
    juminhyo_mynumber: ["マイナンバー付の住民票", "Juminhyo có MyNumber", 0],
    nenkin_record: ["年金記録照会", "Giấy tra cứu lịch sử Nenkin", 0],
    insured_record_nofu2: ["被保険者記録照会（納付II）", "Lịch sử đóng Nenkin", 0],
    health_check: ["健康診断書", "Giấy khám sức khỏe", 0, 1],
    photo_3x4: ["証明写真 3×4", "Ảnh thẻ 3×4", 0],
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
      items: ["juminhyo_mynumber", "nenkin_record", "insured_record_nofu2", "health_check"],
    },
  ];
  const documentGrid = document.getElementById("visa-documents");
  const documentCard = (key, value) =>
    `<article class="visa-document-card ${value[2] ? "is-required" : "is-optional"}"><div class="visa-document-title"><h3>${value[0]}</h3><p>${value[1]}</p></div><div class="visa-required">${value[2] ? "必須 / Bắt buộc" : "任意 / Tùy ý"}</div><label class="visa-file-picker"><input type="file" name="documents[${key}]${value[3] ? "[]" : ""}" accept=".jpg,.jpeg,.png,.webp,.pdf,image/jpeg,image/png,image/webp,application/pdf" ${value[2] ? "required" : ""} ${value[3] ? "multiple" : ""}><b>ファイルを選ぶ</b><span>Kéo thả, Ctrl+V hoặc chọn file</span><small>JPG, PNG, WebP, PDF · 最大 10 MB</small></label><div class="visa-file-name">未選択 / Chưa chọn</div></article>`;
  documentGrid.innerHTML = categories
    .map(
      (category, index) =>
        `<section class="visa-document-category" data-step="${index}" ${index ? "hidden" : ""}><header><span>${String(index + 1).padStart(2, "0")}</span><div><h3>${category.ja}</h3><p>${category.vi}</p></div></header><div class="visa-category-grid">${category.items.map((key) => documentCard(key, docs[key])).join("")}</div></section>`,
    )
    .join("");
  const stepList = document.getElementById("visa-step-list");
  const scrollToChecklist = () => requestAnimationFrame(() =>
    stepList.closest(".visa-panel").scrollIntoView({ behavior: "smooth", block: "start" }),
  );
  stepList.innerHTML = categories
    .map(
      (category, index) =>
        `<li data-step="${index}"><button type="button" data-step-nav="${index}" aria-label="Lượt ${index + 1}: ${category.vi}"><span>${index + 1}</span><b>${category.vi}</b><small>${category.ja}</small></button></li>`,
    )
    .join("");
  const allowed = ["image/jpeg", "image/png", "image/webp", "application/pdf"];
  const categorySections = Array.from(documentGrid.querySelectorAll(".visa-document-category"));
  const applicantPanel = form.querySelector(".visa-fields").closest(".visa-panel");
  const stepStatus = document.getElementById("visa-step-status");
  const backButton = document.getElementById("visa-step-back");
  const saveExitButton = document.getElementById("visa-step-save-exit");
  const submitButton = document.getElementById("visa-step-submit");
  const activeApplication = document.getElementById("visa-active-application");
  const activeCode = document.getElementById("visa-active-code");
  const activePin = document.getElementById("visa-active-pin");
  let currentStep = 0;
  const completedSteps = new Set();
  let lastFocusedCard = null;
  let resumeSession = null;
  const alertBox = document.getElementById("visa-alert");
  const apiBase = new URL(form.dataset.endpoint).origin;
  const resumeDetails = document.getElementById("visa-resume-details");
  const resumeCodeInput = document.getElementById("visa-resume-code");
  const resumePinInput = document.getElementById("visa-resume-pin");
  try {
    localStorage.removeItem("visa_application_code");
  } catch (_) {
    // The form no longer relies on persistent browser storage.
  }
  const resumeButton = document.getElementById("visa-resume-button");
  const resumeStatus = document.getElementById("visa-resume-status");
  const resultDialog = document.getElementById("visa-result-dialog");
  const resultCode = document.getElementById("visa-result-code");
  const resultPin = document.getElementById("visa-result-pin");
  const resultLink = document.getElementById("visa-result-link");
  const entryDialog = document.getElementById("visa-entry-dialog");

  const showError = (message, target = alertBox) => {
    alertBox.textContent = message;
    alertBox.hidden = false;
    requestAnimationFrame(() => {
      target.scrollIntoView({ behavior: "smooth", block: "center" });
      if (target.matches?.("input, button, select, textarea"))
        target.focus({ preventScroll: true });
    });
  };

  const clearError = () => {
    alertBox.hidden = true;
    alertBox.textContent = "";
  };

  const stepButtonLabel = (index) => index === categories.length - 1
    ? "Hoàn tất"
    : "Kế Tiếp →";
  const refreshStepIndicators = () => {
    stepList.querySelectorAll("li").forEach((item, position) => {
      item.classList.toggle("is-current", position === currentStep);
      item.classList.toggle("is-complete", completedSteps.has(position));
      const button = item.querySelector("button");
      if (position === currentStep) button.setAttribute("aria-current", "step");
      else button.removeAttribute("aria-current");
    });
  };
  const setStep = (index, message = "") => {
    currentStep = index;
    lastFocusedCard = null;
    categorySections.forEach((section, position) => {
      section.hidden = position !== index;
    });
    applicantPanel.hidden = index !== 0 && Boolean(resumeSession);
    backButton.hidden = index === 0;
    submitButton.textContent = stepButtonLabel(index);
    refreshStepIndicators();
    stepStatus.textContent = message;
    stepStatus.hidden = !message;
  };
  backButton.addEventListener("click", () => {
    if (currentStep > 0) {
      setStep(currentStep - 1);
      scrollToChecklist();
    }
  });
  let saveAndExit = false;
  saveExitButton.addEventListener("click", () => {
    saveAndExit = true;
    form.requestSubmit(submitButton);
  });
  stepList.addEventListener("click", (event) => {
    const button = event.target.closest("button[data-step-nav]");
    if (!button || submitButton.disabled) return;
    const nextStep = Number(button.dataset.stepNav);
    if (nextStep === currentStep) return;
    const hasPendingFiles = Array.from(
      categorySections[currentStep].querySelectorAll('input[type="file"]'),
    ).some((input) => input.files?.length);
    setStep(nextStep, hasPendingFiles
      ? "File ở lượt vừa rời đi chưa được lưu. Hãy quay lại lượt đó và bấm Lưu."
      : "");
    scrollToChecklist();
  });
  setStep(0);

  const setResumeStatus = (message, isError = false) => {
    resumeStatus.textContent = message;
    resumeStatus.style.color = isError ? "#7c241c" : "#237a52";
    resumeStatus.hidden = !message;
  };

  const renderSavedDocuments = (application) => {
    completedSteps.clear();
    form.querySelectorAll(".visa-document-card").forEach((card) => {
      card.classList.remove("has-saved-file");
      delete card.dataset.savedNames;
      card.querySelector(".visa-saved-list")?.remove();
      if (!card.querySelector('input[type="file"]').files?.length) {
        const output = card.querySelector(".visa-file-name");
        output.textContent = "未選択 / Chưa chọn";
        output.classList.remove("has-file");
      }
    });
    const grouped = new Map();
    for (const item of application.documents || []) {
      if (!grouped.has(item.type)) grouped.set(item.type, []);
      grouped.get(item.type).push(item);
      const step = categories.findIndex((category) => category.items.includes(item.type));
      if (step >= 0) completedSteps.add(step);
    }
    for (const [type, items] of grouped) {
      const input = Array.from(form.querySelectorAll('input[type="file"]')).find(
        (element) => element.name.startsWith(`documents[${type}]`),
      );
      const card = input?.closest(".visa-document-card");
      if (!card) continue;
      card.classList.add("has-saved-file");
      card.dataset.savedNames = JSON.stringify(items.map((item) => item.name));
      const output = card.querySelector(".visa-file-name");
      if (!input.files?.length) {
        output.textContent = `✓ Đã lưu: ${items.map((item) => item.name).join(", ")}`;
        output.classList.add("has-file");
      }
      const list = document.createElement("div");
      list.className = "visa-saved-list";
      items.forEach((item) => {
        const row = document.createElement("div");
        row.className = "visa-saved-item";
        const name = document.createElement("span");
        name.textContent = item.name;
        const remove = document.createElement("button");
        remove.type = "button";
        remove.className = "visa-remove-file";
        remove.textContent = "Xóa / 削除";
        remove.dataset.deleteSaved = item.id;
        row.append(name, remove);
        list.append(row);
      });
      card.append(list);
    }
    refreshStepIndicators();
  };

  const openApplication = (application, pin) => {
    resumeSession = { code: application.application_code, pin };
    form.hidden = false;
    form.elements.full_name.value = application.full_name || "";
    form.elements.company_name.value = application.company_name || "";
    renderSavedDocuments(application);
    activeCode.textContent = application.application_code;
    activePin.textContent = pin;
    activeApplication.hidden = false;
    setStep(currentStep);
    setResumeStatus(`Đã mở hồ sơ ${application.application_code}.`);
  };

  documentGrid.addEventListener("click", async (event) => {
    const button = event.target.closest("button[data-delete-saved]");
    if (!button || !resumeSession || submitButton.disabled || !window.confirm("Xóa tài liệu đã lưu này khỏi hồ sơ và Google Drive?")) return;
    button.disabled = true;
    submitButton.disabled = true;
    saveExitButton.disabled = true;
    backButton.disabled = true;
    clearError();
    try {
      const response = await fetch(`${apiBase}/api/visa/delete-document`, {
        method: "POST",
        headers: { "content-type": "application/json" },
        body: JSON.stringify({
          application_code: resumeSession.code,
          pin: resumeSession.pin,
          document_id: button.dataset.deleteSaved,
        }),
      });
      const result = await response.json();
      if (!response.ok || !result.ok) throw new Error(result.error || "Không thể xóa tài liệu.");
      renderSavedDocuments(result.application);
      setStep(currentStep, "Đã xóa tài liệu đã lưu.");
    } catch (error) {
      button.disabled = false;
      showError(error.message);
    } finally {
      submitButton.disabled = false;
      saveExitButton.disabled = false;
      backButton.disabled = false;
    }
  });

  const showResult = (result) => {
    const pin = result.pin || resumeSession?.pin || "";
    resultCode.textContent = result.application_code || "";
    resultPin.textContent = pin;
    resultLink.href = result.resume_url;
    resultLink.textContent = result.resume_url;
    document.getElementById("visa-result-title").textContent = result.partial
      ? "Hồ sơ đã lưu, còn tài liệu chưa tải"
      : result.exiting
        ? "Đã lưu hồ sơ — có thể tiếp tục sau"
      : result.complete
        ? "Đã hoàn tất hồ sơ"
        : result.stageNumber
          ? `Đã lưu lượt ${result.stageNumber}/4`
          : result.resumed
            ? "Đã lưu tài liệu bổ sung"
            : "Đã lưu hồ sơ thành công";
    resultDialog.querySelector(".visa-save-warning").textContent = result.partial
      ? `Đã xác nhận tải ${result.savedCount}/${result.total} file. Hãy lưu mã hồ sơ và PIN, kiểm tra những tài liệu đã lưu rồi gửi lại những file còn thiếu. ${result.error || ""}`
      : result.exiting
        ? "Hãy lưu mã hồ sơ và PIN trước khi đóng trang. Bạn có thể quay lại để nộp bổ sung."
      : "Hãy lưu lại mã hồ sơ và PIN để có thể nộp thêm tài liệu lần sau.";
    document.getElementById("visa-copy-result").textContent = "Sao chép thông tin";
    resultDialog.showModal();
  };

  resumeButton.addEventListener("click", async () => {
    const applicationCode = resumeCodeInput.value.trim();
    const pin = resumePinInput.value.trim();
    if (!applicationCode || !/^\d{6}$/.test(pin)) {
      setResumeStatus("Vui lòng nhập mã hồ sơ và PIN gồm 6 số.", true);
      return;
    }
    resumeButton.disabled = true;
    setResumeStatus("Đang mở hồ sơ…");
    try {
      const response = await fetch(`${apiBase}/api/visa/login`, {
        method: "POST",
        headers: { "content-type": "application/json" },
        body: JSON.stringify({ application_code: applicationCode, pin }),
      });
      const result = await response.json();
      if (!response.ok || !result.ok)
        throw new Error(result.error || "Không thể mở hồ sơ.");
      openApplication(result.application, pin);
      form.scrollIntoView({ behavior: "smooth", block: "start" });
    } catch (error) {
      setResumeStatus(error.message, true);
      resumeDetails.scrollIntoView({ behavior: "smooth", block: "center" });
    } finally {
      resumeButton.disabled = false;
    }
  });

  const requestedCode = new URLSearchParams(location.search).get("resume");
  resumeDetails.open = Boolean(requestedCode);
  resumeCodeInput.value = requestedCode || "";
  resumePinInput.value = "";
  if (requestedCode) {
    resumePinInput.focus();
  }
  const entryPromptKey = "visa_entry_prompt_seen_v2";
  const rememberEntryChoice = () => {
    try { sessionStorage.setItem(entryPromptKey, "1"); } catch (_) { /* Session storage is optional. */ }
    entryDialog.close();
  };
  document.getElementById("visa-entry-new").addEventListener("click", () => {
    rememberEntryChoice();
    try {
      sessionStorage.removeItem("visa_pending_submission_id");
      sessionStorage.removeItem("visa_pending_identity");
    } catch (_) { /* Session storage is optional. */ }
    resumeDetails.open = false;
    resumeCodeInput.value = "";
    resumePinInput.value = "";
    form.elements.full_name.focus();
  });
  document.getElementById("visa-entry-resume").addEventListener("click", () => {
    rememberEntryChoice();
    resumeDetails.open = true;
    resumeDetails.scrollIntoView({ behavior: "smooth", block: "start" });
    resumeCodeInput.focus();
  });
  entryDialog.addEventListener("cancel", (event) => event.preventDefault());
  let entryChoiceSeen = false;
  try { entryChoiceSeen = sessionStorage.getItem(entryPromptKey) === "1"; } catch (_) { /* Show prompt. */ }
  if (!requestedCode && !entryChoiceSeen) entryDialog.showModal();

  document.getElementById("visa-close-result").addEventListener("click", () =>
    resultDialog.close(),
  );
  document.getElementById("visa-copy-result").addEventListener("click", async () => {
    const text = `Mã hồ sơ: ${resultCode.textContent}\nMã PIN: ${resultPin.textContent}\nLink tiếp tục: ${resultLink.href}`;
    await navigator.clipboard.writeText(text);
    document.getElementById("visa-copy-result").textContent = "Đã sao chép ✓";
  });
  document.getElementById("visa-copy-active").addEventListener("click", async () => {
    await navigator.clipboard.writeText(
      `Mã hồ sơ: ${activeCode.textContent}\nMã PIN: ${activePin.textContent}`,
    );
    document.getElementById("visa-copy-active").textContent = "Đã sao chép ✓";
  });

  const filesFromClipboard = (clipboard) => {
    if (!clipboard) return [];
    if (clipboard.files?.length) return Array.from(clipboard.files);
    const files = [];
    for (const item of clipboard.items || []) {
      if (item.kind === "file") files.push(item.getAsFile());
    }
    return files.filter(Boolean);
  };

  const pendingFiles = new WeakMap();
  const internalFileChanges = new WeakSet();
  const sameFile = (left, right) =>
    left.name === right.name && left.size === right.size &&
    left.lastModified === right.lastModified && left.type === right.type;
  const mergeFiles = (previous, selected, multiple) => {
    if (!multiple) return selected.slice(0, 1);
    const merged = [...previous];
    for (const file of selected)
      if (!merged.some((existing) => sameFile(existing, file))) merged.push(file);
    return merged;
  };
  const setFiles = (input, files) => {
    const transfer = new DataTransfer();
    files.forEach((file) => transfer.items.add(file));
    input.files = transfer.files;
    internalFileChanges.add(input);
    try {
      input.dispatchEvent(new Event("change", { bubbles: true }));
    } finally {
      internalFileChanges.delete(input);
    }
  };
  const assignFiles = (input, files) => {
    const selected = Array.from(files || []).filter(Boolean);
    if (!selected.length) return;
    setFiles(input, mergeFiles(pendingFiles.get(input) || [], selected, input.multiple));
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
      if (input.multiple && !internalFileChanges.has(input)) {
        const merged = mergeFiles(
          pendingFiles.get(input) || [], Array.from(input.files || []), true,
        );
        const transfer = new DataTransfer();
        merged.forEach((file) => transfer.items.add(file));
        input.files = transfer.files;
      }
      const output = input
        .closest(".visa-document-card")
        .querySelector(".visa-file-name");
      const card = input.closest(".visa-document-card");
      const previousUrls = JSON.parse(card.dataset.previewUrls || "[]");
      previousUrls.forEach((url) => URL.revokeObjectURL(url));
      card.querySelector(".visa-file-review")?.remove();
      const files = Array.from(input.files || []);
      pendingFiles.set(input, files);
      output.classList.remove("has-file");
      if (!files.length) {
        const savedNames = JSON.parse(card.dataset.savedNames || "[]");
        output.textContent = savedNames.length
          ? `✓ Đã lưu: ${savedNames.join(", ")}`
          : "未選択 / Chưa chọn";
        output.classList.toggle("has-file", savedNames.length > 0);
        return;
      }
      if (files.some((file) => file.size > 10 * 1024 * 1024 || !allowed.includes(file.type))) {
        setFiles(input, files.filter((file) => file.size <= 10 * 1024 * 1024 && allowed.includes(file.type)));
        const documentName = card.querySelector(".visa-document-title p")?.textContent;
        showError(
          `${documentName || "Tài liệu"}: file không đúng định dạng hoặc vượt quá 10 MB.`,
          card,
        );
        return;
      }
      output.textContent = "✓ " + files.map((file) => file.name).join(", ");
      output.classList.add("has-file");
      const review = document.createElement("div");
      review.className = "visa-file-review";
      const previewUrls = [];
      files.forEach((file, fileIndex) => {
        const url = URL.createObjectURL(file);
        previewUrls.push(url);
        const item = document.createElement("div");
        item.className = "visa-file-preview-item";
        if (file.type === "application/pdf") {
          const frame = document.createElement("iframe");
          frame.title = file.name;
          frame.src = url;
          item.appendChild(frame);
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
        const remove = document.createElement("button");
        remove.type = "button";
        remove.textContent = "Xóa file đã chọn";
        remove.addEventListener("click", () => {
          setFiles(input, Array.from(input.files || []).filter((_, index) => index !== fileIndex));
        });
        actions.append(remove);
        item.appendChild(actions);
        review.appendChild(item);
      });
      card.dataset.previewUrls = JSON.stringify(previewUrls);
      card.appendChild(review);
    }),
  );
  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    const exitAfterSave = saveAndExit;
    saveAndExit = false;
    clearError();
    const invalidField = form.querySelector(":invalid");
    if (invalidField) {
      const message =
        invalidField.name === "full_name"
          ? "Vui lòng nhập họ và tên."
          : invalidField.name === "company_name"
            ? "Vui lòng nhập tên công ty ứng tuyển."
            : "Vui lòng kiểm tra trường được đánh dấu.";
      showError(message, invalidField);
      invalidField.reportValidity();
      return;
    }
    const button = submitButton;
    button.disabled = true;
    backButton.disabled = true;
    saveExitButton.disabled = true;
    button.textContent = "送信中… / Đang gửi…";
    const selected = Array.from(fileInputs).flatMap((input) =>
      Number(input.closest(".visa-document-category").dataset.step) === currentStep
        ? Array.from(input.files || []).map((file) => ({ input, file }))
        : [],
    );
    let savedCount = 0;
    let initialResult = null;
    let latestResult = null;
    let currentUpload = null;
    const removeSelectedFile = ({ input, file }) => {
      const remaining = Array.from(input.files || []);
      const index = remaining.findIndex((item) => item === file || sameFile(item, file));
      if (index >= 0) remaining.splice(index, 1);
      setFiles(input, remaining);
    };
    const send = async (data) => {
      const response = await fetch(form.dataset.endpoint, { method: "POST", body: data });
      const result = await response.json().catch(() => ({
        ok: false,
        error: `Máy chủ trả về HTTP ${response.status} mà không có nội dung chi tiết.`,
      }));
      if (!response.ok || !result.ok)
        throw new Error(result.error || `Máy chủ trả về HTTP ${response.status}.`);
      return result;
    };
    try {
      if (!resumeSession) {
        const applicantIdentity = JSON.stringify([
          form.elements.full_name.value.trim(),
          form.elements.company_name.value.trim(),
        ]);
        let submissionId = sessionStorage.getItem("visa_pending_submission_id");
        if (
          !submissionId ||
          sessionStorage.getItem("visa_pending_identity") !== applicantIdentity
        ) {
          submissionId = crypto.randomUUID().replaceAll("-", "");
          sessionStorage.setItem("visa_pending_submission_id", submissionId);
          sessionStorage.setItem("visa_pending_identity", applicantIdentity);
        }
        const metadata = new FormData();
        metadata.set("full_name", form.elements.full_name.value.trim());
        metadata.set("company_name", form.elements.company_name.value.trim());
        metadata.set("submission_id", submissionId);
        initialResult = await send(metadata);
        if (!initialResult.application?.application_code || !initialResult.pin)
          throw new Error("Máy chủ chưa trả đủ mã hồ sơ và PIN. Vui lòng thử lại.");
        openApplication(initialResult.application, initialResult.pin);
        sessionStorage.removeItem("visa_pending_submission_id");
        sessionStorage.removeItem("visa_pending_identity");
      }
      latestResult = initialResult;
      for (const { input, file } of selected) {
        button.textContent = `Đang tải file ${savedCount + 1}/${selected.length}…`;
        currentUpload = { input, file };
        const data = new FormData();
        data.set("resume_code", resumeSession.code);
        data.set("resume_pin", resumeSession.pin);
        data.append(input.name, file, file.name);
        latestResult = await send(data);
        savedCount += 1;
        removeSelectedFile(currentUpload);
        currentUpload = null;
      }
      if (latestResult?.application) renderSavedDocuments(latestResult.application);
      clearError();
      if (exitAfterSave || currentStep === categories.length - 1) {
        showResult({
          ...latestResult,
          application_code: resumeSession.code,
          pin: resumeSession.pin,
          resume_url: `${location.origin}/apply-visa/?resume=${encodeURIComponent(resumeSession.code)}`,
          complete: !exitAfterSave && currentStep === categories.length - 1,
          exiting: exitAfterSave,
          stageNumber: currentStep + 1,
        });
        form.hidden = true;
        activeApplication.hidden = true;
        resumeDetails.open = true;
        resumeCodeInput.value = resumeSession.code;
        resumePinInput.value = "";
        setResumeStatus("Hồ sơ đã lưu. Dùng mã hồ sơ và PIN để tiếp tục khi cần.");
      } else {
        const completed = currentStep + 1;
        setStep(
          completed,
          `${selected.length ? "Đã lưu" : "Đã bỏ qua"} lượt ${completed}/4. Bạn có thể chọn bất kỳ nhóm nào để tiếp tục.`,
        );
        scrollToChecklist();
      }
    } catch (error) {
      const connectionLost =
        error instanceof TypeError || /load failed|failed to fetch/i.test(error.message);
      if (connectionLost && currentUpload && latestResult?.application) {
        try {
          const response = await fetch(`${apiBase}/api/visa/login`, {
            method: "POST",
            headers: { "content-type": "application/json" },
            body: JSON.stringify({
              application_code: resumeSession.code,
              pin: resumeSession.pin,
            }),
          });
          const status = await response.json();
          if (response.ok && status.ok) {
            const type = currentUpload.input.name.match(/^documents\[([^\]]+)\]/)?.[1];
            const count = (application) =>
              (application?.documents || []).filter(
                (item) => item.type === type && item.name === currentUpload.file.name,
              ).length;
            if (count(status.application) > count(latestResult.application)) {
              savedCount += 1;
              removeSelectedFile(currentUpload);
            }
            latestResult = { ...latestResult, application: status.application };
            renderSavedDocuments(status.application);
          }
        } catch (_) {
          // Keep the file selected if the connection is still unavailable.
        }
      }
      const message = connectionLost
        ? "Kết nối bị ngắt. File đang gửi có thể đã được lưu; hãy kiểm tra hồ sơ trước khi thử lại."
        : error.message;
      if (resumeSession && selected.length && (exitAfterSave || currentStep === categories.length - 1)) {
        showResult({
          application_code: resumeSession.code,
          pin: resumeSession.pin,
          resume_url: `${location.origin}/apply-visa/?resume=${encodeURIComponent(resumeSession.code)}`,
          partial: true,
          savedCount,
          total: selected.length,
          error: message,
        });
      } else {
        showError(message);
      }
    } finally {
      button.disabled = false;
      backButton.disabled = false;
      saveExitButton.disabled = false;
      button.textContent = stepButtonLabel(currentStep);
    }
  });
})();
