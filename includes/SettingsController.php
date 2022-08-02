<?php

namespace Contacts\Manager;

use Exception;

/**
 * Plugin settings handler class
 */
class SettingsController
{
  /**
   * @var string $prefix  Prefix for the plugin option names
   */
  protected $prefix = 'contacts_manager';

  /**
   * Gets the plugin options object
   */
  public function getSettingOptions(): array
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

  /**
   * Checks if the option name and the value (if exists) are valid
   * 
   * @param string $option  The name of the option
   * @param string $value   The option value
   */
  public function checkValidity($option, $value = 'NO_VALUE'): void
  {
    $options = $this->getSettingOptions();

    if (!isset($option, $options)) {
      throw new Exception("Trying to access invalid option '$option'", 404);
    }

    if ($value != 'NO_VALUE') {
      $validator = $options[$option];
      $type = $validator['type'];

      $exception = new Exception("Invalid value '$value' provided for option '$option'", 400);

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

  /**
   * Gets the value of the given option
   * 
   * @param string $option  The option name
   */
  public function getOption($option): string
  {
    $this->checkValidity($option);

    return get_option("{$this->prefix}_{$option}");
  }

  /**
   * Updates the value of an option
   * 
   * @param string $option  The option name
   * @param string $value   The option value
   */
  public function updateOption($option, $value): bool
  {
    $this->checkValidity($option, $value);

    return update_option("{$this->prefix}_{$option}", $value);
  }
}
