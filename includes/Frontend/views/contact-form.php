<div class="wrap">
  <form class="cm-contact-form" action="" method="post">
    <h3>Add Contact</h3>

    <div class="input-outer">
      <div class="input-wrapper">
        <label class="input-label" for="contact_name">
          <span class="required-indicator">*</span>
          Name:
        </label>
        <div class="input-inner">
          <input class="target" type="text" name="name" id="contact_name">
        </div>
      </div>
      <div class="input-error" id="name_error"></div>
    </div>

    <div class="input-outer">
      <div class="input-wrapper">
        <label class="input-label" for="contact_email">
          <span class="required-indicator">*</span>
          Email:
        </label>
        <div class="input-inner">
          <input type="email" name="email" id="contact_email">
        </div>
      </div>
      <div class="input-error" id="email_error"></div>
    </div>

    <div class="input-outer">
      <div class="input-wrapper">
        <label class="input-label" for="contact_phone">
          <span class="required-indicator">*</span>
          Phone no:
        </label>
        <div class="input-inner">
          <input type="text" name="phone" id="contact_phone">
        </div>
      </div>
      <div class="input-error" id="phone_error"></div>
    </div>

    <div class="input-outer">
      <div class="input-wrapper">
        <label class="input-label" for="contact_address">
          <span class="required-indicator">*</span>
          Address:
        </label>
        <div class="input-inner">
          <input type="text" name="address" id="contact_address">
        </div>
      </div>
      <div class="input-error" id="address_error"></div>
    </div>

    <?php wp_nonce_field('cm-frontend-shortcode'); ?>

    <input type="hidden" name="action" value="cm_contact_form">

    <p id="success-message"></p>
    <p id="error-message"></p>

    <input type="submit" name="send_form" value="Submit">
  </form>
</div>