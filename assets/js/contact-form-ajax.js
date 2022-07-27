function validateName() {
  const errorEl = jQuery("#name_error");
  errorEl.text("");

  const value = jQuery("#contact_name").val();
  if (value === "") {
    errorEl.text("Name is required");
    return false;
  }

  return true;
}

function validateEmail() {
  const errorEl = jQuery("#email_error");
  errorEl.text("");

  const value = jQuery("#contact_email").val();

  if (value === "") {
    errorEl.text("Email is required");
    return false;
  }

  const emailRegex = new RegExp(".+@.+\\..+", "g");

  if (!emailRegex.test(value)) {
    errorEl.text("Please enter a valid email address");
    return false;
  }

  return true;
}

function validatePhone() {
  const errorEl = jQuery("#phone_error");
  errorEl.text("");

  const value = jQuery("#contact_phone").val();

  if (value === "") {
    errorEl.text("Phone number is required");
    return false;
  }

  const phoneRegex = new RegExp("^[-+ ()\\d]+$");

  if (!phoneRegex.test(value)) {
    errorEl.text("Please enter a valid phone number");
    return false;
  }

  return true;
}

function validateAddress() {
  const errorEl = jQuery("#address_error");
  errorEl.text("");

  const value = jQuery("#contact_address").val();

  if (value === "") {
    errorEl.text("Address is required");
    return false;
  }

  return true;
}

function formValidated() {
  const nameValidated = validateName();
  const emailValidated = validateEmail();
  const phoneValidated = validatePhone();
  const addressValidated = validateAddress();

  return nameValidated && emailValidated && phoneValidated && addressValidated;
}

(function () {
  jQuery("#contact_name").on("input", validateName);

  jQuery("#contact_email").on("input", validateEmail);

  jQuery("#contact_phone").on("input", validatePhone);

  jQuery("#contact_address").on("input", validateAddress);

  jQuery("form.cm-contact-form").on("submit", function (e) {
    clearMessage();

    e.preventDefault();

    if (!formValidated()) {
      jQuery("#error-message").text(
        "Please fix the errors before submitting the form"
      );
      return;
    }

    var data = jQuery(this).serialize();

    const successEl = jQuery("#success-message");
    const errorEl = jQuery("#error-message");

    jQuery
      .post(contacts_manager_ajax.ajax_url, data, function (data) {
        if (data.success) {
          successEl.text(data.data.message);
        } else {
          errorEl.text(data.data.message);
        }
      })
      .fail(function (xhr) {
        const res = JSON.parse(xhr.responseText);
        errorEl.text(res.data.message);
      });
  });

  jQuery("input").on("input", clearMessage);

  function clearMessage() {
    const successEl = jQuery("#success-message");
    const errorEl = jQuery("#error-message");

    successEl.text("");
    errorEl.text("");
  }
})();
