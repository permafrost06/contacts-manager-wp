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

  public function checkValidity($name, $email, $phone, $address)
  {
    if (empty($name)) {
      throw new Exception('Name is empty', 400);
    }
    if (empty($email)) {
      throw new Exception('Email is empty', 400);
    }
    if (empty($phone)) {
      throw new Exception('Phone is empty', 400);
    }
    if (empty($address)) {
      throw new Exception('Address is empty', 400);
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      throw new Exception('Email is invalid', 400);
    }
    if (!preg_match('/^[-+ ()\d]+$/', $phone)) {
      throw new Exception('Phone number is invalid', 400);
    }
    if (strlen($phone) < 5 || strlen($phone) > 20) {
      throw new Exception('Phone number length must be between 5 and 20', 400);
    }
  }

  public function addContact($name, $email, $phone, $address)
  {
    $this->checkValidity($name, $email, $phone, $address);

    if ($this->checkEmailExists($email)) {
      throw new Exception("Email '$email' already used", 400);
    }

    $response = $this->db->insert(
      $this->table_name,
      array('name' => $name, 'email' => $email, 'phone' => $phone, 'address' => $address)
    );
    if (!$response) {
      throw new Exception("Could not insert contact", 500);
    }
  }

  public function getContact($id)
  {
    $data = $this->db->get_row("SELECT * FROM {$this->table_name} WHERE `id` = '$id'");

    if (!$data) {
      throw new Exception("Contact with '$id' does not exist", 404);
    }

    return $data;
  }

  public function checkEmailExists($email)
  {
    $row = $this->db->get_row("SELECT email FROM {$this->table_name} WHERE email = '$email'");

    if (is_null($row)) return false;
    else return true;
  }

  public function getAllContacts()
  {
    $data = $this->db->get_results("SELECT * FROM {$this->table_name}", "ARRAY_A");

    if (is_null($data)) {
      throw new Exception("Could not get contacts", 500);
    }

    return $data;
  }

  public function getContactsPaged($page = 0, $limit = 10, $order_by = 'id', $ascending = true)
  {
    $order = ($ascending) ? 'ASC' : 'DESC';
    $offset = $page * $limit;

    $data = $this->db->get_results(
      "SELECT * FROM {$this->table_name} ORDER BY {$order_by} {$order} LIMIT {$offset}, {$limit}",
      "ARRAY_A"
    );

    if (is_null($data)) {
      throw new Exception("Could not get contacts", 500);
    }

    $page = [
      'page' => $page,
      'limit' => $limit,
      'total' => $this->getContactCount(),
      'order_by' => $order_by,
      'order' => $order,
      'data' => $data
    ];

    return $page;
  }

  public function getContactCount()
  {
    $count = (int) $this->db->get_var("SELECT count(id) FROM {$this->table_name}");

    if (is_null($count)) {
      throw new Exception("Could not get contacts", 500);
    }

    return $count;
  }

  public function deleteContact($id)
  {
    $this->getContact($id);

    $response = $this->db->delete($this->table_name, array('id' => $id));

    if (!$response) {
      throw new Exception("Could not delete contact with id '$id'. ID might be invalid", 404);
    }
  }

  public function updateContact($id, $name, $email, $phone, $address)
  {
    $this->checkValidity($name, $email, $phone, $address);

    $contact = $this->getContact($id);

    if (
      $contact->name == $name && $contact->email == $email &&
      $contact->phone == $phone && $contact->address == $address
    ) {
      throw new Exception('No changes were made', 400);
    }

    $response = $this->db->update(
      $this->table_name,
      array('name' => $name, 'email' => $email, 'phone' => $phone, 'address' => $address),
      array('id' => $id)
    );

    if (!$response) {
      throw new Exception("Could not update contact with id '$id'", 500);
    }
  }
}
