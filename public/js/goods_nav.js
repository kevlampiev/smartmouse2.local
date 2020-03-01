async function getGoodsPage(categoryId, pageNo) {
  let result = await postJson("goodsCategory.php", {
    categoryId: categoryId,
    pageNo: pageNo
  });
  if (result.status == "success") {
    let container = document.querySelector(".main");
    container.innerHTML = result.page;
  }
}
