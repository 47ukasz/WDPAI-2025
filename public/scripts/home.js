const spinner = document.getElementById("spinner");
const items = document.querySelector(".items");
const pagination = document.querySelector(".pagination");
const paginationList = document.querySelector(".pagination-list");
const prevButton = document.querySelector(
  ".pagination-menu button:first-of-type"
);
const nextButton = document.querySelector(
  ".pagination-menu button:last-of-type"
);
const itemsList = document.querySelector(".items-list");
const searchForm = document.querySelector("form.search");
const searchInput = searchForm.querySelector("input[type='text']");

const paginationInfo = {
  page: 1,
  pageSize: 5,
  totalPages: 1,
  searchTitle: "",
};

function loadData(page = 1) {
  itemsList.innerHTML = "";
  paginationInfo.page = Math.max(1, page);
  spinner.removeAttribute("hidden");

  console.log(paginationInfo.searchTitle);

  fetch(
    `http://localhost:8080/search-offers?page=${paginationInfo.page}}&pageSize=${paginationInfo.pageSize}&title=${paginationInfo.searchTitle}`
  )
    .then((response) => response.json())
    .then((data) => {
      console.log(data);
      spinner.setAttribute("hidden", "");

      const itemsData = data.items.length > 0 ? data.items : [];
      const total = data.total > 0 ? data.total : 0;

      paginationInfo.page = data.page > 0 ? data.page : paginationInfo.pageSize;
      paginationInfo.pageSize =
        data.pageSize > 0 ? data.pageSize : paginationInfo.pageSize;

      paginationInfo.totalPages = Math.max(
        1,
        Math.ceil(total / paginationInfo.pageSize)
      );

      renderItems(itemsData);
      renderPagination();

      items.classList.add("show");
      pagination.classList.add("show");

      handleSynchronizeUrl();
    });
}

function renderItems(items) {
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

    const itemHeader = document.createElement("div");
    itemHeader.className = "item-header";

    const title = document.createElement("p");
    title.className = "item-title";
    title.textContent = item.title;

    const itemLink = document.createElement("a");
    itemLink.href = `/item/${item.id}`;
    itemLink.title = "Przejdź na stronę przedmiotu";
    itemLink.className = "item-link";
    itemLink.innerHTML = '<i class="fa-solid fa-arrow-right fa-2xl"></i>';

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

    itemHeader.append(title, itemLink);
    info.append(price, phone);
    inner.append(itemHeader, description, info);
    content.append(inner);
    li.append(img, content);

    itemsList.appendChild(li);
  });
}

function renderPagination() {
  paginationList.innerHTML = "";
  const pages = [];

  for (let i = 1; i <= paginationInfo.totalPages; i += 1) {
    pages.push(i);
  }

  pages.forEach((page) => {
    const li = document.createElement("li");
    li.textContent = page;

    if (page === paginationInfo.page) {
      li.classList.add("selected");
    }

    paginationList.appendChild(li);
  });

  prevButton.disabled = paginationInfo.page <= 1;
  nextButton.disabled = paginationInfo.page >= paginationInfo.totalPages;
}

function handleSearchFormSubmit(e) {
  e.preventDefault();
  const value = searchInput.value;

  paginationInfo.searchTitle = value;
  loadData();
  searchInput.value = "";
}

function handleSynchronizeUrl() {
  const url = new URL(window.location.href);

  let changed = false;

  if (!url.searchParams.has("page")) {
    url.searchParams.set("page", paginationInfo.page);
    changed = true;
  }

  if (!url.searchParams.has("pageSize")) {
    url.searchParams.set("pageSize", paginationInfo.pageSize);
    changed = true;
  }

  if (changed) {
    history.replaceState({}, "", url);
  } else {
    history.pushState({}, "", url);
  }
}

prevButton.addEventListener("click", () => {
  if (paginationInfo.page > 1) {
    loadData(paginationInfo.page - 1);
  }
});

nextButton.addEventListener("click", () => {
  if (paginationInfo.page < paginationInfo.totalPages) {
    loadData(paginationInfo.page + 1);
  }
});

searchForm.addEventListener("submit", handleSearchFormSubmit);

document.addEventListener("DOMContentLoaded", () => {
  handleSynchronizeUrl();

  const url = new URL(window.location.href);
  const page = Number(url.searchParams.get("page")) ?? paginationInfo.page;
  const pageSize =
    Number(url.searchParams.get("pageSize")) ?? paginationInfo.pageSize;
  const searchTitle = url.searchParams.get("title") ?? "";

  paginationInfo.page = page;
  paginationInfo.pageSize = pageSize;

  if (searchTitle !== "" || searchTitle !== undefined) {
    paginationInfo.searchTitle = searchTitle;
  }

  loadData();
});
