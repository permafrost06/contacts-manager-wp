<h1>Contacts Manager Settings</h1>
<form action="" method="post">
  <p>Are you sure you want to delete contact with ID <?php echo $_GET['id'] ?>?</p>
  <p><?php echo $contact->name ?></p>
  <p><?php echo $contact->email ?></p>
  <p><?php echo $contact->phone ?></p>
  <p><?php echo $contact->address ?></p>
  <input type="hidden" name="id" value="<?php echo esc_attr($contact->id) ?>">
  <?php wp_nonce_field('delete-contact'); ?>
  <?php submit_button('Delete', 'button-link-delete', 'delete_contact'); ?>
  <a href="<?php echo admin_url('admin.php?page=contacts-manager') ?>">
    <button class="button button-primary">Cancel</button>
  </a>
</form>