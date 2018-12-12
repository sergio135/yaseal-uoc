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
      return res.articles.map(
        article => `
        <div class="card">
            <div class="card__img">
                <img src="./public/images/world.png">
            </div>
            <div class="card__content">
                <div class="row between">
                    <div class="header-icon">
                        <img src="./public/images/${article.image}" />
                    </div>
                    <div class="header-title">
                        <h3>${article.title}</h3>
                        <h6>${article.subTitle}</h6>
                    </div>
                    <div class="header-time">
                        <span>${article.create}</span>
                    </div>
                </div>
                <div>
                    <p>${article.description}</p>
                </div>
                <div class="row between">
                    <span class="button red outline" onClick="">Eliminar</span>
                    <span class="button yellow outline" onClick="">Ver más!</span>
                </div>
            </div>
        </div>
      `
      );
    }
  });
};

///////// Global Config /////////////
const yaseal = {
  urls: {
    domain: window.location.origin
  },
  api: {
    login: `${window.location.origin}/yaseal-local/api/login`,
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
  // if (loginForm && registerForm) {
  //   loginForm.addEventListener("submit", event => {
  //     event.preventDefault();
  //     const { email, password } = event.target.children;
  //     axios
  //       .post(yaseal.api.login, { email, password })
  //       .then(function(res) {
  //         document.write(res.data);
  //       })
  //       .catch(function(err) {
  //         console.error(err);
  //       });
  //   });
  //
  //   registerForm.addEventListener("submit", event => {
  //     event.preventDefault();
  //     const { email, password } = event.target.children;
  //     axios
  //       .post(yaseal.api.register, { email, password })
  //       .then(function(res) {
  //         console.log(res);
  //       })
  //       .catch(function(err) {
  //         console.error(err);
  //       });
  //   });
  // }
};
