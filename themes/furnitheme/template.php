<?php
/**
 * @file
 * Contains the theme's functions to manipulate Drupal's default markup.
 *
 *
 *   For more information, please visit the Theme Developer's Guide on
 *   Drupal.org: http://drupal.org/node/173880
 *
 * CREATE OR MODIFY VARIABLES FOR YOUR THEME
 *
 *   Each tpl.php template file has several variables which hold various pieces
 *   of content. You can modify those variables (or add new ones) before they
 *   are used in the template files by using preprocess functions.
 *
 *   This makes THEME_preprocess_HOOK() functions the most powerful functions
 *   available to themers.
 *
 *   It works by having one preprocess function for each template file or its
 *   derivatives (called theme hook suggestions). For example:
 *     THEME_preprocess_page    alters the variables for page.tpl.php
 *     THEME_preprocess_node    alters the variables for node.tpl.php or
 *                              for node--forum.tpl.php
 *     THEME_preprocess_comment alters the variables for comment.tpl.php
 *     THEME_preprocess_block   alters the variables for block.tpl.php
 *
 *   For more information on preprocess functions and theme hook suggestions,
 *   please visit the Theme Developer's Guide on Drupal.org:
 *   http://drupal.org/node/223440 and http://drupal.org/node/1089656
 */

/**
 * Implementation of hook_js_alter
 */
function furnitheme_js_alter(&$javascript) {

	if (isset($javascript['misc/jquery.form.js'])) {
		$jquery_path = drupal_get_path('theme','furnitheme') . '/js/jquery.form.min.js';
		
		//We duplicate the important information from the Drupal one
		$javascript[$jquery_path] = $javascript['misc/jquery.form.js'];
		//..and we update the information that we care about
		$javascript[$jquery_path]['version'] = '3.35';
		$javascript[$jquery_path]['data'] = $jquery_path;
		
		unset($javascript['misc/jquery.form.js']);
	}
}

/**
 * Implementation of hook_css_alter
 */
function furnitheme_css_alter(&$css) {

}

/**
 * Override or insert variables into the page templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 */
 //!PreprocessPage
function furnitheme_preprocess_page(&$vars) {

	global $user, $theme_path, $base_path;

	drupal_add_css($theme_path . '/css/ui/base/jquery.ui.all.css', array('group' => -100, 'every_page' => TRUE));
	
	drupal_add_js('misc/jquery.form.js');
	drupal_add_library('system', 'drupal.ajax');	
	drupal_add_library('system', 'ui.accordion');	
	drupal_add_library('system', 'jquery.bbq'); //for processing url in javacsript

	//jquery ui
	drupal_add_js(drupal_get_path('module', 'webform_ajax') . '/js/webform_ajax.js', 'file');
	
	//item description page thumbnail gallery
	drupal_add_js($theme_path . '/js/jquery.pikachoose.min.js');
	drupal_add_css($theme_path . '/css/pikachoose.css');
	
	//dropdown custom controls		
	drupal_add_js($theme_path . '/js/jquery.dropkick-1.0.0.js');
	drupal_add_css($theme_path . '/css/dropkick.css');
	
	$italia_editions_gallery_paths = array("natuzzi-italia", "natuzzi-italia/*", "natuzzi-italia/*/*", "natuzzi-editions", "natuzzi-editions/*", "natuzzi-editions/*/*");
	$paths_with_keyholes = array('<front>', 'collections', 'natuzzi-italia', 'natuzzi-editions');
	$paths_no_title = array_merge(array("node/*", "sale", "collections", "taxonomy/term/*", "moving-sale"), $italia_editions_gallery_paths);
	$paths_no_tabs = array_merge(array("taxonomy/term/*", "natuzzi-italia", "natuzzi-editions"), $italia_editions_gallery_paths);	
	
	//determine whether to show/hide titles and promo area depending on path
	$vars['show_keyhole'] = drupal_match_path(current_path(), implode("\n", $paths_with_keyholes));
	$vars['show_promo'] =  arg(0) != "moving-sale";
	$vars['show_title'] = !drupal_match_path(current_path(), implode("\n", $paths_no_title));
	
	if (drupal_match_path(current_path(), "node/*")) {
		drupal_add_js($theme_path . '/lib/jscrollpane/jquery.jscrollpane.min.js');
		drupal_add_js($theme_path . '/lib/jscrollpane/mwheelIntent.js');
		drupal_add_css($theme_path. '/lib/jscrollpane/jquery.jscrollpane.css');
	}
	
	//disable taxonomy tabs
	if (drupal_match_path(current_path(), implode("\n", $paths_no_tabs))) {
		unset($vars['tabs']);
	}
	
	//set footer menu links	
	furnitheme_set_up_footer_menu($vars);
	
	//set up top menu
	furnitheme_set_up_top_menu($vars);	
	
	
	if (isset($vars['node']) && arg(2) != 'edit') {
		$node = $vars['node'];
		if ($node->type == 'item') {
			$content = $vars['page']['content']['system_main']['nodes'][$node->nid];

			furn_global_preprocess_node_common_fields($content, 'page', $node);
						
			$vars['page']['content']['system_main']['nodes'][$node->nid] = $content;			
				
		} else if ($node->type == 'blog') {
			$vars['show_title'] = TRUE;
    }

	}
	
	$vars['contact_page'] = FALSE;
	if (arg(0) == 'contact') {	
		//contact us page
		$vars['contact_page'] = TRUE;
	}
	
	if (!$vars['is_front'] && arg(0) != 'coupon') {
  	$coupon_link = $base_path . "coupon";
    $left_section_extra = "";
    $left_section_extra .= '<a href="'.$coupon_link.'" title="Get 10% OFF Coupon!">';
    $left_section_extra .= theme("image", array("path" => 'public://promo/coupons/10_percent_coupon_button.jpg', "alt" => "Get 10% OFF Coupon!", "attributes" => array('width' => '246', 'height' => '123')));
    $left_section_extra .= "</a>";
  	  
   	$vars['page']['left_section_extra'] = array(
   	  "#markup" => $left_section_extra,
    );
  }
	
	//moving sale extra
	/*if (arg(0) == 'moving-sale') {
	  $left_section_extra = <<<EOT
  	  <img src="$theme_path/images/landing/start_shopping_block.jpg" border="0" usemap="#map1" alt="Start Shopping" />
      <map name="map1" id="map1">
        <area  shape="rect" coords="0,60,245,246" alt="Shop for Metropol Sectional" title="Metropol Sectional" target="_self" href="http://www.furnitalia.com/item/metropol-sectional-30gd"     />
        <area  shape="rect" coords="0,247,244,421" alt="Shop for Italsofa Josa Chair" title="Josa Chair by Italsofa" target="_self" href="http://www.furnitalia.com/item/josa-i341-armchair"     />
        <area  shape="rect" coords="0,420,245,592" alt="Shop for Cammeo Chair by Natuzzi" title="Cammeo Chair" target="_self" href="http://www.furnitalia.com/item/cammeo-1576-black"     />
      </map>
EOT;
	  
  	$vars['page']['left_section_extra'] = array(
  	  "#markup" => $left_section_extra,
    );
  }*/
	
}

/**
 * Override or insert variables into the html templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("html" in this case.)
 */
function furnitheme_preprocess_html(&$variables, $hook) {
	if (in_array(arg(0), array('collections', 'natuzzi-italia', 'natuzzi-editions')) && arg(1) == '') {
		 $variables['classes_array'][] = 'keyhole';
	}
	//drupal_add_css(path_to_theme() . '/css/ie.css', array('group' => CSS_THEME, 'weight' => 115, 'browsers' => array('IE' => 'gte IE 6', '!IE' => FALSE), 'preprocess' => FALSE));
}

/**
 * Returns HTML for a select form element.
 *
 * It is possible to group options together; to do this, change the format of
 * $options to an associative array in which the keys are group labels, and the
 * values are associative arrays in the normal $options format.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties of the element.
 *     Properties used: #title, #value, #options, #description, #extra,
 *     #multiple, #required, #name, #attributes, #size.
 *
 * @ingroup themeable
 */
function furnitheme_select($variables) {
  $element = $variables['element'];

  element_set_attributes($element, array('id', 'name', 'size'));
  _form_set_class($element, array('form-select'));

  return '<select' . drupal_attributes($element['#attributes']) . '>' . furnitheme_form_select_options($element) . '</select>';
}

/**
 * Converts a select form element's options array into HTML.
 *
 * @param $element
 *   An associative array containing the properties of the element.
 * @param $choices
 *   Mixed: Either an associative array of items to list as choices, or an
 *   object with an 'option' member that is an associative array. This
 *   parameter is only used internally and should not be passed.
 *
 * @return
 *   An HTML string of options for the select form element.
 */
function furnitheme_form_select_options($element, $choices = NULL) {
  if (!isset($choices)) {
    $choices = $element['#options'];
  }
  // array_key_exists() accommodates the rare event where $element['#value'] is NULL.
  // isset() fails in this situation.
  $value_valid = isset($element['#value']) || array_key_exists('#value', $element);
  $value_is_array = $value_valid && is_array($element['#value']);
  $options = '';
  $i = 0;
  foreach ($choices as $key => $choice) {
    if (is_array($choice)) {
      $options .= '<optgroup label="' . $key . '">';
      $options .= form_select_options($element, $choice);
      $options .= '</optgroup>';
    }
    elseif (is_object($choice)) {
      $options .= form_select_options($element, $choice->option);
    }
    else {
      $key = (string) $key;
      if ($value_valid && (!$value_is_array && (string) $element['#value'] === $key || ($value_is_array && in_array($key, $element['#value'])))) {
        $selected = ' selected="selected"';
      }
      else {
        $selected = '';
      }
      $extra_attrs = isset($element['#extra_option_attributes']) && isset($element['#extra_option_attributes'][$i]) ? $element['#extra_option_attributes'][$i] : '';
      $options .= '<option value="' . check_plain($key) . '"' . $selected . ' ' . $extra_attrs . '>' . check_plain($choice) . '</option>';
    }
    
    ++$i;
  }
  return $options;
}

/**
 * Preprocess callback for hook_webform_mail_message
 */
/*function furnitheme_preprocess_webform_mail_message(&$vars) {
  $node = $vars['node'];

  //coupon webform
  dsm($vars['node']);
  if (in_array($vars['node']->nid, array(514, 834))) {
    $vars['theme_hook_suggestion'] = 'webform_mail_coupon';
  }
}*/

/**
 * Implements hook_form_alter().
 *
 * Alter search form
 */
function furnitheme_form_search_block_form_alter(&$form, &$form_state, $form_id) {
	
	global $base_path, $theme_path;
	
	$path = $base_path . $theme_path;
	
	$form['actions']['submit']['#theme_wrappers'] = array("image_button");
	$form['actions']['submit']['#button_type'] = "image";
	$form['actions']['submit']['#src'] = $path . "/images/Go_Button_Active.png";

}

/**
 * Implements hook_form_alter().
 *
 * Alter search form
 */
function furnitheme_form_webform_client_form_33_alter(&$form, &$form_state, $form_id) {
	
	global $base_path, $theme_path;
	
	$path = $base_path . $theme_path;
	
	$form['actions']['submit']['#theme_wrappers'] = array("image_button");
	$form['actions']['submit']['#button_type'] = "image";
	$form['actions']['submit']['#src'] = $path . "/images/Join_Button_Active.png";
}


/**
 * Implements hook_form_alter().
 *
 * Alter search form
 */
function furnitheme_form_search_form_alter(&$form, &$form_state, $form_id) {

	global $base_path, $theme_path;
	
	$path = $base_path . $theme_path;
	
	unset($form['advanced']);
	
	$form['basic']['submit']['#theme_wrappers'] = array("image_button");
	$form['basic']['submit']['#button_type'] = "image";
	$form['basic']['submit']['#src'] = $path . "/images/Go_Button_Active.png";

}

/**
 * Implements hook_preprocess_region().
 *
 * Alter search form
 */
function furnitheme_preprocess_region(&$variables, $hook) {
    if($variables['region'] == "content_top"){
        $variables['classes_array'][] = 'clearfix';
    }
}


/**
 * Formats a product's length, width, and height.
 *
 * @param $variables
 *   An associative array containing:
 *   - length: A numerical length value.
 *   - width: A numerical width value.
 *   - height: A numerical height value.
 *   - units: String abbreviation representing the units of measure.
 *   - attributes: (optional) Array of attributes to apply to enclosing DIV.
 *
 * @see uc_length_format()
 * @ingroup themeable
 */
function furnitheme_uc_product_dimensions($variables) {
  $length = $variables['length'];
  $width = $variables['width'];
  $height = $variables['height'];
  $units = $variables['units'];
  $attributes = $variables['attributes'];
  $attributes['class'][] = "product-info";
  $attributes['class'][] = "dimensions";
  $attributes['class'][] = "field";

  $output = '';
  if ($length || $width || $height) {
    $output = '<div ' . drupal_attributes($attributes) . '>';
    $output .= '<div class="field-label product-info-label">' . t('Dimensions') . ':</div> ';
    $output .= '<span class="product-info-value">' ;
    $output .= uc_length_format($length, $units) . ' × ';
    $output .= uc_length_format($width, $units) . ' × ';
    $output .= uc_length_format($height, $units) . '</span>';
    $output .= '</div>';
  }

  return $output;
}

/**
 * Formats a product's model number.
 *
 * @param $variables
 *   An associative array containing:
 *   - model: Product model number, also known as SKU.
 *   - attributes: (optional) Array of attributes to apply to enclosing DIV.
 *
 * @ingroup themeable
 */
function furnitheme_uc_product_model($variables) {
  $model = $variables['model'];
  $attributes = $variables['attributes'];
  $attributes['class'][] = "product-info";
  $attributes['class'][] = "model";

  $output = '<div ' . drupal_attributes($attributes) . '>';
  $output .= '<span class="product-info-label">' . t('SKU') . ':</span> ';
  $output .= '<span class="product-info-value">' . check_plain($model) . '</span>';
  $output .= '</div>';

  return $output;
}

function furnitheme_views_mini_pager($vars) {
  global $pager_page_array, $pager_total;

  $tags = $vars['tags'];
  $element = $vars['element'];
  $parameters = $vars['parameters'];

  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // max is the maximum page number
  $pager_max = $pager_total[$element];
  // End of marker calculations.

  if ($pager_total[$element] > 1) {

    $li_previous = theme('pager_previous',
      array(
        'text' => (isset($tags[1]) ? $tags[1] : t('‹‹')),
        'element' => $element,
        'interval' => 1,
        'parameters' => $parameters,
      )
    );
    if (empty($li_previous)) {
      $li_previous = "&nbsp;";
    }

    $li_next = theme('pager_next',
      array(
        'text' => (isset($tags[3]) ? $tags[3] : t('››')),
        'element' => $element,
        'interval' => 1,
        'parameters' => $parameters,
      )
    );

    if (empty($li_next)) {
      $li_next = "&nbsp;";
    }

    $items[] = array(
      'class' => array('pager-previous'),
      'data' => $li_previous,
    );

    $items[] = array(
      'class' => array('pager-current'),
      'data' => t('@current of @max', array('@current' => $pager_current, '@max' => $pager_max)),
    );
    

    // Set up link to view all results in one page - taken from theme_pager_link
    $query = array();
    if (count($parameters)) {
      $query = drupal_get_query_parameters($parameters, array());
    }
    if ($query_pager = pager_get_query_parameters()) {
      $query = array_merge($query, $query_pager);
    }
    $query['items_per_page'] = 'All';
    $attributes['href'] = url($_GET['q'], array('query' => $query));
    $li_view_all = '<a' . drupal_attributes($attributes) . '>' . 'View All' . '</a>';
    
    $items[] = $li_view_all;
    
    $items[] = array(
      'class' => array('pager-next'),
      'data' => $li_next,
    );
    return theme('item_list',
      array(
        'items' => $items,
        'title' => NULL,
        'type' => 'ul',
        'attributes' => array('class' => array('pager')),
      )
    );
  } else {
  
    // Set up link to view results by pages
    $query = array();
    if (count($parameters)) {
      $query = drupal_get_query_parameters($parameters, array());
    }
    if ($query_pager = pager_get_query_parameters()) {
      $query = array_merge($query, $query_pager);
    }
    $query['items_per_page'] = '9';
    $attributes['href'] = url($_GET['q'], array('query' => $query));
    $li_view_paged = '<a' . drupal_attributes($attributes) . '>' . 'View by pages' . '</a>';
    
    $items[] = $li_view_paged;

    return theme('item_list',
      array(
        'items' => $items,
        'title' => NULL,
        'type' => 'ul',
        'attributes' => array('class' => array('pager')),
      )
    );
  }
}

/**
 * Custom theme for pager links
 */
function furnitheme_pager($variables) {

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

function furnitheme_webform_mail_headers($vars) {
  
  /*
  For MIME-attachment emails use these headers:
  ---------------------------------
  global $drupal_hash_salt;
  
  if (513 == $vars['node']->nid) {
      $hash = md5($drupal_hash_salt);
      $mime_boundary = "==Multipart_Boundary_x{$hash}x";
      $headers = array(
        "Content-Type" => "multipart/mixed; boundary=\"".$mime_boundary."\"",
        "MIME-Version"  => "1.0",
        "X-Mailer" => 'Drupal Webform (PHP/'.phpversion().')',
      );
      return $headers;
  }*/
  
  $headers = array(
    'Content-Type'  => 'text/html; charset=UTF-8; format=flowed; delsp=yes',
    'X-Mailer'      => 'Drupal Webform (PHP/'. phpversion() .')',
  );
  return $headers;
}

function furnitheme_set_up_footer_menu (&$vars) {
	$info_menu = array(
		'<span class="menu-label">About Furnitalia</span>',
		l("About us", 'about'),
		l("Contact us", "contact"), 
		l("Blog", "blog"),
	);
	$vars['footer_info_menu'] = array(
		'#theme' => 'item_list',
		'#items' => array_values($info_menu),
		'#type' => 'ul',
		'#attributes' => array('class' => 'links'),
	);
	
	$user_menu = array();	
	$user_menu []= '<span class="menu-label">Account information</span>';
	$user_menu []= l("Account Details", 'user');
	$user_menu []= l("Favorites", 'my-favorites');		
	$user_menu []= l("Order status", 'my-orders');		
	$user_menu []= l("My Cart", 'cart');
	
	$vars['footer_user_menu'] = array(
		'#theme' => 'item_list',
		'#items' => array_values($user_menu),
		'#type' => 'ul',
		'#attributes' => array('class' => 'links'),
	);
	
	$policies_menu = array(
		'<span class="menu-label">Policies and information</span>',
		l("FAQ", 'faq'),
		l("Shipping and Delivery", "shipping-deliveries"), 
		l("Terms of Service", "service-terms"),
		l("Privacy Policy", "privacy-policy"),	
	);
	$vars['footer_policy_menu'] = array(
		'#theme' => 'item_list',
		'#items' => array_values($policies_menu),
		'#type' => 'ul',
		'#attributes' => array('class' => 'links'),
	);
}

function furnitheme_set_up_top_menu (&$vars) {
	global $user;
	
	$top_menu = array();
	
	if ($user->uid == 0) {	
		$top_menu []= l("Sign in", 'user/login');
		$top_menu []= l("Register", 'user/register');
	} else {
		$user_fields = user_load($user->uid);
		$welcome_msg = '<span class="welcome">Welcome, <span class="welcome-user">' . $user_fields->field_first_name['und'][0]['value'] . '</span></span>';
		$top_menu [] = $welcome_msg;
		$top_menu []= l("Sign out", 'user/logout');	
	}

	$top_menu []= l("My Cart", 'cart');
	$top_menu []= l("Favorites", 'my-favorites');
	
	if(!$vars['show_keyhole']) {
		$top_menu []= l("Stores", 'contact');
	}
	
	$vars['page']['header']['top_menu'] = array(
		'#theme' => 'item_list',
		'#items' => array_values($top_menu),
		'#type' => 'ul',
		'#attributes' => array('class' => 'menu'),
	);
}


/**
 * Implements hook_form_alter().
 *
 * Alter exposed filter form in views
 */
/*function furnitheme_form_views_exposed_form_alter(&$form, &$form_state, $form_id) {  
	
	global $conf;

	if (isset($conf['SITE_ID']) && $conf['SITE_ID'] != 'desktop') {	
  	drupal_add_css(drupal_get_path('theme', 'furnimobile') . "/lib/dropkick/dropkick.css");
    drupal_add_js (drupal_get_path('theme', 'furnimobile')  . "/lib/dropkick/jquery.dropkick-1.0.0.js");
  }
	
  if ($form['#id'] == 'views-exposed-form-taxonomy-term-page') { 		 
	 
    //reset action attribute so form resubmits to same page
    $form['#action'] = "";

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
        
	} else if (in_array($form['#id'], array('views-exposed-form-taxonomy-term-page-brands', 'views-exposed-form-taxonomy-term-page-in-store', 'views-exposed-form-taxonomy-term-page-italia', 'views-exposed-form-taxonomy-term-page-editions', 'views-exposed-form-taxonomy-term-page-clearance'))) {
	
    //reset action attribute so form resubmits to same page
    $form['#action'] = "";

		// prevent recursion - only execute once
    static $called_brand = 0;
    if ($called_brand === $form['#id']) {
      return;
    }		    
    $called_brand = $form['#id']; 				    // flag as called

    //get view results
    $view = $form_state['view']; 				      // views_get_current_view();

    $temp_view = $view->clone_view(); 			  // create a temp view
    $temp_view->init_display();
    
    if ($form['#id'] == 'views-exposed-form-taxonomy-term-page-in-store') {
    	$temp_view->set_display('page_in_store');
    } elseif ($form['#id'] == 'views-exposed-form-taxonomy-term-page-clearance') {
    	$temp_view->set_display('page_clearance');
    } else {
	    $temp_view->set_display('page_brands');
    }
    
    $temp_view->pre_execute();
    $temp_view->set_items_per_page(0); 			        // we want results from all pages
    $temp_view->display_handler->has_exposed = 0;   // prevent recursion
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

	}
	
	//set default sort order
	if(!isset($_REQUEST['sort_by'])) {
	  if (isset($form['sort_by'])) {
		  $form['sort_by']['#value'] = 'title';
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
  
}*/

/**
 * Default preprocess function for all filter forms.
 */
/*function furnitheme_preprocess_views_exposed_form(&$vars) {
  /*$form = &$vars['form'];

  // Render new created sort field.
  if (isset($form['sorting'])) {
    $form['sorting']['#printed'] = FALSE;
    $vars['sorting'] = drupal_render($form['sorting']);

    // Need to rebuild the submit button.
    $form['submit']['#printed'] = FALSE;
    $vars['button'] = drupal_render_children($form);
  }
}
// */


/**
 * Override or insert variables into the maintenance page template.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("maintenance_page" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_maintenance_page(&$variables, $hook) {
  // When a variable is manipulated or added in preprocess_html or
  // preprocess_page, that same work is probably needed for the maintenance page
  // as well, so we can just re-use those functions to do that work here.
  STARTERKIT_preprocess_html($variables, $hook);
  STARTERKIT_preprocess_page($variables, $hook);
}
// */


/**
 * Override or insert variables into the comment templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("comment" in this case.)
 */

/**
 * Override or insert variables into the region templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("region" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_region(&$variables, $hook) {
  // Don't use Zen's region--sidebar.tpl.php template for sidebars.
  //if (strpos($variables['region'], 'sidebar_') === 0) {
  //  $variables['theme_hook_suggestions'] = array_diff($variables['theme_hook_suggestions'], array('region__sidebar'));
  //}
}
// */

/**
 * Override or insert variables into the block templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_block(&$variables, $hook) {
  // Add a count to all the blocks in the region.
  // $variables['classes_array'][] = 'count-' . $variables['block_id'];

  // By default, Zen will use the block--no-wrapper.tpl.php for the main
  // content. This optional bit of code undoes that:
  //if ($variables['block_html_id'] == 'block-system-main') {
  //  $variables['theme_hook_suggestions'] = array_diff($variables['theme_hook_suggestions'], array('block__no_wrapper'));
  //}
}
// */
