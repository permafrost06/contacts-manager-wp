<?php

namespace Contacts\Manager;

class Ajax
{
  function __construct()
  {
    foreach ($this->get_actions() as $action => $handler) {
      add_action('wp_ajax_' . $action, [$this, $handler]);
    }
  }

  function get_actions()
  {
    return [
      'cm_contact_form' => 'submit_form',
      'ajax_test' => 'ajax_test'
    ];
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

  public function ajax_test()
  {
    if ('test' == $_POST['message']) {
      wp_send_json_success();
    } else {
      wp_send_json_error();
    }
  }
}
