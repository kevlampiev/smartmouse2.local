let order = new Vue({
  el: "#order_details",
  data: {
    cartItems: [],
    url: "/cart_operations",
    img_url: "img/goods/",
    deliveryType: "courier",
    paymentType: "cash",
    userIsLogged: false,
    deliveryAdress: {
      ZIPCode: "",
      city: "",
      details: ""
    },
    contactName: "",
    contactPhone: ""
  },

  methods: {
    getData() {
      this.cartItems = getLocalCart();
      this.userIsLogged = getCookie("is_logged_in") === "true";
    },

    /**
     *
     * @param {Number} id Поиск товара в корзине по id товара
     */
    getCartItem(id) {
      return this.cartItems.find((el, index) => el.id == id);
    },
    /**
     * Возвращает путь к странице товара
     * @param {object} item
     */
    getPathToGood(item) {
      return "/good/" + item.id;
    },

    /**
     * Устанавливаем в корзине новое количество товара или добавляем новый
     * @param {Good} good товар который доавляем
     * @param {Number} amount количество
     */
    async addToCart(good, amount = null) {
      if (amount === null) {
        amount = 1;
      }
      await editCartItem(good, amount);
    },

    /**
     * Удаляет товар из корзины. Совсем
     * @param {CartItem} good
     */
    async removeFromCart(good) {
      await deleteCartItem(good);
    },

    /**
     * Покидает страницу заказа
     */
    leaveThePage() {
      document.location.href = "/";
    }
  },

  computed: {
    cartSum: function() {
      let res = 0;
      this.cartItems.forEach(el => {
        res += el.price * el.amount;
      });
      return res;
    },

    cartAmount: function() {
      let res = 0;
      this.cartItems.forEach(el => {
        res += parseInt(el.amount);
      });
      return res;
    }
  },

  mounted() {
    this.getData();
    document.addEventListener("cartChanged", this.getData);
    document.addEventListener("storage", this.getData);
  }
});
