<?php

namespace Contacts\Manager;

class Ajax
{
  public $prefix = 'cm';

  function __construct()
  {
    foreach ($this->get_actions() as $action => $handler) {
      $nopriv = isset($handler['nopriv']) ? $handler['nopriv'] : false;

      if ($nopriv) {
        add_action("wp_ajax_nopriv_{$this->prefix}_{$action}", $handler['function']);
      }

      add_action("wp_ajax_{$this->prefix}_{$action}", $handler['function']);
    }
  }

  function get_actions()
  {
    return [
      'contact_form' => ['function' => [$this, 'submit_form'], 'nopriv' => true],
      'get_all_contacts' => ['function' => [$this, 'handle_get_all_contacts']],
      'add_contact' => ['function' => [$this, 'handle_add_contact']],
      'get_contact' => ['function' => [$this, 'handle_get_contact']],
      'update_contact' => ['function' => [$this, 'handle_update_contact']],
      'delete_contact' => ['function' => [$this, 'handle_delete_contact']],
    ];
  }

  public function submit_form()
  {
    if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'cm-contact-form')) {
      wp_send_json_error([
        'message' => 'Nonce verification failed'
      ]);
    }

    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_text_field($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $address = sanitize_textarea_field($_POST['address']);

    try {
      ContactsController::add_contact($name, $email, $phone, $address);

      wp_send_json_success(['message' => 'Successfully added contact!']);
    } catch (\Exception $error) {
      wp_send_json_error(['message' => $error->getMessage()]);
    }
  }

  public function handle_get_all_contacts()
  {
    check_ajax_referer('admin_app');

    try {
      $contacts = ContactsController::get_all_contacts();

      wp_send_json_success(['contacts' => $contacts]);
    } catch (\Exception $error) {
      wp_send_json_error(['error' => $error->getMessage()]);
    }
  }

  public function handle_add_contact()
  {
    check_ajax_referer('admin_app');

    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_text_field($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $address = sanitize_textarea_field($_POST['address']);

    try {
      ContactsController::add_contact($name, $email, $phone, $address);

      wp_send_json_success();
    } catch (\Exception $error) {
      wp_send_json_error(['error' => $error->getMessage()]);
    }
  }

  public function handle_get_contact()
  {
    check_ajax_referer('admin_app');

    $id = sanitize_text_field($_POST['id']);

    try {
      $contact = ContactsController::get_contact($id);

      wp_send_json_success(['contact' => $contact]);
    } catch (\Exception $error) {
      wp_send_json_error(['error' => $error->getMessage()]);
    }
  }

  public function handle_update_contact()
  {
    check_ajax_referer('admin_app');

    $id = sanitize_text_field($_POST['id']);
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_text_field($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $address = sanitize_textarea_field($_POST['address']);

    try {
      ContactsController::update_contact($id, $name, $email, $phone, $address);

      wp_send_json_success();
    } catch (\Exception $error) {
      wp_send_json_error(['error' => $error->getMessage()]);
    }
  }

  public function handle_delete_contact()
  {
    check_ajax_referer('admin_app');

    $id = sanitize_text_field($_POST['id']);

    try {
      ContactsController::delete_contact($id);

      wp_send_json_success();
    } catch (\Exception $error) {
      wp_send_json_error(['error' => $error->getMessage()]);
    }
  }
}
