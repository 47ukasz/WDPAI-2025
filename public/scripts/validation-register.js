import {
  isEmail,
  isNotEmpty,
  hasMinLength,
  isValidName,
  markValidation,
  arePasswordsSame,
  renderErrors,
  clearErrors,
} from "./validation-helpers.js";

const form = document.querySelector("form");
const emailInput = document.querySelector('input[name="email"]');
const userNameInput = document.querySelector('input[name="userName"]');
const surnameInput = document.querySelector('input[name="surname"]');
const passwordInput = document.querySelector('input[name="password"]');
const repeatPasswordInput = document.querySelector(
  'input[name="repeatPassword"]'
);
const formValidation = document.querySelector(".form-validation");

function validateEmail() {
  setTimeout(() => {
    const value = emailInput.value;
    markValidation(emailInput, isNotEmpty(value) && isEmail(value));
  }, 500);
}

function validateUserName() {
  setTimeout(() => {
    markValidation(
      userNameInput,
      isNotEmpty(userNameInput.value) &&
        hasMinLength(userNameInput.value, 2) &&
        isValidName(userNameInput.value)
    );
  }, 500);
}

function validateSurname() {
  setTimeout(() => {
    markValidation(
      surnameInput,
      isNotEmpty(surnameInput.value) &&
        hasMinLength(surnameInput.value, 2) &&
        isValidName(surnameInput.value)
    );
  }, 500);
}

function validatePassword() {
  setTimeout(() => {
    markValidation(
      passwordInput,
      isNotEmpty(passwordInput.value) && hasMinLength(passwordInput.value, 6)
    );

    validateRepeatPassword();
  }, 500);
}

function validateRepeatPassword() {
  setTimeout(() => {
    const pass = passwordInput.value;
    const repeat = repeatPasswordInput.value;

    markValidation(
      repeatPasswordInput,
      isNotEmpty(repeat) &&
        arePasswordsSame(pass, repeat) &&
        hasMinLength(repeat, 6)
    );
  }, 500);
}

function onSubmit(e) {
  let errors = [];

  const emailValue = emailInput.value.trim();
  const userNameValue = userNameInput.value.trim();
  const surnameValue = surnameInput.value.trim();
  const passwordValue = passwordInput.value;
  const repeatPasswordValue = repeatPasswordInput.value;

  // email
  if (!isNotEmpty(emailValue)) {
    errors.push("Wprowadź adres email.");
  } else if (!isEmail(emailValue)) {
    errors.push("Wprowadź poprawny adres email.");
  }

  // imie
  if (!isNotEmpty(userNameValue)) {
    errors.push("Wprowadź imię.");
  } else if (!hasMinLength(userNameValue, 2)) {
    errors.push("Imię powinno składać się z conajmniej dwóch znaków.");
  } else if (!isValidName(userNameValue)) {
    errors.push("Imię powinno składać się wyłącznie z liter alfabetu.");
  }

  // nazwisko
  if (!isNotEmpty(surnameValue)) {
    errors.push("Wprowadź nazwisko.");
  } else if (!hasMinLength(surnameValue, 2)) {
    errors.push("Nazwisko powinno składać się z conajmniej dwóch znaków.");
  } else if (!isValidName(surnameValue)) {
    errors.push("Nazwisko powinno składać się wyłącznie z liter alfabetu.");
  }

  // haslo
  if (!isNotEmpty(passwordValue)) {
    errors.push("Wprowadź hasło.");
  } else if (!hasMinLength(passwordValue, 6)) {
    errors.push("Hasło powinno składać się z conajmniej sześciu znaków.");
  }

  // haslo 2
  if (!isNotEmpty(repeatPasswordValue)) {
    errors.push("Powtórz hasło.");
  } else if (
    isNotEmpty(passwordValue) &&
    !arePasswordsSame(passwordValue, repeatPasswordValue)
  ) {
    errors.push("Hasła muszą być takie same.");
  }

  markValidation(emailInput, isNotEmpty(emailValue) && isEmail(emailValue));
  markValidation(
    userNameInput,
    isNotEmpty(userNameValue) &&
      hasMinLength(userNameValue, 2) &&
      isValidName(userNameValue)
  );
  markValidation(
    surnameInput,
    isNotEmpty(surnameValue) &&
      hasMinLength(surnameValue, 2) &&
      isValidName(surnameValue)
  );
  markValidation(
    passwordInput,
    isNotEmpty(passwordValue) && hasMinLength(passwordValue, 6)
  );
  markValidation(
    repeatPasswordInput,
    isNotEmpty(repeatPasswordValue) &&
      arePasswordsSame(passwordValue, repeatPasswordValue) &&
      hasMinLength(repeatPasswordValue, 6)
  );

  if (errors.length > 0) {
    e.preventDefault();
    renderErrors(errors, formValidation);
  } else {
    clearErrors(formValidation);
  }
}

emailInput.addEventListener("keyup", validateEmail);
userNameInput.addEventListener("keyup", validateUserName);
surnameInput.addEventListener("keyup", validateSurname);
passwordInput.addEventListener("keyup", validatePassword);
repeatPasswordInput.addEventListener("keyup", validateRepeatPassword);

form.addEventListener("submit", onSubmit);
