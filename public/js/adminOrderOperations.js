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
