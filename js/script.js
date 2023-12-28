const showInputBtn = document.querySelector("#showInputBtn");
const passwordInput = document.querySelector("#passwordInput");

showInputBtn.addEventListener("click", () => {
    passwordInput.type = passwordInput.type === "password" ? "text" : "password";
    showInputBtn.textContent = passwordInput.type === "password" ? "show" : "hide";
});
