<?php

namespace Contacts\Manager;

/**
 * Frontend handler class
 */
class Frontend
{
  public function __construct(ContactsController $contacts_controller)
  {
    new Frontend\Shortcode($contacts_controller);
  }
}
