<?php
/**
 * Plugin Name: Gist Snippets
 * Plugin URI:  http://plugish.com
 * Description: Integrate your GitHub Gists into your posts with a simple interface.
 * Version:     0.1.0
 * Author:      JayWood
 * Author URI:  http://plugish.com
 * Donate link: http://plugish.com
 * License:     GPLv2
 * Text Domain: gist-snippets
 * Domain Path: /languages
 *
 * @link http://plugish.com
 *
 * @package Gist Snippets
 * @version 0.1.0
 */

/**
 * Copyright (c) 2017 JayWood (email : jjwood2004@gmail.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Built using generator-plugin-wp
 */

namespace JW\Gist_Snippets;

use Exception;

function gs_autoload_classes( $class_name ) {

	if ( false === strpos( $class_name, 'JW\Gist_Snippets' ) ) {
		return;
	}

	// Break everything into parts.
	$class_array = explode( '\\', $class_name );

	// Build the filename from the last item in the array.
	$filename = strtolower( str_ireplace( '-', '_',	end( $class_array )	) );

	// Cut off the first, and last item from the array
	$new_dir = array_slice( $class_array, 2, count( $class_array ) - 3 );

	// Glue the pieces back together.
	$new_dir = implode( '/', array_map( 'strtolower', $new_dir ) );

	// Build the directory.
	$new_dir = trailingslashit( $new_dir ) . 'class-' . $filename;

	Gist_Snippets::load_file( $new_dir );
}
spl_autoload_register( '\JW\Gist_Snippets\gs_autoload_classes' );

/**
 * Main initiation class
 *
 * @since  NEXT
 */
final class Gist_Snippets {

	/**
	 * Current version
	 *
	 * @var  string
	 * @since  NEXT
	 */
	const VERSION = '0.1.0';

	/**
	 * URL of plugin directory
	 *
	 * @var string
	 * @since  NEXT
	 */
	protected $url = '';

	/**
	 * Path of plugin directory
	 *
	 * @var string
	 * @since  NEXT
	 */
	protected $path = '';

	/**
	 * Plugin basename
	 *
	 * @var string
	 * @since  NEXT
	 */
	protected $basename = '';

	/**
	 * Singleton instance of plugin
	 *
	 * @var Gist_Snippets
	 * @since  NEXT
	 */
	protected static $single_instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since  NEXT
	 * @return Gist_Snippets A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Sets up our plugin
	 *
	 * @since  NEXT
	 */
	protected function __construct() {
		$this->basename = plugin_basename( __FILE__ );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->path     = plugin_dir_path( __FILE__ );
	}

	/**
	 * Attach other plugin classes to the base plugin class.
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function plugin_classes() {

		$this->item = new namespace\API\Base( $this );
		// Attach other plugin classes to the base plugin class.
		// $this->plugin_class = new GS_Plugin_Class( $this );
	} // END OF PLUGIN CLASSES FUNCTION

	/**
	 * Add hooks and filters
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function hooks() {

		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Activate the plugin
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function _activate() {
		// Make sure any rewrite functionality has been loaded.
		flush_rewrite_rules();
	}

	/**
	 * Deactivate the plugin
	 * Uninstall routines should be in uninstall.php
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function _deactivate() {}

	/**
	 * Init hooks
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function init() {
		if ( $this->check_requirements() ) {
			load_plugin_textdomain( 'gist-snippets', false, dirname( $this->basename ) . '/languages/' );
			$this->plugin_classes();
		}
	}

	/**
	 * Check if the plugin meets requirements and
	 * disable it if they are not present.
	 *
	 * @since  NEXT
	 * @return boolean result of meets_requirements
	 */
	public function check_requirements() {
		if ( ! $this->meets_requirements() ) {

			// Add a dashboard notice.
			add_action( 'all_admin_notices', array( $this, 'requirements_not_met_notice' ) );

			// Deactivate our plugin.
			add_action( 'admin_init', array( $this, 'deactivate_me' ) );

			return false;
		}

		return true;
	}

	/**
	 * Deactivates this plugin, hook this function on admin_init.
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function deactivate_me() {
		deactivate_plugins( $this->basename );
	}

	/**
	 * Check that all plugin requirements are met
	 *
	 * @since  NEXT
	 * @return boolean True if requirements are met.
	 */
	public static function meets_requirements() {
		// Do checks for required classes / functions
		// function_exists('') & class_exists('').
		// We have met all requirements.
		return true;
	}

	/**
	 * Adds a notice to the dashboard if the plugin requirements are not met
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function requirements_not_met_notice() {
		// Output our error.
		echo '<div id="message" class="error">';
		echo '<p>' . sprintf( __( 'Gist Snippets is missing requirements and has been <a href="%s">deactivated</a>. Please make sure all requirements are available.', 'gist-snippets' ), admin_url( 'plugins.php' ) ) . '</p>';
		echo '</div>';
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  NEXT
	 * @param string $field Field to get.
	 * @throws Exception Throws an exception if the field is invalid.
	 * @return mixed
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'version':
				return self::VERSION;
			case 'basename':
			case 'url':
			case 'path':
				return $this->$field;
			default:
				throw new Exception( 'Invalid '. __CLASS__ .' property: ' . $field );
		}
	}

	/**
	 * Include a file from the includes directory
	 *
	 * @since  NEXT
	 * @param  string $filename Name of the file to be included.
	 * @return bool   Result of include call.
	 */
	public static function include_file( $filename ) {
		$file = self::dir( 'includes/class-'. $filename .'.php' );
		if ( file_exists( $file ) ) {
			return include_once( $file );
		}
		return false;
	}

	/**
	 * This plugin's directory
	 *
	 * @since  NEXT
	 * @param  string $path (optional) appended path.
	 * @return string       Directory and path
	 */
	public static function dir( $path = '' ) {
		static $dir;
		$dir = $dir ? $dir : trailingslashit( dirname( __FILE__ ) );
		return $dir . $path;
	}

	/**
	 * This plugin's url
	 *
	 * @since  NEXT
	 * @param  string $path (optional) appended path.
	 * @return string       URL and path
	 */
	public static function url( $path = '' ) {
		static $url;
		$url = $url ? $url : trailingslashit( plugin_dir_url( __FILE__ ) );
		return $url . $path;
	}

	/**
	 * Built for the autoloader.
	 *
	 * @param        $file
	 * @param string $dir
	 *
	 * @return void
	 *
	 * @author JayWood
	 * @since  NEXT
	 */
	public static function load_file( $file, $dir = 'includes' ) {
		$dir = trailingslashit( $dir );

		$file = self::dir( $dir . $file ) . '.php';

		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}
}

/**
 * Grab the Gist_Snippets object and return it.
 * Wrapper for Gist_Snippets::get_instance()
 *
 * @since  NEXT
 * @return Gist_Snippets  Singleton instance of plugin class.
 */
function gist_snippets() {
	return Gist_Snippets::get_instance();
}

// Kick it off.
add_action( 'plugins_loaded', array( gist_snippets(), 'hooks' ) );

register_activation_hook( __FILE__, array( gist_snippets(), '_activate' ) );
register_deactivation_hook( __FILE__, array( gist_snippets(), '_deactivate' ) );
