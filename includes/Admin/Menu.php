<?php

namespace Contacts\Manager\Admin;

/**
 * Menu handler class
 */
class Menu
{
  public $contacts_table;

  function __construct($contacts_table)
  {
    $this->contacts_table = $contacts_table;

    add_action('admin_menu', [$this, 'admin_menu']);
  }

  public function admin_menu()
  {
    add_menu_page("Contacts Manager Settings", "Contacts Manager", "manage_options", "contacts-manager", [$this->contacts_table, 'plugin_page']);
  }
}
