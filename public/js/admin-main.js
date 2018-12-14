/////////////////////////////////////////////////////
// Archivo global de JS para añadir interactividad //
/////////////////////////////////////////////////////
//////// Auxilar Functions ///////////

const renderArticles = tag => {
  const tagString = tag ? `/${tag}` : "";
  console.log(newAttack.urls.articlesApi + tagString);
  return Fetch(newAttack.urls.articlesApi + tagString).then(res => {
    if (res.error) {
      throw new Error(res.error);
    } else {
      return res.articles.map(article => article);
    }
  });
};

///////// Global Config /////////////
const yaseal = {
  urls: {
    domain: window.location.origin
  },
  api: {
    login: `${window.location.origin}/api/login`,
    register: `${window.location.origin}/api/register`
  }
};

///////// FrontEnd logic /////////////
window.onload = () => {
  const loginButton = document.querySelector("#login-button");
  const registerButton = document.querySelector("#register-button");
  const loginArea = document.querySelector("#login-area");
  const registerArea = document.querySelector("#register-area");
  if (loginButton && registerButton && loginArea && registerArea) {
    loginButton.addEventListener("click", event => {
      loginButton.classList.add("enable");
      registerButton.classList.remove("enable");
      loginArea.style.display = "block";
      registerArea.style.display = "none";
    });
    registerButton.addEventListener("click", event => {
      registerButton.classList.add("enable");
      loginButton.classList.remove("enable");
      registerArea.style.display = "block";
      loginArea.style.display = "none";
    });
  }

  const loginForm = document.querySelector("#login-form");
  const registerForm = document.querySelector("#register-form");
  const errorsBox = document.querySelector("#errors-form");
  if (loginForm && registerForm) {
    loginForm.addEventListener("submit", event => {
      event.preventDefault();
      const { email, password } = event.target.children;
      axios
        .post(yaseal.api.login, {
          email: email.value,
          password: password.value
        })
        .then(function(res) {
          window.location.href = `${window.location.origin}/admin_panel/panel`;
        })
        .catch(function(err) {
          console.error(err.response.data);
          errorsBox.innerHTML = err.response.data.error.msg;
        });
    });

    registerForm.addEventListener("submit", event => {
      event.preventDefault();
      const { email, password, role, name } = event.target.children;
      axios
        .post(yaseal.api.register, {
          name: name.value,
          email: email.value,
          password: password.value,
          role: role.value
        })
        .then(function(res) {
          window.location.href = `${window.location.origin}/admin_panel/panel`;
        })
        .catch(function(err) {
          console.error(err.response.data);
          errorsBox.innerHTML = err.response.data.error.msg;
        });
    });
  }
};
