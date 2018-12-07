/////////////////////////////////////////////////////
// Archivo global de JS para añadir interactividad //
/////////////////////////////////////////////////////
//////// Auxilar Functions ///////////
const Fetch = (url, headers) =>
  fetch(url, {
    headers: {
      "Content-Type": "application/json"
    },
    ...headers
  }).then(res => {
    if (res.ok) {
      return res.json();
    } else {
      throw new Error(res.statusText);
    }
  });

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
const newAttack = {
  urls: {
    domain: window.location.origin,
    loginApi: `${window.location.origin}/new/api/login`,
    registerApi: `${window.location.origin}/new/api/login`,
    articlesApi: `${window.location.origin}/new/api/articles`,
    dashboard: `${window.location.origin}/new/dashboard`
  }
};

///////// FrontEnd logic /////////////
window.onload = () => {
  const loginButton = document.querySelector("#login-button");
  const registerButton = document.querySelector("#register-button");
  const loginForm = document.querySelector("#login-form");
  const registerForm = document.querySelector("#register-form");
  if (loginButton && registerButton && loginForm && registerForm) {
    loginButton.addEventListener("click", event => {});
  }
  authForm.addEventListener("submit", event => {
    event.preventDefault();
    if (event.target.attributes.register) {
      Fetch(newAttack.urls.registerApi).then(res => {
        if (res.error) {
          const errBox = document.querySelector(".auth .error-modal");
          errBox.style.display = "block";
          errBox.innerHTML = res.error.messagge;
        } else {
          window.location.href = newAttack.urls.dashboard;
        }
      });
    } else {
      Fetch(newAttack.urls.loginApi).then(res => {
        if (res.error) {
          const errBox = document.querySelector(".auth .error-modal");
          errBox.style.display = "block";
          errBox.innerHTML = res.error.messagge;
        } else {
          window.location.href = newAttack.urls.dashboard;
        }
      });
    }
  });
};
