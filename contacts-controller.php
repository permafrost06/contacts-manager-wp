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
    $response = $this->db->insert($this->this->table_name, array('name' => $name, 'email' => $email, 'phone' => $phone, 'address' => $address));
    if (!$response) {
      throw new Exception("Could not insert contact");
    }
  }

  public function get_contact($id)
  {
    $data = $this->db->get_row("SELECT * FROM $this->table_name WHERE `id` = '$id'", "ARRAY_A");

    if (!$data) {
      throw new Exception("Could not get contact. ID might be invalid");
    }

    return $data;
  }

  public function get_all_contacts()
  {
    $data = $this->db->get_results("SELECT * FROM $this->table_name", "ARRAY_A");

    if (!$data) {
      throw new Exception("Could not get contacts");
    }

    return $data;
  }

  public function remove_contact($id)
  {
    try {
      $this->get_contact($id);

      $response = $this->db->delete($this->table_name, array('id' => $id));

      if (!$response) {
        throw new Exception("Could not delete contact. ID might be invalid");
      }
    } catch (Exception $error) {
      throw new Exception("Contact does not exist");
    }
  }

  public function update_contact($id, $name, $email, $phone, $address)
  {
    try {
      $this->get_contact($id);

      $response = $this->db->update($this->table_name, array('name' => $name, 'email' => $email, 'phone' => $phone, 'address' => $address), array('id' => $id));

      if (!$response) {
        throw new Exception("Could not update contact");
      }
    } catch (Exception $error) {
      throw new Exception("Contact does not exist");
    }
  }
}
