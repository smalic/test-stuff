<?php
/*
 * Plugin Name: Q Movies
 * Description: Test project for a job at Q Agency.
 * Author: Stefan Malic
 * Version: 1.0.0
 */

define( 'Q_MOVIES_PLUGIN', plugin_dir_path( __FILE__ ) );
define( 'Q_MOVIES_PLUGIN_URL', plugins_url( '/q-movies/' ) );

require Q_MOVIES_PLUGIN . 'Movies.php';

$movies = new \Q\Movies();
$movies->instantiate();
