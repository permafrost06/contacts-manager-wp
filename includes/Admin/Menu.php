<?php

namespace Contacts\Manager\Admin;

/**
 * Menu handler class
 */
class Menu
{
  function __construct()
  {
    add_action('admin_menu', [$this, 'admin_menu']);
  }

  public function admin_menu()
  {
    add_menu_page(__('Contacts Manager Settings', 'contacts-manager'), 'Contacts Manager', 'manage_options', 'contacts-manager', [$this, 'vue_app_entrypoint'], 'dashicons-id-alt', 25);
  }

  public function vue_app_entrypoint()
  {
?>
    <div id="app"></div>
<?php

    wp_enqueue_script('admin-vue-app');
  }
}
