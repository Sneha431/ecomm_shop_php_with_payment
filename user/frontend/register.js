register.addEventListener("click",registerNewUser);
function registerNewUser()
{
  fetchCall("register.php",responseUserTableInfo);
  function responseUserTableInfo(data)
  {
    const formdiv=document.createElement("div");
    formdiv.className="formdiv";
    const h2=document.createElement("h2");
    h2.textContent="User Registration";
    formdiv.appendChild(h2);
    const form=document.createElement("form");
    form.className="register-form";
    const columns=data.columns;
    columns.forEach(col => {
      const input = document.createElement('input');
      input.name=col.Field;
      input.placeholder=col.Field;
      switch(col.Field)
      {
        case "password":
          input.type="password";
          break;
        case "email":
          input.type="email";
          break;
        default:
          input.type="text";
          break;
      }
      form.appendChild(input);
    });
    const submit=document.createElement("input");
    submit.type="submit";
    submit.name="register";
    submit.addEventListener("click",registerFormSubmit);
    form.appendChild(submit);
    formdiv.appendChild(form);
    displayOverlay(formdiv);
  }
}
function registerFormSubmit(e)
{
  e.preventDefault();
  const form = document.querySelector(".register-form");
  const formData = new FormData(form);
  fetchCall("register.php",responseRegisterFormSubmit,'POST',formData);
  function responseRegisterFormSubmit(data)
  {
    if(data.registration)
   {
    alert("Registration Successful!");
    removeOverlay();
   }
   else{
    
    alert(data.error);
    form.reset();
   }
  }
}