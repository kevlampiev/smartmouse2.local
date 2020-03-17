/* 
Общий модуль работы с корзиной
*/


//Task. Переделать на получение json-строки как сказано  в https://stackoverflow.com/questions/21602381/passing-php-string-of-json-with-qoutes-to-onclick-function


function notifyAddItem() {
    let notifyForm=document.querySelector('.buy-notification')
    notifyForm.classList.remove('hidden-form')
    notifyForm.classList.add('zoomIn')
    setTimeout(()=>{
        notifyForm.classList.remove('zoomIn')
        notifyForm.classList.add('hidden-form')
    },550)
}

/**
 * Обновляет корзину в localStore из базы
 */
async function updateLocalCart() {
    let cart = await getDBCart()
    saveLocalCart(cart)
}

/**
 * Добавляет 1 товар с заданными характеристиками в корзину
 * @param {Number} id 
 * @param {String} name 
 * @param {String} img 
 * @param {Number} price 
 * @param {String} currency 
 */
async function addItemToCarts(id, name, img, price, currency) {
    let cartItem = {
        id: id,
        name: name,
        img: img,
        price: price,
        currency: currency,
        amount: 1
    }

    let registered = getCookie('is_logged_in')

    if (registered !== undefined) {
        await addToDBCartItem(cartItem)
        await updateLocalCart()
    } else {
        addLocalCartItem(cartItem)
    }

    notifyAddItem()
    document.dispatchEvent(new CustomEvent("cartChanged", {
        detail: { action: "item added" }
    }))

}

/**
 * Изменяет товар в корзине
 * @param {Object} item 
 * @param {Number} newAmount 
 */
async function editCartItem(item, newAmount) {
    item.amount = Math.max(0, newAmount)
    let registered = getCookie('is_logged_in')

    if (registered !== undefined) {
        await editDBCartItem(item)
        await updateLocalCart()
    } else {
        editLocalCartItem(item)
    }
    document.dispatchEvent(new CustomEvent("cartChanged", {
        detail: { action: "item changed" }
    }))

}

/**
 * Удаляет товар из корзины
 * @param {Object} item  
 */
async function deleteCartItem(item) {
    let registered = getCookie('is_logged_in')

    if (registered !== undefined) {
        await deleteDBCartItem(item)
        await updateLocalCart()
    } else {
        deleteLocalCartItem(item)
    }
    document.dispatchEvent(new CustomEvent("cartChanged", {
        detail: { action: "item changed" }
    }))

}

async function mergeCarts() {
    let cart = getLocalCart()
    if (cart !== undefined) {
        await mergeToBDCart(cart)
        await updateLocalCart()
        document.dispatchEvent(new CustomEvent("cartChanged", {
            detail: { action: "carts merged" }
        }))
    }
}