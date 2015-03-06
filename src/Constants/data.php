<?php
/*
|--------------------------------------------------------------------------
| Wordpress constants
|--------------------------------------------------------------------------
| Taken from: http://wpengineer.com/2382/wordpress-constants-overview/
|
| Settings in here should be considered as cross-environment settings.
| Environment specfic settings should be set in you .env.{environment}.php file
| Any key with a *null* value will be ignored and use the Wordpress default
*/

return array(

	/*
	|--------------------------------------------------------------------------
	| System constants
	|--------------------------------------------------------------------------
	*/
	/*
	AUTOSAVE_INTERVAL
	Defines an interval, in which WordPress should do an autosave.
	Value: time in seconds (Default: 60)
	'AUTOSAVE_INTERVAL' => null,
	*/

	/*
	CORE_UPGRADE_SKIP_NEW_BUNDLED
	Allows you to skip new bundles files like plugins and/or themes on upgrades.
	Values: true|false
	*/
	// We'll let composer manage this instead
	'CORE_UPGRADE_SKIP_NEW_BUNDLED' => true,

	/*
	DISABLE_WP_CRON
	Deactivates the cron function of WordPress.
	Values: true|false
	*/
	// Disabling cron
	'DISABLE_WP_CRON' => true,

	/*
	EMPTY_TRASH_DAYS
	Controls the number of days before WordPress permanently deletes posts, pages, attachments, and comments, from the trash bin.
	Value: time in days (Default: 30)
	'EMPTY_TRASH_DAYS' => null,
	*/

	/*
	IMAGE_EDIT_OVERWRITE
	Allows WordPress to override an image after editing or to save the image as a copy.
	Values: true|false
	'IMAGE_EDIT_OVERWRITE' => null,
	*/

	/*
	MEDIA_TRASH
	(De)activates the trash bin function for media.
	Values: true|false (Default: false)
	'MEDIA_TRASH' => true,
	*/

	/*
	WPLANG
	Defines the language which WordPress should use.
	Values: string (Default: en_US)
	'WPLANG' => 'en_US',
	*/

	/*
	WP_CRON_LOCK_TIMEOUT
	Defines a period of time in which only one cronjob will be fired. Since WordPress 3.3.
	Value: time in seconds (Default: 60)
	'WP_CRON_LOCK_TIMEOUT' => null,
	*/

	/*
	WP_MAIL_INTERVAL
	Defines a period of time in which only one mail request can be done.
	Value: time in seconds (Default: 300)
	'WP_MAIL_INTERVAL' => null,
	*/

	/*
	WP_POST_REVISIONS
	(De)activates the revision function for posts. A number greater than 0 defines the number of revisions for one post.
	Values: true|false|number (Default: true)
	*/
	// Setting to a reasonable number of revisions
	'WP_POST_REVISIONS' => 2,

	/*
	WP_MAX_MEMORY_LIMIT
	Allows you to change the maximum memory limit for some WordPress functions.
	Values: See PHP docs (Default: 256M)
	'WP_MAX_MEMORY_LIMIT' => null,
	*/

	/*
	|--------------------------------------------------------------------------
	| Theming-related constants
	|--------------------------------------------------------------------------
	/*
	WP_DEFAULT_THEME
	Defines the language which WordPress should use.
	Value: template name (Default: twentyeleven)
	*/
	'WP_DEFAULT_THEME' => 'pressor',

	/*
	WP_USE_THEMES
	(De)activates the loading of themes.
	Values: true|false
	*/
	'WP_USE_THEMES' => true,

	/*
	BACKGROUND_IMAGE
	Defines a default background image.
	'BACKGROUND_IMAGE' => null,
	*/

	/*
	HEADER_IMAGE
	Defines a default header image.
	'HEADER_IMAGE' => null,

	/*
	HEADER_IMAGE_HEIGHT
	Defines the height of the header image.
	'HEADER_IMAGE_HEIGHT' => null,
	*/

	/*
	HEADER_IMAGE_WIDTH
	Defines the width of the header image.
	'HEADER_IMAGE_WIDTH' => null,
	*/

	/*
	HEADER_TEXTCOLOR
	Defines the font color for the header text.
	'HEADER_TEXTCOLOR' => null,
	*/

	/*
	NO_HEADER_TEXT
	(De)activates the support for header text.
	Values: true|false
	'NO_HEADER_TEXT' => null,
	*/

	/*
	STYLESHEETPATH
	Defines the absolute path to the stylesheet of the current theme.
	'STYLESHEETPATH' => null,
	*/

	/*
	TEMPLATEPATH
	Defines the absolute path to the template files of the current theme.
	'TEMPLATEPATH' => null,
	*/

	/*
	|--------------------------------------------------------------------------
	| Database constants
	|--------------------------------------------------------------------------
	*/
	/*
	DB_CHARSET
	Defines the database charset.
	Values: See MySQL docs (Default: utf8)
	*/
	'DB_CHARSET' => 'utf8',

	/*
	DB_COLLATE
	Defines the database collation.
	Values: See MySQL docs (Default: utf8_general_ci)
	*/
	'DB_COLLATE' => 'utf8_general_ci',

	/*
	DB_HOST, DB_NAME, DB_PASSWORD, DB_USER
	These are explicitly set by our .env.php file
	*/
	'DB_HOST' => getenv('DB_HOST'),
	'DB_NAME' => getenv('DB_NAME'),
	'DB_PASSWORD' => getenv('DB_PASSWORD'),
	'DB_USER' => getenv('DB_USER'),

	/*
	WP_ALLOW_REPAIR
	Allows you to automatically repair and optimize the database tables via /wp-admin/maint/repair.php.
	Value: true
	'WP_ALLOW_REPAIR' => null,
	*/

	/*
	CUSTOM_USER_TABLE
	Allows you to define a custom user table.
	Value: table name
	'CUSTOM_USER_TABLE' => null,
	*/

	/*
	CUSTOM_USER_META_TABLE
	Allows you to define a custom user meta table.
	Value: table name
	'CUSTOM_USER_META_TABLE' => null,
	*/

	/*
	|--------------------------------------------------------------------------
	| Path constants
	|--------------------------------------------------------------------------
	/*
	WP_LANG_DIR
	Absolute path to the folder with language files.
	Default: WP_CONTENT_DIR /languages or WP_CONTENT_DIR WPINC /languages
	'WP_LANG_DIR' => null,
	*/

	/*
	WP_PLUGIN_DIR
	Absolute path to the plugins dir.
	Default: WP_CONTENT_DIR /plugins
	'WP_PLUGIN_DIR' => null,
	*/

	/*
	WP_PLUGIN_URL
	URL to the plugins dir.
	Default: WP_CONTENT_URL /plugins
	'WP_PLUGIN_URL' => null,
	*/

	/*
	WP_CONTENT_DIR
	Absolute path to the wp-content dir.
	Default: ABSPATH wp-content
	*/
	'WP_CONTENT_DIR' => app_path() . '/wp-content',

	/*
	WP_CONTENT_URL
	URL to wp-content dir
	Default: WP_SITEURL wp-content
	'WP_CONTENT_URL' => null,
	*/

	/*
	WP_HOME
	Home URL of your WordPress.
	*/
	'WP_HOME' => null,

	/*
	WP_SITEURL
	URL to the WordPress root dir.
	*/
	'WP_SITEURL' => null,

	/*
	WP_TEMP_DIR
	Absolute path to a dir, where temporary files can be saved.
	'WP_TEMP_DIR' => null,
	*/

	/*
	WPMU_PLUGIN_DIR
	Absolute path to the must use plugin dir.
	Default: WP_CONTENT_DIR /mu-plugins
	'WPMU_PLUGIN_DIR' => null,
	*/

	/*
	WPMU_PLUGIN_URL
	URL to the must use plugin dir.
	Default: WP_CONTENT_URL /mu-plugins
	'WPMU_PLUGIN_URL' => null,
	*/

	/*
	|--------------------------------------------------------------------------
	| Debug constants
	|--------------------------------------------------------------------------
	*/
	/*
	SAVEQUERIES
	(De)activates the saving of database queries in an array ($wpdb->queries).
	Values: true|false
	*/
	// we'll save some processing and skip this
	'SAVEQUERIES' => false,

	/*
	SCRIPT_DEBUG
	(De)activates the loading of compressed Javascript and CSS files.
	Values: true|false
	'SCRIPT_DEBUG' => null,
	*/

	/*
	WP_DEBUG
	(De)activates the debug mode in WordPress.
	Values: true|false (Default: false)
	'WP_DEBUG' => null,
	*/

	/*
	WP_DEBUG_DISPLAY
	(De)activates the display of errors on the screen.
	Values: true|false|null (Default: true)
	'WP_DEBUG_DISPLAY' => null,
	*/

	/*
	WP_DEBUG_LOG
	(De)activates the writing of errors to the /wp-content/debug.log file.
	Values: true|false (Default: false)
	'WP_DEBUG_LOG' => null,
	*/

	/*
	|--------------------------------------------------------------------------
	| Security and cookie constants
	|--------------------------------------------------------------------------
	*/
	/*
	ADMIN_COOKIE_PATH
	Path to the /wp-admin/ dir.
	Default: SITECOOKIEPATH wp-admin or for Multisite in subdirectory SITECOOKIEPATH
	'ADMIN_COOKIE_PATH' => null,
	*/

	/*
	ALLOW_UNFILTERED_UPLOADS
	Allows unfiltered uploads by admins.
	Value: true
	'ALLOW_UNFILTERED_UPLOADS' => null,
	*/

	/*
	AUTH_COOKIE
	Cookie name for the authentication.
	Default: wordpress_ COOKIEHASH
	'AUTH_COOKIE' => null,
	*/

	/*
	COOKIEHASH
	Hash for generating cookie names.
	'COOKIEHASH' => null,
	*/

	/*
	COOKIEPATH
	Path to WordPress root dir.
	Default: Home URL without http(s)://
	'COOKIEPATH' => null,
	*/

	/*
	COOKIE_DOMAIN
	Domain of the WordPress installation.
	Default: false or for Multisite with subdomains .domain of the main site
	'COOKIE_DOMAIN' => null,
	*/

	/*
	CUSTOM_TAGS
	Allows you to override the list of secure HTML tags. See /wp-includes/kses.php.
	Values: true|false (Default: false)
	'CUSTOM_TAGS' => null,
	*/

	/*
	DISALLOW_FILE_EDIT
	Allows you to disallow theme and plugin edits via WordPress editor.
	Value: true
	*/
	// We'll be responsible programmers and not let Wordpress edit anything
	'DISALLOW_FILE_EDIT' => true,

	/*
	DISALLOW_FILE_MODS
	Allows you to disallow the editing, updating, installing and deleting of plugins, themes and core files via WordPress Backend.
	Value: true
	*/
	// We'll manage any package updates via composer
	'DISALLOW_FILE_MODS' => true,

	/*
	DISALLOW_UNFILTERED_HTML
	Allows you to disallow unfiltered HTML for every user, admins too.
	Value: true
	'DISALLOW_UNFILTERED_HTML' => null,
	*/

	/*
	FORCE_SSL_ADMIN
	Activates SSL for logins and in the backend.
	Values: true|false (Default: false)
	'FORCE_SSL_ADMIN' => null,
	*/

	/*
	FORCE_SSL_LOGIN
	Activates SSL for logins.
	Values: true|false (Default: false)
	'FORCE_SSL_LOGIN' => null,
	*/

	/*
	LOGGED_IN_COOKIE
	Cookie name for logins.
	Default: wordpress_logged_in_ COOKIEHASH
	'LOGGED_IN_COOKIE' => null,
	*/

	/*
	PASS_COOKIE
	Cookie name for the password.
	Default: wordpresspass_ COOKIEHASH
	'PASS_COOKIE' => null,
	*/

	/*
	PLUGINS_COOKIE_PATH
	Path to the plugins dir.
	Default: WP_PLUGIN_URL without http(s)://
	'PLUGINS_COOKIE_PATH' => null,
	*/

	/*
	SECURE_AUTH_COOKIE
	Cookie name for the SSL authentication.
	Default: wordpress_sec_ COOKIEHASH
	'SECURE_AUTH_COOKIE' => null,
	*/

	/*
	SITECOOKIEPATH
	Path of you site.
	Default: Site URL without http(s)://
	'SITECOOKIEPATH' => null,
	*/

	/*
	TEST_COOKIE
	Cookie name for the test cookie.
	Default: wordpress_test_cookie
	'TEST_COOKIE' => null,
	*/

	/*
	USER_COOKIE
	Cookie name for users.
	Default: wordpressuser_ COOKIEHASH
	'USER_COOKIE' => null,
	*/

	/*
	AUTH_KEY, AUTH_SALT, LOGGED_IN_KEY, LOGGED_IN_SALT, NONCE_KEY, NONCE_SALT, SECURE_AUTH_KEY, SECURE_AUTH_SALT
	salts
	*/
	'AUTH_KEY' =>         'put your unique phrase here',
	'SECURE_AUTH_KEY' =>  'put your unique phrase here',
	'LOGGED_IN_KEY' =>    'put your unique phrase here',
	'NONCE_KEY' =>        'put your unique phrase here',
	'AUTH_SALT' =>        'put your unique phrase here',
	'SECURE_AUTH_SALT' => 'put your unique phrase here',
	'LOGGED_IN_SALT' =>   'put your unique phrase here',
	'NONCE_SALT' =>       'put your unique phrase here',

	/*
	|--------------------------------------------------------------------------
	| Our custom constants
	|--------------------------------------------------------------------------
	*/
	/*
	WP_TABLE_PREFIX
	This is our custom constant to set the $table_prefix variable
	'WP_TABLE_PREFIX' => 'wp_',
	*/


);
