add_shortcode('photos', 'shortcode_photos');
function shortcode_photos($atts){
	ob_start();

	$cat = $atts['cat'];
	?>
	<p>
	<!-- <div id="drama"> -->
	<!-- 	<ul> -->
		<?php
		$stage_width=500;	// How big is the area the images are scattered on
		$stage_height=500;	
		
		global $post;
		$photos = new WP_Query("post_type=gallery&showposts=-1&gallery-category=".$cat);
		$i=1;?>
		<div id="gallery">
	  <?php  //echo "<pre>";print_r($photos);echo "</pre>";
		while($photos->have_posts()): $photos->the_post();	
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
		<!--  <li> -->
		
		<a class="fancybox" rel="fncbx" href="<?php echo $large[0]; ?>" target="_blank" >
		<div id="pic-<?php echo $i++ ;?>" class="pic" style="top:<?php echo $top; ?>px; left:<?php echo $left ;?>px;  -moz-transform:rotate(<?php echo $rot; ?>deg); -webkit-transform:rotate(<?php $rot;?>deg)">
		 
		 
			<?php echo $html; ?>
		
		 </div>
		  </a>
		<!--  </li>  -->
			<?php
		endwhile;
		wp_reset_postdata();
		?>
		</div>
		<!-- </ul> -->
	<!-- </div> -->
	</p>
	<?php 

	$out = ob_get_contents();
	ob_end_clean();

	return $out;
}?>