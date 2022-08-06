# contacts-manager-wp

A wordpress plugin to add and manage contacts.

The admin menu of the plugin is available at the "Settings" menu in the admin panel as "Contacts Manager".
Here, the admin can see all the contacts in the plugin's database and can add, edit, or remove contacts as needed.

Shortcode `contacts-manager` can be used to display the complete user table in a page.
An argument `id` can be passed to display inidividual contact.
Example: `[contacts-manager id="23"]` will display the contact with id "23".

Shortcode `contact-form` can be used to create a form to add a new contact.

## Instrtuctions

Run `npm run build:dev` to generate dev bundle.

Run `npm run watch` to generate dev bundle watch src folder for changes.

Run `npm run watch:hot` to run webpack-dev-server and get HMR.

Run `npm run build` to generate production bundle.

## Export plugin as `.zip`

Run `npm run export` to generate production bundle and compress the plugin into an archive that can be uploaded and installed in wordpress.
