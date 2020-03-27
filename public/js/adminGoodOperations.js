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