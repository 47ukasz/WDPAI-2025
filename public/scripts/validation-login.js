import {
  isEmail,
  isNotEmpty,
  markValidation,
  renderErrors,
  clearErrors,
} from "./validation-helpers.js";

const form = document.querySelector("form");
const emailInput = document.querySelector('input[name="email"]');
const passwordInput = document.querySelector('input[name="password"]');
const formValidation = document.querySelector(".form-validation");

function validateEmail() {
  setTimeout(function () {
    markValidation(emailInput, isEmail(emailInput.value));
  }, 500);
}

function validatePassword() {
  setTimeout(function () {
    markValidation(passwordInput, isNotEmpty(passwordInput.value));
  }, 500);
}

function onSubmit(e) {
  let errors = [];
  const emailValue = emailInput.value;
  const passwordValue = passwordInput.value;

  if (!isNotEmpty(emailValue)) {
    errors.push("Wprowadź adres email.");
  } else if (!isEmail(emailValue)) {
    errors.push("Wprowadź poprawny adres email.");
  }

  if (!isNotEmpty(passwordValue)) {
    errors.push("Wprowadź hasło.");
  }

  markValidation(emailInput, isEmail(emailInput.value));
  markValidation(passwordInput, isNotEmpty(passwordInput.value));

  if (errors.length > 0) {
    e.preventDefault();
    renderErrors(errors, formValidation);
  } else {
    clearErrors(formValidation);
  }
}

emailInput.addEventListener("keyup", validateEmail);
passwordInput.addEventListener("keyup", validatePassword);

form.addEventListener("submit", onSubmit);
