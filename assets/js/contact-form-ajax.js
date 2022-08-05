function validateName() {
  const errorEl = jQuery(this)
    .parents(".input-outer")
    .children(".input-error.name-error");
  errorEl.text("");

  const value = jQuery(this).val();
  if (value === "") {
    errorEl.text("Name is required");
    return false;
  }

  return true;
}

async function emailExists(email) {
  const response = await jQuery.get(contacts_manager_ajax.ajax_url, {
    action: "cm_check_email_exists",
    _ajax_nonce: contacts_manager_ajax.nonce,
    email,
  });

  return response.data;
}

async function validateEmail() {
  const errorEl = jQuery(this)
    .parents(".input-outer")
    .children(".input-error.email-error");
  errorEl.text("");

  const value = jQuery(this).val();

  if (value === "") {
    errorEl.text("Email is required");
    return false;
  }

  const emailRegex = new RegExp(".+@.+\\..+", "g");

  if (!emailRegex.test(value)) {
    errorEl.text("Please enter a valid email address");
    return false;
  }

  if (await emailExists(value)) {
    errorEl.text("Email already used");
    return false;
  }

  return true;
}

function validatePhone() {
  const errorEl = jQuery(this)
    .parents(".input-outer")
    .children(".input-error.phone-error");
  errorEl.text("");

  const value = jQuery(this).val();

  if (value === "") {
    errorEl.text("Phone number is required");
    return false;
  }

  if (value.length < 5 || value.length > 20) {
    errorEl.text("Phone number length must be between 5 and 20");
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
  const errorEl = jQuery(this)
    .parents(".input-outer")
    .children(".input-error.address-error");
  errorEl.text("");

  const value = jQuery(this).val();

  if (value === "") {
    errorEl.text("Address is required");
    return false;
  }

  return true;
}

function clearForm(formEl) {
  formEl.find(".name-input").val("");
  formEl.find(".email-input").val("");
  formEl.find(".phone-input").val("");
  formEl.find(".address-input").val("");
}

async function formValidated(formEl) {
  const nameValidated = validateName.call(
    jQuery(formEl).find(".input.name-input")[0]
  );
  const emailValidated = await validateEmail.call(
    jQuery(formEl).find(".input.email-input")[0]
  );
  const phoneValidated = validatePhone.call(
    jQuery(formEl).find(".input.phone-input")[0]
  );
  const addressValidated = validateAddress.call(
    jQuery(formEl).find(".input.address-input")[0]
  );

  return nameValidated && emailValidated && phoneValidated && addressValidated;
}

(function () {
  jQuery(".name-input").on("input", validateName);

  jQuery(".email-input").on("input", validateEmail);

  jQuery(".phone-input").on("input", validatePhone);

  jQuery(".address-input").on("input", validateAddress);

  jQuery("form.cm-contact-form").on("submit", async function (e) {
    const formEl = jQuery(this);

    clearMessage(formEl);

    e.preventDefault();

    const successEl = jQuery(this).find(".success-message");
    const errorEl = jQuery(this).find(".error-message");

    if (!(await formValidated(this))) {
      errorEl.text("Please fix the errors before submitting the form");
      return;
    }

    const data = jQuery(this).serialize();

    jQuery
      .post(contacts_manager_ajax.ajax_url, data, function (data) {
        if (data.success) {
          successEl.text("Contact added successfully ");
          clearForm(formEl);
        } else {
          errorEl.text(data.data.error);
        }
      })
      .fail(function (xhr) {
        const res = JSON.parse(xhr.responseText);
        errorEl.text(res.data.error);
      });
  });

  jQuery("input").on("input", function () {
    clearMessage(jQuery(this).parents("form.cm-contact-form"));
  });

  function clearMessage(formEl) {
    const successEl = formEl.find(".success-message");
    const errorEl = formEl.find(".error-message");

    successEl.text("");
    errorEl.text("");
  }
})();
