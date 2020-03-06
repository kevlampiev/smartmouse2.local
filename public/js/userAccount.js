let wnd = document.querySelector("#editAnyButNotPass");
let editInp = wnd.querySelector("#editEnyInput");
let mainLabel = wnd.querySelector("h4");
let nameLabel = document.querySelector(".userNameDspl");

//-----------------------------------------------------
//Редактирование всех полей, кроме пароля
//-----------------------------------------------------
function startEditUserData(fieldName, title) {
  mainLabel.innerHTML = title;
  let oldValue = document.querySelector("#" + fieldName + " .fieldBlock__data")
    .innerHTML;
  oldValue = oldValue.replace(/\t/g, "");
  wnd.setAttribute("data-fieldName", fieldName);
  editInp.value = oldValue;
  wnd.classList.remove("hidden-form");
}

function cancelEditUserData() {
  wnd.classList.add("hidden-form");
}

async function proceedEditUserData() {
  let newValue = editInp.value;
  let fieldName = wnd.getAttribute("data-fieldName");
  document.styleSheets.cursor = "wait";
  let result = await postJson("/useraccount", {
    action: "editUserInfo",
    fieldName: fieldName,
    value: newValue
  });

  if (result.hasOwnProperty("status") && result.status === "success") {
    document.querySelector(
      "#" + fieldName + " .fieldBlock__data"
    ).innerHTML = newValue;
    //Особый случай - изменение имени, надопоправить шанку сайта
    if (fieldName == "name") {
      nameLabel.innerHTML = newValue;
    }
    wnd.classList.add("hidden-form");
    document.styleSheets.cursor = "pointer";
  }
}

//-----------------------------------------------------
//Редактирование пароля
//-----------------------------------------------------
let wndPass = document.querySelector("#editPass");
let editcurrentPass = document.querySelector("#currentPassword");
let editNewPass1 = document.querySelector("#newPassword1");
let editNewPass2 = document.querySelector("#newPassword2");

function startEditUserPass() {
  wndPass.classList.remove("hidden-form");
}

function cancelEditUserPass() {
  wndPass.classList.add("hidden-form");
}

async function proceedEditUserPass() {
  if (editNewPass1.value != editNewPass2.value) {
    alert("Entered passwords don't match");
  } else {
    let result = await postJson("/useraccount", {
      action: "editUserInfo",
      fieldName: "password",
      currentPassword: editcurrentPass.value,
      value: editNewPass1.value
    });
    alert(result);
    wnd.classList.add("hidden-form");
  }
}

document.addEventListener("load", () => {
  wnd = document.querySelector(".fieldEditForm");
  editInp = wnd.querySelector("input");
  mainLabel = wnd.querySelector("h4");
});
