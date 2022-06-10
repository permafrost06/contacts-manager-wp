<?php

/**
 * Plugin Name: Contacts Manager
 */

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

  function add_contact($name, $email, $phone, $address)
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'contacts_manager_table';

    $wpdb->insert($table_name, array('name' => $name, 'email' => $email, 'phone' => $phone, 'address' => $address));
  }

  function get_contact($id)
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'contacts_manager_table';

    $data = $wpdb->get_row("SELECT * FROM $table_name WHERE `id` = '$id'", "ARRAY_A");

    return $data;
  }

  function get_all_contacts()
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'contacts_manager_table';

    $data = $wpdb->get_results("SELECT * FROM $table_name", "ARRAY_A");

    return $data;
  }

  function remove_contact($id)
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'contacts_manager_table';

    $wpdb->delete($table_name, array('id' => $id));
  }

  function update_contact($id, $name, $email, $phone, $address)
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'contacts_manager_table';

    $wpdb->update($table_name, array('name' => $name, 'email' => $email, 'phone' => $phone, 'address' => $address), array('id' => $id));
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
    $data = $this->get_contact($id);

    $output = "<div>";

    foreach ($data as $field) {
      $output .= "<p>" . $field . "</p>";
    }

    $output .= "</div>";

    return $output;
  }

  function render_complete_table()
  {
    $data = $this->get_all_contacts();

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

    $this->add_contact($name, $email, $phone, $address);

    wp_send_json_success(array('message' => 'Successfully added contact!'));

    wp_die(); // All ajax handlers die when finished
  }
}

// WP_List_Table is not loaded automatically so we need to load it in our application
if (!class_exists('WP_List_Table')) {
  require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class Example_List_Table extends WP_List_Table
{
  /**
   * Prepare the items for the table to process
   *
   * @return Void
   */
  public function prepare_items()
  {
    $columns = $this->get_columns();
    $hidden = $this->get_hidden_columns();
    $sortable = $this->get_sortable_columns();

    $data = $this->table_data();
    usort($data, array(&$this, 'sort_data'));

    $perPage = 10;
    $currentPage = $this->get_pagenum();
    $totalItems = count($data);

    $this->set_pagination_args(array(
      'total_items' => $totalItems,
      'per_page'    => $perPage
    ));

    $data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

    $this->_column_headers = array($columns, $hidden, $sortable);
    $this->items = $data;
  }

  /**
   * Override the parent columns method. Defines the columns to use in your listing table
   *
   * @return Array
   */
  public function get_columns()
  {
    $columns = array(
      'id'          => 'ID',
      'name'        => 'Name',
      'email'       => 'Email',
      'phone'       => 'Phone',
      'address'     => 'Address',
      'actions'     => 'Actions'
    );

    return $columns;
  }

  /**
   * Define which columns are hidden
   *
   * @return Array
   */
  public function get_hidden_columns()
  {
    return array();
  }

  /**
   * Define the sortable columns
   *
   * @return Array
   */
  public function get_sortable_columns()
  {
    return array(
      'id' => array('id', false),
      'name' => array('name', false),
      'email' => array('email', false),
      'phone' => array('phone', false),
      'address' => array('address', false)
    );
  }

  /**
   * Get the table data
   *
   * @return Array
   */
  private function table_data()
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'contacts_manager_table';

    $data = $wpdb->get_results("SELECT * FROM $table_name", "ARRAY_A");

    return $data;
  }

  protected function column_actions($item)
  {
    return sprintf(
      '<a href="?page=contacts-manager-settings&action=edit&id=%s"><button class="button button-primary">Edit</button></a>
      <a href="?page=contacts-manager-settings&action=delete&id=%s"><button class="button button-link-delete">Remove</button></a>',
      $item['id'],
      $item['id']
    );
  }

  /**
   * Define what data to show on each column of the table
   *
   * @param  Array $item        Data
   * @param  String $column_name - Current column name
   *
   * @return Mixed
   */
  public function column_default($item, $column_name)
  {
    switch ($column_name) {
      case 'id':
      case 'name':
      case 'email':
      case 'phone':
      case 'address':
        return $item[$column_name];

      default:
        return print_r($item, true);
    }
  }

  /**
   * Allows you to sort the data by the variables set in the $_GET
   *
   * @return Mixed
   */
  private function sort_data($a, $b)
  {
    // Set defaults
    $orderby = 'id';
    $order = 'asc';

    // If orderby is set, use this as the sort column
    if (!empty($_GET['orderby'])) {
      $orderby = $_GET['orderby'];
    }

    // If order is set use this as the order
    if (!empty($_GET['order'])) {
      $order = $_GET['order'];
    }


    $result = strcmp($a[$orderby], $b[$orderby]);

    if ($order === 'asc') {
      return $result;
    }

    return -$result;
  }
}

$contacts_manager_plugin = new contacts_manager_plugin();
