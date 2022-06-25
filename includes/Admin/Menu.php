<?php

namespace Contacts\Manager\Admin;

/**
 * Menu handler class
 */
class Menu
{

  function __construct()
  {
    add_action('admin_menu', [$this, 'admin_menu']);
  }

  public function admin_menu()
  {
    add_menu_page("Contacts Manager Settings", "Contacts Manager", "manage_options", "contacts-manager-settings", [$this, 'plugin_page']);
  }

  public function plugin_page()
  {
    echo 'Hello World';
    // $dir = plugin_dir_path(__FILE__);
    // include($dir . "admin-page.php");
  }
}
