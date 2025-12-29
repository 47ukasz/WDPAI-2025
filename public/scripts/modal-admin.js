const modal = document.querySelector(".modal");
const modalTriggerButtons = document.querySelectorAll(".modal-trigger");
const modalTitleSpan = modal.querySelector(".modal-title span");
const modalForm = modal.querySelector("form");
const modalFormInput = modalForm.querySelector("input");
const cancelBtn = modalForm.querySelector("button[type='button']");

const modalBackground = modal.parentElement;

function handleOpenModal(item) {
  if (!item) {
    return;
  }

  const userId = item.dataset.id;
  const userFullName = item.querySelector("#full-name");
  const modalTitleText = userFullName.textContent;

  modalFormInput.value = userId;

  modalTitleSpan.textContent = modalTitleText;
  modalBackground.classList.add("background-show");
}

function handleCloseModal() {
  modalBackground.classList.remove("background-show");
}

function handleDeleteUser(id) {
  const user = document.querySelector(`tr[data-id="${id}"]`);
  const totalItems = modalTriggerButtons.length;

  if (!user) {
    return;
  }

  if (totalItems <= 1) {
    const mainElement = document.querySelector("main");
    const sections = mainElement.querySelectorAll("section");
    const outOfItemsPara = document.createElement("p");
    outOfItemsPara.classList.add("no-items");
    outOfItemsPara.textContent = "Brak treści do wyświetlenia.";

    mainElement.append(outOfItemsPara);

    sections.forEach((section) => section.remove());
  }

  user.remove();
}

function handleFormSubmit(e) {
  e.preventDefault();

  const userId = modalFormInput.value;

  fetch("user-delete", {
    method: "DELETE", // na razie dowolne
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      id: userId,
    }),
  })
    .then((res) => {
      if (!res.ok) {
        throw new Error("Request failed");
      }

      return res.json();
    })
    .then((data) => {
      const isDeleted = data.deleted;
      console.log("Success:", isDeleted);

      if (isDeleted) {
        handleDeleteUser(userId);
      }

      handleCloseModal();
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Coś poszło nie tak");
    });
}

modalTriggerButtons.forEach((btn) => {
  const user = btn.closest("tr");

  btn.addEventListener("click", () => handleOpenModal(user));
});

cancelBtn.addEventListener("click", handleCloseModal);
modalForm.addEventListener("submit", handleFormSubmit);
