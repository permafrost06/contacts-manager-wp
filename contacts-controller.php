<?php
class Contacts_Controller
{
  private $db;
  private $table_name;

  public function __construct()
  {
    global $wpdb;

    $this->db = $wpdb;
    $this->table_name = $this->db->prefix . 'contacts_manager_table';
  }

  public function add_contact($name, $email, $phone, $address)
  {
    $this->db->insert($this->this->table_name, array('name' => $name, 'email' => $email, 'phone' => $phone, 'address' => $address));
  }

  public function get_contact($id)
  {
    $data = $this->db->get_row("SELECT * FROM $this->table_name WHERE `id` = '$id'", "ARRAY_A");

    return $data;
  }

  public function get_all_contacts()
  {
    $data = $this->db->get_results("SELECT * FROM $this->table_name", "ARRAY_A");

    return $data;
  }

  public function remove_contact($id)
  {
    $this->db->delete($this->table_name, array('id' => $id));
  }

  public function update_contact($id, $name, $email, $phone, $address)
  {
    $this->db->update($this->table_name, array('name' => $name, 'email' => $email, 'phone' => $phone, 'address' => $address), array('id' => $id));
  }
}
