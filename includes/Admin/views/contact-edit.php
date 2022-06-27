<h1>Contact Manager Settings</h1>
<h2>Edit Contact</h2>
<?php
if (isset($_GET['contact-updated'])) { ?>
  <div class="notice notice-success settings-error is-dismissible">
    <p><strong>Contact updated successfully</strong></p>
  </div>
<?php } ?>
<form enctype="multipart/form-data" action="" method="post">
  <table class="form-table">
    <tbody>
      <tr>
        <th scope="row"><label for="name_input">Name</label></th>
        <td>
          <input name="name" id="name_input" type="text" value="<?php echo esc_attr($contact->name) ?>">

          <?php if ($this->has_error('name')) { ?>
            <p class="description error"><?php echo $this->get_error('name') ?></p>
          <?php
          } ?>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="email_input">Email</label></th>
        <td>
          <input name="email" id="email_input" type="text" value="<?php echo esc_attr($contact->email) ?>">
          <?php if ($this->has_error('email')) { ?>
            <p class="description error"><?php echo $this->get_error('email') ?></p>
          <?php
          } ?>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="phone_input">Phone</label></th>
        <td>
          <input name="phone" id="phone_input" type="text" value="<?php echo esc_attr($contact->phone) ?>">
          <?php if ($this->has_error('phone')) { ?>
            <p class="description error"><?php echo $this->get_error('phone') ?></p>
          <?php
          } ?>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="address_input">Address</label></th>
        <td>
          <textarea name="address" id="address_input" type="text"><?php echo $contact->address ?></textarea>
          <?php if ($this->has_error('address')) { ?>
            <p class="description error"><?php echo $this->get_error('address') ?></p>
          <?php
          } ?>
        </td>
      </tr>
    </tbody>
  </table>

  <input type="hidden" name="id" value="<?php echo esc_attr($contact->id) ?>">
  <?php wp_nonce_field('new-contact'); ?>
  <?php submit_button('Update Contact', 'primary', 'submit_contact'); ?>
</form>