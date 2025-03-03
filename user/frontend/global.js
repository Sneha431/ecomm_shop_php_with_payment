document.addEventListener('DOMContentLoaded', requestCategories);
document.addEventListener('DOMContentLoaded', requestBanners);
document.addEventListener('DOMContentLoaded', requestFeatured);
document.addEventListener('DOMContentLoaded', requestNewArrivals);
document.addEventListener('DOMContentLoaded', checkLoginStatus);


//common function
function populateCatalogue(products, catalogueParent) {
    if (products) {
        // const featuredSection = document.querySelector(".featured-products");

        const catalogue = document.createElement('div');
        catalogue.className = "catalogue";
        products.forEach(prod => {
            const card = document.createElement('div');
            card.className = "card";
            card.addEventListener("click", getProductdetails.bind(prod));
            const imgDiv = document.createElement('div');
            imgDiv.className = "card-img";
            const descDiv = document.createElement('div');
            descDiv.className = "card-description";
            card.appendChild(imgDiv);
            card.appendChild(descDiv);
            const img = document.createElement('img');
            img.src = `http://localhost:8081/${prod.image}`;
            imgDiv.appendChild(img);
            const namep = document.createElement('p');
            namep.className = "product-name";
            namep.textContent = prod.name;
            const pricep = document.createElement('p');
            pricep.className = "product-price";
            pricep.textContent = `$${prod.price}`;
            descDiv.appendChild(namep);
            descDiv.appendChild(pricep);
            catalogue.appendChild(card);
        });

        catalogueParent.appendChild(catalogue);
    }
}

//refractoring fetch function
function fetchCall(resource, callBack, method = "GET",data = undefined) {
    const url = 'http://localhost:8081/user/backend/';
    fetch(url + resource, {
            method,
           mode:"cors",
            credentials:"include",
            body:data,
            // headers: {
            //     "Content-Type": "application/json" // Set Content-Type header to handle JSON
            // }
        })
        .then(res => res.json())
        .then(data => {
            callBack(data);
        }).catch((err) => console.log(err))
}

function displayOverlay(modal) {
    const main = document.querySelector("main");
    const overlay = document.createElement('div');
    overlay.className = "overlay";
    overlay.addEventListener("click", removeOverlay);
    main.appendChild(overlay);
    const modalContainer = document.createElement('div');
    modalContainer.className = "modal-container";
    modalContainer.appendChild(modal);
    main.appendChild(modalContainer);
}

function getProductdetails() {
    console.log(this);
    const main = document.querySelector("main");
    fetchCall(`inventory.php?id=${this.id}`, responseInventory.bind(this));

    function responseInventory(data) {
        const stock = +(data.stock);
        console.log(data);
        const modal = document.createElement('div');
        modal.className = "modal";
        const img = document.createElement('img');
        img.src = `http://localhost:8081${this.image}`;
        modal.appendChild(img);
        const modalDesc = document.createElement('div');
        modalDesc.className = "modal-desc";
        modal.appendChild(modalDesc);
        const title = document.createElement('div');
        title.textContent = this.name;
        modalDesc.appendChild(title);
        const description = document.createElement('div');
        description.textContent = this.description;
        modalDesc.appendChild(description);
        const price = document.createElement('div');
        price.textContent = `$${this.price}`;
        modalDesc.appendChild(price);
        const stockdiv = document.createElement('div');
        switch (true) {
            case stock > 10:
                stockdiv.textContent = "In Stock";
                stockdiv.style.color = "green";
                break;
            case stock > 0 && stock <= 10:
                stockdiv.textContent = `Only ${stock} left`;
                stockdiv.style.color = "green";
                break;
            case stock == 0:
                stockdiv.textContent = "Out Of Stock";
                stockdiv.style.color = "red";
                break;
            default:
                stockdiv.textContent = "Not Sure";
                break;


        }
        modalDesc.appendChild(stockdiv);
        const select = document.createElement("select");
        if (stock == 0) {
            select.disabled = true;
        } else {
            const counter = (stock > 10) ? 10 : stock;
            for (let index = 1; index <= counter; index++) {
                const option = document.createElement('option');
                option.value = index;
                option.textContent = index;
                select.appendChild(option);

            }
        }
        modalDesc.appendChild(select);
        const addtocart = document.createElement('button');
        addtocart.className = "add-to-cart";
        addtocart.textContent = "Add To Cart";
        modalDesc.appendChild(addtocart);
        displayOverlay(modal);

    }
   
}

function removeOverlay() {
    const overlay = document.querySelector(".overlay");
    const modalContainer = document.querySelector(".modal-container");
    if (overlay) {
        overlay.remove();
    }
    if (modalContainer) {
        modalContainer.remove();
    }
}