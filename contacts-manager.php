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

class contacts_manager_plugin
{
  private static $contacts_controller = null;

  function __construct()
  {
    add_action('admin_menu', array($this, 'admin_settings_page'));

    // init shortcode
    add_action('init', array($this, 'shortcodes_init'));

    // render custom page
    add_action('wp_head', array($this, 'render_contact_form'));

    // enqueue jquery script to send ajax request and handle response
    add_action('wp_enqueue_scripts', array($this, "enqueue_plugin_scripts"));

    // handle jquery response
    add_action('wp_ajax_contacts_manager_handle_ajax', array($this, 'ajax_handler'));

    // add db table registration hook
    register_activation_hook(__FILE__, array($this, "create_table"));

    self::$contacts_controller = new  Contacts_Controller();
  }

  function admin_settings_page()
  {
    add_menu_page("Contacts Manager Settings", "Contacts Manager", "manage_options", "contacts-manager-settings", array($this, "admin_settings_html"));
  }

  function admin_settings_html()
  {
    $dir = plugin_dir_path(__FILE__);
    include($dir . "admin-page.php");
  }

  function contacts_manager_shortcode($atts = [], $content = null, $tag = '')
  {
    $atts = array_change_key_case((array) $atts, CASE_LOWER);

    $output = '<div class="contacts-mgr-box">';

    if (array_key_exists('id', $atts)) {
      $output .= $this->render_contact_card($atts['id']);
    } else {
      $output .= $this->render_complete_table();
    }

    if (!is_null($content)) {
      $output .= apply_filters('the_content', $content);
    }

    $output .= '</div>';

    return $output;
  }

  function shortcodes_init()
  {
    add_shortcode('contacts-manager', array($this, 'contacts_manager_shortcode'));
  }

  function create_table()
  {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $create_table_query = "
            CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}contacts_manager_table` (
              `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `name` text NOT NULL,
              `email` text NOT NULL,
              `phone` text NOT NULL,
              `address` text NOT NULL
            ) {$charset_collate};
    ";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($create_table_query);
  }

  function render_contact_form()
  {
    if (isset($_GET['contact-form'])) {
      $dir = plugin_dir_path(__FILE__);
      include($dir . "contact-form.php");
      exit();
    }
  }

  function render_contact_card($id)
  {
    $data = self::$contacts_controller->get_contact($id);

    $output = "<div>";

    foreach ($data as $field) {
      $output .= "<p>" . $field . "</p>";
    }

    $output .= "</div>";

    return $output;
  }

  function render_complete_table()
  {
    $data = self::$contacts_controller->get_all_contacts();

    $output = '<table>
      <thead>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Address</th>
      </thead>
      <tbody>';

    foreach ($data as $row) {
      $output .= '<tr>';
      foreach ($row as $field) {
        $output .= '<td>' . $field . '</td>';
      }
      $output .= '</tr>';
    }

    $output .= '</tbody>
    </table>';

    return $output;
  }

  /**
   * Enqueue my scripts and assets.
   *
   * @param $hook
   */
  function enqueue_plugin_scripts()
  {
    wp_enqueue_style('base-style', plugin_dir_url(__FILE__) . '/styles/base.css');

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

  /**
   * Handles my AJAX request.
   */
  function ajax_handler()
  {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    self::$contacts_controller->add_contact($name, $email, $phone, $address);

    wp_send_json_success(array('message' => 'Successfully added contact!'));

    wp_die(); // All ajax handlers die when finished
  }
}
