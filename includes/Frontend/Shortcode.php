<?php

namespace Contacts\Manager\Frontend;

use Exception;
use Contacts\Manager\ContactsController;

/**
 * Shortcode handler class
 */
class Shortcode
{
  /**
   * @var ContactsController $contacts_controller
   */
  protected $contacts_controller;

  public function __construct(ContactsController $contacts_controller)
  {
    $this->contacts_controller = $contacts_controller;

    add_shortcode('contacts-manager', [$this, 'renderContacts']);
    add_shortcode('contact-form', [$this, 'renderContactForm']);
  }

  /**
   * Load the content of a file to a string and return it
   * 
   * @param string  $filename Name of the file in the "views" folder
   * @param mixed   $data     Any data the file may need
   */
  public function loadFile(string $filename, $data = ""): string
  {
    ob_start();
    include __DIR__ . '/views/' . $filename;
    return ob_get_clean();
  }

  /**
   * Shortcode handler function for shortcode 'contact-form'
   */
  public function renderContactForm(): string
  {
    wp_enqueue_style('cm-contact-form-style');
    wp_enqueue_script('cm-contact-form-ajax');

    return $this->loadFile('contact-form.php');
  }

  /**
   * Shortcode handler function for shortcode 'contacts-manager'
   * 
   * @param array|string $atts  The shortcode attributes
   */
  public function renderContacts($atts): string
  {
    $atts = array_change_key_case((array) $atts, CASE_LOWER);

    if (array_key_exists('id', $atts)) {
      return $this->renderContactCard($atts['id']);
    } else {
      return $this->renderCompleteTable();
    }
  }

  /**
   * Render function for contact card
   * 
   * @param string $id  The ID of the contact
   */
  public function renderContactCard(string $id): string
  {
    try {
      $contact = $this->contacts_controller->getContact($id);

      wp_enqueue_style('cm-contact-card-style');
      wp_enqueue_script('cm-contact-card-ajax');

      return $this->loadFile('contact-card.php', $contact);
    } catch (Exception $error) {
      $message = __('Contact does not exist', 'contacts-manager');

      wp_enqueue_style('cm-error-page-style');

      return $this->loadFile('error.php', $message);
    }
  }

  /**
   * Render function for contact table
   */
  public function renderCompleteTable(): string
  {
    wp_enqueue_style('cm-contacts-table-style');
    wp_enqueue_script('cm-contact-table-ajax');

    return $this->loadFile('contact-table.php');
  }
}
