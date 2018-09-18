<?php
/**
 * Fuel is a fast, lightweight, community driven PHP 5.4+ framework.
 *
 * @package    Fuel
 * @version    1.8.1
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2018 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * NOTICE:
 *
 * If you need to make modifications to the default configuration, copy
 * this file to your app/config folder, and make them in there.
 *
 * This will allow you to upgrade fuel without losing your custom config.
 */

return array(
	/**
	 * The active theme to use.  This can also be set in code using Theme::active('foo');
	 */
	'active' => 'default',

	/**
	 * The fallback theme to use.  If a view is not found in the active theme, this theme
	 * is used as a fallback.  This can also be set in code using Theme::fallback('foo');
	 */
	'fallback' => 'default',

	/**
	 * The theme search paths.  They are searched in the order given.  You can add paths
	 * on the fly via Theme::add_path($path) or Theme::add_paths(array($path1, $path2));
	 */
	'paths' => array(
		APPPATH.'themes',
	),

	/**
	 * The folder inside the theme to be used to store assets.  This is relative to the
	 * theme's path.
	 */
	'assets_folder' => 'assets',

	/**
	 * The extension for theme view files.
	 */
	'view_ext' => '.html',

	/**
	 * Whether to require a theme info file
	 */
	'require_info_file' => false,

	/**
	 * The theme info file name
	 */
	'info_file_name' => 'themeinfo.php',

	/**
	 * Auto prefixing for modules
	 *
	 * If true, the view to be loaded will be prefixed by the name of the current module (if any)
	 * If a string, it will be prefixed too, allowing you to store all modules in a subfolder
	 * if false, module prefixing is not used
	 */
	'use_modules' => false,
);
