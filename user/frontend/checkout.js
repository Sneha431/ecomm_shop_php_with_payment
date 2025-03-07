function checkout()
{
  console.log("checkout button clicled");
  const main = document.querySelector("main");
  main.innerHTML="";
  const formDiv=document.createElement("div");
  formDiv.className="form-div";
  const h2=document.createElement("h2");
  h2.textContent="Delivery Address Form";
  formDiv.appendChild(h2);
  const addressForm = document.createElement("form");
  addressForm.className="address-form";

  const address = document.createElement("input");
  address.type="text";
  address.name="address";
  address.placeholder="Address";
  addressForm.appendChild(address);

const city = document.createElement("input");
  city.type="text";
  city.name="city";
  city.placeholder="City";
  addressForm.appendChild(city);

  const postcode = document.createElement("input");
  postcode.type="text";
  postcode.name="postcode";
  postcode.placeholder="Postcode";
  addressForm.appendChild(postcode);

  const submit = document.createElement("input");
  submit.type="submit";
  addressForm.appendChild(submit);
fetchCall("login.php?q=check_status",loginStatusResponse);
function loginStatusResponse(data)
{
    if(data.user=="guest")
    {
       const name = document.createElement("input");
        name.type="text";
        name.name="name";
        name.placeholder="Name";
        addressForm.insertBefore(name,addressForm.firstElementChild);
    }
    addressForm.addEventListener("submit",sendCheckoutRequest);
}

formDiv.appendChild(addressForm);
main.appendChild(formDiv);
}

function sendCheckoutRequest(e)
{
e.preventDefault();
const formData = new FormData(this);
fetchCall("checkout.php",responseSendCheckout,'POST',formData);
function responseSendCheckout(data)
{
if(data.url)
{
  window.location=data.url;
}
}
}