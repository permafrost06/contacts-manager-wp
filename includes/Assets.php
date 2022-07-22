<?php

namespace Contacts\Manager;

/**
 * Assets handler class
 */
class Assets
{
  public function __construct()
  {
    add_action('wp_enqueue_scripts', [$this, 'register_assets']);
    add_action('admin_enqueue_scripts', [$this, 'register_admin_assets']);
  }

  public function get_scripts()
  {
    return [
      'cm-contact-form-ajax' => [
        'src' => CONTACTS_MANAGER_ASSETS . '/js/contact-form-ajax.js',
        'version' => filemtime(CONTACTS_MANAGER_PATH . '/assets/js/contact-form-ajax.js')
      ],
    ];
  }

  public function get_admin_scripts()
  {
    return [
      'admin-vue-app' => [
        /* remove-next-line-in-production */
        'src' => 'http://localhost:8081/main.js',
        /* uncomment-next-line-in-production */
        // 'src' => CONTACTS_MANAGER_ASSETS . '/js/admin_app/main.js',
        'version' => filemtime(CONTACTS_MANAGER_PATH . '/assets/js/admin_app/main.js'),
        'deps' => ['jquery']
      ]
    ];
  }

  public function get_styles()
  {
    return [
      'cm-contacts-table-style' => [
        'src' => CONTACTS_MANAGER_ASSETS . '/styles/contacts-table.css',
        'version' => filemtime(CONTACTS_MANAGER_PATH . '/assets/styles/contacts-table.css')
      ],
      'cm-contact-form-style' => [
        'src' => CONTACTS_MANAGER_ASSETS . '/styles/contact-form.css',
        'version' => filemtime(CONTACTS_MANAGER_PATH . '/assets/styles/contact-form.css')
      ],
      'cm-contact-card-style' => [
        'src' => CONTACTS_MANAGER_ASSETS . '/styles/contact-card.css',
        'version' => filemtime(CONTACTS_MANAGER_PATH . '/assets/styles/contact-card.css')
      ]
    ];
  }

  public function register_assets()
  {
    $scripts = $this->get_scripts();

    foreach ($scripts as $handle => $script) {
      $deps = isset($script['deps']) ? $script['deps'] : false;
      $footer = isset($script['footer']) ? $script['footer'] : true;

      wp_register_script($handle, $script['src'], $deps, $script['version'], $footer);
    }

    $styles = $this->get_styles();

    foreach ($styles as $handle => $style) {
      $deps = isset($style['deps']) ? $style['deps'] : false;

      wp_register_style($handle, $style['src'], $deps, $style['version']);
    }

    wp_localize_script(
      'cm-contact-form-ajax',
      'contacts_manager_ajax',
      array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'error' => 'Something went wrong in the server',
      )
    );
  }

  public function register_admin_assets()
  {
    $scripts = $this->get_admin_scripts();

    foreach ($scripts as $handle => $script) {
      $deps = isset($script['deps']) ? $script['deps'] : false;
      $footer = isset($script['footer']) ? $script['footer'] : true;

      wp_register_script($handle, $script['src'], $deps, $script['version'], $footer);
    }

    wp_localize_script(
      'admin-vue-app',
      'contactsMgrAdmin',
      [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('admin_app'),
      ]
    );
  }
}
