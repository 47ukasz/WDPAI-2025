const modal = document.querySelector(".modal");
const modalTriggerButtons = document.querySelectorAll(".modal-trigger");
const modalTitleSpan = modal.querySelector(".modal-title span");
const modalForm = modal.querySelector("form");
const modalFormInput = modalForm.querySelector("input");
const cancelBtn = modalForm.querySelector("button[type='button']");

const modalBackground = modal.parentElement;

function handleOpenModal(item) {
  console.log("test");

  if (!item) {
    return;
  }

  console.log("test 2");

  const itemId = item.dataset.id;
  const itemTitle = item.querySelector(".item-title").textContent;

  const splitedTitle = itemTitle.split(" ");
  const modalTitleText = `${splitedTitle[0]
    .charAt(0)
    .toUpperCase()}${splitedTitle[0].slice(1)} ${splitedTitle[1]}...`;

  modalFormInput.value = itemId;

  modalTitleSpan.textContent = modalTitleText;
  modalBackground.classList.add("background-show");
}

function handleCloseModal() {
  modalBackground.classList.remove("background-show");
}

function handleDeleteItem(id) {
  const item = document.querySelector(`li[data-id="${id}"]`);
  const totalItems = modalTriggerButtons.length;

  if (!item) {
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

  item.remove();
}

function handleFormSubmit(e) {
  e.preventDefault();

  const itemId = modalFormInput.value;

  fetch("offer-delete", {
    method: "DELETE", // na razie dowolne
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      id: itemId,
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
        handleDeleteItem(itemId);
      }

      handleCloseModal();
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Coś poszło nie tak");
    });
}

modalTriggerButtons.forEach((btn) => {
  const item = btn.closest("li");

  btn.addEventListener("click", () => handleOpenModal(item));
});

cancelBtn.addEventListener("click", handleCloseModal);
modalForm.addEventListener("submit", handleFormSubmit);
