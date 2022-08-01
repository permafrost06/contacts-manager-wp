<?php

namespace Contacts\Manager;

use Exception;

class SettingsController
{
  protected $prefix = 'contacts_manager';

  public function getSettingOptions()
  {
    return [
      'table_limit' => ['type' => 'numeric', 'desc' => 'Number of table items to show in one page', 'min' => 5, 'max' => 20],
      'table_order_by' => ['type' => 'select', 'desc' => 'Order table items by', 'options' => [
        ['label' => "ID", 'value' => "id"],
        ['label' => "Name", 'value' => "name"],
        ['label' => "Email", 'value' => "email"],
        ['label' => "Phone", 'value' => "phone"],
        ['label' => "Address", 'value' => "address"],
      ]],
      'background_color' => ['type' => 'color', 'desc' => 'Background color of table and contact card', 'regex' => "/^#[A-Fa-f\d]{6,8}/"]
    ];
  }

  public function checkValidity($option, $value = 'NO_VALUE')
  {
    $options = $this->getSettingOptions();

    if (!isset($option, $options)) {
      throw new Exception('Trying to access invalid option');
    }

    if ($value != 'NO_VALUE') {
      $validator = $options[$option];
      $type = $validator['type'];

      $exception = new Exception("Invalid value '$value' provided for option '$option'");

      if ($type == 'numeric') {
        if (!is_numeric($value)) throw $exception;
      } elseif ($type == 'select') {
        $values = array_map(function ($opt) {
          return $opt['value'];
        }, $validator['options']);

        if (!in_array($value, $values)) throw $exception;
      } elseif (isset($validator['regex'])) {
        if (!preg_match($validator['regex'], $value)) throw $exception;
      }
    }
  }

  public function getOption($option)
  {
    $this->checkValidity($option);

    return get_option("{$this->prefix}_{$option}");
  }

  public function updateOption($option, $value)
  {
    $this->checkValidity($option, $value);

    return update_option("{$this->prefix}_{$option}", $value);
  }
}
