<?php

namespace Contacts\Manager;

use Exception;

class ContactsController
{
  protected $db;
  protected $table_name;

  public function __construct()
  {
    global $wpdb;

    $this->db = $wpdb;
    $this->table_name = $wpdb->prefix . 'contacts_manager_table';
  }

  public function addContact($name, $email, $phone, $address)
  {
    if (empty($name)) {
      throw new Exception("Name is empty");
    }

    $response = $this->db->insert($this->table_name, array('name' => $name, 'email' => $email, 'phone' => $phone, 'address' => $address));
    if (!$response) {
      throw new Exception("Could not insert contact");
    }
  }

  public function getContact($id)
  {
    $data = $this->db->get_row('SELECT * FROM ' . $this->table_name . " WHERE `id` = '$id'");

    if (!$data) {
      throw new Exception("Contact does not exist");
    }

    return $data;
  }

  public function getAllContacts()
  {
    $data = $this->db->get_results('SELECT * FROM ' . $this->table_name, "ARRAY_A");

    if (is_null($data)) {
      throw new Exception("Could not get contacts");
    }

    return $data;
  }

  public function deleteContact($id)
  {
    $this->getContact($id);

    $response = $this->db->delete($this->table_name, array('id' => $id));

    if (!$response) {
      throw new Exception("Could not delete contact. ID might be invalid");
    }
  }

  public function updateContact($id, $name, $email, $phone, $address)
  {
    $contact = $this->getContact($id);

    if ($contact->name == $name && $contact->email == $email && $contact->phone == $phone && $contact->address == $address) {
      throw new Exception('No changes were made');
    }

    $response = $this->db->update($this->table_name, array('name' => $name, 'email' => $email, 'phone' => $phone, 'address' => $address), array('id' => $id));

    if (!$response) {
      throw new Exception("Could not update contact");
    }
  }
}
