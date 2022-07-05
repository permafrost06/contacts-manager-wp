# contacts-manager-wp

A wordpress plugin to add and manage contacts.

The admin menu of the plugin is available at the "Settings" menu in the admin panel as "Contacts Manager".
Here, the admin can see all the contacts in the plugin's database and can add, edit, or remove contacts as needed.

Shortcode `contacts-manager` can be used to display the complete user table in a page.
A parameter `id` can be passed to display inidividual contact.
Example: `[contacts-manager id="23"]` will display the contact with id "23".

Shortcode `contact-form` can be used to create a form to add a new contact.

## Installation

Run `npm run build:dev` to generate dev bundle.

Run `npm run watch` to generate dev bundle watch src folder for changes.

Run `npm run watch:live` to run webpack-dev-server and get HMR. See [HMR instructions section](https://github.com/permafrost06/contacts-manager-wp#hmr-instructions) for more info.

Run `npm run build` to generate production bundle.

## HMR Instructions

To use HMR, load the js bundle from WDS e.g. `http://localhost:8081/main.js` instead of the assets folder in the [`Assets.php`](https://github.com/permafrost06/contacts-manager-wp/blob/admin-vue-integration/includes/Assets.php#L30) file. This must be changed back in all other cases.

### Example:

#### For HMR:

```javascript
'src' => 'http://localhost:8081/main.js',
// 'src' => CONTACTS_MANAGER_ASSETS . '/js/admin_app/main.js',
```

#### For other uses:

```javascript
// 'src' => 'http://localhost:8081/main.js',
'src' => CONTACTS_MANAGER_ASSETS . '/js/admin_app/main.js',
```

## Todo:

- [x] Add/remove/edit functionality in the admin panel.
- [ ] Add plugin actions and filters.
- [x] Handle errors
- [x] Separate concerns
- [x] Handle errors on admin app
- [x] Add tree shaking for element-plus
- [x] Add form validation
- [x] Add pagination to admin table
- [x] Add a better production build script
- [x] Sanitize inputs
- [x] Add plugin description, version etc.
- [x] Move plugin menu and change icon
- [x] Submit form on enter
- [x] Submit and not fire action with form button
- [x] Set submit method
- [x] Try to enable hmr for the vue app dev
- [ ] Disable button if contact not changed
- [ ] Add error codes to error JSON
- [ ] Fix update form momentarily blank on mount
- [ ] Clean up shortcode code
- [ ] Maybe change rendered shortcode styles
- [ ] Add docblocks
