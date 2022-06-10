<?php
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
      'per_page' => $perPage
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
      'id' => 'ID',
      'name' => 'Name',
      'email' => 'Email',
      'phone' => 'Phone',
      'address' => 'Address',
      'actions' => 'Actions'
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
   * @param Array $item Data
   * @param String $column_name - Current column name
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
