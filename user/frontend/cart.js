const carticon = document.querySelector(".cart")
const localCart = {
    cart: null,
    length: 0,
    total: 0.0
};
carticon.addEventListener("click", showCart);

function updateCart() {
    fetchCall("cart.php", responseUpdateCart);
}

function responseUpdateCart(data) {
    console.log(data);
    const {
        total,
        ...cart
    } = data.cart;
    console.log(total);
    console.log(cart);
    localCart.cart = cart;
    localCart.total = total;
    localCart.length = Object.keys(cart).length;
    if (localCart.length > 0) {
        carticon.classList.add('cart-not-empty');
        const rootCss = document.querySelector(":root");
        rootCss.style.setProperty("--cart-size", `'${localCart.length}'`);
    } else {
        carticon.classList.remove('cart-not-empty');
    }

}

function addproducttocart() {

    const select = document.querySelector("select");
    console.log(select.value);
    const payload = new URLSearchParams();
    payload.append("id", this.id);
    payload.append("image", this.image);
    payload.append("price", this.price);
    payload.append("stock", this.stock);
    payload.append("quantity", select.value);

    fetchCall("cart.php", responseUpdateCart, "POST", payload);
}

function showCart() {
    const main = document.querySelector("main");
    if (localCart.length <= 0) {
        if (main.children[0].classList.contains("cart-container")) {
            location.replace(location.pathname);
        } else
            alert("Cart is empty");
        return;
    }
    setActiveCategory(null);

    main.innerHTML = "";
    const container = document.createElement("div");
    container.className = "cart-container";
    const imgHeading = document.createElement("div");
    imgHeading.textContent = "Item";
    container.appendChild(imgHeading);
    const quantityHeading = document.createElement("div");
    quantityHeading.textContent = "Quantity";
    container.appendChild(quantityHeading);
    const availabilityHeading = document.createElement("div");
    availabilityHeading.textContent = "Availability";
    container.appendChild(availabilityHeading);
    const orderValueHeading = document.createElement("div");
    orderValueHeading.textContent = "Order Value";
    container.appendChild(orderValueHeading);
    for (const [id, product] of Object.entries(localCart.cart)) {
        const {
            image,
            price,
            quantity,
            stock
        } = product;
        const imgDiv = document.createElement('div');
        const imgElem = document.createElement('img');
        imgElem.src = `http://localhost:8081/${image}`;
        imgDiv.appendChild(imgElem);
        container.appendChild(imgDiv);
        const quantityDiv = document.createElement('div');
        const select = document.createElement("select");
        select.addEventListener("change", updateQuantity.bind(id));
        const counter = (stock > 10) ? 10 : stock;
        for (let index = 1; index <= counter; index++) {
            const option = document.createElement('option');
            option.value = index;
            option.textContent = index;
            if (index === +quantity)
                option.setAttribute("selected", "");
            select.appendChild(option);

        }
        quantityDiv.appendChild(select);
        container.appendChild(quantityDiv);
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
        container.appendChild(stockdiv);
        const priceDiv = document.createElement('div');
        priceDiv.textContent = `$${price}`;
        const deleteBtn = document.createElement('button');
        deleteBtn.className = "delete-product-btn";
        deleteBtn.textContent = "Delete";
        deleteBtn.addEventListener("click", deleteProduct.bind(id));
        priceDiv.appendChild(deleteBtn);
        container.appendChild(priceDiv);
    }
    const totalDiv = document.createElement('div');
    totalDiv.className = "total-div";
    totalDiv.textContent = `Total:$${localCart.total}`;
    container.appendChild(totalDiv);
    const navDiv = document.createElement('div');
    navDiv.className = "nav-div";
    const continueshopbtn = document.createElement('button');
    continueshopbtn.className = "continue-shopping-btn";
    continueshopbtn.textContent = "Continue Shopping";
    navDiv.appendChild(continueshopbtn);
    const checkoutshopbtn = document.createElement('button');
    checkoutshopbtn.className = "checkout-btn";
    checkoutshopbtn.textContent = "Checkout";
    checkoutshopbtn.addEventListener("click",checkout);

    navDiv.appendChild(checkoutshopbtn);
    container.appendChild(navDiv);
    main.appendChild(container);
}

function updateQuantity(e) {
    // e.preventDefault();
    console.log(e.target.value);
    const payload = new URLSearchParams();
    payload.append("quantity", e.target.value);
    payload.append("id", this);
    fetchCall("cart.php", responseUpdateQuantity, "PATCH", payload);

}

function deleteProduct() {
    const payload = new URLSearchParams();
    payload.append("id", this);
    fetchCall("cart.php", responseUpdateQuantity, "DELETE", payload)
}

function responseUpdateQuantity(data) {
    responseUpdateCart(data);
    showCart();
}