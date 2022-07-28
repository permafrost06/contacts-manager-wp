<?php

namespace Contacts\Manager;

use Exception;

class SettingsController
{
  protected $prefix = "contacts_manager_";

  public function getSettingOptions()
  {
    return [
      'table_limit' => ['desc' => 'Number of table items to show in one page', 'numeric' => true],
      'table_order_by' => ['desc' => 'Order table items by', 'values_list' => ['id', 'name', 'email', 'phone', 'address']],
      'background_color' => ['desc' => 'Background color of table and contact card', 'regex' => "/^#\d{6,8}/"]
    ];
  }

  public function checkValidity($option, $value = "NO_VALUE")
  {
    $options = $this->getSettingOptions();

    if (!isset($option, $options)) {
      throw new Exception("Trying to access invalid option");
    }

    if ($value != "NO_VALUE") {
      $validator = $options[$option];

      $numeric = isset($validator['numeric']) ? $validator['numeric'] : false;
      $values_list = isset($validator['values_list']) ? $validator['values_list'] : [];
      $regex = isset($validator['regex']) ? $validator['regex'] : '';

      $exception = new Exception("Invalid value provided for option");

      if ($numeric) {
        if (!is_numeric($value)) throw $exception;
      } elseif (!empty($values_list)) {
        if (!in_array($value, $values_list)) throw $exception;
      } elseif ($regex != '') {
        if (!preg_match($regex, $value)) throw $exception;
      }
    }
  }

  public function getOption($option)
  {
    $this->checkValidity($option);

    return get_option("{$this->prefix}{$option}");
  }

  public function updateOption($option, $value)
  {
    $this->checkValidity($option, $value);

    return update_option("{$this->prefix}{$option}", $value);
  }
}
