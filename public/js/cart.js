let cartItem = {
  props: {
    cartItem: Object,
    index: Number
  },
  data() {
    return {
      img_url: "/img/goods/"
    };
  },
  template: `<div class="cartItem">
    
                    <img class="cartItem__img" :src="img_url+cartItem.img" alt="Изображение">
                    <p class="cartItem__name"> {{cartItem.name}} </p>
                    <p class="cartItem__price"> {{cartItem.price.toFixed(2)}}</p>
                    <button class="cartItem__plusBtn" @click="$parent.addToCart(cartItem,cartItem.amount+1)">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                    <input class="cartItem__quantity" type="number" min="0" max="99" v-model.lazy="cartItem.amount" @change="$parent.addToCart(cartItem,cartItem.amount)">
                    <button class="cartItem__minusBtn"
                        @click="$parent.addToCart(cartItem,cartItem.amount-1)">
                        <i class="fa fa-minus" aria-hidden="true"></i>
                    </button>
                    <button class="cartItem__minusBtn" @click="$parent.deleteFromCart(cartItem)">
                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                    </button>
                    <p class="cartItem__totalSum">
                        {{(Number(cartItem.price)*Number(cartItem.amount)).toFixed(2)}}</p>
                </div>`
};

let cart = {
  props: {
    isvisiblecart: Boolean
  },
  data() {
    return {
      cartItems: [],
      url: "/cart_operations"
    };
  },

  methods: {
    getData() {
      this.cartItems = getLocalCart();
    },

    sendCart() {
      console.log("Not relized yet");
    },

    /**
     *
     * @param {Number} id Поиск товара в корзине по id товара
     */
    getCartItem(id) {
      return this.cartItems.find((el, index) => el.id == id);
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
    deleteFromCart(good) {
      console.log("Not relized yet");
    },

    /**
     * Удаляет все товары из корзины у которых количество == 0
     */
    compressCart() {
      this.cartItems = this.cartItems.filter(el => el.quantity != 0);
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
        res += el.amount;
      });
      return res;
    }
  },

  mounted() {
    this.getData();
    //setInterval(this.getData, 500)
    document.addEventListener("cartChanged", this.getData);
    document.addEventListener("storage", this.getData);
  },

  template: `<div class="basketWindow" v-if="isvisiblecart" ref="cart">
                    <div class="basketWindow__refSquare">

                    </div>
                    <h2 class="basketWindow__header"> shopping list </h2>
                    <div v-if="cartAmount===0" class="emptyBasket">Basket is empty</div>
                    <div class="basketWindow__itemContainer" >
                      <cart-item v-for="(cartItem,index) in cartItems" :cartItem="cartItem" :key="cartItem.id" :index="index"> </cart-item> 
                    </div>
                    <div class="basketWindow__footer">
                        <div> Total: {{cartAmount}} items for {{cartSum.toFixed(2)}} $ </div>
                        <div class="basket__controls">
                            <button class="cartButton orangeStyled" @click="$parent.isVisibleCart=false">Close</button>
                            <button class="cartButton orangeStyled" @click="sendCart()">Make order</button>
                            <button class="cartButton orangeStyled" @click="compressCart()">Recalc</button>
                        </div>
                    </div>
                </div>`,

  components: {
    "cart-item": cartItem
  }
};
