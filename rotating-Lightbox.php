<?php
/* 
* Plugin Name: Rotating Lightbox Gallery
* Plugin URI: http://www.wp-themeforest.com
* Description: An Awesome CSS3 Lightbox Gallery With jQuery
* Version: 1.0
* Author: Anil Sharma
* Author URI: http://www.wp-themeforest.com
* Copyright 2011  Anil Kumar Sharma  (email : kaushal83anil@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
define('JS_DIR', WP_PLUGIN_DIR.'/rotation-Lightbox-gallery');
define('JS_URL', WP_PLUGIN_URL.'/rotation-Lightbox-gallery');

//include_once 'shortcode.php';

// Activating plugin
register_activation_hook(__FILE__, 'rotation_activate');
function rotation_activate(){
	add_option('js_width', '750');
	add_option('js_height', '345');
	add_option('js_pause', true);
	add_option('js_paging', true);
	add_option('js_nav', true);
}

/* Slider Post Types */
add_action('init', 'rotation_custom_init');
function rotation_custom_init() 
{
  $labels = array(
	'name' => _x('Gallery', 'post type general name'),
    'singular_name' => _x('Gallery', 'post type singular name'),
    'add_new' => _x('Add New', 'image'),
    'add_new_item' => __('Add New image'),
    'edit_item' => __('Edit image'),
    'new_item' => __('New image'),
    'view_item' => __('View image'),
    'search_items' => __('Search image'),
    'not_found' =>  __('No image found'),
    'not_found_in_trash' => __('No image found in Trash'), 
    'parent_item_colon' => '',
    'menu_name' => 'Gallery'
  );
  $args = array(
	'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'has_archive' => false, 
    'hierarchical' => false,
    'menu_position' => 20,
    'supports' => array('title','thumbnail')
  ); 
  register_post_type('Gallery',$args);
}

// Load javascripts and css files
if(!is_admin()){
	add_action('wp_print_scripts', 'rotation_load_js');
	function rotation_load_js(){
		wp_enqueue_script('jquery');
		wp_enqueue_script('fancybox', JS_URL.'/fancybox/jquery.fancybox-1.2.6.pack.js');
		wp_enqueue_script('jquery-ui.min', JS_URL.'/js/jquery-ui.min.js');
		wp_enqueue_script('script', JS_URL.'/js/script');
	}

	add_action('wp_print_styles', 'rotation_load_css');
	function rotation_load_css(){
		wp_enqueue_style('fancybox', JS_URL.'/fancybox/jquery.fancybox-1.2.6.css');
		wp_enqueue_style('demo', JS_URL.'/css/demo.css');
	}

add_action('wp_head', 'rotation_head_code');
	function rotation_head_code(){
		$out = "<script>
		jQuery(document).ready(function() {							
				jQuery('a[rel=fncbx]').fancybox({});
			
		});
		</script>";
		echo $out;
	}
}
/*shorcode start*/
function rotation($atts){
	ob_start();
		$stage_width=500;	// How big is the area the images are scattered on
		$stage_height=500;	
		
		global $post;
		$gallery = new WP_Query("post_type=gallery&showposts=-1");
		$i=1;?>
		<div id="gallery">
	  <?php 
		while($gallery->have_posts()): $gallery->the_post();	
		$left=rand(0,$stage_width);
		$top=rand(0,100);
		$rot = rand(-40,40);		
			if($top>$stage_height-130 && $left > $stage_width-230)
										{
											/* Prevent the images from hiding the drop box */
											$top-=120+130;
											$left-=230;
										}
				?>		
		 <?php $post_thumbnail_id = get_post_thumbnail_id( $post_id );
			$html = wp_get_attachment_image( $post_thumbnail_id , 'gallery-thumb');
		    $large = wp_get_attachment_image_src( $post_thumbnail_id, 'large');
		?>	
	
		
		<a class="fancybox" rel="fncbx" href="<?php echo $large[0]; ?>" target="_blank" title="<?php the_title(); ?>" >
		<div id="pic-<?php echo $i++ ;?>" class="pic" style="top:<?php echo $top; ?>px; left:<?php echo $left ;?>px;  -moz-transform:rotate(<?php echo $rot; ?>deg); -webkit-transform:rotate(<?php $rot;?>deg)">
		 
		 
			<?php echo $html; ?>
		
		 </div>
		  </a>
		
			<?php
		endwhile;
		wp_reset_postdata();
		?>
		</div>
	<?php 

	$out = ob_get_contents();
	ob_end_clean();

	return $out;
}
add_shortcode('Gallery', 'rotation');
add_theme_support('post-thumbnails');
add_image_size('gallery-thumb',100,100,true);