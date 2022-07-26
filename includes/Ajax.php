<?php

namespace Contacts\Manager;

use Exception;
use Contacts\Manager\Http\Request;

class Ajax
{
  protected $prefix = 'cm';
  protected $contacts_controller;
  protected $request;

  public function __construct(ContactsController $contacts_controller)
  {
    $this->contacts_controller = $contacts_controller;
    $this->request = new Request();

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

  public function checkReferer($referer = 'admin_app')
  {
    if (!check_ajax_referer($referer, false, false)) {
      wp_send_json_error(['message' => 'Nonce check failed'], 403);
    }
  }

  public function submitForm()
  {
    $this->checkReferer('cm-contact-form');

    $contact = $this->request->getContactObject();

    try {
      $this->contacts_controller->addContact($contact['name'], $contact['email'], $contact['phone'], $contact['address']);

      wp_send_json_success(['message' => 'Successfully added contact!']);
    } catch (Exception $error) {
      wp_send_json_error(['message' => $error->getMessage()], 403);
    }
  }

  public function handleGetAllContacts()
  {
    $this->checkReferer();

    try {
      $contacts = $this->contacts_controller->getAllContacts();

      wp_send_json_success(['contacts' => $contacts]);
    } catch (Exception $error) {
      wp_send_json_error(['error' => $error->getMessage()], 403);
    }
  }

  public function handleAddContact()
  {
    $this->checkReferer();

    $contact = $this->request->getContactObject();

    try {
      $this->contacts_controller->addContact($contact['name'], $contact['email'], $contact['phone'], $contact['address']);

      wp_send_json_success();
    } catch (Exception $error) {
      wp_send_json_error(['error' => $error->getMessage()], 403);
    }
  }

  public function handleGetContact()
  {
    $this->checkReferer();

    $id = $this->request->input('id');

    try {
      $contact = $this->contacts_controller->getContact($id);

      wp_send_json_success(['contact' => $contact]);
    } catch (Exception $error) {
      wp_send_json_error(['error' => $error->getMessage()], 404);
    }
  }

  public function handleUpdateContact()
  {
    $this->checkReferer();

    $contact = $this->request->getContactObject();

    try {
      $this->contacts_controller->updateContact($contact['id'], $contact['name'], $contact['email'], $contact['phone'], $contact['address']);

      wp_send_json_success();
    } catch (Exception $error) {
      wp_send_json_error(['error' => $error->getMessage()], 401);
    }
  }

  public function handleDeleteContact()
  {
    $this->checkReferer();

    $id = $this->request->input('id');

    try {
      $this->contacts_controller->deleteContact($id);

      wp_send_json_success();
    } catch (Exception $error) {
      wp_send_json_error(['error' => $error->getMessage()], 404);
    }
  }
}
