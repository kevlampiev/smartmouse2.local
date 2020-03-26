async function anotherStep(id) {
  let comment = prompt("Enter comment (optional)", "");
  let historyEl = document.querySelector("#order_history_comp");
  let result = await postJson("/admin/orderdetails/" + id, {
    action: "nextstep",
    comment: comment
  });
  if (result.status == "success") {
    // document.location.href = "/admin/orderdetails/" + id;
    historyEl.innerHTML = result.content;
  } else {
    alert(result.status);
  }
}

async function cancelOrder(id) {
  let comment = prompt("Enter comment (optional)", "");
  let result = await postJson("/admin/orderdetails/" + id, {
    action: "cancelOrder",
    comment: comment
  });
  if (result.status == "success") {
    document.location.href = "/admin/orderdetails/" + id;
  } else {
    alert(result.status);
  }
}

async function editOrder(id) {
  let delivery = document.querySelector("#delivery").value;
  let payment = document.querySelector("#payment").value;
  let contactName = document.querySelector("#contactName").value;
  let contactPhone = document.querySelector("#contactPhone").value;
  let deliveryAdress = document.querySelector("#deliveryAdress").value;
  let comments = document.querySelector("#comments").value;

  let result = await postJson("/admin/orderdetails/" + id, {
    action: "editOrder",
    delivery: delivery,
    payment: payment,
    contactName: contactName,
    contactPhone: contactPhone,
    deliveryAdress: deliveryAdress,
    comments: comments
  });
  alert(result.status);
}

/**
 * Морально устаревшая функция для обновления количества только по 1 предмету в заказе
 * @param {Object} el
 * @param {number} orderId
 * @param {number} goodId
 */
async function updatePosition(el, orderId, goodId) {
  let amount = el.parentNode.parentNode.querySelector(".amount_input").value;
  let requestBody = {
    action: "editOrderPosition",
    orderId: orderId,
    goodId: goodId,
    amount: amount
  };
  console.dir(requestBody);
  let result = await postJson("/admin/orderdetails/" + orderId, requestBody);
  alert(result.status);
}

/**
 * Морально устаревшая функция для удаления 1 элемента
 * @param {Object} el
 * @param {number} orderId
 * @param {number} goodId
 */
async function deletePosition(el, orderId, goodId) {
  let node = el.parentNode.parentNode;

  let requestBody = {
    action: "deleteOrderPosition",
    orderId: orderId,
    goodId: goodId
  };
  console.dir(requestBody);
  let result = await postJson("/admin/orderdetails/" + orderId, requestBody);

  if (result.status == "Ok") {
    node.parentNode.removeChild(node);
  } else {
    alert(result.status);
  }
}

/**
 * Выставляет класс пометки для удаления строки с заказом
 * @param {Object} el DOM-элемент с тэгом <a> входящий в состав строки с заказом
 */
function markPositionForDelete(el) {
  if (el.innerText == "Delete") {
    el.parentNode.parentNode.classList.add("deleted_order_position");
    el.innerText = "Undelete";
  } else {
    el.parentNode.parentNode.classList.remove("deleted_order_position");
    el.innerText = "Delete";
  }
}

/**
 * Выставляет класс, показывающий что value изменилось
 * @param {Object} el DOM-элемент <input>, содержимое которого изменяется
 */
function markChanged(el) {
  el.classList.add("changed_input");
}

/**
 * Записывает на сервере все изменения, проведенные клиентом с позициями заказа
 */
async function applyOrderPosChanges(id) {
  // let deletedPositions = document.querySelectorAll(".deleted_order_position");
  // console.dir(deletedPositions);
  let result = await postJson("/admin/orderdetails/" + id, {
    action: "updatePositions",
    forEdit: getPosToEdit(),
    forDel: getPosToDel()
  });
}

function getPosToEdit() {
  let changedEls = document.querySelectorAll(
    ".order_position_row td .changed_input"
  );
  let requestData = [];
  changedEls.forEach(el => {
    let row = el.parentNode.parentNode;
    requestData.push({
      orderId: row.dataset.orderid,
      goodId: row.dataset.goodid,
      amount: el.value
    });
  });
  return requestData;
}

function getPosToDel() {
  let deletedPositions = document.querySelectorAll(".deleted_order_position");

  let requestData = [];
  deletedPositions.forEach(el => {
    requestData.push({
      orderId: el.dataset.orderid,
      goodId: el.dataset.goodid
    });
  });
  return requestData;
}
