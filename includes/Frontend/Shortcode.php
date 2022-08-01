<?php

namespace Contacts\Manager\Frontend;

use Exception;
use Contacts\Manager\ContactsController;

class Shortcode
{
  protected $contacts_controller;

  /**
   * Initializes the class
   */
  public function __construct(ContactsController $contacts_controller)
  {
    $this->contacts_controller = $contacts_controller;

    add_shortcode('contacts-manager', [$this, 'renderContacts']);
    add_shortcode('contact-form', [$this, 'renderContactForm']);
  }

  /**
   * Shortcode handler function
   * 
   * @param array $atts
   * @param string content
   * 
   * @return string
   */
  public function renderContacts($atts = [])
  {
    $atts = array_change_key_case((array) $atts, CASE_LOWER);

    if (array_key_exists('id', $atts)) {
      return $this->renderContactCard($atts['id']);
    } else {
      return $this->renderCompleteTable();
    }
  }

  public function renderContactCard($id)
  {
    try {
      $contact = $this->contacts_controller->getContact($id);

      wp_enqueue_style('cm-contact-card-style');
      wp_enqueue_script('cm-contact-card-ajax');

      ob_start();
      include __DIR__ . '/views/contact-card.php';
      return ob_get_clean();
    } catch (Exception $error) {
      $message = __('Contact does not exist', 'contacts-manager');

      wp_enqueue_style('cm-error-page-style');

      ob_start();
      include __DIR__ . '/views/error.php';
      return ob_get_clean();
    }
  }

  public function renderCompleteTable()
  {
    wp_enqueue_style('cm-contacts-table-style');
    wp_enqueue_script('cm-contact-table-ajax');

    ob_start();
    include __DIR__ . '/views/contact-table.php';
    return ob_get_clean();
  }

  public function renderContactForm($atts = [], $content = null)
  {
    wp_enqueue_style('cm-contact-form-style');
    wp_enqueue_script('cm-contact-form-ajax');

    ob_start();
    include __DIR__ . '/views/contact-form.php';
    return ob_get_clean();
  }
}
