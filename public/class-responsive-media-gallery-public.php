<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.vsourz.com/
 * @since      1.0.0
 *
 * @package    Responsive_Media_Gallery
 * @subpackage Responsive_Media_Gallery/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Responsive_Media_Gallery
 * @subpackage Responsive_Media_Gallery/public
 * @author     vsourz Dizital <mehul@vsourz.com>
 */
class Responsive_Media_Gallery_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Responsive_Media_Gallery_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Responsive_Media_Gallery_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/responsive-media-gallery-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( "magnific-popup-css_vsz", plugin_dir_url( __FILE__ ) . 'css/magnific-popup.css', array(), $this->version, 'all' );
		wp_enqueue_style('font-awesome', plugin_dir_url( __FILE__ ). 'css/font-awesome.css', array(), $this->version, 'all');
		wp_register_style('mosaic', plugin_dir_url( __FILE__ ). 'css/jquery.mosaic.css', array(), $this->version, 'all');
		wp_register_style('unite-gallery', plugin_dir_url( __FILE__ ). 'css/unite-gallery.css', array(), $this->version, 'all');
	}
	
	

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Responsive_Media_Gallery_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Responsive_Media_Gallery_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_register_script( "masonry-js", plugin_dir_url( __FILE__ ) . 'js/masonry.pkgd.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script( "jquery-mosaic", plugin_dir_url( __FILE__ ) . 'js/jquery.mosaic.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( "imagesloaded-js", plugin_dir_url( __FILE__ ) . 'js/imagesloaded.pkgd.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script( "unitegallery-js", plugin_dir_url( __FILE__ ) . 'js/unitegallery.js', array( 'jquery' ), $this->version, true );
        wp_register_script( "ug-theme-tiles-js", plugin_dir_url( __FILE__ ) . 'themes/tiles/ug-theme-tiles.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/responsive-media-gallery-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( "magnific_vsz12", plugin_dir_url( __FILE__ ) . 'js/jquery.magnific-popup.js', array( 'jquery' ), $this->version, false );
		
		wp_enqueue_script( "masonry-js");
	}
	public function vsz_gallery_register_shortcodes(){
		//Add short code for "vsz_responsive_gallery"
		add_shortcode( 'vsz_responsive_gallery', array( $this, 'vsz_gallery_display_front' ));
	}
	
	public function vsz_gallery_load_more(){
		//include(plugin_dir_url( __FILE__ ).'partials/load_more.php');	
		include(dirname(__FILE__).'/partials/load_more.php');	
		exit;
	}
	public function vsz_gallery_load_more_masonary(){
		//include(plugin_dir_url( __FILE__ ).'partials/load_more.php');	
		include(dirname(__FILE__).'/partials/load_more_masonry.php');	
		exit;
	}
	public function vsz_gallery_load_more_mosaic(){
		//include(plugin_dir_url( __FILE__ ).'partials/load_more.php');	
		include(dirname(__FILE__).'/partials/load_more_mosaic.php');	
		exit;
	}
	
	//Display "vsz_responsive_gallery" 
	public function vsz_gallery_display_front($atts, $content, $name){
		
		/////////// If layout is passed in short code
		if(isset($atts['layout'])){
			$template = $atts['layout'];
			if($template == 'masonry'){
				/////////// If layout is masonary
				return require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/vsz_gallery_display_front-mesonary.php';
			}
			else if($template == 'mosaic'){
				return require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/vsz_gallery_display_front-mosaic.php';
			}
			else{
				/////////// If layout is not masonary
			 return require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/vsz_gallery_display_front.php';
			}
		}
		/////////// Layout is not passed so check in admin option for layout
		else{
			/////////// Id is present
			if(isset($atts['id'])){	
				 $template=get_post_meta($atts['id'],'gallery_template_layout',true);
				
				 if($template == 'masonry'){
					/////////// If layout is masonary
					return require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/vsz_gallery_display_front-mesonary.php';
				 }
				 else if($template == 'mosaic'){
					return require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/vsz_gallery_display_front-mosaic.php';
				}
				 else{
					
					/////////// If layout is not masonary
					return require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/vsz_gallery_display_front.php';
					
				 }
			}
			////////// Category is present
			if(isset($atts['category'])){
				$term = get_term_by( 'slug', $atts['category'], 'vsz_cat' );
				if(isset($term->term_id)){
					$template=get_term_meta($term->term_id,'gallery_template_layout',true);
					if($template == 'masonry'){
						/////////// If layout is masonary
						return require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/vsz_gallery_display_front-mesonary.php';
					}
					else if($template == 'mosaic'){
						return require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/vsz_gallery_display_front-mosaic.php';
					}
					else{
						/////////// If layout is not masonary
						return require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/vsz_gallery_display_front.php';
					}
				}
			}
		}
	}
}
