<?php

namespace Contacts\Manager\Http;

use \Exception;

/**
 * HTTP request handler class
 */
class Request
{
  /**
   * Returns the method of request
   */
  public function method(): string
  {
    return $_SERVER['REQUEST_METHOD'];
  }

  /**
   * Returns the specified request variable
   * 
   * @param string  $name       Name of the request variable
   * @param bool    $sanitized  Whether to sanitize the variable. True by default.
   * @param bool    $textarea   Whether the field is a textarea. Fale by default.
   */
  public function input($name, $sanitized = true, $textarea = false): string
  {
    if (key_exists($name, $_REQUEST)) {
      $var = $_REQUEST[$name];
    } else {
      throw new Exception("Request variable '$name' does not exist", 400);
    }

    if ($textarea && $sanitized)
      return sanitize_textarea_field($var);
    elseif ($sanitized)
      return sanitize_text_field($var);
    else
      return $var;
  }

  /**
   * Returns the specified boolean request variable
   * 
   * @param string  $name       Name of the request variable
   */
  public function inputBool($name): bool
  {
    return strtolower($this->input($name)) == 'true' ? true : false;
  }

  /**
   * Returns an array with fields specified by the arguments
   * 
   * @param array $fields           Array with the names of input vars
   * @param array $textarea_fields  Array with the names of textarea vars
   * @param array $bool_fields      Array with the names of boolean vars
   * @param bool  $sanitized        Whether to sanitize the fields. True by default.
   */
  public function getObject($fields = [], $textarea_fields = [], $bool_fields = [], $sanitized = true): array
  {
    $object = [];

    foreach ($fields as $field) {
      try {
        $object[$field] = $sanitized ? $this->input($field) : $this->input($field, false);
      } catch (Exception $error) {
        if ($error->getMessage() != "Request variable '$field' does not exist")
          throw $error;
      }
    }

    foreach ($textarea_fields as $field) {
      try {
        $object[$field] = $sanitized ? $this->input($field, true, true) : $this->input($field, false);
      } catch (Exception $error) {
        if ($error->getMessage() != "Request variable '$field' does not exist")
          throw $error;
      }
    }

    foreach ($bool_fields as $field) {
      try {
        $object[$field] = $sanitized ? $this->inputBool($field) : $this->inputBool($field, false);
      } catch (Exception $error) {
        if ($error->getMessage() != "Request variable '$field' does not exist")
          throw $error;
      }
    }

    return $object;
  }

  /**
   * Returns contact object
   * 
   * @param bool  $sanitized  Whether to sanitize the request variables
   */
  public function getContactObject($sanitized = true): array
  {
    $fields = ['id', 'name', 'email', 'phone'];
    $textarea_fields = ['address'];

    return $this->getObject($fields, $textarea_fields, [], $sanitized);
  }

  /**
   * Returns pagination arguments object
   * 
   * @param bool  $sanitized  Whether to sanitize the request variables
   */
  public function getPaginationArgs($sanitized = true): array
  {
    $fields = ['page', 'limit', 'orderby'];
    $bool_fields = ['ascending'];

    return $this->getObject($fields, [], $bool_fields, $sanitized);
  }
}
