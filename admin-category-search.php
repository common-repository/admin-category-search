<?php

/*
Plugin Name: Admin category search
Plugin URI: https://github.com/atillaordog/wp-admin-search
Description: This plugin adds search functionality to taxonomies and dropdowns like author
Version: 1.3
Author: Atilla Ordog
Author URI: http://www.gadratilprogramming.net
Text Domain: admin-category-search
Domain Path: /languages
*/

class WPAdminCategorySearch {
	private $_options = array(
		'categoriesTab' => array(
			'name' => 'Taxonomy(Categories) Search Tab',
			'value' => '1',
			'description' => 'Adds a search tab to taxonomies after Most Used'
		),
		'categoriesInField' => array(
			'name' => 'Taxonomy(Categories) In-field search',
			'value' => '0',
			'description' => 'Adds a search field after the taxonomy title that filters the list below'
		),
		'author' => array(
			'name' => 'Dropdowns(author) search',
			'value' => '0',
			'description' => 'Adds a search field after the dropdown that filters the dropdown elements'
		),
		'showSubcategories' => array(
			'name' => 'Show subcategories when results found',
			'value' => '0',
			'description' => 'If a found category has subcategories, those will be shown'
		)
	);

	private $_optionsGroup = 'wp_admin_category_search_options';

	private $_optionsPrefix = 'wp_admin_category_search_';

	function __construct() {
		load_plugin_textdomain( 'admin-category-search', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 

		add_action('admin_init', array($this, 'register_settings'));
		add_action('admin_menu', array($this, 'admin_menu' ));
		add_action('admin_footer', array($this, 'set_lang_constants'));
	}

	function admin_menu() {
		add_options_page(
			__('WP Admin category Search Settings', 'admin-category-search'),
			__('Category search', 'admin-category-search'),
			'manage_options',
			'wp_admin_category_search_settings',
			array(
				$this,
				'settings_page'
			)
		);
	}

	/**
	 * Goes trough the options and adds loads scripts where needed
	 *
	 * @return void
	 */
	function load_scripts(){
		//First add the main js
		add_action( 'admin_enqueue_scripts', function($hook){
			if ( 'post.php' != $hook && $hook != 'post-new.php' )
			{
				return;
			}	
			
			wp_register_script($this->_optionsPrefix.'main_js', site_url().'/wp-content/plugins/admin-category-search/js/adminSearch.js');
			wp_enqueue_script($this->_optionsPrefix.'main_js', false, array(), false, true);
		});
		foreach ( $this->_options as $key => $option ){
			if ( $option['value'] === '1' ){
				add_action( 'admin_enqueue_scripts', function($hook) use ($key){
					if ( 'post.php' != $hook && $hook != 'post-new.php' )
					{
						return;
					}	
					
					wp_register_script($this->_optionsPrefix.$key, site_url().'/wp-content/plugins/admin-category-search/js/'.$key.'.js');
					wp_enqueue_script($this->_optionsPrefix.$key, false, array(), false, true);
				});
			}
		}
	}

	/**
	 * Iterates trough options and checks if they exist
	 * If the option exists, it loads the value, otherwise creates the option
	 *
	 * @return void
	 */
	function register_settings() {
		foreach( $this->_options as $key => $option ){
			register_setting( $this->_optionsGroup, $this->_optionsPrefix.$key );
			$value = get_option($this->_optionsPrefix.$key);
			if ( $value === false ){
				add_option($this->_optionsPrefix.$key, $option['value']);
			} else {
				$this->_options[$key]['value'] = $value;
			}
		}
		$this->load_scripts();
	}

	function settings_page() {
		include_once('settings.php');
	}

	function set_lang_constants() {
	?>
  	<script>
		  jQuery(document).ready(function(){
			if (typeof adminCategorySearch != 'undefined') {
				adminCategorySearch.lang.search = '<?php _e("Search", 'admin-category-search'); ?>';
				adminCategorySearch.lang.reset = '<?php _e("Reset", 'admin-category-search') ?>';
				adminCategorySearch.lang.resetSearchResults = '<?php _e("Reset Search Results", 'admin-category-search'); ?>';
				adminCategorySearch.lang.go = '<?php _e("Go", 'admin-category-search'); ?>';
			}
		  });
	</script>
  	<?php
	}
}

new WPAdminCategorySearch;