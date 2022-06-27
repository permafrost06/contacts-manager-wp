<?php

namespace Contacts\Manager;

class Ajax
{
  function __construct()
  {
    add_action('wp_ajax_cm_contact_form', [$this, 'submit_form']);
  }

  public function submit_form()
  {
    if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'cm-contact-form')) {
      wp_send_json_error([
        'message' => 'Nonce verification failed'
      ]);
    }

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    try {
      \Contacts\Manager\ContactsController::add_contact($name, $email, $phone, $address);

      wp_send_json_success(['message' => 'Successfully added contact!']);
    } catch (\Exception $error) {
      wp_send_json_error(['message' => 'Could not add contact!']);
    }
  }
}
