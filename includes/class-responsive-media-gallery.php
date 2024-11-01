<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.vsourz.com/
 * @since      1.0.0
 *
 * @package    Responsive_Media_Gallery
 * @subpackage Responsive_Media_Gallery/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Responsive_Media_Gallery
 * @subpackage Responsive_Media_Gallery/includes
 * @author     vsourz Dizital <mehul@vsourz.com>
 */
class Responsive_Media_Gallery {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Responsive_Media_Gallery_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'responsive-media-gallery';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Responsive_Media_Gallery_Loader. Orchestrates the hooks of the plugin.
	 * - Responsive_Media_Gallery_i18n. Defines internationalization functionality.
	 * - Responsive_Media_Gallery_Admin. Defines all hooks for the admin area.
	 * - Responsive_Media_Gallery_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-responsive-media-gallery-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-responsive-media-gallery-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-responsive-media-gallery-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-responsive-media-gallery-public.php';

		$this->loader = new Responsive_Media_Gallery_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Responsive_Media_Gallery_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Responsive_Media_Gallery_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Responsive_Media_Gallery_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		/***************************** Register custom Post Type **************************************/
		
		// call for register custom post type gallery
		$this->loader->add_action( 'init', $plugin_admin, 'vszgallery_register_posttype_gallery');
		
		
		/******************************* Add custom meta boxes here*****************************************/
		
		// add meta box for gallery on define display fields.
		$this->loader->add_action( 'add_meta_boxes_vsz_gallery', $plugin_admin, 'add_vszgallery_metabox');
		
		// call for save list blocks meta data
		$this->loader->add_action( 'save_post_vsz_gallery', $plugin_admin, 'vszgallery_save_meta_box_data',10, 3 );
		
		
		/****************************** Customize admin screen **************************************/
		
		// Add button table nav in filter section
		// $this->loader->add_filter( 'views_edit-vsz_gallery', $plugin_admin,'vsz_gallery_display_shortcode_to_views' );
		
		
		// Add button in table nav section
		$this->loader->add_action( 'manage_posts_extra_tablenav', $plugin_admin,'vsz_gallery_add_button_to_views', 10, 1 );
		
		// Add Table header in custom field
		$this->loader->add_filter( 'manage_edit-vsz_gallery_columns',$plugin_admin, 'add_gallery_customcolumn' ) ;
		
		//Display Value for custom field in table
		$this->loader->add_action( 'manage_vsz_gallery_posts_custom_column',$plugin_admin, 'custom_manage_gallery_columns', 10, 2 );
		
		// Show custom field with sortable icon
		$this->loader->add_filter( 'manage_edit-vsz_gallery_sortable_columns', $plugin_admin,'vsz_gallery_custom_sortorder_sortable_columns' );
		
		// Add sorting filter
		$this->loader->add_filter( 'request',$plugin_admin , 'custom_post_gallery_sortable_columns' );
		
		// save sortorder value when click on save sort order button.
		$this->loader->add_action( 'init', $plugin_admin, 'vsz_gallery_save_sortorder_value');
		
		/**************************** Customize category screen ***************************************/
		
		// for add shortcode column in category listing page
		$this->loader->add_filter("manage_edit-vsz_cat_columns", $plugin_admin, 'vsz_gallery_category_add_column' );
		
		// for add content in shortcode column
		$this->loader->add_filter("manage_vsz_cat_custom_column", $plugin_admin, 'vsz_gallery_category_column_text', 10, 3);

		////////// Add custom fields in category 
		$this->loader->add_action('vsz_cat_edit_form_fields',$plugin_admin,'gallery_cat_extra_category_fields'); 
		$this->loader->add_action('vsz_cat_add_form_fields',$plugin_admin,'gallery_cat_extra_category_fields');
		$this->loader->add_action('edited_vsz_cat',$plugin_admin,'gallery_cat_save_extra_category_fields');
		$this->loader->add_action('create_vsz_cat',$plugin_admin,'gallery_cat_save_extra_category_fields');
		
		////////// call ajax for checking video exists
		$this->loader->add_action('wp_ajax_check_video_exists',$plugin_admin,'gallery_check_video');
		$this->loader->add_action('wp_ajax_get_image_src',$plugin_admin,'vsz_gallery_get_image_src');

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Responsive_Media_Gallery_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	
		// this short code call for display gallery  related content.
		$this->loader->add_action( 'after_setup_theme',$plugin_public,'vsz_gallery_register_shortcodes' );
		
		////////// call ajax for checking video exists
		$this->loader->add_action('wp_ajax_load_more_images',$plugin_public,'vsz_gallery_load_more');
		$this->loader->add_action('wp_ajax_nopriv_load_more_images',$plugin_public,'vsz_gallery_load_more');
		
		$this->loader->add_action('wp_ajax_load_more_images_masonary',$plugin_public,'vsz_gallery_load_more_masonary');
		$this->loader->add_action('wp_ajax_nopriv_load_more_images_masonary',$plugin_public,'vsz_gallery_load_more_masonary');
		
		$this->loader->add_action('wp_ajax_load_more_images_mosaic',$plugin_public,'vsz_gallery_load_more_mosaic');
		$this->loader->add_action('wp_ajax_nopriv_load_more_images_mosaic',$plugin_public,'vsz_gallery_load_more_mosaic');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Responsive_Media_Gallery_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
