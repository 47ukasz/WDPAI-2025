export function renderErrors(errors, form) {
  if (!form) {
    return;
  }

  form.innerHTML = "";

  errors.forEach((error) => {
    const li = document.createElement("li");
    li.textContent = error;
    form.appendChild(li);
  });
}

export function clearErrors(form) {
  if (!form) {
    return;
  }

  form.innerHTML = "";
}

export function hasMinLength(value, len) {
  return value.trim().length >= len;
}

export function hasMaxLength(value, len) {
  return value.trim().length <= len;
}

export function isPriceValid(value) {
  if (value === "" || value === null || value === undefined) {
    return false;
  }

  const num = Number(value);

  return Number.isFinite(num) && num >= 0;
}

export function isPhoneValid(value) {
  const re = /^(?:\+48\s?)?(?:\d[\s-]?){8}\d$/;

  return re.test(value.trim());
}

export function markValidation(element, condition) {
  !condition
    ? element.classList.add("no-valid")
    : element.classList.remove("no-valid");
}

export function isEmail(email) {
  return /\S+@\S+\.\S+/.test(email);
}

export function isNotEmpty(value) {
  return value.trim() !== "";
}

export function isValidName(value) {
  return /^\p{L}+([\s-]\p{L}+)*$/u.test(value);
}

export function arePasswordsSame(password, confirmedPassword) {
  return password === confirmedPassword;
}

export function isAllowedImageType(file) {
  const allowed = ["image/png", "image/jpeg", "image/jpg", "image/svg+xml"];
  return allowed.includes(file.type);
}
