<?php

namespace Contacts\Manager;

/**
 * Assets handler class
 */
class Assets
{
  function __construct()
  {
    add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
  }

  public function enqueue_assets()
  {
    wp_enqueue_style('cm-base-style', CONTACTS_MANAGER_ASSETS . '/styles/base.css', false, filemtime(CONTACTS_MANAGER_PATH . '/assets/styles/base.css'));
  }
}
