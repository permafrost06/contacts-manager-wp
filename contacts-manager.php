<?php

/**
 * Plugin Name: Contacts Manager
 */

$dir = plugin_dir_path(__FILE__);
include($dir . "contacts-controller.php");
include($dir . "admin-table.php");

if (!function_exists('write_log')) {
  function write_log($log)
  {
    if (true === WP_DEBUG) {
      if (is_array($log) || is_object($log)) {
        error_log(print_r($log, true));
      } else {
        error_log($log);
      }
    }
  }
}

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
    $this->define_constants();

    add_action('plugins_loaded', [$this, 'init_plugin']);

    // render custom page
    add_action('wp_head', array($this, 'render_contact_form'));

    // enqueue jquery script to send ajax request and handle response
    add_action('wp_enqueue_scripts', array($this, "enqueue_plugin_scripts"));

    // handle jquery response
    add_action('wp_ajax_contacts_manager_handle_ajax', array($this, 'ajax_handler'));

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
  public function define_constants()
  {
    define('CONTACTS_MANAGER_VERSION', self::version);
    define('CONTACTS_MANAGER_FILE', __FILE__);
    define('CONTACTS_MANAGER_PATH', __DIR__);
    define('CONTACTS_MANAGER_URL', plugins_url('', CONTACTS_MANAGER_PATH));
    define('CONTACTS_MANAGER_ASSETS', CONTACTS_MANAGER_URL . '/assets');
  }

  /**
   * Initialize the plugin
   * 
   * @return void
   */
  public function init_plugin()
  {
    \Contacts\Manager\ContactsController::init();

    if (is_admin()) {
      new Contacts\Manager\Admin();
    } else {
      new \Contacts\Manager\Frontend();
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

  function render_contact_form()
  {
    if (isset($_GET['contact-form'])) {
      $dir = plugin_dir_path(__FILE__);
      include($dir . "contact-form.php");
      exit();
    }
  }

  function enqueue_plugin_scripts()
  {
    wp_enqueue_style('base-style', plugin_dir_url(__FILE__) . 'styles/base.css');

    wp_enqueue_script(
      'ajax-script',
      plugins_url('/js/form-ajax-handler.js', __FILE__),
      array('jquery'),
      '1.0.0'
    );

    wp_localize_script(
      'ajax-script',
      'my_ajax_obj',
      array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('title_example'),
      )
    );
  }

  function ajax_handler()
  {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    try {
      $this->contacts_controller->add_contact($name, $email, $phone, $address);

      wp_send_json_success(array('message' => 'Successfully added contact!'));
    } catch (Exception $error) {
      wp_send_json_error(array('message' => 'Could not add contact!'));
    }
  }
}

function contacts_manager()
{
  return Contacts_Manager::init();
}

contacts_manager();
