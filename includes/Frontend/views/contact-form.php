<div class="wrap">
  <form class="cm-contact-form" action="" method="post">
    <h3>Add Contact</h3>

    <div class="input-outer">
      <div class="input-wrapper">
        <label class="input-label" for="contact_name">Name: </label>
        <div class="input-inner">
          <input class="target" type="text" name="name" id="contact_name">
        </div>
      </div>
    </div>

    <div class="input-outer">
      <div class="input-wrapper">
        <label class="input-label" for="contact_email">Email: </label>
        <div class="input-inner">
          <input type="email" name="email" id="contact_email">
        </div>
      </div>
    </div>

    <div class="input-outer">
      <div class="input-wrapper">
        <label class="input-label" for="contact_phone">Phone no: </label>
        <div class="input-inner">
          <input type="text" name="phone" id="contact_phone">
        </div>
      </div>
    </div>

    <div class="input-outer">
      <div class="input-wrapper">

        <label class="input-label" for="contact_address">Address: </label>
        <div class="input-inner">
          <input type="text" name="address" id="contact_address">
        </div>
      </div>
    </div>

    <?php wp_nonce_field('cm-contact-form'); ?>

    <input type="hidden" name="action" value="cm_contact_form">
    <input type="submit" name="send_form" value="Submit">
    <p id="success-message"></p>
    <p id="error-message"></p>
  </form>
</div>