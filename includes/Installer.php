<?php

namespace Contacts\Manager;

/**
 * Initialize the installer class
 */
class Installer
{
  /**
   * Run the installer
   * 
   * @return void
   */
  public function run()
  {
    $this->add_version();
    $this->create_table();
  }

  public function add_version()
  {
    $installed = get_option('contacts_manager_installed');

    if (!$installed) {
      update_option('contacts_manager_installed', time());
    }

    update_option('contacts_manager_version', CONTACTS_MANAGER_VERSION);
  }

  public function create_table()
  {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $create_table_query = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}contacts_manager_table` (
              `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `name` text NOT NULL,
              `email` text NOT NULL,
              `phone` text NOT NULL,
              `address` text NOT NULL
            ) {$charset_collate};
    ";

    if (!function_exists('dbDelta')) {
      require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    }

    dbDelta($create_table_query);
  }
}