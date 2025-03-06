const login = document.querySelector(".login");
const register = document.querySelector(".register");
const logout = document.querySelector(".logout");
const loggeduser = document.querySelector(".logged-user");

login.addEventListener('click', userLogin);
logout.addEventListener('click', userLogout);
function userLogin(e) {
    e.preventDefault();
    const formDiv = document.createElement('div');
    formDiv.className = "formDiv";
    const h2 = document.createElement('h2');
    h2.textContent = "Login Form";
    formDiv.appendChild(h2);
    const loginForm = document.createElement('form');
    loginForm.className = "login-form";
    loginForm.method="POST";
    const userName = document.createElement('input');
    userName.type = "text";
    userName.name = "username";
    userName.placeholder = "User Name";
    const password = document.createElement("input");
    password.type = "password";
    password.name = "password";
    password.placeholder = "PassWord";
    const submit = document.createElement("input");
    submit.type = "submit";
    submit.name = "Login";
    submit.addEventListener("click",userLoginRequest);
    loginForm.appendChild(userName);
    loginForm.appendChild(password);
    loginForm.appendChild(submit);
    formDiv.appendChild(loginForm);
    displayOverlay(formDiv);


}

function userLoginRequest(e)
{
  e.preventDefault();
  const form =document.querySelector(".login-form");
  const formData= new FormData(form);
  // console.log(formData);
  // for (let [key, value] of formData.entries()) {
  //   console.log(key + ": " + value);
  // }
  
  fetchCall("login.php",userLoginResponse,"POST",formData);
  function userLoginResponse(data){
   data.user && displayLoggedUser(data.user);
   data.user && updateCart();
  }


}


function showHideIcon(icon, flag) {
    flag ? icon.style.display = "none" : icon.style.display = "block";
}

function displayLoggedUser(user)
{
  removeOverlay();
  showHideIcon(loggeduser, false);
  const loggedusername=document.querySelector(".username");
  loggedusername.textContent=user;
   showHideIcon(register,true);
   showHideIcon(logout,false);
  showHideIcon(login,true);
   showHideIcon(loggeduser,false);

}

function checkLoginStatus()
{
  fetchCall("login.php?q=check_status",responseUserLogin);
  function responseUserLogin(data)
  {
data.user!="guest" && displayLoggedUser(data.user);
data.user=="guest" && displayLoginRegisterIcons();
  }
}

function displayLoginRegisterIcons()
{
  showHideIcon(register,false);
   showHideIcon(logout,true);
  showHideIcon(login,false);
   showHideIcon(loggeduser,true);
}

function userLogout()
{
  fetchCall("login.php",responseLogout);

  function responseLogout(data)
  {
     console.log(data);
    data.logout && displayLoginRegisterIcons();
    data.logout && updateCart();
  }
}