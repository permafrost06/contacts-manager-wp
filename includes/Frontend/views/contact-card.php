  <div class="contacts-mgr-box contact-card">
    <p class="field">
      <span class="field-name">Name:</span>
      <?php esc_html_e($data->name) ?>
    </p>
    <p class="field">
      <span class="field-name">Email:</span>
      <a href="mailto:<?php esc_attr_e($data->email) ?>">
        <?php esc_html_e($data->email) ?>
      </a>
    </p>
    <p class="field">
      <span class="field-name">Phone:</span>
      <a href="tel:<?php esc_attr_e($data->phone) ?>">
        <?php esc_html_e($data->phone) ?>
      </a>
    </p>
    <p class="field">
      <span class="field-name">Address:</span>
      <?php esc_html_e($data->address) ?>
    </p>
  </div>