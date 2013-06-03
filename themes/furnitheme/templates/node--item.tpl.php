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

	$item_raised = isset($node->index) && ($node->index % 4 == 1 || $node->index % 4 == 0);

	$classes .= " gallery-item";
	$classes .= ' brand' . $node->field_brand['und'][0]['tid'];
	
	if($item_raised) {
		$classes .= ' raised';
	}

		
	$additional_attribs = '';
	if ($node->nid % 2 == 0) $additional_attribs .= " data-clearance=\"true\"";
?>

<article class="node-<?php print $node->nid; ?> <?php print $classes; ?> clearfix"<?php print $attributes; ?>>


	<?php
	//krumo($node);
	//krumo($content);
	hide($content['list_price']);
	hide($content['sell_price']);

	/*$first_image = $node->field_image['und'][0];	
	$image_html = theme('image_style', array(
		'style_name' => 'thumbnail',
		'path' => file_build_uri($first_image['filename']),
		'alt' => $first_image['alt'],
	));
	//print render($image_html);*/
	if (isset($node->index) && ($node->index % 4 == 1 || $node->index % 4 == 0)) {
		//print theme('image', $node->field_thumb_91x114['und'][0]);
		//print render(field_view_field('node', $node, 'field_thumb_91x114'));
		
		$thumb = $node->field_thumb_91x114['und'][0];	
	}
	else{
		//print theme('image',$node->field_thumb_94x78['und'][0]);
		//print render(field_view_field('node', $node, 'field_thumb_94x78'));		
		$thumb = $node->field_thumb_94x78['und'][0];			
	}
	$thumb_path = file_build_uri($thumb['uri']);
	print theme("image", array("path" => $thumb['uri']));
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

  <?php //krumo($content); ?>
  
  <div id="item-images">
	  <?php //print render($content['field_image']);?>
	  <ul id="pikame" >
	  	<?php foreach($content['field_image']['#items'] as $i => $image) :?>
		  	<li><a href="#"><?php print theme("image", array("path" => $image['uri'])); ?></a></li>
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
