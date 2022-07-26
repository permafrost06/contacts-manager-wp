  <div class="contacts-mgr-box contact-card">
    <p class="field">
      <span class="field-name">Name:</span>
      <?php esc_html_e($contact->name) ?>
    </p>
    <p class="field">
      <span class="field-name">Email:</span>
      <a href="mailto:<?php esc_attr_e($contact->email) ?>">
        <?php esc_html_e($contact->email) ?>
      </a>
    </p>
    <p class="field">
      <span class="field-name">Phone:</span>
      <a href="tel:<?php esc_attr_e($contact->phone) ?>">
        <?php esc_html_e($contact->phone) ?>
      </a>
    </p>
    <p class="field">
      <span class="field-name">Address:</span>
      <?php esc_html_e($contact->address) ?>
    </p>
  </div>