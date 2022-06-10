<?php
if (isset($_GET['confirm'])) {
  if (isset($_GET['id'])) {
    if ('delete' == $_GET['confirm']) {
      self::$contacts_controller->remove_contact($_GET['id']);
?>
      <div class="notice notice-error settings-error is-dismissible">
        <p><strong>User <?php echo $_GET['id'] ?> removed</strong></p>
      </div>
    <?php
    }

    if ('edit' == $_GET['confirm']) {
      self::$contacts_controller->update_contact($_GET['id'], $_POST['name'], $_POST['email'], $_POST['phone'], $_POST['address']);
    ?>
      <div class="notice notice-success settings-error is-dismissible">
        <p><strong>User <?php echo $_GET['id'] ?> updated</strong></p>
      </div>
    <?php
    }
  }

  if ('add' == $_GET['confirm']) {
    self::$contacts_controller->add_contact($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['address']);
    ?>
    <div class="notice notice-success settings-error is-dismissible">
      <p><strong>User added</strong></p>
    </div>
    <?php
  }
}

if (isset($_GET['action'])) {
  if (isset($_GET['id'])) {
    $data = self::$contacts_controller->get_contact($_GET['id']);

    if ('edit' == $_GET['action']) {
    ?>
      <h1>Contact Manager Settings</h1>
      <h2>Edit Contact</h2>
      <form action="?page=contacts-manager-settings&confirm=edit&id=<?php echo $_GET['id'] ?>" method="post">
        <table class="form-table">
          <tbody>
            <tr>
              <th scope="row">
                <span>ID</span>
              </th>
              <td>
                <span><?php echo $data['id'] ?></span>
              </td>
            </tr>
            <tr>
              <th scope="row"><label for="name_input">Name</label></th>
              <td>
                <input name="name" id="name_input" value="<?php echo $data['name'] ?>" type="text">
              </td>
            </tr>
            <tr>
              <th scope="row"><label for="email_input">Email</label></th>
              <td>
                <input name="email" id="email_input" value="<?php echo $data['email'] ?>" type="text">
              </td>
            </tr>
            <tr>
              <th scope="row"><label for="phone_input">Phone</label></th>
              <td>
                <input name="phone" id="phone_input" value="<?php echo $data['phone'] ?>" type="text">
              </td>
            </tr>
            <tr>
              <th scope="row"><label for="address_input">Address</label></th>
              <td>
                <textarea name="address" id="address_input" type="text"><?php echo $data['address'] ?></textarea>
              </td>
            </tr>
            <tr>
              <td>
                <a href="?page=contacts-manager-settings&confirm=edit&id=<?php echo $_GET['id'] ?>">
                  <button class="button button-primary">Submit</button>
                </a>
                <a href="?page=contacts-manager-settings">
                  <button type="button" class="button">Cancel</button>
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </form>
    <?php
    } elseif ('delete' == $_GET['action']) {
    ?>
      <h1>Contacts Manager Settings</h1>
      <p>Are you sure you want to delete contact with ID <?php echo $_GET['id'] ?>?</p>
      <p><?php echo $data['name'] ?></p>
      <p><?php echo $data['email'] ?></p>
      <p><?php echo $data['phone'] ?></p>
      <p><?php echo $data['address'] ?></p>
      <p>
        <a href="?page=contacts-manager-settings&confirm=delete&id=<?php echo $_GET['id'] ?>">
          <button class="button button-link-delete">Delete</button>
        </a>
        <a href="?page=contacts-manager-settings">
          <button class="button button-primary">Cancel</button>
        </a>
      </p>
    <?php
    }
  }
  if ('add' == $_GET['action']) {
    ?>
    <h1>Contact Manager Settings</h1>
    <h2>Edit Contact</h2>
    <form enctype="multipart/form-data" action="?page=contacts-manager-settings&confirm=add" method="post">
      <table class="form-table">
        <tbody>
          <tr>
            <th scope="row"><label for="name_input">Name</label></th>
            <td>
              <input name="name" id="name_input" type="text">
            </td>
          </tr>
          <tr>
            <th scope="row"><label for="email_input">Email</label></th>
            <td>
              <input name="email" id="email_input" type="text">
            </td>
          </tr>
          <tr>
            <th scope="row"><label for="phone_input">Phone</label></th>
            <td>
              <input name="phone" id="phone_input" type="text">
            </td>
          </tr>
          <tr>
            <th scope="row"><label for="address_input">Address</label></th>
            <td>
              <textarea name="address" id="address_input" type="text"></textarea>
            </td>
          </tr>
          <tr>
            <td>
              <a href="?page=contacts-manager-settings&confirm=add">
                <button class="button button-primary">Add</button>
              </a>
              <a href="?page=contacts-manager-settings">
                <button type="button" class="button">Cancel</button>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  <?php
  }
} else {
  $exampleListTable = new Example_List_Table();
  $exampleListTable->prepare_items();
  ?>
  <div class="wrap">
    <h1>Contacts Manager Settings</h1>
    <div id="icon-users" class="icon32"></div>
    <h2>Contacts List</h2>
    <p>
      <a href="?page=contacts-manager-settings&action=add">
        <button class="button button-primary">Add new contact</button>
      </a>
    </p>
    <?php $exampleListTable->display(); ?>
  </div>
<?php
}
