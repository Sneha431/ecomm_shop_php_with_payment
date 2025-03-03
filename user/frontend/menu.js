function requestCategories() {
    fetchCall("menu.php", responseCategories);

    function responseCategories(data) {
        console.log(data);
        const nav = document.querySelector(".navigation");
        if (data.categories) {
            const ul = document.createElement("ul");
            data.categories.forEach(cat => {
                const li = document.createElement("li");
                li.className = cat;
                li.textContent = cat;
                li.addEventListener('click', getCategoryProducts.bind(cat));
                ul.appendChild(li);
            });
            nav.append(ul);
        }
    }
}

function getCategoryProducts() {
    const cat = this;
    const main = document.querySelector("main");
    setActiveCategory(cat);
    fetchCall(`products.php?category=${cat}`,
        responseCategoryProducts);

    function responseCategoryProducts(data) {
        console.log(data);
        if (data.products) {
            main.innerHTML = "";
            populateCatalogue(data.products, main);
        }

    }
}

function setActiveCategory(cat) {
    const categorylist = document.querySelectorAll(".navigation li");
    const root = document.querySelector(":root");
    const primaryColor = window.getComputedStyle(root).getPropertyValue('--primaryColor');
    categorylist.forEach(category => {

        if (category.classList.contains(cat)) {
            console.log(category.className);
            category.style.backgroundColor = primaryColor;
        } else {
            category.style.backgroundColor = "initial";
        }
    });
}