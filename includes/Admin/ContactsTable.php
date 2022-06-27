<?php

namespace Contacts\Manager\Admin;

use Contacts\Manager\Traits\Form_Error;

/**
 * Contacts Table handler class
 */
class ContactsTable
{
  use Form_Error;

  public function plugin_page()
  {
    $action = isset($_GET['action']) ? $_GET['action'] : 'list';
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id) {
      $contact = \Contacts\Manager\ContactsController::get_contact($id);
    }

    switch ($action) {
      case 'add':
        $template = __DIR__ . '/views/contact-add.php';
        break;

      case 'edit':
        $template = __DIR__ . '/views/contact-edit.php';
        break;

      case 'delete':
        $template = __DIR__ . '/views/contact-delete.php';
        break;

      default:
        $template = __DIR__ . '/views/contact-list.php';
        break;
    }

    if (file_exists($template)) {
      include $template;
    }
  }

  public function form_handler()
  {
    if (!isset($_POST['submit_contact'])) {
      return;
    }

    if (!wp_verify_nonce($_POST['_wpnonce'], 'new-contact')) {
      wp_die("nonce not valid");
    }

    if (!current_user_can('manage_options')) {
      wp_die("user does not have permission");
    }

    $id = isset($_POST['id']) ? intval($_GET['id']) : 0;
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_text_field($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
    $address = isset($_POST['address']) ? sanitize_textarea_field($_POST['address']) : '';

    if (empty($name)) {
      $this->errors['name'] = "Please provide a name";
    }

    if (empty($email)) {
      $this->errors['email'] = "Please provide an email";
    }

    if (empty($phone)) {
      $this->errors['phone'] = "Please provide a phone number";
    }

    if (empty($address)) {
      $this->errors['address'] = "Please provide an address";
    }

    if (!empty($this->errors)) {
      return;
    }

    if ($id) {
      try {
        \Contacts\Manager\ContactsController::update_contact($id, $name, $email, $phone, $address);
      } catch (\Exception $error) {
        wp_die($error);
      }

      $redirect_url = admin_url('admin.php?page=contacts-manager&contact-updated=true&action=edit&id=' . $id);
    } else {
      try {
        \Contacts\Manager\ContactsController::add_contact($name, $email, $phone, $address);
      } catch (\Exception $error) {
        wp_die($error);
      }

      $redirect_url = admin_url('admin.php?page=contacts-manager&inserted=true');
    }

    wp_redirect($redirect_url);
    exit;
  }

  public function delete_handler()
  {
    if (!isset($_POST['delete_contact'])) {
      return;
    }

    if (!wp_verify_nonce($_POST['_wpnonce'], 'delete-contact')) {
      wp_die("nonce not valid");
    }

    if (!current_user_can('manage_options')) {
      wp_die("user does not have permission");
    }

    $id = isset($_POST['id']) ? intval($_GET['id']) : 0;

    if ($id) {
      try {
        \Contacts\Manager\ContactsController::delete_contact($id);
      } catch (\Exception $error) {
        wp_die($error);
      }

      $redirect_url = admin_url('admin.php?page=contacts-manager&contact-deleted=true');
    }

    wp_redirect($redirect_url);
    exit;
  }
}
