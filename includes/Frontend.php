<?php

namespace Contacts\Manager;

class Frontend
{
  public function __construct(ContactsController $contacts_controller)
  {
    new Frontend\Shortcode($contacts_controller);
  }
}
