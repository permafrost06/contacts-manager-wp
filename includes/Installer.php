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
    $this->addVersion();
    $this->createTable();
  }

  public function addVersion()
  {
    $installed = get_option('contacts_manager_installed');

    if (!$installed) {
      update_option('contacts_manager_installed', time());
    }

    update_option('contacts_manager_version', CONTACTS_MANAGER_VERSION);
  }

  public function createTable()
  {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $create_table_query = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}contacts_manager_table` (
              `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `name` varchar(32) NOT NULL,
              `email` varchar(255) NOT NULL,
              `phone` varchar(15) NOT NULL,
              `address` varchar(255) NOT NULL
            ) {$charset_collate};
    ";

    if (!function_exists('dbDelta')) {
      require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    }

    dbDelta($create_table_query);
  }
}
