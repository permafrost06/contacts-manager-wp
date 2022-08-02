<?php

namespace Contacts\Manager;

/**
 * Assets handler class
 */
class Assets
{
  public function __construct()
  {
    add_action('wp_enqueue_scripts', [$this, 'registerAssets']);
    add_action('admin_enqueue_scripts', [$this, 'registerAdminAssets']);
  }

  /**
   * Get the javascript files for the plugin
   */
  public function getScripts(): array
  {
    return [
      'cm-contact-form-ajax' => [
        'src' => CONTACTS_MANAGER_ASSETS . '/js/contact-form-ajax.js',
        'version' => filemtime(CONTACTS_MANAGER_PATH . '/assets/js/contact-form-ajax.js'),
        'deps' => ['jquery']
      ],
      'cm-contact-table-ajax' => [
        'src' => CONTACTS_MANAGER_ASSETS . '/js/contact-table-ajax.js',
        'version' => filemtime(CONTACTS_MANAGER_PATH . '/assets/js/contact-table-ajax.js'),
        'deps' => ['jquery', 'cm-settings-ajax']
      ],
      'cm-settings-ajax' => [
        'src' => CONTACTS_MANAGER_ASSETS . '/js/settings-ajax.js',
        'version' => filemtime(CONTACTS_MANAGER_PATH . '/assets/js/settings-ajax.js'),
        'deps' => ['jquery']
      ],
      'cm-contact-card-ajax' => [
        'src' => CONTACTS_MANAGER_ASSETS . '/js/contact-card-ajax.js',
        'version' => filemtime(CONTACTS_MANAGER_PATH . '/assets/js/contact-card-ajax.js'),
        'deps' => ['jquery', 'cm-settings-ajax']
      ]
    ];
  }

  /**
   * Get the admin javascript files for the plugin
   */
  public function getAdminScripts(): array
  {
    return [
      'admin-vue-app' => [
        /* next-line-enables-hmr */
        'src' => 'http://localhost:8081/main.js',
        /* next-line-disables-hmr */
        // 'src' => CONTACTS_MANAGER_ASSETS . '/js/admin_app/main.js',
        'version' => filemtime(CONTACTS_MANAGER_PATH . '/assets/js/admin_app/main.js'),
        'deps' => ['jquery']
      ]
    ];
  }

  /**
   * Get the stylesheets for the plugin
   */
  public function getStyles(): array
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
      ],
      'cm-error-page-style' => [
        'src' => CONTACTS_MANAGER_ASSETS . '/styles/error-page.css',
        'version' => filemtime(CONTACTS_MANAGER_PATH . '/assets/styles/error-page.css')
      ]
    ];
  }

  /**
   * Register the plugin assets
   */
  public function registerAssets(): void
  {
    $scripts = $this->getScripts();

    foreach ($scripts as $handle => $script) {
      $deps = isset($script['deps']) ? $script['deps'] : false;
      $footer = isset($script['footer']) ? $script['footer'] : true;

      wp_register_script($handle, $script['src'], $deps, $script['version'], $footer);
    }

    $styles = $this->getStyles();

    foreach ($styles as $handle => $style) {
      $deps = isset($style['deps']) ? $style['deps'] : false;

      wp_register_style($handle, $style['src'], $deps, $style['version']);
    }

    wp_localize_script(
      'cm-contact-form-ajax',
      'contacts_manager_form_ajax',
      [
        'ajax_url' => admin_url('admin-ajax.php'),
      ]
    );

    wp_localize_script(
      'cm-contact-table-ajax',
      'contacts_mgr_table_ajax',
      [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('admin_app'),
      ]
    );

    wp_localize_script(
      'cm-contact-card-ajax',
      'contacts_manager_card_ajax',
      [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('admin_app')
      ]
    );
  }

  /**
   * Register the plugin admin assets
   */
  public function registerAdminAssets(): void
  {
    $scripts = $this->getAdminScripts();

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
