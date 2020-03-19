let header = new Vue({
  el: ".header",
  // delimiters: ["${", "}"],
  data: {
    registered: false,
    name: "guest",
    cartCount: 0,
    cartSumm: 0,
    logInInProcess: false, //открывает диалоговае окно ввода пароля
    login: "",
    password: "",
    rememberMe: true,
    isVisibleCart: false,
    cartBtnAvailable: true
  },
  methods: {
    startLogin() {
      this.logInInProcess = true;
    },
    closeLoginWnd() {
      this.logInInProcess = false;
    },
    cancelLogin() {
      this.login = "";
      this.password = "";
      this.closeLoginWnd();
    },
    drawName() {
      let nameEl = document.querySelector(".userNameDspl");
      nameEl.innerHTML = this.name;
    },
    async proceedLogin() {
      let requestBody = {
        login: this.login,
        password: this.password
      };
      if (this.rememberMe) {
        requestBody.rememberMe = "rememberMe";
      }

      let result = await postJson("/userlogin", requestBody);
      if ("error" in result) {
        alert(result.error);
      } else {
        this.name = result.name;
        this.cart_count = result.cart_count;
        this.cart_summ = result.cart_summ;
        this.registered = true;
        if (getLocalCart === []) {
          saveLocalCart(result.cart);
        } else {
          mergeCarts();
        }
        this.$refs.cart.getData();
        this.closeLoginWnd();
        this.drawName();
      }
    },
    logOut() {
      destroyLocalCard();
      document.location.href = "/";
    },
    getData() {
      this.registered = getCookie("is_logged_in") === "true";
    }
  },
  mounted() {
    this.getData();
  },
  components: {
    cart: cart,
    order_comp: order
  }
});
