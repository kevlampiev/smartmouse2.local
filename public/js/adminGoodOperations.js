let priceEditMode="none";
let currentPriceId=0;
const form=document.querySelector('#edit-price-form')
const editDate=document.querySelector('#editDate')
const editPrice=document.querySelector('#editPrice')
const editCurrency=document.querySelector('#editCurrency')
const goodPriceBlock=document.querySelector('#good_price_block')



/**
 * Выставляет класс, показывающий что value изменилось
 * @param {Object} el DOM-элемент <input>, содержимое которого изменяется
 */
function markChanged(el) {
    el.classList.add("changed_input");
  }

  function showPriceEditDlg() {
    document.querySelector('#edit-price-form').classList.remove("hidden")
  }

  function cancelPriceEditDlg() {
    document.querySelector('#edit-price-form').classList.add("hidden")
  }

  function startPriceAdd() {
    editDate.valueAsDate= new Date();
    editPrice.value=0
    
    priceEditMode="add";
    form.classList.remove("hidden")
  }


  function startPriceEdit(id,price,currency,date) {

    let tmpDate=new Date(date)
    tmpDate.setDate(tmpDate.getDate() + 1)
    currentPriceId=id
    editPrice.value=price
    editCurrency.value=currency
    editDate.valueAsDate= tmpDate

    priceEditMode="edit";
    form.classList.remove("hidden")
  }


  function postPriceEditing(id) {
    switch (priceEditMode) {
      case 'add': addNewPrice(id);
                  break;
      case 'edit': commitEditPrice(id);
                  break;            
      default: alert('undefined operation '+priceEditMode)            
    }
  }

  async function addNewPrice(goodId) {
    let priceObj={
      action: 'addPrice',
      id: goodId,
      dateOpen: editDate.valueAsDate.toISOString().slice(0, 19).replace('T', ' '),
      price: editPrice.value,
      currency: editCurrency.value
    }

    result=await postJson('/admin/goodedit/'+goodId,priceObj)
    if (result.status=="success") {
      goodPriceBlock.innerHTML=result.content
      priceEditMode='none'
      form.classList.add("hidden")
    } else {
      alert(result.status)
    }
  }

  async function commitEditPrice(goodId) {
    let priceObj={
      action: 'editPrice',
      id: currentPriceId,
      dateOpen: editDate.valueAsDate.toISOString().slice(0, 19).replace('T', ' '),
      price: editPrice.value,
      currency: editCurrency.value
    }

    result=await postJson('/admin/goodedit/'+goodId,priceObj)
    if (result.status=="success") {
      goodPriceBlock.innerHTML=result.content
      priceEditMode='none'
      form.classList.add("hidden")
    } else {
      alert(result.status)
    }
  }

async function deletePrice(goodId, priceId) {
  if (!confirm("Really delete this price?")) {
    return 0;
  }
    
    result=await postJson('/admin/goodedit/'+goodId,{
      action: "deletePrice",
      priceId: priceId
    })
    if (result.status=="success") {
      goodPriceBlock.innerHTML=result.content      
    } else {
      alert(result.status)
    }

  }