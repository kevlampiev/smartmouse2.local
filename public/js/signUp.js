let regForm = new Vue({
  el: ".registration_form",
  data: {
    login: "",
    pass1: "",
    pass2: "",
    name: "",
    phone: "",
    email: "",
    loginEntered: false,
    password1Entered: false,
    password2Entered: false,
    nameEntered: false,
    phoneEntered: false,
    emailEntered: false
  },
  computed: {
    loginValid: function() {
      let sample = /^[a-zA-Z][a-zA-Z0-9-_\.]{1,20}$/;
      return this.login != "" || this.login.match(sample);
    },
    phoneValid: function() {
      let sample = /^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/;
      return this.phone.match(sample);
    },
    emailValid: function() {
      let sample = /^(?!.*@.*@.*$)(?!.*@.*\-\-.*\..*$)(?!.*@.*\-\..*$)(?!.*@.*\-$)(.*@.+(\..{1,11})?)$/;
      return this.email.match(sample);
    },
    passwordsMatch: function() {
      return this.pass1 == this.pass2;
    }
  },
  methods: {
    checkData: function() {
      return (
        this.loginValid &&
        this.phoneValid &&
        this.emailValid &&
        this.passwordsMatch
      );
    },
    allowSend: function() {
      if (!this.checkData()) {
        let errLst = document.querySelector(".errors-list");
        errLst.innerHTML = "Pls correct errors before saving";
        displayErrWndw();
      } else {
        document.forms.regform.submit();
      }
    },
    quitPage() {
      document.location.href = "/index.php"
    }
  }
});

displayErrWndw = function() {
  let wnd = document.querySelector(".error-notification");
  let errListEl = wnd.querySelector(".errors-list");
  if (errListEl.innerHTML.trim() != "") {
    wnd.classList.remove("hidden-form");
  } else {
    wnd.classList.add("hidden-form");
  }
};

function errWindowClose() {
  let wnd = document.querySelector(".error-notification");
  wnd.classList.add("hidden-form");
}

document.addEventListener("DOMContentLoaded", displayErrWndw);
