async function getSetting(option, url, nonce) {
  const { _, data } = await jQuery.get(url, {
    action: "cm_get_setting",
    _ajax_nonce: nonce,
    option_name: option,
  });

  return data;
}
