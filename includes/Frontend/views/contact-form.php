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
          <input class="input name-input" type="text" name="name" id="contact_name">
        </div>
      </div>
      <div class="input-error name-error"></div>
    </div>

    <div class="input-outer">
      <div class="input-wrapper">
        <label class="input-label" for="contact_email">
          <span class="required-indicator">*</span>
          Email:
        </label>
        <div class="input-inner">
          <input class="input email-input" type="email" name="email" id="contact_email">
        </div>
      </div>
      <div class="input-error email-error"></div>
    </div>

    <div class="input-outer">
      <div class="input-wrapper">
        <label class="input-label" for="contact_phone">
          <span class="required-indicator">*</span>
          Phone no:
        </label>
        <div class="input-inner">
          <input class="input phone-input" type="text" name="phone" id="contact_phone">
        </div>
      </div>
      <div class="input-error phone-error"></div>
    </div>

    <div class="input-outer">
      <div class="input-wrapper">
        <label class="input-label" for="contact_address">
          <span class="required-indicator">*</span>
          Address:
        </label>
        <div class="input-inner">
          <input class="input address-input" type="text" name="address" id="contact_address">
        </div>
      </div>
      <div class="input-error address-error"></div>
    </div>

    <?php wp_nonce_field('cm-frontend-shortcode'); ?>

    <input type="hidden" name="action" value="cm_contact_form">

    <p class="message success-message"></p>
    <p class="message error-message"></p>

    <input class="submit-input" type="submit" name="send_form" value="Submit">
  </form>
</div>