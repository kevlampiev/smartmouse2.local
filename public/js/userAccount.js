let wnd = document.querySelector(".fieldEditForm");
let editInp = wnd.querySelector("input");
let mainLabel = wnd.querySelector("h4");


function startEditUserData(fieldName, title) {
  mainLabel.innerHTML = title;
  let oldValue = document.querySelector("#" + fieldName + " .fieldBlock__data")
    .innerHTML;
  wnd.setAttribute("data-fieldName", fieldName);
  editInp.value = oldValue;
  wnd.classList.remove("hidden-form");
}

function cancelEditUserData() {
  wnd.classList.add("hidden-form");
}

async function proceedEditUserData() {
  let newValue = editInp.value;
  let fieldName=wnd.getAttribute('data-fieldName')
  let result= await postJson('editUserInfo.php',{
    action: 'editUserInfo',
    fieldName: fieldName,
    value: newValue
  });

  if (result.hasOwnProperty('status')&&result.status==='success') {    
    document.querySelector("#" + fieldName + " .fieldBlock__data")
    .innerHTML=newValue
  } 
  wnd.classList.remove('hidden-form')
}


document.addEventListener('load',()=> {
    wnd = document.querySelector(".fieldEditForm");
    editInp = wnd.querySelector("input");
    mainLabel = wnd.querySelector("h4");    
})