let loginBtn = document.querySelector("#loginBtn");
let registerBtn = document.querySelector("#registerBtn");

if (loginBtn != null) loginBtn.onclick = (e) => login(e);
if (registerBtn != null) registerBtn.onclick = (e) => register(e);
