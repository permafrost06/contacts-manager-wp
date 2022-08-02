<?php

namespace Contacts\Manager;

use Exception;
use Contacts\Manager\Http\Request;

class Ajax
{
  protected $prefix = 'cm';
  protected $contacts_controller;
  protected $settings_controller;
  protected $request;

  public function __construct(ContactsController $contacts_controller, SettingsController $settings_controller)
  {
    $this->contacts_controller = $contacts_controller;
    $this->settings_controller = $settings_controller;
    $this->request = new Request();

    set_exception_handler([$this, 'exceptionHandler']);

    foreach ($this->getActions() as $action => $handler) {
      $nopriv = isset($handler['nopriv']) ? $handler['nopriv'] : false;

      if ($nopriv) {
        add_action("wp_ajax_nopriv_{$this->prefix}_{$action}", $handler['function']);
      }

      add_action("wp_ajax_{$this->prefix}_{$action}", $handler['function']);
    }
  }

  public function exceptionHandler(Exception $error)
  {
    wp_send_json_error(['error' => $error->getMessage()], $error->getCode());
  }

  public function getActions()
  {
    return [
      'contact_form' => ['function' => [$this, 'submitForm'], 'nopriv' => true],
      'get_all_contacts' => ['function' => [$this, 'handleGetAllContacts']],
      'get_contact_page' => ['function' => [$this, 'handleGetContactPage']],
      'check_email_exists' => ['function' => [$this, 'handleCheckEmailExists']],
      'add_contact' => ['function' => [$this, 'handleAddContact']],
      'get_contact' => ['function' => [$this, 'handleGetContact']],
      'update_contact' => ['function' => [$this, 'handleUpdateContact']],
      'delete_contact' => ['function' => [$this, 'handleDeleteContact']],
      'get_setting' => ['function' => [$this, 'handleGetSetting']],
      'update_setting' => ['function' => [$this, 'handleUpdateSetting']],
      'get_all_settings' => ['function' => [$this, 'handleGetAllSettings']]
    ];
  }

  public function checkReferer($referer = 'admin_app')
  {
    if (!check_ajax_referer($referer, false, false)) {
      wp_send_json_error(['error' => 'Nonce check failed'], 401);
    }
  }

  public function checkRefererMultiple($referers = ['cm-contact-form', 'admin_app'])
  {
    $verified = false;

    foreach ($referers as $referer) {
      $verified = $verified || check_ajax_referer($referer, false, false);
    }

    if (!$verified) {
      wp_send_json_error(['error' => 'Nonce check failed'], 401);
    }
  }

  public function submitForm()
  {
    $this->checkReferer('cm-contact-form');

    $contact = $this->request->getContactObject();

    try {
      $this->contacts_controller->addContact(
        $contact['name'],
        $contact['email'],
        $contact['phone'],
        $contact['address']
      );

      wp_send_json_success(['message' => 'Successfully added contact!']);
    } catch (Exception $error) {
      wp_send_json_error(['error' => $error->getMessage()], $error->getCode());
    }
  }

  public function handleGetAllContacts()
  {
    $this->checkReferer();

    $contacts = $this->contacts_controller->getAllContacts();

    wp_send_json_success(['contacts' => $contacts]);
  }

  public function handleGetContactPage()
  {
    $this->checkReferer();

    $pArgs = $this->request->getPaginationArgs();

    $page = $this->contacts_controller->getContactsPaged(
      $pArgs["page"],
      $pArgs["limit"],
      $pArgs["orderby"],
      $pArgs["ascending"]
    );

    wp_send_json_success($page);
  }

  public function handleCheckEmailExists()
  {
    $this->checkRefererMultiple();

    $email = $this->request->input('email');

    $exists = $this->contacts_controller->checkEmailExists($email);

    wp_send_json_success($exists);
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

  public function handleGetContact()
  {
    $this->checkReferer();

    $id = $this->request->input('id');

    $contact = $this->contacts_controller->getContact($id);

    wp_send_json_success(['contact' => $contact]);
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

  public function handleGetSetting()
  {
    $this->checkReferer();

    $option = $this->request->input('option_name');

    $option_value = $this->settings_controller->getOption($option);

    if ($option_value)
      wp_send_json_success($option_value);
    else wp_send_json_error(['error' => 'Setting value not set']);
  }

  public function handleUpdateSetting()
  {
    $this->checkReferer();

    $option = $this->request->input('option_name');
    $value = $this->request->input('option_value');

    $this->settings_controller->updateOption($option, $value);
    wp_send_json_success(['message' => 'Setting updated successfully']);
  }

  public function handleGetAllSettings()
  {
    $this->checkReferer();

    $settings = $this->settings_controller->getSettingOptions();
    wp_send_json_success($settings);
  }
}
