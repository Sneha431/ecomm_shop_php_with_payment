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