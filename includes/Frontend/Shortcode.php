<?php

namespace Contacts\Manager\Frontend;

class Shortcode
{
  /**
   * Initializes the class
   */
  function __construct()
  {
    add_shortcode('contacts-manager', [$this, 'render_shortcode']);
  }

  /**
   * Shortcode handler function
   * 
   * @param array $atts
   * @param string content
   * 
   * @return string
   */
  function render_shortcode($atts = [], $content = null)
  {
    wp_enqueue_style('cm-base-style');
    $atts = array_change_key_case((array) $atts, CASE_LOWER);

    $output = '';

    if (array_key_exists('id', $atts)) {
      $output .= $this->render_contact_card($atts['id']);
    } else {
      $output .= $this->render_complete_table();
    }

    if (!is_null($content)) {
      $output .= apply_filters('the_content', $content);
    }

    return $output;
  }

  function render_contact_card($id)
  {
    try {
      $contact = \Contacts\Manager\ContactsController::get_contact($id);

      $output = '<div class="contacts-mgr-box"><div>
    <p class="field">' . $contact->name . '</p>
    <p class="field">' . $contact->email . '</p>
    <p class="field">' . $contact->phone . '</p>
    <p class="field">' . $contact->address . '</p>
    </div></div>';
    } catch (\Exception $error) {
      $output = '<div class="contacts-mgr-box"><p>Invalid contact selected</p></div>';
    }

    return $output;
  }

  function render_complete_table()
  {
    try {
      $data = \Contacts\Manager\ContactsController::get_all_contacts();

      $output = '<div class="contacts-mgr-box contacts-table"><table class="table table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Address</th>
        </tr>
        </thead>
      <tbody>';

      foreach ($data as $row) {
        $output .= '<tr>';
        foreach ($row as $field) {
          $output .= '<td>' . $field . '</td>';
        }
        $output .= '</tr>';
      }

      $output .= '</tbody>
    </table></div>';
    } catch (\Exception $error) {
      $output = '<div class="contacts-mgr-box"><p>Could not get table</p></div>';
    }

    return $output;
  }
}
