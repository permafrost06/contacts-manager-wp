(async function () {
  bg_color = await getSetting(
    "background_color",
    contacts_manager_card_ajax.ajax_url,
    contacts_manager_card_ajax.nonce
  );

  jQuery(".contacts-mgr-box.contact-card").css("background-color", bg_color);
})();
