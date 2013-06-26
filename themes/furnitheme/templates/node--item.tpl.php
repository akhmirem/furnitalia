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
	//if ($node->nid % 2 == 0) $additional_attribs .= " data-clearance=\"true\"";
?>

<article class="node-<?php print $node->nid; ?> <?php print $classes; ?> clearfix"<?php print $attributes; ?>>


	<?php

	hide($content['list_price']);
	hide($content['sell_price']);

	$image = $node->field_image['und'][0];

	$image_html = theme('image_style', array(
		'style_name' => 'medium',
		'path' => $image['uri'], //file_build_uri($image['filename']),
		'alt' => $image['alt'],
	));
	print render($image_html);

	?>

	<div class="item-details">
		<header>
			<h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>" class="title"><?php print $title; ?></a></h2>
		</header>
		
		<?php print render($content['list_price']); ?>
		<?php print render($content['sell_price']); ?>

	</div>
	
</article><!-- /.node -->

<?php
} else { //full page view
?>

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
  </div>
  <div id="item-info">
	  <?php print render($content['model']); ?>
	  <?php print render($content['body']); ?>
	  
	  <p class="item-info-p">Dimensions:<br/><?php print render($content['dimensions']);?></p>
	  <p class="item-info-p">Details:<br/><?php print render($content['field_details']);?></p>
	  <p class="item-info-p">
	  	Price:<br/>
	  	MSRP:<?php print render($content['list_price']);?><br/>
	  	SPECIAL:<?php print render($content['sell_price']);?><br/>
	  </p>
	  
	  <p class="item-info-p">Brand:<br/><?php print render($content['field_brand']);?></p>
	  <p class="item-info-p"><?php print render($content['add_to_cart']);?></p>	  
	  
	  <?php print render($content['links']['flag']); ?>
	  
  </div>


</article><!-- /.node -->

<?php } ?>
