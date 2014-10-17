<?php

/**
*  * Override or insert variables into the page templates.
*   *
*    * @param $variables
*     *   An array of variables to pass to the theme template.
*      * @param $hook
*       *   The name of the template being rendered ("page" in this case.)
*        */
//!PreprocessPage
function furnimobile_preprocess_page(&$vars) {

    global $user;
    
    drupal_add_library('system', 'ui.accordion');

    drupal_add_library('system', 'drupal.ajax');	    
   	drupal_add_js(drupal_get_path('module', 'webform_ajax') . '/js/webform_ajax.js', 'file');
   	drupal_add_css(drupal_get_path("theme", "furnimobile"). "/lib/fancybox/jquery.fancybox.css");
   	drupal_add_js(drupal_get_path("theme", "furnimobile"). "/lib/fancybox/jquery.fancybox.pack.js");
	
	//set up top menu
	furnimobile_set_up_top_menu($vars);	
	
	//navigation menu items
	furnimobile_set_up_footer_menu($vars);
	
	$italia_editions_gallery_paths = array("natuzzi-italia", "natuzzi-italia/*", "natuzzi-italia/*/*", "natuzzi-editions", "natuzzi-editions/*", "natuzzi-editions/*/*");
	$paths_no_title = array_merge(array("<front>", "node/*", "sale", "collections", "taxonomy/term/*", "moving-sale"), $italia_editions_gallery_paths);
	$vars['show_title'] = !drupal_match_path(current_path(), implode("\n", $paths_no_title));
	
	if(drupal_match_path(current_path(), implode("\n", array("<front>", "front", "node/*")))) {
		_page_include_mightyslider_resources();
	}
	
	//!BANNER
	if (variable_get('show_banner', FALSE)) {
		if (drupal_match_path(current_path(), implode("\n", array_merge(array('node/*', 'collections', 'taxonomy/term/*'), $italia_editions_gallery_paths)))) {
			$files_dir = base_path() . variable_get('file_public_path', conf_path() . '/files'); 
			$banner_img_path = $files_dir . "/promo/oct2014/320x140_MobileSecondary.jpg";
      $banner_html = l('<img src="' . $banner_img_path . '"/>', 
        "living", 
        array(
          'html' => TRUE, 
          'attributes' => array(
            'class' => array('banner-link')
          ), 
          'query' => array(
            'availability' => 'in_stock'
          )
        )
      ); //'query' => array('utm_source' => 'mobile', 'utm_medium' => 'banner', 'utm_campaign' => 'moving-sale'
			$vars['page']['banner'] = array(
				'#markup' => $banner_html,
			);
		}
	}
	
	if (isset($vars['node']) && arg(2) != 'edit') {
		$node = $vars['node'];
		if ($node->type == 'item') {
			
			_page_include_mightyslider_resources();
		
			$content = $vars['page']['content']['system_main']['nodes'][$node->nid];

			preprocess_node_common_fields($content, 'page');
						
			$vars['page']['content']['system_main']['nodes'][$node->nid] = $content;			
				
		} else if ($node->type == 'blog') {
			$vars['show_title'] = TRUE;
		}
	}
	
	if (arg(0) == "search") {
		$vars['page']['highlighted']['#access'] = FALSE;
	}
	
	if (arg(0) == 'node' && arg(1) == '33') {
		//if we are on newsletter subscription page, don't display subscription block
		$vars['page']['footer']['webform_client-block-33'] = "";
	}
	
	_page_set_breadcrumbs($vars);
	

}

function furnimobile_webform_mail_headers($variables) {
  $headers = array(
    'Content-Type'  => 'text/html; charset=UTF-8; format=flowed; delsp=yes',
    'X-Mailer'      => 'Drupal Webform (PHP/'. phpversion() .')',
  );
  return $headers;
}

function furnimobile_set_up_footer_menu (&$vars) {
	$attrs = array('attributes' => array('class'=>array('furn-grey')));
		
	$info_menu = array(
		'<span class="menu-label">About Furnitalia</span>',
		l("About us", 'about', $attrs),
		l("Contact us", "contact", $attrs), 
		l("Blog", "blog", $attrs),
	);
	$vars['footer_info_menu'] = array(
		'#theme' => 'item_list',
		'#items' => array_values($info_menu),
		'#type' => 'ul',
		'#attributes' => array('class' => 'links'),
	);
	
	$user_menu = array();	
	$user_menu []= '<span class="menu-label">Account information</span>';
	$user_menu []= l("Account Details", 'user', $attrs);
	$user_menu []= l("Favorites", 'my-favorites', $attrs);		
	$user_menu []= l("Order status", 'my-orders', $attrs);		
	$user_menu []= l("My Cart", 'cart', $attrs);
	
	$vars['footer_user_menu'] = array(
		'#theme' => 'item_list',
		'#items' => array_values($user_menu),
		'#type' => 'ul',
		'#attributes' => array('class' => 'links'),
	);
	
	$policies_menu = array(
		'<span class="menu-label">Policies and information</span>',
		l("FAQ", 'faq', $attrs),
		l("Shipping and Delivery", "shipping-deliveries", $attrs), 
		l("Terms of Service", "service-terms", $attrs),
		l("Privacy Policy", "privacy-policy", $attrs),	
	);
	$vars['footer_policy_menu'] = array(
		'#theme' => 'item_list',
		'#items' => array_values($policies_menu),
		'#type' => 'ul',
		'#attributes' => array('class' => 'links'),
	);
}


function furnimobile_set_up_top_menu (&$vars) {
	global $user;
	
	$top_menu = array();
	
	/*if ($user->uid == 0) {	
		$top_menu []= l("Sign in", 'user/login');
		$top_menu []= l("Register", 'user/register');
	} else {
		$user_fields = user_load($user->uid);
		$welcome_msg = '<span class="welcome">Welcome, <span class="welcome-user">' . $user_fields->field_first_name['und'][0]['value'] . '</span></span>';
		$top_menu [] = $welcome_msg;
		$top_menu []= l("Sign out", 'user/logout');	
	}*/

	//$top_menu []= "<a href=\"#\" title=\"menu\" id=\"menu-toggle\">MENU</a>";
	$attrs = array('attributes' => array('class'=>array('furn-grey')));
	$top_menu []= l("Stores", 'contact', $attrs);
	$top_menu []= l("Favorites", 'my-favorites', $attrs);
	$top_menu []= l("My Cart", 'cart', $attrs);
	
	
	$vars['page']['page_top']['top_menu'] = array(
		'#theme' => 'item_list',
		'#items' => array_values($top_menu),
		'#type' => 'ul',
		'#attributes' => array('class' => 'menu furn-ucase'),
	);
}

function preprocess_node_common_fields(&$content, $hook) {
	

	//!TEMPORARILY FORCE REMOVE ADD TO CART BUTTON
	//if (!empty($content['field_show_add_to_cart']) && $content['field_show_add_to_cart']['#items'][0]['value'] == '0') {
		unset($content['add_to_cart']);
	//}
	
	//for collection items, disable list price, sale price, change label for price
	if (isset($content['field_starting_from_price']) && $content['field_starting_from_price']['#items'][0]['value']) {
		unset($content['list_price']);
		$content['sell_price']['#title'] = "FROM:";
		unset($content['field_special_price']);
		
		return;
	}

	unset($content['sell_price']['#title']);
	
	$epsilon = 0.01;
	$sell_price = isset($content['sell_price']['#value']) ? $content['sell_price']['#value'] : $content['sell_price'];
	
	unset($content['list_price']);

	
	$sale_price_set = FALSE;
	$special_price = NULL;
	
	/*
	MOVING SALE
	-----------------------------------
	$excelsior = 27;
	$bdi = 29;
	$nicolle_miller = 28;

	$sale_price_set = TRUE; //<--moving sale
	if (isset($content['field_brand']['#items'])) {
		$brand = (int) $content['field_brand']['#items'][0]['tid'];
	} else {
		$brand = (int) $content['field_brand']['und'][0]['tid'];
	}
	if ($brand == $excelsior || $brand == $bdi || $brand == $nicolle_miller){
		$sale_price_set = FALSE; //no sale price
	}
	-----------------------------------
	*/
	
	if(furn_global_show_sale_price($content)) {

		 if(!empty($content['field_special_price']) && isset($content['field_special_price']['#items']) && $content['field_special_price']['#items'][0]['value']) {
		 	$special_price = $content['field_special_price']['#items'][0]['value'];
		 	$diff = abs(floatval($special_price) - floatval($sell_price));
		 	if ($diff > $epsilon) {
			 	$sale_price_set = TRUE; //sell price and special price are not same
			}
		 }
	}
		
	if($sale_price_set) {
	
		//don't display MSRP:
		unset($content['list_price']);
		
		$new_sale_price = array(		
			'#title' => "SPECIAL:",
			'#theme' => "uc_product_price",
			'#value' => $special_price,
			'#attributes' => array('class' => array('sell-price')),		
		);
		
		/*
		MOVING SALE PRICE
		--------------------
		$content['sale_price'] =  array( //$new_sale_price;
			'#markup' => '<span class="promo-price"><a href="#" class="furn-red promo-link" style="font-weight:400; font-size:1.2em; text-decoration:underline;">PROMO!</a></span>',
		);*/
		
		$content['sale_price'] =  $new_sale_price;
		
		$content['sell_price']['#attributes']['class'] = array('old-price');
	  	
	} else {
		$content['sell_price']['#attributes']['class'][] = 'furn-red';
	}
	
}


/**
 * Override or insert variables into the node templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("node" in this case.)
 */
function furnimobile_preprocess_node(&$vars, $hook) {
	if ($vars['type'] == 'item') {
		preprocess_node_common_fields($vars['content'], 'page');	
	}
}

/**
 * Implements hook_form_alter().
 *
 * Alter search form
 */
function furnimobile_form_webform_client_form_33_alter(&$form, &$form_state, $form_id) {
	
	global $base_path, $theme_path;
	
	$path = $base_path . $theme_path;
	
	$form['actions']['submit']['#theme_wrappers'] = array("image_button");
	$form['actions']['submit']['#button_type'] = "image";
	$form['actions']['submit']['#src'] = $path . "/images/buttons/JOIN_Button_50x29.png";
}


/**
 * Implements hook_form_alter().
 *
 * Alter search form
 */
function furnimobile_form_search_form_alter(&$form, &$form_state, $form_id) {

	global $base_path, $theme_path;
	
	$path = $base_path . $theme_path;
	
	unset($form['advanced']);

	
	$form['basic']['submit']['#theme_wrappers'] = array("image_button");
	$form['basic']['submit']['#button_type'] = "image";
	$form['basic']['submit']['#src'] = $path . "/images/buttons/GO_Button_50x29.png";

}

/**
 * Implements hook_form_alter().
 *
 * Alter search form
 */
function furnimobile_form_search_block_form_alter(&$form, &$form_state, $form_id) {
	
	global $base_path, $theme_path;
	
	$path = $base_path . $theme_path;
	
	$form['search_block_form']['#default_value'] = "SEARCH";
	
	$form['actions']['submit']['#theme_wrappers'] = array("image_button");
	$form['actions']['submit']['#button_type'] = "image";
	$form['actions']['submit']['#src'] = $path . "/images/buttons/GO_Button_50x29.png";

}

/**
 * Implements hook_form_alter().
 *
 * Alter exposed filter form in views
 */
function furnimobile_form_views_exposed_form_alter(&$form, &$form_state, $form_id) {
  drupal_add_css(drupal_get_path('theme', 'furnimobile') . "/lib/dropkick/dropkick.css");
  drupal_add_js (drupal_get_path('theme', 'furnimobile')  . "/lib/dropkick/jquery.dropkick-1.0.0.js");
	
  if ($form['#id'] == 'views-exposed-form-taxonomy-term-page') { 
		
		 
    // prevent recursion - only execute once
    static $called = 0;
    if ($called === $form['#id']) {
      return;
    }
    $called = $form['#id']; // flag as called
    
    // get view results
    $view = $form_state['view']; //views_get_current_view();
    
    $temp_view = $view->clone_view(); // create a temp view
    $temp_view->init_display();
    $temp_view->pre_execute();
    $temp_view->set_items_per_page(0); // we want results from all pages
    $temp_view->display_handler->has_exposed = 0; // prevent recursion
    $temp_view->execute();
    $results = $temp_view->result;
    
    // return if no results
    if (!$results) {
      return;
    }
    
    // assemble results into a comma-separated nid list
    foreach($results as $row) {
      $nids[] = $row->nid;
    }
   
    // get the list of used terms
    $used_tids = db_query("SELECT GROUP_CONCAT(DISTINCT CAST (br.field_brand_tid AS CHAR) SEPARATOR ',') FROM {field_data_field_brand} br WHERE br.entity_id IN (:nids)", array(":nids" => $nids))->fetchField();
    
    
    if ($used_tids) {
    	$used_tids = explode(',', $used_tids);
    } else {
    	$used_tids = array(); // this shoudln't happen, but just in case...
    }	   
	    
		foreach($form['brand']['#options'] as $key => $option) {
			// unset the unused term options
			if ($key !== 'All' && !in_array($key, $used_tids)) {
				unset($form['brand']['#options'][$key]);
			}
		}
		
		if (isset($form['availability'])) {
					   
		  // get the list of used terms
	    $used_tids = db_query("SELECT GROUP_CONCAT(DISTINCT CAST (br.field_availability_value AS CHAR) SEPARATOR ',') FROM {field_data_field_availability} br WHERE br.entity_id IN (:nids)", array(":nids" => $nids))->fetchField();
	    
	    if ($used_tids) {
	      $used_tids = explode(',', $used_tids);
	    } else {
	      $used_tids = array(); // this shoudln't happen, but just in case...
	    }
	    
			foreach($form['availability']['#options'] as $key => $option) {
				// unset the unused term options
				if ($key !== 'All' && !in_array($key, $used_tids)) {
					unset($form['availability']['#options'][$key]);
				}
			}			
		}
		
		//set default sort order
		if(!isset($_REQUEST['sort_by'])) {
		  if (isset($form['sort_by'])) {
  		  $form['sort_by']['#value'] = 'title';
  		}
    }
        
  } else if (in_array($form['#id'], array('views-exposed-form-taxonomy-term-page-brands', 'views-exposed-form-taxonomy-term-page-in-store', 'views-exposed-form-taxonomy-term-page-italia', 'views-exposed-form-taxonomy-term-page-editions'))) {
	
    // prevent recursion - only execute once
    static $called_brand = 0;
    if ($called_brand === $form['#id']) {
      return;
    }		    
    $called_brand = $form['#id']; 				  // flag as called

	// get view results
    $view = $form_state['view']; 				  // views_get_current_view();
    
	$temp_view = $view->clone_view(); 			  // create a temp view
    $temp_view->init_display();
    
    if ($form['#id'] == 'views-exposed-form-taxonomy-term-page-in-store') {
    	$temp_view->set_display('page_in_store');
    } else {
	    $temp_view->set_display('page_brands');
    }
    
    $temp_view->pre_execute();
    $temp_view->set_items_per_page(0); 			  // we want results from all pages
    $temp_view->display_handler->has_exposed = 0; // prevent recursion
    $temp_view->execute();
    $results = $temp_view->result;
    
    // return if no results
    if (!$results) {
    	return;
    }
    	    		    
    // assemble results into a comma-separated nid list
    foreach($results as $row) {
    	$nids[] = $row->nid;
    }
		
		if (isset($form['category'])) {			
		   
	    // get the list of used terms
	    $used_tids = db_query("SELECT GROUP_CONCAT(DISTINCT CAST (CASE WHEN h.parent=0 THEN br.field_category_tid ELSE h.parent END AS CHAR) SEPARATOR ',') FROM {field_data_field_category} br INNER JOIN {taxonomy_term_hierarchy} h on h.tid=br.field_category_tid WHERE br.entity_id IN (:nids)", array(":nids" => $nids))->fetchField();
	    
	    		    
	    if ($used_tids) {
	      $used_tids = explode(',', $used_tids);
	    } else {
	      $used_tids = array(); // this shoudln't happen, but just in case...
	    }
		    
			foreach($form['category']['#options'] as $key => $option) {
				// unset the unused term options
				if ($key !== 'All' && !in_array($key, $used_tids)) {
					unset($form['category']['#options'][$key]);
				}
			}
			
		}
		
		if (isset($form['availability'])) {
					   
	    // get the list of used terms
	    $used_tids = db_query("SELECT GROUP_CONCAT(DISTINCT CAST (br.field_availability_value AS CHAR) SEPARATOR ',') FROM {field_data_field_availability} br WHERE br.entity_id IN (:nids)", array(":nids" => $nids))->fetchField();
	    
	    if ($used_tids) {
	      $used_tids = explode(',', $used_tids);
	    } else {
	      $used_tids = array(); // this shoudln't happen, but just in case...
	    }
		    
			foreach($form['availability']['#options'] as $key => $option) {
				// unset the unused term options
				if ($key !== 'All' && !in_array($key, $used_tids)) {
					unset($form['availability']['#options'][$key]);
				}
			}
		}
		
		//set default sort order
		if(!isset($_REQUEST['sort_by'])) {
		  if (isset($form['sort_by'])) {
  		  $form['sort_by']['#value'] = 'title';
  		}
    }

	}
	
	if (strstr($form['#id'], 'views-exposed-form-taxonomy-term-page')) {
		if (isset($form['sort_by'])) {
			//alter SORT options -- remove Discounted price sort order if SALE prices are not shown
			if (!variable_get('show_sale_prices', FALSE)) {
				unset($form['sort_by']['#options']['field_special_price_value']);
			}

		}
	}
  
}


/**
 * Custom theme for pager links
 */
function furnimobile_pager($variables) {

  $tags = $variables['tags'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $quantity = $variables['quantity'];
  global $pager_page_array, $pager_total;

  // Calculate various markers within this pager piece:
  // Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // first is the first page listed by this pager piece (re quantity)
  $pager_first = $pager_current - $pager_middle + 1;
  // last is the last page listed by this pager piece (re quantity)
  $pager_last = $pager_current + $quantity - $pager_middle;
  // max is the maximum page number
  $pager_max = $pager_total[$element];
  // End of marker calculations.

  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }

  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  // End of generation loop preparation.
  
  $first_item_text = '1';
  $first_item_class = "";
  if ($i < 2) {
	  $first_item_class = "with-separator";	//show border separator if current page is 1
  }
  
  $li_first = theme('pager_first', array('text' =>  $first_item_text, 'element' => $element, 'parameters' => $parameters));
  $li_previous = theme('pager_previous', array('text' => (isset($tags[1]) ? $tags[1] : t('‹ previous')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_next = theme('pager_next', array('text' => (isset($tags[3]) ? $tags[3] : t('next ›')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_last = theme('pager_last', array('text' =>  $pager_max, 'element' => $element, 'parameters' => $parameters));

  if ($pager_total[$element] > 1) {
    
    if ($li_previous) {
      $items[] = array(
        'class' => array('pager-previous'),
        'data' => $li_previous,
      );
    }
    
    if ($li_first) {
      $items[] = array(
        'class' => array('pager-first', $first_item_class),
        'data' => $li_first,
      );
    }

    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1) {
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '…',
        );
      }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {

      	$cur_text = $i;
      	$sep_class = 'with-separator';  	
      	if ($i == $pager_last && $pager_last + 1 != $pager_max || $i == $pager_max) {
    		//unset separator if ... follows current  		
			$cur_text = $i;
			$sep_class = '';
        }
        
        //if last item in the range, unset the explicit 'last' pager
        if ($i == $pager_max) {
        	$li_last = "";
    	}
          
        if ($i < $pager_current && $i > 1) {
          $items[] = array(
            'class' => array('pager-item', $sep_class),
            'data' => theme('pager_previous', array('text' => $cur_text, 'element' => $element, 'interval' => ($pager_current - $i), 'parameters' => $parameters)),
          );
        }
        if ($i == $pager_current) {         
          $items[] = array(
            'class' => array('pager-current', $sep_class),
            'data' => $cur_text,
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
            'class' => array('pager-item', $sep_class),
            'data' => theme('pager_next', array('text' => $cur_text, 'element' => $element, 'interval' => ($i - $pager_current), 'parameters' => $parameters)),
          );
        }
    	
      }
      if ($i < $pager_max) {
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '…',
        );
      }
    }
    // End generation.
    
    if ($li_last) {
      $items[] = array(
        'class' => array('pager-last'),
        'data' => $li_last,
      );
    }
    
    if ($li_next) {
      $items[] = array(
        'class' => array('pager-next'),
        'data' => $li_next,
      );
    }
    return '<h2 class="element-invisible">' . t('Pages') . '</h2>' . theme('item_list', array(
      'items' => $items,
      'attributes' => array('class' => array('pager')),
    ));
  }
}

function _page_include_mightyslider_resources() {
	//add  gallery JS/css files
	//drupal_add_css(drupal_get_path('theme', 'furnimobile') . "/lib/mightyslider/src/css/mightyslider.css", array('group' => CSS_DEFAULT, 'every_page' => FALSE));
	//drupal_add_css(drupal_get_path('theme', 'furnimobile') . "/styles/gallery.css", array('group' => CSS_THEME, 'every_page' => FALSE));			
	
	
	//drupal_add_js(drupal_get_path('theme', 'furnimobile') . "/lib/mightyslider/src/js/mightyslider.min.js", array('group' => JS_THEME));
	drupal_add_js(drupal_get_path('theme', 'furnimobile') . "/lib/mightyslider/src/js/mightyslider_new.min.js", array('group' => JS_THEME));
	drupal_add_js(drupal_get_path('theme', 'furnimobile') . "/lib/mightyslider/js/jquery.easing.1.3.js", array('group' => JS_THEME));

	// !!! Disable touch/swipe events for now
	//drupal_add_js(drupal_get_path('theme', 'furnimobile') . "/lib/mightyslider/js/jquery.mobile.just-touch.js");
}

function _page_set_breadcrumbs(&$vars) {

	$natuzzi_italia = 21;
	$natuzzi_editions = 22;
	
	$attrs = array('attributes' => array('class'=>array('furn-grey', 'furn-e2-a')));
	$active_class= "active-breadcrumb furn-red furn-ucase furn-e2-a";

	#Process breadcrumbs	
	$links = array();
	
	if (isset($vars['node']) && $vars['node']->type == 'item'){
		#item page breadcrumbs

		#get most relevant category of the item
		$node = $vars['node'];
		$weight = 1000;
		$term_index = -1;
		foreach($node->field_category['und'] as $i => $cat) {
			if ($weight > $cat['taxonomy_term']->weight) {
				$term_index = $i;
			}
		}
		$category = $node->field_category['und'][$i]['taxonomy_term'];
		
		#get item's parent category
		$parent_term = "";
		$parent_term_tid = furn_global_get_parent_category($category->tid);
		if ($parent_term_tid) {
			$parent_term = taxonomy_term_load($parent_term_tid);
		}
		
		#determine if it falls under ALL categories or Natuzzi Italia or Natuzzi Editions
		$brand = $node->field_brand['und'][0]['tid'];
		$path_prefix = "";
		if ($brand == $natuzzi_italia) {
			$top_breadcrumb = l("Natuzzi Italia", "natuzzi-italia", $attrs);
			$path_prefix = "natuzzi-italia";
		} else if ($brand == $natuzzi_editions) {
			$top_breadcrumb = l("Natuzzi Editions", "natuzzi-editions", $attrs);
			$path_prefix = "natuzzi-editions";
		} else {
			$top_breadcrumb = l("All Collections", "collections", $attrs);
		}
		
		$links[] = $top_breadcrumb;
		if ($parent_term && $parent_term_tid != $category->tid) {
			$links[] = l($parent_term->name, (empty($path_prefix) ? "" : $path_prefix . "/") . drupal_get_path_alias('taxonomy/term/' . $parent_term->tid), $attrs);
		}
		$links[] = l($category->name, (empty($path_prefix) ? "" : $path_prefix . "/") . drupal_get_path_alias("taxonomy/term/$category->tid"), $attrs);

	}
	else if (arg(0) == 'natuzzi-italia'  || arg(0) == 'natuzzi-editions' || (arg(0) == 'taxonomy' && arg(1) == 'term')) {
		
		
		if (arg(0) == 'natuzzi-italia' || arg(0) == 'natuzzi-editions') {
			if(arg(1) == "") {
				$links[] = l("Home", "<front>", $attrs);
				$links[] = "<span class=\"". $active_class . "\">". ucwords(str_replace("-", " ", arg(0))) ."</span>";
			} else {
				$alias = arg(1);
				if (arg(2) != "") {
					$alias .= "/" . arg(2);
				}
				$normal_path = explode('/', drupal_get_normal_path($alias)); //TO-DO check for validity
				if (is_array($normal_path) && count($normal_path) == 3) { //taxonomy/term/xxx
					$active_cat_tid = $normal_path[2];
					$active_parent_tid = furn_global_get_parent_category($active_cat_tid);
				}
				
				if (arg(0) == 'natuzzi-italia') {
					$top_breadcrumb = l("Natuzzi Italia", "natuzzi-italia", $attrs);
				} else if (arg(0) == 'natuzzi-editions') {
					$top_breadcrumb = l("Natuzzi Editions", "natuzzi-editions", $attrs);
				}
				
				$parent_term = taxonomy_term_load($active_parent_tid);
				$term = taxonomy_term_load($active_cat_tid);
	
				$links[] = $top_breadcrumb;			
				if ($active_cat_tid != $active_parent_tid) {
					$links[] = l($parent_term->name, arg(0) . "/" . drupal_get_path_alias('taxonomy/term/' . $parent_term->tid), $attrs);
					$links[] = "<span class=\"". $active_class . "\">$term->name</span>";		
				} else {
					$links[] = "<span class=\"". $active_class . "\">$parent_term->name</span>";
				}
			}
			
		} else {
			$active_cat_tid = arg(2);
			$term = taxonomy_term_load($active_cat_tid);
			
			if ($term->vid == 3) { 
				//currently brand page is in focus
				$links[] = l("All Brands", "brands", $attrs);	
				$links[] = "<span class=\"". $active_class . "\">$term->name</span>";
			} else {	
				//category page is in focus
				$active_parent_tid = furn_global_get_parent_category($active_cat_tid);
				$links[] = l("All Collections", "collections", $attrs);
				if ($active_cat_tid != $active_parent_tid) {
					$parent_term = taxonomy_term_load($active_parent_tid);
					$links[] = l($parent_term->name, drupal_get_path_alias('taxonomy/term/' . $parent_term->tid), $attrs);
				}
				$links[] = "<span class=\"". $active_class . "\">$term->name</span>";
			}
		}
	}
	
	if (count($links) > 1){
		drupal_set_breadcrumb($links);
		$vars['breadcrumb'] = theme('breadcrumb', $links);
	} else {
		drupal_set_breadcrumb(array());
	}
	
	
}

function furnimobile_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];

  if (!empty($breadcrumb)) {
    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';

    $output .= '<div class="breadcrumb">' . implode(' <span class="furn-e2-a separator">/</span> ', $breadcrumb) . '</div>';
    return $output;
  }
}

/**
 * Override or insert variables into the html templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("html" in this case.)
 */
function furnimobile_preprocess_html(&$variables, $hook) {
	$italia_editions_gallery_paths = array("natuzzi-italia", "natuzzi-italia/*", "natuzzi-italia/*/*", "natuzzi-editions", "natuzzi-editions/*", "natuzzi-editions/*/*");
	$paths = array_merge(array("sale", "in-stores", "collections", "taxonomy/term/*"), $italia_editions_gallery_paths);
	if(drupal_match_path(current_path(), implode("\n", $paths))) {
		 $variables['classes_array'][] = 'gallery-grid';
	}
}
