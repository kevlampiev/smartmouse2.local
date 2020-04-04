let priceEditMode = "none";
let currentPriceId = 0;
let newMainImg = "";

//Объект с первоначальными данными
let initialGoodValues = {
  img: "",
  title: "",
  category: 0,
  description: "",
};

const form = document.querySelector("#edit-price-form");
const editDate = document.querySelector("#editDate");
const editPrice = document.querySelector("#editPrice");
const editCurrency = document.querySelector("#editCurrency");
const goodPriceBlock = document.querySelector("#good_price_block");
const imgEl = document.querySelector("#main-good-img");
const loadGoodImgForm = document.querySelector("#load_img_form");
const bigImgDesc = document.querySelector("#bigImgDescript");

const singleLoader = new Dropzone("#fileupload", {
  url: "/admin/fileUpload",
  maxFiles: 1,
  init: function () {
    this.element.innerHTML =
      '<div class="dz-message"> Click to open file dialog or drag picture here...</div>';

    this.on("complete", function (file) {
      let dz = this;
      setTimeout(function () {
        dz.removeAllFiles(true);
        dz.element.innerHTML =
          '<div class="dz-message"> Click to open file dialog or drag picture here...</div>';
      }, 1000);
    });
  },
  acceptedFiles: "image/*",
  success: (file, responce) => {
    console.dir(responce);
    resp = JSON.parse(responce);
    if (resp.status == "success") {
      imgEl.src = "/img/goods/" + resp.filename;
      imgEl.classList.add("changed_img");
      newMainImg = resp.filename;
      bigImgDesc.innerHTML =
        "Main image for with good ave been changed. Click <Save changes> to apply";
    } else {
      alert(resp.status);
    }
    loadGoodImgForm.classList.add("hidden");
  },
});

/**
 * Выставляет класс, показывающий что value изменилось
 * @param {Object} el DOM-элемент <input>, содержимое которого изменяется
 */
function markChanged(el) {
  el.classList.add("changed_input");
}

function showPriceEditDlg() {
  document.querySelector("#edit-price-form").classList.remove("hidden");
}

function cancelPriceEditDlg() {
  document.querySelector("#edit-price-form").classList.add("hidden");
}

function startPriceAdd() {
  editDate.valueAsDate = new Date();
  editPrice.value = 0;

  priceEditMode = "add";
  form.classList.remove("hidden");
}

function startPriceEdit(id, price, currency, date) {
  let tmpDate = new Date(date);
  tmpDate.setDate(tmpDate.getDate() + 1);
  currentPriceId = id;
  editPrice.value = price;
  editCurrency.value = currency;
  editDate.valueAsDate = tmpDate;

  priceEditMode = "edit";
  form.classList.remove("hidden");
}

function postPriceEditing(id) {
  switch (priceEditMode) {
    case "add":
      addNewPrice(id);
      break;
    case "edit":
      commitEditPrice(id);
      break;
    default:
      alert("undefined operation " + priceEditMode);
  }
}

async function addNewPrice(goodId) {
  let priceObj = {
    action: "addPrice",
    id: goodId,
    dateOpen: editDate.valueAsDate.toISOString().slice(0, 19).replace("T", " "),
    price: editPrice.value,
    currency: editCurrency.value,
  };

  result = await postJson("/admin/goodedit/" + goodId, priceObj);
  if (result.status == "success") {
    goodPriceBlock.innerHTML = result.content;
    priceEditMode = "none";
    form.classList.add("hidden");
  } else {
    alert(result.status);
  }
}

async function commitEditPrice(goodId) {
  let priceObj = {
    action: "editPrice",
    id: currentPriceId,
    dateOpen: editDate.valueAsDate.toISOString().slice(0, 19).replace("T", " "),
    price: editPrice.value,
    currency: editCurrency.value,
  };

  result = await postJson("/admin/goodedit/" + goodId, priceObj);
  if (result.status == "success") {
    goodPriceBlock.innerHTML = result.content;
    priceEditMode = "none";
    form.classList.add("hidden");
  } else {
    alert(result.status);
  }
}

async function deletePrice(goodId, priceId) {
  if (!confirm("Really delete this price?")) {
    return 0;
  }

  result = await postJson("/admin/goodedit/" + goodId, {
    action: "deletePrice",
    priceId: priceId,
  });
  if (result.status == "success") {
    goodPriceBlock.innerHTML = result.content;
  } else {
    alert(result.status);
  }
}

function editMainGoodImg() {
  loadGoodImgForm.classList.remove("hidden");
}

function cancelLoadFiles() {
  loadGoodImgForm.classList.add("hidden");
}

async function saveGeneralInfo(id) {
  let requestBody = {
    action: "updateGeneralInfo",
    goodId: id,
    name: document.querySelector("#name").value,
    category: document.querySelector("#category").value,
    description: document.querySelector("#description").value,
    mainImg: imgEl.src.split("/").pop(),
  };

  console.dir(requestBody);

  result = await postJson("/admin/goodedit/" + id, requestBody);
  if (result.status == "success" || result.status == "Ok") {
    removeEditMarks();
    readInitParams();
  } else {
    alert(result.status);
  }
}

function readInitParams() {
  initialGoodValues.img = imgEl.src;
  initialGoodValues.title = document.querySelector("#name").value;
  initialGoodValues.category = document.querySelector("#category").value;
  initialGoodValues.description = document.querySelector("#description").value;
}

function restoreInitParams() {
  imgEl.src = initialGoodValues.img;
  document.querySelector("#name").value = initialGoodValues.title;
  document.querySelector("#category").value = initialGoodValues.category;
  document.querySelector("#description").value = initialGoodValues.description;
  removeEditMarks();
}

function removeEditMarks() {
  imgEl.classList.remove("changed_img");
  bigImgDesc.innerHTML =
    "It's the main image of this good. To load a new image press on the picture";
  document.querySelector("#name").classList.remove("changed_input");
  document.querySelector("#category").classList.remove("changed_input");
  document.querySelector("#description").classList.remove("changed_input");
}

window.onload = () => {
  readInitParams();
};
