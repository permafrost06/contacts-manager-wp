<?php

/**
 * Plugin Name: Contacts Manager
 * Plugin URI: https://github.com/permafrost06/contacts-manager-wp/
 * Description: A demonstration plugin that can add/edit/delete and view contacts
 * Version: 1.0.0
 * Author: permafrost06
 * Author URI: https://github.com/permafrost06/
 * License: GPL v2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: contacts-manager
 */

if (!defined('ABSPATH')) {
  exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugin class
 */

final class Contacts_Manager
{
  /**
   * Plugin version
   * 
   * @var string
   */
  const version = '1.0';

  /**
   * Class constructor
   */
  private function __construct()
  {
    $this->defineConstants();

    add_action('plugins_loaded', [$this, 'initPlugin']);

    // add db table registration hook
    register_activation_hook(__FILE__, [$this, 'activate']);
  }

  /**
   * Initializes a singleton instance
   * 
   * @return \Contacts_Manager
   */
  public static function init()
  {
    static $instance = false;

    if (!$instance) {
      $instance = new self();
    }

    return $instance;
  }

  /**
   * Define the required plugin constants
   * 
   * @return void
   */
  public function defineConstants()
  {
    define('CONTACTS_MANAGER_VERSION', self::version);
    define('CONTACTS_MANAGER_FILE', __FILE__);
    define('CONTACTS_MANAGER_PATH', __DIR__);
    define('CONTACTS_MANAGER_URL', plugins_url('', CONTACTS_MANAGER_FILE));
    define('CONTACTS_MANAGER_ASSETS', CONTACTS_MANAGER_URL . '/assets');
  }

  /**
   * Initialize the plugin
   * 
   * @return void
   */
  public function initPlugin()
  {
    new Contacts\Manager\Assets();

    $contacts_controller = new Contacts\Manager\ContactsController();
    $settings_controller = new Contacts\Manager\SettingsController();

    if (defined('DOING_AJAX') && DOING_AJAX) {
      new Contacts\Manager\Ajax($contacts_controller, $settings_controller);
    }

    if (is_admin()) {
      new Contacts\Manager\Admin();
    } else {
      new Contacts\Manager\Frontend($contacts_controller);
    }
  }

  /**
   * Plugin activation function
   * 
   * @return void
   */
  public function activate()
  {
    $installer = new \Contacts\Manager\Installer();
    $installer->run();
  }
}

function contacts_manager()
{
  return Contacts_Manager::init();
}

contacts_manager();
