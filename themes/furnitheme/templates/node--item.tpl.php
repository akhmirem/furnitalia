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
if ($teaser) { //item teaser view

	$classes .= " gallery-item";
	$classes .= ' brand' . ((isset($node->field_brand['und'])) ? $node->field_brand['und'][0]['tid'] : '');

	$additional_attribs = '';
?>

<article class="node-<?php print $node->nid; ?> <?php print $classes; ?> clearfix"<?php print $attributes; ?>>

	<?php	

	hide($content['list_price']);
	hide($content['sell_price']);
	unset($content['field_availability']['#title']);

	$image = $node->field_image['und'][0];

	$image_html = theme('image_style', array(
		'style_name' => 'medium',
		'path' => $image['uri'],
		'alt' => $image['alt'],
	));
	print render($image_html);

	?>

	<div class="item-details">
		<header>
			<h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>" class="title"><?php print $title; ?></a></h2>
		</header>
		
		<?php $content['list_price']['#title'] = "MSRP:";?>
		<?php $content['sell_price']['#title'] = "Special:";?>
		
		<?php print render($content['list_price']); ?>
		<?php print render($content['sell_price']); ?>
		<?php print render($content['field_availability']); ?>

	</div>
	
</article><!-- /.node -->

<?php
} else { //full page view
?>

<?php dsm($content); ?>
<article class="node-<?php print $node->nid; ?> <?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php if ($unpublished): ?>
    <header>
      <?php if ($unpublished): ?>
        <p class="unpublished"><?php print t('Unpublished'); ?></p>
      <?php endif; ?>
    </header>
  <?php endif; ?>

  <?php //krumo($content['field_image']['#items']); ?>
  
  
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
	  
	  <span class="favorites"><a href="#">schematics pdf<img src="<?php print base_path() . path_to_theme(); ?>/images/SubLink_Arrow_Red_6x9.png" class="sublink-arrow"/></a></span>
  	  <span class="favorites"><a href="#">related products<img src="<?php print base_path() . path_to_theme(); ?>/images/SubLink_Arrow_Red_6x9.png" class="sublink-arrow"/></a></span>
	  
  </div>
  <div id="item-info">
	  
	  
	   <h1 class="title" id="page-title"><?php print $node->title; ?></h1>
  		
	  <?php print render($content['model']); ?>
	  <?php print render($content['body']); ?>  
	  
	  <p class="item-info-p">
	  	<?php print render($content['dimensions']);?>
	  </p>
	  
	  <?php if (is_array($content['field_details'])):?>
	  <p class="item-info-p">
		<?php $content['field_details'][0]['#markup'] =  nl2br($content['field_details']['#items'][0]['value']); ?>
	  	<?php print render($content['field_details']);?>
	  </p>
	  <?php endif; ?>
	  
	  <p class="item-info-p">
	  	
	  	Price:<br/>
	  	<?php 
	  	$content['list_price']['#title'] = 'MSRP:';
	  	$content['sell_price']['#title'] = 'Special:';
	  	print render($content['list_price']);
	  	print render($content['sell_price']);
	  	?>
	  </p>
	  
	  <?php if (is_array($content['field_brand']['#object']->field_brand['und'][0]['taxonomy_term']->field_brand_image)) : ?>
	  <?php $brand_image = $content['field_brand']['#object']->field_brand['und'][0]['taxonomy_term']->field_brand_image['und'][0]; ?>
	  <?php print theme("image", array("path" => $brand_image['uri'])); ?>
	  <?php endif; ?>
	  
	  
	  <?php if(is_array($content['field_availability'])) {
	  		$availability_icon = '<img src="' . base_path() . path_to_theme() . '/images/availability-icon.png"/>';
			$content['field_availability'][0]['#markup'] = $availability_icon . $content['field_availability'][0]['#markup'];
	  }
	  ?>
	  
	  <?php print render($content['field_availability']); ?>
	  
	  <p class="item-info-p"><?php print render($content['add_to_cart']);?></p>	  
	  
	  <a href="/request" id="request-quote" title="Request quote/info">Request quote/info</a>
	  
  </div>


</article><!-- /.node -->

<?php } ?>
