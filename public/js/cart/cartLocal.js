class CartItem {
    constructor(id, name, img, price, currency, amount) {
        this.id = id
        this.name = name
        this.img = img
        this.price = price
        this.currency = currency
        this.amount = amount
    }
}


function getLocalCart() {
    let result = JSON.parse(localStorage.getItem('cart'))
    if (!result) {
        result = []
    }
    return result
}

function saveLocalCart(cart) {
    localStorage.setItem('cart', JSON.stringify(cart))
}

function destroyLocalCard() {
    localStorage.removeItem('cart')
    return []
}

function addLocalCartItem(item) {
    let cart = getLocalCart()
    if (cart !== undefined) {
        let cartEl = cart.find(el => el.id == item.id)
        if (cartEl === undefined) {
            cart.push(item)
        } else {
            cartEl.amount = Math.max(item.amount + cartEl.amount, 0)
        }
    } else {
        //если не было корзины - заводим
        cart = []
        cart.push(item)
    }
    saveLocalCart(cart)
}


function editLocalCartItem(item) {
    let cart = getLocalCart()
    if (cart !== undefined) {
        let cartEl = cart.find(el => el.id == item.id)
        if (cartEl === undefined) {
            cart.push(item)
        } else {
            cartEl.amount = item.amount
        }
    } else {
        //если не было корзины - заводим
        cart = []
        cart.push(item)
    }
    saveLocalCart(cart)
}

function deleteLocalCartItem(item) {
    let cart = getLocalCart()
    if (cart !== undefined) {
        index=cart.indexOf(item)
        if (index!=undefined) {cart.splice(index,1)}
    } else {
        //если не было корзины - заводим
        cart = []
    }
    saveLocalCart(cart)
}