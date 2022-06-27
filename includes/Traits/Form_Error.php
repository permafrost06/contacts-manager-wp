<?php

namespace Contacts\Manager\Traits;

/**
 * Form error handler class
 */
trait Form_Error
{
  /**
   * Holds the errors
   * 
   * @var array
   */
  public $errors = [];

  /**
   * Check if the form has error
   * 
   * @param string $key
   * 
   * @return boolean
   */
  public function has_error($key)
  {
    return isset($this->errors[$key]) ? true : false;
  }

  /**
   * Get the error by key
   * 
   * @param string $key
   * 
   * @return string
   */
  public function get_error($key)
  {
    if (isset($this->errors[$key])) {
      return $this->errors[$key];
    }

    return false;
  }
}
