  <div class="wrap">
    <h1>Contacts Manager Settings</h1>
    <div id="icon-users" class="icon32"></div>
    <h2>Contacts List</h2>
    <?php
    if (isset($_GET['inserted'])) { ?>
      <div class="notice notice-success settings-error is-dismissible">
        <p><strong>Contact added</strong></p>
      </div>
    <?php } ?>
    <?php
    if (isset($_GET['contact-deleted'])) { ?>
      <div class="notice notice-success settings-error is-dismissible">
        <p><strong>Contact deleted.</strong></p>
      </div>
    <?php } ?>
    <p>
      <a href="<?php echo admin_url('admin.php?page=contacts-manager&action=add'); ?>">
        <button class="button button-primary">Add new contact</button>
      </a>
    </p>
    <?php
    $table = new Contacts\Manager\Admin\Contact_List();
    $table->prepare_items();
    $table->display();
    ?>
  </div>