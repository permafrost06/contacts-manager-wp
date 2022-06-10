<?php
class Contacts_Controller
{
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
}
