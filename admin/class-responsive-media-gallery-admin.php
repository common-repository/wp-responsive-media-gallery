<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.vsourz.com/
 * @since      1.0.0
 *
 * @package    Responsive_Media_Gallery
 * @subpackage Responsive_Media_Gallery/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Responsive_Media_Gallery
 * @subpackage Responsive_Media_Gallery/admin
 * @author     vsourz Dizital <mehul@vsourz.com>
 */
class Responsive_Media_Gallery_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		
		wp_register_style( 'responsive-media-gallery', plugin_dir_url( __FILE__ ) . 'css/vsz_gallery-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style('display_popup_css', plugin_dir_url( __FILE__ ). 'css/magnific-popup.css', array(), $this->version, 'all');
		wp_enqueue_style('font-awesome-css', plugin_dir_url( __FILE__ ). 'css/font-awesome.css', array(), $this->version, 'all');
		
	}

	/**
	 * Register the JavaScript for the admin area.
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
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/vsz_gallery-admin.js', array( 'jquery' ), $this->version, false );
		
		// For add colour js file for gallery
		wp_register_script('colour_picker_js', plugin_dir_url( __FILE__ ). 'js/jscolor.js');
		wp_register_script('Sortable', plugin_dir_url( __FILE__ ). 'js/Sortable.js');
		wp_register_script('display_popup_js', plugin_dir_url( __FILE__ ). 'js/jquery.magnific-popup.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('colour_picker_js');
		wp_enqueue_script('display_popup_js');
		wp_enqueue_script('media-upload');
		if(isset($post)){
			wp_enqueue_media(array('post' => $post->ID,));
		}
		
		wp_enqueue_media();
	}
	
	/************************************** Register custom post type and taxonomy **********************************/
	public function vszgallery_register_posttype_gallery(){
	
		$cap_type 	= 'post';
		$plural 	= 'Gallery';
		$single 	= 'Gallery';
		$cpt_name 	= 'vsz_gallery';
		$text_domain = 'vsz_gallery';
		
		
		// define labels for show in admin side gallery post type
		$gallery_labels = array(
							'name'                  => __( $plural, $text_domain ),
							'singular_name'         => __( $single, $text_domain ),
							'add_new'               => __( "Add New ", $text_domain ),
							'add_new_item'          => __( "Add New ", $text_domain ),
							'edit_item'             =>  __( "Edit {$single}" , $text_domain ),
							'new_item'              => __( "New {$single}", $text_domain ),
							'all_items' 			=> __( $plural, $text_domain ),
							'view_item'             => __( "View {$single}", $text_domain ),
							'search_items'          => __( "Search {$plural}", $text_domain ),
							'not_found'             => __( "No {$plural} Found", $text_domain ),
							'not_found_in_trash'    => __( "No {$plural} Found in Trash", $text_domain ),
						);
		
		
		// define which arguments are passed in gallery posts type and used.
		$gallery_args = array(
								'labels'                => $gallery_labels,
								'public'                => false,
								'show_ui'               => true,
								'show_in_nav_menus'		=> false,
								'publicly_queryable' 	=> true,
								'capability_type'       => 'post',
								'menu_icon'				=> 'dashicons-format-gallery',
								'menu_position'			=> 52,
								'exclude_from_search' 	=> true,
								'hierarchical' 			=> true,
								'has_archive' 			=> false,
								'supports'              => array( 'title'),
								'query_var'				=> false,							
							);
	
		// register custom post type for gallery
		register_post_type(strtolower( $cpt_name ),$gallery_args);
		
		
		$plural 	 = 'Gallery Category';
		$single 	 = 'Gallery Category';
		$tax_name 	 = 'vsz_cat';
		$text_domain = 'vsz_gallery';
		
		// define label for display in category page and post page
		$labels_gallery = array(
			'name' 				=> _x( $plural , 'taxonomy general name' ),
			'singular_name' 	=> _x( $single , 'taxonomy singular name' ),
			'search_items' 		=> __( "Search {$plural}", $text_domain ),
			'all_items' 		=> __( $plural, $text_domain ),
			'parent_item' 		=> __( "Parent {$single} ", $text_domain ),
			'parent_item_colon' => __( "Parent {$single} ", $text_domain ),
			'edit_item' 		=> __( "Edit {$single}", $text_domain ),
			'update_item' 		=> __( "Update {$single}", $text_domain ),
			'add_new_item' 		=> __( "Add {$single}", $text_domain ),
			'new_item_name' 	=> __( "New {$single}", $text_domain ),
			'menu_name' 		=> __( $plural, $text_domain ),
		);   
		
		// register taxonomies(category) for gallery post
		register_taxonomy(strtolower($tax_name),array(strtolower( $cpt_name )), array(
			'hierarchical' 		=> true,
			'labels' 			=> $labels_gallery,
			'query_var' 		=> false,
			'show_ui' 			=> true,
			'show_in_nav_menus'		=> false,
			'show_admin_column' => true,
		));
	}
	// Add meta boxes in gallery post type add and edit form
	public function add_vszgallery_metabox(){
		$cpt_name = 'vsz_gallery';
		// define meta box on gallery post type
		add_meta_box(
			'gallery_display_field',
			__( 'Gallery Detail', 'vsz_gallery'),
			array($this,'add_vszgallery_display_field_callback'),
			$cpt_name,
			'normal',
			'high'
			);
			
			// define meta box on gallery post type
		add_meta_box(
			'gallery_display_colour_field',
			__( 'Display Detail', 'vsz_gallery'),
			array($this,'add_vszgallery_display_colour_field_callback'),
			$cpt_name,
			'side',
			'low'
			);
	}
	
	//Display content in meta box is here 
	function add_vszgallery_display_field_callback($post){
		$fieldDetail = "addForm";
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/add_vszgallery_display_fields.php';
	}
	
	
	//Display Colour code for front uses
	public function add_vszgallery_display_colour_field_callback($post){
		$fieldDetail = "addForm";
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/add_vszgallery_meta_fields.php';
	}
	
	//save sort order and meta box content value when post is saved 
	public function vszgallery_save_meta_box_data($post_id, $post, $update){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/vszgallery_save_meta_box_data.php';
	}
	
	
	// Add button on gallery listing page
	public function vsz_gallery_display_shortcode_to_views($views){
	
		// $views['shortcode_show'] = '<label style="background-color:yellow;font-size:15px;">You can directly apply this shortcode <b> [vsz_responsive_gallery] </b>to display all posts in page.</label>';
		
		return $views;
	}
	
	// define the manage_posts_extra_tablenav callback 
	function vsz_gallery_add_button_to_views( $which ) { 
		
		// make action magic happen here... 
		if(!is_admin()){
			return;
		}	
		global $current_screen;
		// Not our post type, exit earlier
		if( 'vsz_gallery' != $current_screen->post_type )
			return;
		
		echo '<input type="button" name="sortorder_value"  onclick="saveSortOrder()"   title="Save Sortorder" class="button button-primary" style="float:right;" value="Save Sortorder">';
		
		?><script type="text/javascript">
			////////////// For sortorder functionality
			function saveSortOrder(){
				 jQuery('#posts-filter').attr("method", "post");
				document.getElementById('posts-filter').submit();
			}
		</script><?php
		
	}
	
	// Add custom colums in gallery listing table
	public function add_gallery_customcolumn($columns){
	
		//// updating the columns at listing table
		if(isset($columns['date'])){
			unset($columns['date']);
			//unset($columns['taxonomy-ticket_cat']);
		}
		
		$columns['sortorder'] = __( 'Sortorder' );
		$columns['status'] = __( 'Status' );
		$columns['short_code'] = __( 'Short code' );
		$columns['date'] = __( 'Date');
				
		return $columns;
	}
	
	// Display custom table header value in table 
	public function custom_manage_gallery_columns($column , $post_id){
		//////// display cuystom values for custom columns
		global $post;
		$arrMeta = get_post_meta( $post_id );
		switch( $column ) {
			// For Sortorder column
			case 'sortorder':
				/* Get the post meta. */
				
				$sortOrderValue = '';
				$sortOrderValue = isset($arrMeta['gallery_sortorder'][0]) ? $arrMeta['gallery_sortorder'][0] : '';
					echo '<input type="number" class="tiny-text" min="1" size="2" name="gallery_sortorder['.$post->ID.']" value="'.$sortOrderValue.'" title="Sortorder">';
				break;
			// For status column
			case 'status': 
					$status = isset($arrMeta['gallery_status'][0]) ? $arrMeta['gallery_status'][0] : '';
					?><a href="<?php print admin_url('edit.php?post_type=vsz_gallery');?>&galleryStatusId=<?php echo $post_id;?>&status=<?php echo $status;?>" title="<?php echo ucfirst($status);?>"><?php echo ucfirst($status);?></a><?php
				break;
			// For short code column
			case 'short_code': 
					print '<label>[vsz_responsive_gallery id='.$post_id.']</label>';
				break;
			/* Just break out of the switch statement for everything else. */
			default :
				break;
		}
	}
	
	// Give sorting link on post listing table
	public function vsz_gallery_custom_sortorder_sortable_columns($sortable_columns){
		$sortable_columns[ 'sortorder' ] = 'Sortorder';
		$sortable_columns[ 'title' ] = 'Title';
		$sortable_columns[ 'date' ] = 'Date';
		$sortable_columns[ 'status' ] = 'Status';
		return $sortable_columns;
	}
	
	// Define for sorting  value when custom column sorting request
	public function custom_post_gallery_sortable_columns($vars){
		
		// For set when sorting request for Sortorder column
		if ( isset( $vars['orderby'] ) && 'Sortorder' == $vars['orderby'] && $vars['post_type'] == 'vsz_gallery' ) {
			$sort = $vars['order'] == 'asc' ? 'asc' : 'desc';
			$vars = array_merge( $vars, array(
				'meta_key' => 'gallery_sortorder',
				'orderby' => 'meta_value_num',
				'order'     => $sort
			) );
		
		}
		
		// For set when sorting request for Status column
		if ( isset( $vars['orderby'] ) && 'Status' == $vars['orderby'] && $vars['post_type'] == 'vsz_gallery' ) {
			$sort = $vars['order'] == 'asc' ? 'asc' : 'desc';
			$vars = array_merge( $vars, array(
				'meta_key' => 'gallery_status',
				'orderby' => 'meta_value',
				'order'     => $sort
			) );
		
		}
		
		return $vars; 
	}
	
	
	//Update post related changes on admin screen
	public function vsz_gallery_save_sortorder_value(){
		// For sortorder
		if(isset($_POST['gallery_sortorder']) && !empty($_POST['gallery_sortorder']) && $_POST['post_type'] == 'vsz_gallery' ){
			foreach($_POST['gallery_sortorder'] as $key => $value){
				update_post_meta($key,'gallery_sortorder',$value);
			}
		}
		
		// For status
		if(isset($_GET['galleryStatusId']) && !empty($_GET['galleryStatusId']) && $_GET['post_type'] == 'vsz_gallery' && !empty($_GET['status'])){
			
			if($_GET['status'] == 'active'){
				
				update_post_meta($_GET['galleryStatusId'],'gallery_status','inactive');
			}
			else{
				update_post_meta($_GET['galleryStatusId'],'gallery_status','active');
			}
		}
		
	}
	
	/**************************** Category table related function ******************************/
	
	// for change title of column in category listing page
	public function vsz_gallery_category_add_column( $columns ) {
		
		$columns['shortcode'] = __( 'shortcode' );
		
		return $columns;
	}
	
	
	// for add content in shortcode column
	public function vsz_gallery_category_column_text($out, $column_name, $term_id) {
		$term = get_term($term_id, 'vsz_cat');
		switch ($column_name) {
			case 'shortcode': 
				// get header image url
				//$data = maybe_unserialize($theme->description);
				if(isset($term->slug)){
					$out .= '[vsz_responsive_gallery category="' . $term->slug . '"]'; 
				}
				break;
			
			default:
				break;
		}
		return $out;
	}
	
	function gallery_cat_extra_category_fields($term){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/vszgallery_category_field.php';
	}
	function gallery_cat_save_extra_category_fields($term_id){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/vszgallery_category_save_field.php';
	}
	
	////// Call ajax for checking video exists or not
	function gallery_check_video (){
		require_once plugin_dir_path(__FILE__)."partials/checkVideoExists.php";
	}
	////// Call ajax to get image src
	function vsz_gallery_get_image_src (){
		require_once plugin_dir_path(__FILE__)."partials/getImageSrc.php";
	}

}
