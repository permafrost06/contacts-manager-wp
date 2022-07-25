<?php

namespace Contacts\Manager\Http;

use \Exception;

class Request
{
  public function method()
  {
    return $_SERVER['REQUEST_METHOD'];
  }

  public function input($name, $sanitized = true, $textarea = false)
  {
    if (key_exists($name, $_REQUEST)) {
      $var = $_REQUEST[$name];
    } else {
      throw new Exception("Request variable does not exist");
    }

    if ($textarea && $sanitized)
      return sanitize_textarea_field($var);
    elseif ($sanitized)
      return sanitize_text_field($var);
    else
      return $var;
  }

  public function getContactObject($sanitized = true)
  {
    $contact = [];
    $fields = ['id', 'name', 'email', 'phone'];
    $textarea_fields = ['address'];

    foreach ($fields as $field) {
      try {
        $contact[$field] = $sanitized ? $this->input($field) : $this->input($field, false);
      } catch (Exception $e) {
      }
    }

    foreach ($textarea_fields as $field) {
      try {
        $contact[$field] = $sanitized ? $this->input($field, true, true) : $this->input($field, false);
      } catch (Exception $e) {
      }
    }

    return $contact;
  }
}
