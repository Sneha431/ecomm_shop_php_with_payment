document.addEventListener('DOMContentLoaded',requestCategories);
document.addEventListener('DOMContentLoaded',requestBanners);
function requestCategories(){
 fetch('http://localhost:8081/user/backend/menu.php', {
    method: 'GET',
   headers: {
        "Content-Type": "application/json"  // Set Content-Type header to handle JSON
    }
}).then((res)=>res.json())
  .then((data)=>
 { const nav = document.querySelector(".navigation");
  if(data.categories)
  {
const ul =document.createElement("ul");
data.categories.forEach(cat => {
  const li = document.createElement("li");
  li.className=cat;
  li.textContent=cat;
  li.addEventListener('click',getCategoryProducts);
  ul.appendChild(li);
});
nav.append(ul);
  }}
  )
  .catch((error)=>console.log(error))
}

function getCategoryProducts(){
  console.log("clicked");
}
function requestBanners(){
 fetch('http://localhost:8081/user/backend/banner.php', {
    method: 'GET',
   headers: {
        "Content-Type": "application/json"  // Set Content-Type header to handle JSON
    }
}).then((res)=>res.json())
  .then((data)=>
 { 
  console.log(data);
  if(data.banners)
{
const banners= data.banners;
banners.forEach(banner => {
const slide = document.createElement('div');
slide.className="swiper-slide";
slide.style.backgroundImage=`url('http://localhost:8081/${banner.image}')`;
slide.style.height="70vh";
slide.style.backgroundSize="cover";
const swiperWrapper =document.querySelector(".swiper-wrapper");
const h3= document.createElement('h3');
h3.textContent=banner.name;
const p = document.createElement('p');
p.textContent=banner.description;
const button = document.createElement('button');
button.textContent= 'Shop Now';
slide.appendChild(h3);
slide.appendChild(p);
slide.appendChild(button);
swiperWrapper.append(slide);
});

callCarousel();
}
}).catch((error)=>console.log(error))
}
function callCarousel(){
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