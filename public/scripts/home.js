const spinner = document.getElementById("spinner");
const items = document.querySelector(".items");
const pagination = document.querySelector(".pagination");

function loadData() {
  spinner.removeAttribute("hidden");
  fetch("http://localhost:8080/search-offers", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    // body: JSON.stringify({
    //   query: "",
    //   minPrice: null,
    //   maxPrice: null,
    // }),
  })
    .then((response) => response.json())
    .then((data) => {
      spinner.setAttribute("hidden", "");
      renderItems(data);
      items.classList.add("show");
      pagination.classList.add("show");
      console.log(data);
    });
}

function renderItems(items) {
  const itemsList = document.querySelector(".items-list");
  itemsList.innerHTML = "";

  items.forEach((item) => {
    const li = document.createElement("li");

    const img = document.createElement("img");
    img.className = "item-photo";
    img.src = `/public${item.photo_path}` || "/public/images/jacket.png";
    img.alt = item.title;

    const content = document.createElement("div");
    content.className = "item-content";

    const inner = document.createElement("div");

    const title = document.createElement("p");
    title.className = "item-title";
    title.textContent = item.title;

    const description = document.createElement("p");
    description.className = "item-description";
    description.textContent = item.description;

    const info = document.createElement("div");
    info.className = "item-info";

    const price = document.createElement("p");
    price.className = "item-price";
    price.textContent = `${parseFloat(item.price)} PLN`;

    const phone = document.createElement("p");
    phone.className = "item-number";
    phone.textContent = item.phone_number;

    info.append(price, phone);
    inner.append(title, description, info);
    content.append(inner);
    li.append(img, content);

    itemsList.appendChild(li);
  });
}

document.addEventListener("DOMContentLoaded", () => {
  loadData();
});
