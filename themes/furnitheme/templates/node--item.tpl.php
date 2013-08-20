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
	
if ($teaser) { //item teaser view

	$classes .= " gallery-item";
	$classes .= ' brand' . ((isset($node->field_brand['und'])) ? $node->field_brand['und'][0]['tid'] : '');

	$additional_attribs = '';
?>

<article class="node-<?php print $node->nid; ?> <?php print $classes; ?> clearfix"<?php print $attributes; ?>>

	<?php	

	unset($content['field_availability']['#title']);

	$image = $node->field_image['und'][0];

	$image_html = theme('image_style', array(
		'style_name' => 'medium',
		'path' => $image['uri'],
		'alt' => $image['alt'],
	));
	$image_html_rendered = render($image_html);
	
	$image_linked = l($image_html_rendered, "node/" . $node->nid, array('html' => true));
	
	print $image_linked;

	?>

	<div class="item-details">
		<header>
			<h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>" class="title"><?php print $title; ?></a></h2>
		</header>
		
		
		<?php print render($content['list_price']); ?>
		<?php print render($content['sell_price']); ?>
		<?php print render($content['sale_price']); ?>
		<?php print render($content['field_availability']); ?>

	</div>
	
</article><!-- /.node -->

<?php
} else { //full page view
?>

<?php //dsm($content); ?>
<article class="node-<?php print $node->nid; ?> <?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php if ($unpublished): ?>
    <header>
      <?php if ($unpublished): ?>
        <p class="unpublished"><?php print t('Unpublished'); ?></p>
      <?php endif; ?>
    </header>
  <?php endif; ?>
  
  
  <div id="item-images">

	  <ul id="pikame" >
	  	<?php foreach($content['field_image']['#items'] as $i => $image) :?>

	  		<?php 
	  			$style = "large";
	  			$derivative_uri = image_style_path($style, $image['uri']);
	  			if (!file_exists($derivative_uri)) {
	  				$display_style = image_style_load($style);
	  				image_style_create_derivative($display_style, $image['uri'], $derivative_uri);
	  			}
	  			$img_url  = file_create_url($derivative_uri);
	  			
	  			$style = "thumbnail";
	  			$derivative_uri = image_style_path($style, $image['uri']);
	  			if (!file_exists($derivative_uri)) {
	  				$display_style = image_style_load($style);
	  				image_style_create_derivative($display_style, $image['uri'], $derivative_uri);
	  			}
	  			$thumb_url  = file_create_url($derivative_uri);
	  			
	  			$full_img_url = file_create_url($image['uri']);

	  		?>
	  		
		  	<li><a href="<?php print $full_img_url; ?>"><?php print theme("image", array("path" => $thumb_url, "attributes" => array("ref" => $img_url))); ?></a></li>
		  	

	  	<?php endforeach;?>
	  </ul>
	  
	  <span><a href="#" id="zoom-in"><img src="<?php print base_path() . path_to_theme(); ?>/images/Zoom_In_18x20.png"/>Zoom in</a></span>
	  
	  <span class="favorites"><img src="<?php print base_path() . path_to_theme(); ?>/images/Favorites_Star_Icon_14x14.png"/>	  
		  <?php
		  	global $user;
			if(!$user->uid) {
			    print l(t('Add to favorites'), 'user/login' , array('query'=> array('destination' => 'node/' . $node->nid)));
			} else {
				print $content['links']['flag']['#links']['flag-favorites']['title']; //render($content['links']['flag']); 
			}
		  ?>	  
	  </span>
	  
	  <?php if(isset($content['field_product_pdf']) && count($content['field_product_pdf']['#items']) > 0) : ?>
		  <span class="favorites"><a href="<?php print file_create_url($content['field_product_pdf']['#items'][0]['uri']);?>">schematics pdf<img src="<?php print base_path() . path_to_theme(); ?>/images/SubLink_Arrow_Red_6x9.png" class="sublink-arrow"/></a></span>
	  <?php endif; ?>

  	  <form action="" id="share-form">
 		  <label for="share">SHARE:</label>	
	  	  <select id="share-this" name ="share">
	  	  	<option value="default" >Select an option</option>
	  	  	<option value="facebook">Facebook</option>
	  	  	<option value="twitter">Twitter</option>
	  	  	<option value="plusone">Google+</option>
	  	  	<option value="email">E-mail</option>	  	  	
	  	  </select>
	  	  <input type="hidden" name="data-url" value="<?php print $base_url . "/" . $node_url ?>" />
 	  	  <input type="hidden" name="data-title" value="Check out &quot;<?php print $title ?>&quot; at Furnitalia" />
  	  </form>
	  
  </div>
  <div id="item-info">
	  
		<h1 class="title" id="page-title"><?php print $node->title; ?></h1>
		<?php $content['field_alu']['#title'] = 'Model'; ?>
		<?php print render($content['field_alu']); ?>
		<?php print render($content['body']); ?>  
	  
	  <p class="item-info-p">
	  	<?php print render($content['dimensions']);?>
	  </p>
	  
	  <?php if (isset($content['field_details']) && is_array($content['field_details'])):?>
		  <p class="item-info-p">
			<?php $content['field_details'][0]['#markup'] =  nl2br($content['field_details']['#items'][0]['value']); ?>
		  	<?php print render($content['field_details']);?>
		  </p>
	  <?php endif; ?>
	 
	  
	  <p class="item-info-p">
	  	
	  	Price:<br/>
	  	
	  	<?php 
	  	print render($content['list_price']);
	  	print render($content['sell_price']);
	  	print render($content['sale_price']);
	  	
	  	?>
	  </p>

	  <?php if (is_array($content['field_brand']['#object']->field_brand['und'][0]['taxonomy_term']->field_brand_image)) : ?>
		  <?php $brand_image = $content['field_brand']['#object']->field_brand['und'][0]['taxonomy_term']->field_brand_image['und'][0]; ?>
		  <?php print theme("image", array("path" => $brand_image['uri'])); ?>
	  <?php endif; ?>
	  
	  <?php if (isset($content['field_availability'])) : ?>
		  <?php print render($content['field_availability']); ?>
	  <?php endif; ?>
	  
	  <?php if (isset($content['add_to_cart'])) : ?>
		  <p class="item-info-p"><?php print render($content['add_to_cart']);?></p>	  
	  <?php endif;?>
	  
	  <?php print l("Request info", "request/$node->nid/ajax", array('attributes' => array('id' => 'request-quote'))); ?>
	  
  </div>
  
 

</article><!-- /.node -->

 <hr /><br />


<?php } ?>
