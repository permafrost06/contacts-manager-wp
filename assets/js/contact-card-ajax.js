(async function () {
  bg_color = await getSetting(
    "background_color",
    contacts_manager_ajax.ajax_url,
    contacts_manager_ajax.nonce
  );

  jQuery(".contacts-mgr-box.contact-card").css("background-color", bg_color);
})();
