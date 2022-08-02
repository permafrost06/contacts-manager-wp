<?php

namespace Contacts\Manager\Admin;

use Contacts\Manager\ContactsController;
use Contacts\Manager\SettingsController;
use Contacts\Manager\Ajax as AjaxBase;
use Contacts\Manager\Http\Request;

class Ajax extends AjaxBase
{
  protected $prefix = 'cm';
  protected $contacts_controller;
  protected $settings_controller;
  protected $request;

  public function __construct(ContactsController $contacts_controller, SettingsController $settings_controller, Request $request)
  {
    $this->contacts_controller = $contacts_controller;
    $this->settings_controller = $settings_controller;
    $this->request = $request;

    foreach ($this->getActions() as $action => $handler) {
      add_action("wp_ajax_{$this->prefix}_{$action}", $handler['function']);
    }
  }

  public function getActions()
  {
    return [
      'get_contact' => ['function' => [$this, 'handleGetContact']],
      'add_contact' => ['function' => [$this, 'handleAddContact']],
      'update_contact' => ['function' => [$this, 'handleUpdateContact']],
      'delete_contact' => ['function' => [$this, 'handleDeleteContact']],
      'get_all_settings' => ['function' => [$this, 'handleGetAllSettings']],
    ];
  }

  public function checkReferer($referer = 'admin_app')
  {
    if (!check_ajax_referer($referer, false, false)) {
      wp_send_json_error(['error' => 'Nonce check failed'], 401);
    }
  }

  public function handleGetContact()
  {
    $this->checkReferer();

    $id = $this->request->input('id');

    $contact = $this->contacts_controller->getContact($id);

    wp_send_json_success(['contact' => $contact]);
  }

  public function handleAddContact()
  {
    $this->checkReferer();

    $contact = $this->request->getContactObject();

    $this->contacts_controller->addContact(
      $contact['name'],
      $contact['email'],
      $contact['phone'],
      $contact['address']
    );

    wp_send_json_success();
  }

  public function handleUpdateContact()
  {
    $this->checkReferer();

    $contact = $this->request->getContactObject();

    $this->contacts_controller->updateContact(
      $contact['id'],
      $contact['name'],
      $contact['email'],
      $contact['phone'],
      $contact['address']
    );

    wp_send_json_success();
  }

  public function handleDeleteContact()
  {
    $this->checkReferer();

    $id = $this->request->input('id');

    $this->contacts_controller->deleteContact($id);

    wp_send_json_success();
  }

  public function handleGetAllSettings()
  {
    $this->checkReferer();

    $settings = $this->settings_controller->getSettingOptions();
    wp_send_json_success($settings);
  }
}
