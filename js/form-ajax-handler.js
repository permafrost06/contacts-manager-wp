jQuery(document).ready(function ($) {
  console.log("js/myjquery.js loaded");
  //wrapper
  $("form.signup").on("submit", function (e) {
    //event
    e.preventDefault();
    const name = $("#contact_name").val();
    const email = $("#contact_email").val();
    const phone = $("#contact_phone").val();
    const address = $("#contact_address").val();

    const messageEl = $("#message");

    var this2 = this; //use in callback
    $.post(
      my_ajax_obj.ajax_url,
      {
        //POST request
        _ajax_nonce: my_ajax_obj.nonce, //nonce
        action: "contacts_manager_handle_ajax", //action
        name,
        email,
        phone,
        address,
      },
      function (data) {
        if (data.success) {
          messageEl.text(data.data.message);
          console.log(data);
        } else console.log("failure");
      }
    );
  });
});
