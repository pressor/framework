<?php
// Reference: http://wpengineer.com/2382/wordpress-constants-overview/

return array(
	// list of Wordpress constants we shouldn't overwrite
	'blacklisted' => array(
		'APP_REQUEST' => true,
		'XMLRPC_REQUEST' => true,
		'IFRAME_REQUEST' => true,
		'DOING_AJAX' => true,
		'DOING_AUTOSAVE' => true,
		'DOING_CRON' => true,
		'COMMENTS_TEMPLATE' => true,
		'IS_PROFILE_PAGE' => true,
		'WP_ADMIN' => true,
		'WP_BLOG_ADMIN' => true,
		'WP_NETWORK_ADMIN' => true,
		'WP_USER_ADMIN' => true,
		'WP_IMPORTING' => true,
		'WP_INSTALLING' => true,
		'WP_INSTALLING_NETWORK' => true,
		'WP_LOAD_IMPORTERS' => true,
		'WP_REPAIRING' => true,
		'WP_SETUP_CONFIG' => true,
		'WP_UNINSTALL_PLUGIN' => true,
		'SHORTINIT' => true,
		'ABSPATH' => true,
	),
	// list of Wordpress keys that are required
	'required' => array(
		// database stuff
		'DB_HOST' => true,
		'DB_NAME' => true,
		'DB_USER' => true,
		'DB_PASSWORD' => true,
		'DB_CHARSET' => true,
		'DB_COLLATE' => true,

		// keys
		'AUTH_KEY' => true,
		'SECURE_AUTH_KEY' => true,
		'LOGGED_IN_KEY' => true,
		'NONCE_KEY' => true,

		// salts
		'AUTH_SALT' => true,
		'SECURE_AUTH_SALT' => true,
		'LOGGED_IN_SALT' => true,
		'NONCE_SALT' => true,
	),
);
