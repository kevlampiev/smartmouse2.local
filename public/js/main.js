function getCookie(name) {
  let matches = document.cookie.match(
    new RegExp(
      "(?:^|; )" +
        name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, "\\$1") +
        "=([^;]*)"
    )
  );
  return matches ? decodeURIComponent(matches[1]) : undefined;
}

async function postJson(url, data) {
  try {
    const result = await fetch(url, {
      method: "POST",
      headers: {
        "Content-type": "application/json"
      },
      body: JSON.stringify(data)
    });
    return result.json();
  } catch (err) {
    console.error(err);
    //Task. Надо добить вывод ошибки в отдельном окошке всплывающем. Работа для vue
  }
}
