<?php

namespace Contacts\Manager\Frontend;

use Contacts\Manager\ContactsController;

class Shortcode
{
  public $contacts_controller;

  /**
   * Initializes the class
   */
  public function __construct(ContactsController $contacts_controller)
  {
    $this->contacts_controller = $contacts_controller;

    add_shortcode('contacts-manager', [$this, 'render_contacts']);
    add_shortcode('contact-form', [$this, 'render_contact_form']);
  }

  /**
   * Shortcode handler function
   * 
   * @param array $atts
   * @param string content
   * 
   * @return string
   */
  public function render_contacts($atts = [])
  {
    $atts = array_change_key_case((array) $atts, CASE_LOWER);

    if (array_key_exists('id', $atts)) {
      return $this->render_contact_card($atts['id']);
    } else {
      return $this->render_complete_table();
    }
  }

  public function render_contact_card($id)
  {
    wp_enqueue_style('cm-contact-card-style');

    try {
      $contact = $this->contacts_controller->get_contact($id);

      ob_start();
      include __DIR__ . '/views/contact-card.php';
      return ob_get_clean();
    } catch (\Exception $error) {
      $message = __('Contact does not exist', 'contacts-manager');

      ob_start();
      include __DIR__ . '/views/error.php';
      return ob_get_clean();
    }
  }

  public function render_complete_table()
  {
    wp_enqueue_style('cm-contacts-table-style');

    try {
      $all_contacts = $this->contacts_controller->get_all_contacts();

      ob_start();
      include __DIR__ . '/views/contact-table.php';
      return ob_get_clean();
    } catch (\Exception $error) {
      $message = __('Could not get table', 'contacts-manager');

      ob_start();
      include __DIR__ . '/views/error.php';
      return ob_get_clean();
    }
  }

  public function render_contact_form($atts = [], $content = null)
  {
    wp_enqueue_style('cm-contact-form-style');
    wp_enqueue_script('cm-contact-form-ajax');

    ob_start();
    include __DIR__ . '/views/contact-form.php';
    return ob_get_clean();
  }
}
