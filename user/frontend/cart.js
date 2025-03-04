
const carticon = document.querySelector(".cart")
const localCart ={
  cart:null,
  length:0,
  total:0.0
};

function updateCart()
{
  fetchCall("cart.php",responseUpdateCart);
}

function responseUpdateCart(data)
{
  console.log(data);
const {total,...cart}=data.cart;
console.log(total);
console.log(cart);
localCart.cart=cart;
localCart.total=total;
localCart.length=Object.keys(cart).length;
if(localCart.length > 0)
{
  carticon.classList.add('cart-not-empty');
  const rootCss=document.querySelector(":root");
  rootCss.style.setProperty("--cart-size",`'${localCart.length}'`);
}
else{
  carticon.classList.remove('cart-not-empty');
}

}
function addproducttocart()
{
  console.log(this);
  const select = document.querySelector("select");
  console.log(select.value);
  const payload = new URLSearchParams();
  payload.append("id",this.id);
  payload.append("image",this.image);
  payload.append("price",this.price);
  payload.append("stock",this.stock);
  payload.append("quantity",select.value);
 
  fetchCall("cart.php",responseUpdateCart,"POST", payload);
}