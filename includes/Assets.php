<?php

namespace Contacts\Manager;

/**
 * Assets handler class
 */
class Assets
{
  function __construct()
  {
    add_action('wp_enqueue_scripts', [$this, 'register_assets']);
  }

  public function get_scripts()
  {
    return [
      'cm-contact-form-ajax' => [
        'src' => CONTACTS_MANAGER_ASSETS . '/js/contact-form-ajax.js',
        'version' => filemtime(CONTACTS_MANAGER_PATH . '/assets/js/contact-form-ajax.js')
      ]
    ];
  }

  public function get_styles()
  {
    return [
      'cm-base-style' => [
        'src' => CONTACTS_MANAGER_ASSETS . '/styles/base.css',
        'version' => filemtime(CONTACTS_MANAGER_PATH . '/assets/styles/base.css')
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
}
