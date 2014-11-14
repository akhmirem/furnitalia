<?php
/**
 * @file
 * Zen theme's implementation to display a node.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see zen_preprocess_node()
 * @see template_process()
 */
?>

<?php

global $base_path, $base_url, $theme_path;

if (isset($content['field_availability']) && is_array($content['field_availability'])) {
	$availability_icon = '<img src="' . base_path() . path_to_theme() . '/images/availability-icon.png"/>';
	$content['field_availability'][0]['#markup'] = $availability_icon . $content['field_availability'][0]['#markup'];
}

$pdf_icon = '<img src="' . $base_path . $theme_path . '/images/icons_logos/pdf-icon.png" />';

	
if ($teaser) { //item teaser view

	$classes .= " gallery-item";
	$classes .= ' brand' . ((isset($node->field_brand['und'])) ? $node->field_brand['und'][0]['tid'] : '');

	$additional_attribs = '';
?>

<article class="node-<?php print $node->nid; ?> <?php print $classes; ?> clearfix"<?php print $attributes; ?>>

	<?php	

	unset($content['field_availability']['#title']);

	$image = $node->field_image['und'][0];
	if(isset($image['uri']) && !empty($image['uri'])) {
		$image_html = theme('image_style', array(
			'style_name' => 'mobile_gallery_thumb',
			'path' => $image['uri'],
			'alt' => $image['alt'],
		));
		$image_html_rendered = render($image_html);
		
		$image_linked = l($image_html_rendered, "node/" . $node->nid, array('html' => true));
	
		print $image_linked;
	}

	?>

	<header>
		<h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>" class="title furn-red furn-ucase"><?php print $title; ?></a></h2>
	</header>
	
	<?php if(isset($content['sale_price']) && !empty($content['sale_price'])) : ?>
		<!--<span class="item-clearance"><img src="<?php print base_path() . path_to_theme(); ?>/images/Sale_Icon_Red_40x24.png" alt="Item on clearance"/></span>	-->
	<?php endif; ?>
	
	<div class="item-details">
		
		<?php //print render($content['list_price']); ?>
		<?php print render($content['sell_price']); ?>
		<?php print render($content['sale_price']); ?>

	</div>
	
</article><!-- /.node -->

<?php
} else { //full page view
?>

<?php //dsm($content); ?>
<article class="node-<?php print $node->nid; ?> <?php print $classes; ?> clearfix"<?php print $attributes; ?>>
  
  
  <div id="item-images">
	  
	 <header id="product-header">
		 <h1 class="title furn-ucase furn-red furn-e2-a" id="page-title"><?php print $node->title; ?></h1>
		 <?php $content['field_alu']['#title'] = 'Model'; ?>
		 <?php print render($content['field_alu']); ?>
		 <?php print render($content['model']); ?>
	 </header>
	  
	  <div id="image-gallery" class="mightyslider_modern_skin horizontal">
		<div class="frame" data-mightyslider="width:320,height:240">
			<div class="slide_element">
				<?php foreach($content['field_image']['#items'] as $i => $image) :?>

			  		<?php 
			  			$style = "mobile_large";
			  			$derivative_uri = image_style_path($style, $image['uri']);
			  			if (!file_exists($derivative_uri)) {
			  				$display_style = image_style_load($style);
			  				image_style_create_derivative($display_style, $image['uri'], $derivative_uri);
			  			}
			  			$img_url  = file_create_url($derivative_uri);
			  			
			  			$style = "mobile_thumb";
			  			$derivative_uri = image_style_path($style, $image['uri']);
			  			if (!file_exists($derivative_uri)) {
			  				$display_style = image_style_load($style);
			  				image_style_create_derivative($display_style, $image['uri'], $derivative_uri);
			  			}
			  			$thumb_url  = file_create_url($derivative_uri);
			  			
			  			$full_img_url = file_create_url($image['uri']);
		
			  		?>
			  	
					<div class="slide" data-mightyslider="cover: '<?php print $img_url; ?>', link: { url: '<?php print $full_img_url; ?>', thumbnail: '<?php print $thumb_url; ?>', target: '_blank' }, thumbnail: '<?php print $thumb_url; ?>' ">
						<!--<div class="mSCaption infoBlock infoBlockLeftBlack" data-msanimation="{ speed: 700, easing: 'easeOutQuint', style: { left: 30, opacity: 1 } }">
							<h4>This is an animated block, add any number of them to any type of slide</h4>
							<p>Put completely anything inside - text, images, inputs, links, buttons.</p>
						</div>-->
					</div>
					
				<?php endforeach; ?>
			</div>
		</div>
		<ul class="gal-pager"><li></li></ul>
	  </div>
	  <div id="thumbs-wrapper" ><ul id="thumbs"></ul></div>
  </div>
	  
  <section id="product-info">
	  <section id="pricing">
		  <div class="item-info-p">
		  	
		  	<label class="furn-red furn-ucase">Price:</label>
		  	<?php print render($content['sell_price']); ?>
		  	
		  	<?php print render($content['sale_price']); ?>
		  </div>
		  
		  <?php if (isset($content['add_to_cart'])) : ?>
			  <p class="item-info-p"><?php print render($content['add_to_cart']);?></p>	  
		  <?php endif;?>
		  
		  <?php print l("Request info", "request/$node->nid/ajax", array('attributes' => array('id' => 'request-quote', 'class' => array('furn-button-text')))); ?>
	  </section>
	  
	  <div id="item-brand">
	  	<?php if (isset($content['field_availability'])) : ?>
				<?php print render($content['field_availability']); ?>
		  <?php endif; ?>
			
		  <?php 
		  if (is_array($content['field_brand']['#object']->field_brand['und'][0]['taxonomy_term']->field_brand_image)) {
		  	$brand_image = $content['field_brand']['#object']->field_brand['und'][0]['taxonomy_term']->field_brand_image['und'][0];
	
		  	$style = "mobile_brand_img";
        $derivative_uri = image_style_path($style, $brand_image['uri']);
        if (!file_exists($derivative_uri)) {
				  $display_style = image_style_load($style);
          image_style_create_derivative($display_style, $brand_image['uri'], $derivative_uri);
        }
        $brand_img_url  = file_create_url($derivative_uri);
        print theme("image", array("path" => $derivative_uri, 'attributes' => array("class" => array("brand-img"))));
		  }
		  ?>
	  </div>
	  
	  <div id="product-additional" class="accordion clearfix">
	  	<h3 class="furn-ucase furn-red">Description</h3>
	  	<div> 
	  		<?php print render($content['body']); ?>
	  		<?php print render($content['dimensions']);?>
	  		<?php if (isset($content['field_details']) && is_array($content['field_details'])):?>
				<p class="item-info-p">
					<?php $content['field_details'][0]['#markup'] =  nl2br($content['field_details']['#items'][0]['value']); ?>
					<?php print render($content['field_details']);?>
				</p>
			<?php endif; ?>

	  	</div>
	  	
	  	<h3 class="furn-ucase furn-red">Schematics PDF</h3>
	  	<div>
	  		 <?php if(isset($content['field_product_pdf']) && count($content['field_product_pdf']['#items']) > 0) : ?>
	  		 	<span class="favorites">
	  		 		<a href="<?php print file_create_url($content['field_product_pdf']['#items'][0]['uri']);?>"><?php print $pdf_icon; ?> VIEW SCHEMATICS </a>
	  		 	</span>
	  <?php endif; ?>
	  	</div>
	  	
	  </div>
	  
	  

	    
	  <span class="favorites"><img src="<?php print base_path() . path_to_theme(); ?>/images/icons_logos/favorites_star_icon_16x16.png"/>	  
		  <?php
		  	global $user;
  			if(!$user->uid) {
  			    print l(t('Add to favorites'), 'user/login' , array('query'=> array('destination' => 'node/' . $node->nid)));
  			} else {
  				print $content['links']['flag']['#links']['flag-favorites']['title']; //render($content['links']['flag']); 
  			}
		  ?>	  
	  </span>
	
	  <?php print render($content['sharethis']); ?>  
	  	  
	  <div id="scroll-top"><img src="<?php print $base_path . $theme_path?>/images/icons_logos/link_arrow_previous_6x9.png"/>&nbsp;<a href="#" class="furn-grey">BACK TO TOP</a></div>		

  </section>


  <?php if ($content['has_video']): ?>
<!-- 	  <div id="hidden-video"> -->
	  	  <!-- This is a hidden container for item video, its contents will appear in popup box -->
<!-- 		  <?php print render($content['field_video']); ?>--> 
<!-- 	  </div> -->
  <?php endif; ?>
 
 <?php //print render($content); ?>

</article><!-- /.node -->


<?php } ?>
