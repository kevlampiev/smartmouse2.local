let dbCardUrl = "/cart_operations";

/**
 * Возвращает содержимое корзины в виде массива item'ов
 */
async function getDBCart() {
  let result = await postJson(dbCardUrl, {
    action: "getCart"
  });
  return result;
}

/**
 * Сохраняет корзину в базу, старая корзина затирается
 * @param {Array} cart
 */
async function saveBDCart(cart) {
  let result = await postJson(dbCardUrl, {
    action: "saveCart",
    cart: cart
  });
  return result;
}

/**
 * сливает корзину с корзиной, сохраненной в базе
 * @param {Array} cart
 */
async function mergeToBDCart(cart) {
  let result = await postJson(dbCardUrl, {
    action: "mergeCarts",
    item: cart
  });
  return result;
}

/**
 * Добавляет товары в корзину. Количество товара изменяется на величину item.amount
 * @param {CartItem} item
 */
async function addToDBCartItem(item) {
  let result = await postJson(dbCardUrl, {
    action: "addToCart",
    item: item
  });
  return result;
}

/**
 * Заменяет позицию в козине, количество товара будет равно item.count
 * @param {CartItem} item
 */
async function editDBCartItem(item) {
  let result = await postJson(dbCardUrl, {
    action: "editCartItem",
    item: item
  });
  return result;
}

/**
 * Удаляет позицию в козине. Совсем
 * @param {CartItem} item
 */
async function deleteDBCartItem(item) {
  let result = await postJson(dbCardUrl, {
    action: "removeFromCart",
    item: item
  });
  return result;
}