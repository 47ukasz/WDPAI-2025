import {
  isNotEmpty,
  hasMinLength,
  hasMaxLength,
  isPriceValid,
  isPhoneValid,
  markValidation,
  clearErrors,
  renderErrors,
  isAllowedImageType,
} from "./validation-helpers.js";

const form = document.querySelector("form");
const titleInput = document.querySelector('input[name="title"]');
const descriptionInput = document.querySelector('textarea[name="description"]');
const priceInput = document.querySelector('input[name="price"]');
const phoneInput = document.querySelector('input[name="phone_number"]');
const photoInput = document.querySelector('input[name="photo"]');

const formValidation = document.querySelector(".form-validation");

function validateTitle() {
  setTimeout(() => {
    const v = titleInput.value;
    const ok = isNotEmpty(v) && hasMinLength(v, 5) && hasMaxLength(v, 80);
    markValidation(titleInput, ok);
  }, 300);
}

function validateDescription() {
  setTimeout(() => {
    const v = descriptionInput.value;
    const ok = isNotEmpty(v) && hasMinLength(v, 20) && hasMaxLength(v, 1000);
    markValidation(descriptionInput, ok);
  }, 300);
}

function validatePrice() {
  setTimeout(() => {
    const ok = isPriceValid(priceInput.value);
    markValidation(priceInput, ok);
  }, 300);
}

function validatePhone() {
  setTimeout(() => {
    const ok = isNotEmpty(phoneInput.value) && isPhoneValid(phoneInput.value);
    markValidation(phoneInput, ok);
  }, 300);
}

function validatePhoto() {
  // photo jest opcjonalne
  const file = photoInput.files && photoInput.files[0];
  if (!file) {
    markValidation(photoInput, true);
    return;
  }

  const maxBytes = 4 * 1024 * 1024;

  const ok = isAllowedImageType(file) && file.size <= maxBytes;
  markValidation(photoInput, ok);
}

function onSubmit(e) {
  const errors = [];

  const title = titleInput.value.trim();
  const description = descriptionInput.value.trim();
  const price = priceInput.value;
  const phone = phoneInput.value.trim();
  const file = photoInput.files && photoInput.files[0];

  // tytul
  if (!isNotEmpty(title)) {
    errors.push("Wprowadź tytuł ogłoszenia.");
  } else if (!hasMinLength(title, 5)) {
    errors.push("Tytuł powinien mieć co najmniej 5 znaków.");
  } else if (!hasMaxLength(title, 80)) {
    errors.push("Tytuł nie może mieć więcej niż 80 znaków.");
  }

  // opis
  if (!isNotEmpty(description)) {
    errors.push("Wprowadź opis ogłoszenia.");
  } else if (!hasMinLength(description, 20)) {
    errors.push("Opis powinien mieć co najmniej 20 znaków.");
  } else if (!hasMaxLength(description, 1000)) {
    errors.push("Opis nie może mieć więcej niż 1000 znaków.");
  }

  // cena
  if (!isPriceValid(price)) {
    errors.push("Wprowadź poprawną cenę (0 lub więcej).");
  }

  // numer telefonu
  if (!isNotEmpty(phone)) {
    errors.push("Wprowadź numer telefonu.");
  } else if (!isPhoneValid(phone)) {
    errors.push("Wprowadź poprawny numer telefonu (+48 123 456 789).");
  }

  // zdjecie
  if (file) {
    const maxBytes = 4 * 1024 * 1024;

    if (!isAllowedImageType(file)) {
      errors.push("Zdjęcie musi być w formacie SVG, PNG lub JPG.");
    } else if (file.size > maxBytes) {
      errors.push("Zdjęcie jest za duże (max 4MB).");
    }
  }

  markValidation(
    titleInput,
    isNotEmpty(title) && hasMinLength(title, 5) && hasMaxLength(title, 80)
  );
  markValidation(
    descriptionInput,
    isNotEmpty(description) &&
      hasMinLength(description, 20) &&
      hasMaxLength(description, 1000)
  );
  markValidation(priceInput, isPriceValid(price));
  markValidation(phoneInput, isNotEmpty(phone) && isPhoneValid(phone));
  validatePhoto();

  if (errors.length > 0) {
    e.preventDefault();
    renderErrors(errors, formValidation);
  } else {
    clearErrors(formValidation);
  }
}

titleInput.addEventListener("keyup", validateTitle);
descriptionInput.addEventListener("keyup", validateDescription);
priceInput.addEventListener("input", validatePrice);
phoneInput.addEventListener("input", validatePhone);
photoInput.addEventListener("change", validatePhoto);

form.addEventListener("submit", onSubmit);
