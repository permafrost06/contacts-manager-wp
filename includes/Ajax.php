<?php

namespace Contacts\Manager;

use Exception;
use Contacts\Manager\Http\Request;
use Contacts\Manager\Admin\Ajax as AdminAjax;

/**
 * AJAX handler class for common AJAX calls
 */
class Ajax
{
  /**
   * @var string Prefix for AJAX actions for the plugin
   */
  protected $prefix = 'cm';

  /**
   * @var ContactsController
   */
  protected $contacts_controller;

  /**
   * @var SettingsController
   */
  protected $settings_controller;

  /**
   * @var Request
   */
  protected $request;

  public function __construct(ContactsController $contacts_controller)
  {
    $this->contacts_controller = $contacts_controller;
    $this->settings_controller = new SettingsController();
    $this->request = new Request();

    set_exception_handler([$this, 'exceptionHandler']);

    foreach ($this->getActions() as $action => $handler) {
      $nopriv = isset($handler['nopriv']) ? $handler['nopriv'] : false;

      if ($nopriv) {
        add_action("wp_ajax_nopriv_{$this->prefix}_{$action}", $handler['function']);
      }

      add_action("wp_ajax_{$this->prefix}_{$action}", $handler['function']);
    }

    new AdminAjax($contacts_controller, $this->settings_controller, $this->request);
  }

  /**
   * Handles exceptions that occur during AJAX calls
   */
  public function exceptionHandler(Exception $error): void
  {
    wp_send_json_error(['error' => $error->getMessage()], $error->getCode());
  }

  /**
   * Gets the actions and handlers for AJAX calls
   */
  public function getActions(): array
  {
    return [
      'contact_form' => ['function' => [$this, 'submitForm'], 'nopriv' => true],
      'get_all_contacts' => ['function' => [$this, 'handleGetAllContacts']],
      'get_contact_page' => ['function' => [$this, 'handleGetContactPage']],
      'check_email_exists' => ['function' => [$this, 'handleCheckEmailExists']],
      'get_setting' => ['function' => [$this, 'handleGetSetting']],
      'update_setting' => ['function' => [$this, 'handleUpdateSetting']],
    ];
  }

  /**
   * Checks the referer by validating nonce
   * 
   * @param string $referer
   */
  public function checkReferer(string $referer = 'admin_app'): void
  {
    if (!check_ajax_referer($referer, false, false)) {
      wp_send_json_error(['error' => 'Nonce check failed'], 401);
    }
  }

  /**
   * Checks multiple referers, continues if one nonce is validated
   * 
   * @param array $referers An array of referer strings
   */
  public function checkRefererMultiple(array $referers = ['cm-contact-form', 'admin_app']): void
  {
    $verified = false;

    foreach ($referers as $referer) {
      $verified = $verified || check_ajax_referer($referer, false, false);
    }

    if (!$verified) {
      wp_send_json_error(['error' => 'Nonce check failed'], 401);
    }
  }

  /**
   * Handles the frontend shortcode form submission
   */
  public function submitForm(): void
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

  /**
   * Handles the AJAX call for getting all contacts
   */
  public function handleGetAllContacts(): void
  {
    $this->checkReferer();

    $contacts = $this->contacts_controller->getAllContacts();

    wp_send_json_success(['contacts' => $contacts]);
  }

  /**
   * Handles the AJAX call for getting a page of contacts
   */
  public function handleGetContactPage(): void
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

  /**
   * Handles the AJAX call for checking if a contact with an email exists
   */
  public function handleCheckEmailExists(): void
  {
    $this->checkRefererMultiple();

    $email = $this->request->input('email');

    $exists = $this->contacts_controller->checkEmailExists($email);

    wp_send_json_success($exists);
  }

  /**
   * Handles the AJAX call for getting an option value
   */
  public function handleGetSetting(): void
  {
    $this->checkReferer();

    $option = $this->request->input('option_name');

    $option_value = $this->settings_controller->getOption($option);

    if ($option_value)
      wp_send_json_success($option_value);
    else wp_send_json_error(['error' => 'Setting value not set']);
  }

  /**
   * Handles the AJAX call for updating an option value
   */
  public function handleUpdateSetting(): void
  {
    $this->checkReferer();

    $option = $this->request->input('option_name');
    $value = $this->request->input('option_value');

    $this->settings_controller->updateOption($option, $value);
    wp_send_json_success(['message' => 'Setting updated successfully']);
  }
}
