<?php

namespace Contacts\Manager\Admin;

use Contacts\Manager\ContactsController;
use Contacts\Manager\SettingsController;
use Contacts\Manager\Ajax as AjaxBase;
use Contacts\Manager\Http\Request;

/**
 * AJAX handler class for Admin or protected AJAX calls
 */
class Ajax extends AjaxBase
{
  public function __construct(ContactsController $contacts_controller, SettingsController $settings_controller, Request $request)
  {
    $this->contacts_controller = $contacts_controller;
    $this->settings_controller = $settings_controller;
    $this->request = $request;

    foreach ($this->getActions() as $action => $handler) {
      add_action("wp_ajax_{$this->prefix}_{$action}", $handler['function']);
    }
  }

  /**
   * Gets the actions and handlers for AJAX calls
   */
  public function getActions(): array
  {
    return [
      'get_contact' => ['function' => [$this, 'handleGetContact']],
      'add_contact' => ['function' => [$this, 'handleAddContact']],
      'update_contact' => ['function' => [$this, 'handleUpdateContact']],
      'delete_contact' => ['function' => [$this, 'handleDeleteContact']],
      'get_all_settings' => ['function' => [$this, 'handleGetAllSettings']],
    ];
  }

  /**
   * Checks the referer by validating nonce
   * 
   * @param string $referer
   */
  public function checkReferer($referer = 'admin_app'): void
  {
    if (!check_ajax_referer($referer, false, false)) {
      wp_send_json_error(['error' => 'Nonce check failed'], 401);
    }
  }

  /**
   * Handles the AJAX call for getting a single contact
   */
  public function handleGetContact(): void
  {
    $this->checkReferer();

    $id = $this->request->input('id');

    $contact = $this->contacts_controller->getContact($id);

    wp_send_json_success(['contact' => $contact]);
  }

  /**
   * Handles the AJAX call for adding a contact
   */
  public function handleAddContact(): void
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

  /**
   * Handles the AJAX call for updating a contact
   */
  public function handleUpdateContact(): void
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

  /**
   * Handles the AJAX call for deleting a contact
   */
  public function handleDeleteContact(): void
  {
    $this->checkReferer();

    $id = $this->request->input('id');

    $this->contacts_controller->deleteContact($id);

    wp_send_json_success();
  }

  /**
   * Handles the AJAX call for getting a settings object
   */
  public function handleGetAllSettings(): void
  {
    $this->checkReferer();

    $settings = $this->settings_controller->getSettingOptions();
    wp_send_json_success($settings);
  }
}
