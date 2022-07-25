<?php

namespace Contacts\Manager;

use Exception;

class Ajax
{
  protected $prefix = 'cm';
  protected $contacts_controller;

  public function __construct(ContactsController $contacts_controller)
  {
    $this->contacts_controller = $contacts_controller;

    foreach ($this->getActions() as $action => $handler) {
      $nopriv = isset($handler['nopriv']) ? $handler['nopriv'] : false;

      if ($nopriv) {
        add_action("wp_ajax_nopriv_{$this->prefix}_{$action}", $handler['function']);
      }

      add_action("wp_ajax_{$this->prefix}_{$action}", $handler['function']);
    }
  }

  public function getActions()
  {
    return [
      'contact_form' => ['function' => [$this, 'submitForm'], 'nopriv' => true],
      'get_all_contacts' => ['function' => [$this, 'handleGetAllContacts']],
      'add_contact' => ['function' => [$this, 'handleAddContact']],
      'get_contact' => ['function' => [$this, 'handleGetContact']],
      'update_contact' => ['function' => [$this, 'handleUpdateContact']],
      'delete_contact' => ['function' => [$this, 'handleDeleteContact']],
    ];
  }

  public function submitForm()
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
      $this->contacts_controller->addContact($name, $email, $phone, $address);

      wp_send_json_success(['message' => 'Successfully added contact!']);
    } catch (Exception $error) {
      wp_send_json_error(['message' => $error->getMessage()]);
    }
  }

  public function handleGetAllContacts()
  {
    check_ajax_referer('admin_app');

    try {
      $contacts = $this->contacts_controller->getAllContacts();

      wp_send_json_success(['contacts' => $contacts]);
    } catch (Exception $error) {
      wp_send_json_error(['error' => $error->getMessage()]);
    }
  }

  public function handleAddContact()
  {
    check_ajax_referer('admin_app');

    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_text_field($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $address = sanitize_textarea_field($_POST['address']);

    try {
      $this->contacts_controller->addContact($name, $email, $phone, $address);

      wp_send_json_success();
    } catch (Exception $error) {
      wp_send_json_error(['error' => $error->getMessage()]);
    }
  }

  public function handleGetContact()
  {
    check_ajax_referer('admin_app');

    $id = sanitize_text_field($_REQUEST['id']);

    try {
      $contact = $this->contacts_controller->getContact($id);

      wp_send_json_success(['contact' => $contact]);
    } catch (Exception $error) {
      wp_send_json_error(['error' => $error->getMessage()]);
    }
  }

  public function handleUpdateContact()
  {
    check_ajax_referer('admin_app');

    $id = sanitize_text_field($_POST['id']);
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_text_field($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $address = sanitize_textarea_field($_POST['address']);

    try {
      $this->contacts_controller->updateContact($id, $name, $email, $phone, $address);

      wp_send_json_success();
    } catch (Exception $error) {
      wp_send_json_error(['error' => $error->getMessage()]);
    }
  }

  public function handleDeleteContact()
  {
    check_ajax_referer('admin_app');

    $id = sanitize_text_field($_POST['id']);

    try {
      $this->contacts_controller->deleteContact($id);

      wp_send_json_success();
    } catch (Exception $error) {
      wp_send_json_error(['error' => $error->getMessage()]);
    }
  }
}
