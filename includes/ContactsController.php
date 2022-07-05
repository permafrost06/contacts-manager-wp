<?php

namespace Contacts\Manager;

class ContactsController
{
  private static $db;
  private static $table_name;

  private static $initialized = false;

  public static function init()
  {
    if (self::$initialized)
      return;

    global $wpdb;

    self::$db = $wpdb;
    self::$table_name = $wpdb->prefix . 'contacts_manager_table';
    self::$initialized = true;
  }

  private function __construct()
  {
  }

  public static function add_contact($name, $email, $phone, $address)
  {
    if (empty($name)) {
      throw new \Exception("Name is empty");
    }

    $response = self::$db->insert(self::$table_name, array('name' => $name, 'email' => $email, 'phone' => $phone, 'address' => $address));
    if (!$response) {
      throw new \Exception("Could not insert contact");
    }
  }

  public static function get_contact($id)
  {
    $data = self::$db->get_row('SELECT * FROM ' . self::$table_name . " WHERE `id` = '$id'");

    if (!$data) {
      throw new \Exception("Contact does not exist");
    }

    return $data;
  }

  public static function get_all_contacts()
  {
    $data = self::$db->get_results('SELECT * FROM ' . self::$table_name, "ARRAY_A");

    if (is_null($data)) {
      throw new \Exception("Could not get contacts");
    }

    return $data;
  }

  public static function delete_contact($id)
  {
    self::get_contact($id);

    $response = self::$db->delete(self::$table_name, array('id' => $id));

    if (!$response) {
      throw new \Exception("Could not delete contact. ID might be invalid");
    }
  }

  public static function update_contact($id, $name, $email, $phone, $address)
  {
    $contact = self::get_contact($id);

    if ($contact->name == $name && $contact->email == $email && $contact->phone == $phone && $contact->address == $address) {
      throw new \Exception('No changes were made');
    }

    $response = self::$db->update(self::$table_name, array('name' => $name, 'email' => $email, 'phone' => $phone, 'address' => $address), array('id' => $id));

    if (!$response) {
      throw new \Exception("Could not update contact");
    }
  }
}
