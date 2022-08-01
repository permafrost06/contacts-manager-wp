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
      throw new Exception("Request variable '$name' does not exist");
    }

    if ($textarea && $sanitized)
      return sanitize_textarea_field($var);
    elseif ($sanitized)
      return sanitize_text_field($var);
    else
      return $var;
  }

  public function inputBool($name, $sanitized = true)
  {
    return strtolower($this->input($name)) == 'true' ? true : false;
  }

  public function getObject($fields = [], $textarea_fields = [], $bool_fields = [], $sanitized = true)
  {
    $object = [];

    foreach ($fields as $field) {
      $object[$field] = $sanitized ? $this->input($field) : $this->input($field, false);
    }

    foreach ($textarea_fields as $field) {
      $object[$field] = $sanitized ? $this->input($field, true, true) : $this->input($field, false);
    }

    foreach ($bool_fields as $field) {
      $object[$field] = $sanitized ? $this->inputBool($field) : $this->inputBool($field, false);
    }

    return $object;
  }

  public function getContactObject($sanitized = true)
  {
    $fields = ['id', 'name', 'email', 'phone'];
    $textarea_fields = ['address'];

    return $this->getObject($fields, $textarea_fields, [], $sanitized);
  }

  public function getPaginationArgs($sanitized = true)
  {
    $fields = ['page', 'limit', 'orderby'];
    $bool_fields = ['ascending'];

    return $this->getObject($fields, [], $bool_fields, $sanitized);
  }
}
