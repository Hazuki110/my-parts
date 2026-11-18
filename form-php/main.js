/* ---- お問い合わせフォーム ---- */
(() => {
  const form = document.querySelector(".form");
  if (!form) return;
  const status = form.querySelector(".form__status");
  const button = form.querySelector("button[type=submit]");

  form.addEventListener("submit", async (e) => {
    e.preventDefault(); // ページ移動を止める(自分で送るため)

    if (!form.checkValidity()) {
      status.textContent = "未入力、または形式が正しくない項目があります。";
      form.reportValidity();
      return;
    }

    button.disabled = true; // 二重送信防止
    status.textContent = "送信中…";

    try {
      const res = await fetch(form.action, {
        method: "POST",
        body: new FormData(form),
      });
      const data = await res.json();
      status.textContent = data.message;
      if (data.ok) form.reset();
    } catch (err) {
      status.textContent = "通信に失敗しました。もう一度お試しください。";
    } finally {
      button.disabled = false;
    }
  });
})();
