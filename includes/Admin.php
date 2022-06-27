<?php

namespace Contacts\Manager;

class Admin
{
  function __construct()
  {
    $contacts_table = new Admin\ContactsTable();

    $this->dispatch_actions($contacts_table);

    new Admin\Menu($contacts_table);
  }

  public function dispatch_actions($contacts_table)
  {
    add_action('admin_init', [$contacts_table, 'form_handler']);
    add_action('admin_init', [$contacts_table, 'delete_handler']);
  }
}
