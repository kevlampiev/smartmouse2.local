// let orderItem = {
//   props: {
//     cartItem: Object,
//     index: Number
//   },
//   data() {
//     return {
//       img_url: "/img/goods/"
//     };
//   },
//   template: `<div class="cartItem" style="backgroundcolor: red; position: static;">
    
//                     <img class="cartItem__img" :src="img_url+cartItem.img" alt="Изображение">
//                     <p class="cartItem__name"> {{cartItem.name}} </p>
//                     <p class="cartItem__price"> {{parseFloat(cartItem.price).toFixed(2)}}</p>
//                     <button class="cartItem__plusBtn" @click="$parent.addToCart(cartItem,cartItem.amount+1)">
//                         <i class="fa fa-plus" aria-hidden="true"></i>
//                     </button>
//                     <input class="cartItem__quantity" type="number" min="0" max="99" v-model.lazy="cartItem.amount" @change="$parent.addToCart(cartItem,cartItem.amount)">
//                     <button class="cartItem__minusBtn"
//                         @click="$parent.addToCart(cartItem,cartItem.amount-1)">
//                         <i class="fa fa-minus" aria-hidden="true"></i>
//                     </button>
//                     <button class="cartItem__minusBtn" @click="$parent.removeFromCart(cartItem)">
//                         <i class="fa fa-trash-o" aria-hidden="true"></i>
//                     </button>
//                     <p class="cartItem__totalSum">
//                         {{(Number(cartItem.price)*Number(cartItem.amount)).toFixed(2)}}</p>
//                 </div>`
// };


let order = new Vue({
  el: "#order_details",
  data: {
    
      cartItems: [],
      url: "/cart_operations",
      img_url: "/img/goods/",
      deliveryType: 'courier',
      paymentType: 'cash'
  },

  methods: {
    getData() {
      this.cartItems = getLocalCart();
    },

    /**
     *
     * @param {Number} id Поиск товара в корзине по id товара
     */
    getCartItem(id) {
      return this.cartItems.find((el, index) => el.id == id);
    }
  },

  /**
   * Устанавливаем в корзине новое количество товара или добавляем новый
   * @param {Good} good товар который доавляем
   * @param {Number} amount количество
   */
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
  // },
  // components: {
  //   orderItem: orderItem
  }
})
