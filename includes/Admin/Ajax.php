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
      /* debug-start */
      'create_example_pages' => ['function' => [$this, 'handlePageCreation']],
      /* debug-end */
    ];
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

  /* debug-start */
  /**
   * Handles the creation of example pages - debug only
   */
  public function handlePageCreation(): void
  {
    $posts = [
      'Contact Form Only' => [
        'content_lines' => [
          '<!-- wp:shortcode -->',
          '[contact-form]',
          '<!-- /wp:shortcode -->'
        ]
      ],
      'Contact Form Multiple' => [
        'content_lines' => [
          '<!-- wp:shortcode -->',
          '[contact-form]',
          '<!-- /wp:shortcode -->',
          '<!-- wp:shortcode -->',
          '[contact-form]',
          '<!-- /wp:shortcode -->',
          '<!-- wp:shortcode -->',
          '[contact-form]',
          '<!-- /wp:shortcode -->',
        ]
      ],
      'Contact Table Only' => [
        'content_lines' => [
          '<!-- wp:shortcode -->',
          '[contacts-manager]',
          '<!-- /wp:shortcode -->'
        ]
      ],
      'Contact Table Multiple' => [
        'content_lines' => [
          '<!-- wp:shortcode -->',
          '[contacts-manager]',
          '<!-- /wp:shortcode -->',
          '<!-- wp:shortcode -->',
          '[contacts-manager]',
          '<!-- /wp:shortcode -->',
          '<!-- wp:shortcode -->',
          '[contacts-manager]',
          '<!-- /wp:shortcode -->',
        ]
      ],
      'Contact Card Only' => [
        'content_lines' => [
          '<!-- wp:shortcode -->',
          '[contacts-manager id="5"]',
          '<!-- /wp:shortcode -->'
        ]
      ],
      'Contact Card Error' => [
        'content_lines' => [
          '<!-- wp:shortcode -->',
          '[contacts-manager id="50000"]',
          '<!-- /wp:shortcode -->'
        ]
      ],
      'Contact Card Multiple' => [
        'content_lines' => [
          '<!-- wp:shortcode -->',
          '[contacts-manager id="1"]',
          '<!-- /wp:shortcode -->',
          '<!-- wp:shortcode -->',
          '[contacts-manager id="5"]',
          '<!-- /wp:shortcode -->',
          '<!-- wp:shortcode -->',
          '[contacts-manager id="7"]',
          '<!-- /wp:shortcode -->',
          '<!-- wp:shortcode -->',
          '[contacts-manager id="50000"]',
          '<!-- /wp:shortcode -->',
        ]
      ],
    ];

    foreach ($posts as $post => $attrs) {
      $title = "Contacts Manager - $post";
      $content = '';

      foreach ($attrs['content_lines'] as $line) {
        $content .= $line . "\n";
      }

      if (!post_exists($title)) {
        $my_post = array(
          'post_title'    => $title,
          'post_content'  => $content,
          'post_status'   => 'publish',
          'post_author'   => 1,
          'post_type'     => 'page',
        );

        wp_insert_post($my_post);
      }
    }

    wp_send_json_success();
  }
  /* debug-end */
}
