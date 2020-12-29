# xAPI Post Options

xAPI Post Options is a WordPress plugin that allows admininstrators to send custom xAPI statements from any post type. This is the only plugin that supports configuring the actor, verb, and object elements at the post level. Statements are fired based on JavaScript events that can be configured directly in a post. Data is persisted in a learning record store (LRS) that can be configured on the plugin settings page

## Features
- Configure LRS endpoint, username and password on the plugin settings page. Statements are sent server-side, no credentials are exposed in the DOM.
- Select whether to send statements for non-logged in users.
- Select between two actor settings, mbox and mbox_sha1sum, on the plugin settings page. The currently logged in user's email will be used as the actor element.
- Select from a list of all available post types and only add a metabox to post types you select.
- Select from over 50 DOM events to trigger a statement.
- Select from over 180 pre-defined xAPI verbs, or user your own.
- Configure your own object description and ID.
- JavaScript DOM events trigger xAPI statements via AJAX.

## To do
- Author multiple statements in a post.
- Support other xAPI elements, such as result and context.
- Support JSON statements.
- Support shortcodes in JSON statements.
- Track videos using the video profile specification.

## Installing

Clone or download this repo and either: 

* Unzip the folder and add it the Wordpress plugins directory via FTP
* Upload the zip file via the Wordpress admin panel

## License

GPL v2 or later

## Contributing

Pull requests, bug reports, and feature requests are welcome.
