# contacts-manager-wp

A wordpress plugin to add and manage contacts.

Install the plugin and visit any page in the wordpress site with request parameter `contact-form`
e.g. `http://localhost/?contact-form` to get a form where user can input their particulars to add to the contact form.

The admin menu of the plugin is available at the "Settings" menu in the admin panel as "Contacts Manager".
Here, the admin can see all the contacts in the plugin's database.

Shortcode `contacts-manager` can be used to display the complete user table in a page.
A parameter `id` can be passed to display inidividual contact.
Example: `[contacts-manager id="23"]` will display the contact with id "23".

## Todo:

- Add/remove/edit functionality in the admin panel.
- Add actions and filters.

### Note: Does not check for edge-cases. E.g. if not existent ID is supplied etc.
