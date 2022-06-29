# contacts-manager-wp

A wordpress plugin to add and manage contacts.

The admin menu of the plugin is available at the "Settings" menu in the admin panel as "Contacts Manager".
Here, the admin can see all the contacts in the plugin's database and can add, edit, or remove contacts as needed.

Shortcode `contacts-manager` can be used to display the complete user table in a page.
A parameter `id` can be passed to display inidividual contact.
Example: `[contacts-manager id="23"]` will display the contact with id "23".

Shortcode `contact-form` can be used to create a form to add a new contact.

## Installation

Run `npm run build` to generate main js bundle.

Run `npm run watch` to watch src folder for changes.

### Production build

Optionally delete all files in `assets/js/admin_app` folder.
Change `mode` in `webpack.config.dev.js` to `"production"` and run `npm run build`.

## Todo:

- Sanitize inputs
- Handle errors on admin app
- Add pagination to admin table
- Add a better production build script
- Add tree shaking for element-plus
