<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
	$arrMeta = get_post_meta($post->ID);
	
	?><!-- Meta fields html start here -->
	<div>
		<label for="Layout"><?php _e('Layout'); ?></label>  
		<select id="layout" name="gallery_template_layout" style="display:block;width:100%;">
			<option value="default" <?php if(isset($arrMeta['gallery_template_layout']) && !empty($arrMeta['gallery_template_layout'][0]) && $arrMeta['gallery_template_layout'][0] == 'default' ){ ?>selected="selected" <?php   } ?>>Default</option>
			<option value="masonry" <?php if(isset($arrMeta['gallery_template_layout']) && !empty($arrMeta['gallery_template_layout'][0]) && $arrMeta['gallery_template_layout'][0] == 'masonry' ){ ?>selected="selected" <?php   } ?>>Masonry</option>
			<option value="mosaic" <?php if(isset($arrMeta['gallery_template_layout']) && !empty($arrMeta['gallery_template_layout'][0]) && $arrMeta['gallery_template_layout'][0] == 'mosaic' ){ ?>selected="selected" <?php   } ?>>Mosaic</option>

		</select>	
	</div>
	<div class="shortcode-display">
		<label for="Layout"><?php _e('Short code'); ?></label><?php
		if(isset($arrMeta['gallery_template_layout']) && !empty($arrMeta['gallery_template_layout'][0]) && $arrMeta['gallery_template_layout'][0] == 'masonry' ){
			?><input type="text" value="[vsz_responsive_gallery id=<?php echo $post->ID;?> layout=masonry]" /><?php
		}
		else{
			?><input type="text" value="[vsz_responsive_gallery id=<?php echo $post->ID;?>]" /><?php
		}
	?></div>
	<div>
		<label for="gallery_load_more"><?php _e('Load More'); ?></label>  
		<select name="gallery_load_more" id="gallery_load_more" style="display:block;width:100%;">
			<option value="yes" <?php if(isset($arrMeta['gallery_load_more']) && $arrMeta['gallery_load_more'][0] == 'yes') { ?>selected="selected"<?php  } ?> ><?php _e('Yes'); ?></option>
			<option value="no" <?php if(isset($arrMeta['gallery_load_more']) && $arrMeta['gallery_load_more'][0] == 'no') { ?>selected="selected"<?php  } ?>><?php _e('No'); ?></option>
		</select>
		<br />  
	</div>
	<div>
		<label for="gallery_load_more_item"><?php _e('Load More Items'); ?></label>  
		<input type="number" min="2" step="1" class="tiny-text" size="3" name="gallery_load_more_item" id="gallery_load_more_item" value="<?php print((isset($arrMeta['gallery_load_more_item']) && !empty($arrMeta['gallery_load_more_item'][0])) ? $arrMeta['gallery_load_more_item'][0] : '10');?>" />
		<br />  
	</div>	
	<div>
		<label for="gallery_border_colour"><?php _e('Border colour'); ?></label>  
		<input name="gallery_border_colour" id="gallery_border_colour" size="25" style="width:60%;" class="jscolor" value="<?php print((isset($arrMeta['gallery_border_colour']) && !empty($arrMeta['gallery_border_colour'][0])) ? $arrMeta['gallery_border_colour'][0] : '000000');?>" />
		<br />  
	</div>
	<div>
		<label for="gallery_hover_colour"><?php _e('Hover colour'); ?></label>  
		<input name="gallery_hover_colour" id="gallery_hover_colour" size="25" style="width:60%;" class="jscolor" value="<?php print((isset($arrMeta['gallery_hover_colour']) && !empty($arrMeta['gallery_hover_colour'][0])) ? $arrMeta['gallery_hover_colour'][0] : '0085ba');?>" />
		<br />  
	</div>
	<div>
		<label for=""><?php _e('Thumb dimension:'); ?></label> <br/> 
		<label for="gallery_img_width"><?php _e('Width'); ?></label>  
		<input type="number" min="300" max="2000"  step="1" class="tiny-text" size="3" name="gallery_img_width" id="gallery_img_width" value="<?php print((isset($arrMeta['gallery_img_width']) && !empty($arrMeta['gallery_img_width'][0])) ? $arrMeta['gallery_img_width'][0] : '500');?>" />
		<label for="gallery_img_height"><?php _e('Height'); ?></label>  
		<input type="number" min="300" max="2000" step="1" class="tiny-text" size="3" name="gallery_img_height" id="gallery_img_height" value="<?php print((isset($arrMeta['gallery_img_height']) && !empty($arrMeta['gallery_img_height'][0])) ? $arrMeta['gallery_img_height'][0] : '500');?>" />
		<br/>
		<label for="gallery_num_cols"><?php _e('Number of Columns'); ?></label>  
		<input type="number" min="2" max="5" step="1" class="tiny-text" size="3" name="gallery_num_cols" id="gallery_num_cols" value="<?php print((isset($arrMeta['gallery_num_cols']) && !empty($arrMeta['gallery_num_cols'][0])) ? $arrMeta['gallery_num_cols'][0] : '3');?>" />
		<br/>
	</div>
	<!-- Meta fields html ends here -->
	<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('.shortcode-display input').focus(function(){
			jQuery(this).select();
		});
		
		/*
		** This function is to change the short code value as per layout selected
		*/
		jQuery('#layout').change(function(){
			var layout=jQuery(this).val();
			if(layout != 'default'){
				jQuery('.shortcode-display input').val('[vsz_responsive_gallery id=<?php echo $post->ID;?> layout='+layout+']');
			}
			else{
				jQuery('.shortcode-display input').val('[vsz_responsive_gallery id=<?php echo $post->ID;?>]');
			}
		});
	});
	</script>