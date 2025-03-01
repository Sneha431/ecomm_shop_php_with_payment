document.addEventListener('DOMContentLoaded', requestCategories);
document.addEventListener('DOMContentLoaded', requestBanners);
document.addEventListener('DOMContentLoaded', requestFeatured);
document.addEventListener('DOMContentLoaded', requestNewArrivals);

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
                li.addEventListener('click', getCategoryProducts);
                ul.appendChild(li);
            });
            nav.append(ul);
        }
    }
}

function getCategoryProducts() {
    console.log("clicked");
}

function requestBanners() {

    fetchCall("banner.php", responseBanners);

    function responseBanners(data) {
        if (data.banners) {
            const banners = data.banners;
            banners.forEach(banner => {
                const slide = document.createElement('div');
                slide.className = "swiper-slide";
                slide.style.backgroundImage = `url('http://localhost:8081/${banner.image}')`;
                slide.style.height = "70vh";
                slide.style.backgroundSize = "cover";
                const swiperWrapper = document.querySelector(".swiper-wrapper");
                const h3 = document.createElement('h3');
                h3.textContent = banner.name;
                const p = document.createElement('p');
                p.textContent = banner.description;
                const button = document.createElement('button');
                button.textContent = 'Shop Now';
                slide.appendChild(h3);
                slide.appendChild(p);
                slide.appendChild(button);
                swiperWrapper.append(slide);
            });

            callCarousel();
        }
    }
}


function callCarousel() {
    const swiper = new Swiper('.swiper', {
        // Optional parameters
        loop: true,

        // If we need pagination
        pagination: {
            el: '.swiper-pagination',
        },

        // Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        }
    });
}
//Request for  featured products

function requestFeatured() {
    fetchCall("featured.php", responseFeatured);

    function responseFeatured(data) {
        const featured = data.featured;
        console.log(featured);
        const featuredSection = document.querySelector(".featured-products");
        populateCatalogue(featured, featuredSection);
    }
}

function requestNewArrivals() {
    fetchCall("newArrivals.php", responseNewArrivals);

    function responseNewArrivals(data) {
        const newArrivals = data.newArrivals;
        console.log(newArrivals);
        const newArrivalsSection = document.querySelector(".new-arrivals");
        populateCatalogue(newArrivals, newArrivalsSection);
    }
}

//common function
function populateCatalogue(products, catalogueParent) {
    if (products) {
        // const featuredSection = document.querySelector(".featured-products");

        const catalogue = document.createElement('div');
        catalogue.className = "catalogue";
        products.forEach(prod => {
            const card = document.createElement('div');
            card.className = "card";
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
function fetchCall(resource, callBack, method = "GET") {
    const url = 'http://localhost:8081/user/backend/';
    fetch(url + resource, {
            method,
            headers: {
                "Content-Type": "application/json" // Set Content-Type header to handle JSON
            }
        })
        .then(res => res.json())
        .then(data => {
            // handlefetchResponse(data);
            callBack(data);
        }).catch((err) => console.log(err))
}