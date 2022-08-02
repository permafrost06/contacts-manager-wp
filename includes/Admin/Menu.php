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

  /**
   * Creates a menu for the plugin in the admin panel
   */
  public function adminMenu(): void
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
    add_submenu_page(
      'contacts-manager',
      __('Contacts Manager Settings', 'contacts-manager'),
      __('Settings', 'contacts-manager'),
      'manage_options',
      'contacts-manager#/settings',
      [$this, 'vueAppEntrypoint']
    );
  }

  /**
   * Creates an entrypoint for the Vue.js app
   */
  public function vueAppEntrypoint(): void
  {
?>
    <div id="app"></div>
<?php

    wp_enqueue_script('admin-vue-app');
  }
}
