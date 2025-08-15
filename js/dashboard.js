// Use unique variable names to avoid conflicts
const modalBtnAbrir = document.getElementById("btn-filtrar");
const modalOverlay = document.getElementById("modalOverlay");
const modalBtnFechar = document.getElementById("closeModal");
const modalBtnCancelar = document.getElementById("cancelModal");

// Add null checks for safety
if (modalBtnAbrir) {
  modalBtnAbrir.addEventListener("click", () => {
    modalOverlay.classList.add("active");
  });
}

if (modalBtnFechar) {
  modalBtnFechar.addEventListener("click", () => {
    modalOverlay.classList.remove("active");
  });
}

if (modalBtnCancelar) {
  modalBtnCancelar.addEventListener("click", () => {
    modalOverlay.classList.remove("active");
  });
}

if (modalOverlay) {
  modalOverlay.addEventListener("click", (e) => {
    if (e.target === modalOverlay) {
      modalOverlay.classList.remove("active");
    }
  });
}
