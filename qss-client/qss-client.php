<?php
/*
 * Plugin Name: QSS Client
 * Description: Logs the user in using the Q Symfony Skeleton API.
 * Author: Stefan Malic
 * Version: 1.0.0
 */

define( 'QSS_CLIENT_PLUGIN', plugin_dir_path( __FILE__ ) );

require QSS_CLIENT_PLUGIN . 'classes/Client.php';
require QSS_CLIENT_PLUGIN . 'classes/Core.php';

new Q\Core();
