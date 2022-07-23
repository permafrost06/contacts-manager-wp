<?php

namespace Contacts\Manager\Admin;

/**
 * Menu handler class
 */
class Menu
{
  public function __construct()
  {
    add_action('admin_menu', [$this, 'adminMenu']);
  }

  public function adminMenu()
  {
    add_menu_page(
      __('Contacts Manager Settings', 'contacts-manager'),
      'Contacts Manager',
      'manage_options',
      'contacts-manager',
      [$this, 'vueAppEntrypoint'],
      'dashicons-id-alt',
      25
    );
  }

  public function vueAppEntrypoint()
  {
?>
    <div id="app"></div>
<?php

    wp_enqueue_script('admin-vue-app');
  }
}
