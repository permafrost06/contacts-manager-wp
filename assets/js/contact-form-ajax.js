(function ($) {
  $("form.signup").on("submit", function (e) {
    clearMessage();
    e.preventDefault();

    var data = $(this).serialize();

    const successEl = $("#success-message");
    const errorEl = $("#error-message");

    $.post(contacts_manager_ajax.ajax_url, data, function (data) {
      if (data.success) {
        successEl.text(data.data.message);
      } else {
        errorEl.text(data.data.message);
      }
    }).fail(function () {
      alert(contacts_manager_ajax.error);
    });
  });

  $("input").on("input", clearMessage);

  function clearMessage() {
    const successEl = $("#success-message");
    const errorEl = $("#error-message");

    $("button").prop("disabled", false);

    successEl.text("");
    errorEl.text("");
  }
})(jQuery);
