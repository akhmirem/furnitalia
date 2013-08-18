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
function furnitheme_preprocess_page(&$vars) {

	global $user;

	drupal_add_css(drupal_get_path('theme', 'furnitheme') . '/css/ui/base/jquery.ui.all.css', array('group' => -100, 'every_page' => TRUE));
	
	drupal_add_js('misc/jquery.form.js');
	drupal_add_library('system', 'drupal.ajax');	
	drupal_add_library('system', 'ui.accordion');	
	drupal_add_library('system', 'jquery.bbq'); //for processing url in javacsript

	//jquery ui
	drupal_add_js(drupal_get_path('module', 'webform_ajax') . '/js/webform_ajax.js', 'file');
	
	//item description page thumbnail gallery
	drupal_add_js(drupal_get_path('theme', 'furnitheme') . '/js/jquery.pikachoose.min.js');
	drupal_add_css(drupal_get_path('theme', 'furnitheme') . '/css/pikachoose.css');
	
	//dropdown custom controls		
	drupal_add_js(drupal_get_path('theme', 'furnitheme') . '/js/jquery.dropkick-1.0.0.js');
	drupal_add_css(drupal_get_path('theme', 'furnitheme') . '/css/dropkick.css');

	
	$vars['show_keyhole'] = FALSE;
	$vars['show_promo'] = FALSE;
	
	if (arg(0) == 'front') {
		$vars['show_keyhole'] = TRUE;
		$vars['show_promo'] = TRUE;
	}

	$vars['show_title'] = TRUE;	
	if (arg(0) == 'node' && is_numeric(arg(1))) {
		drupal_add_js(drupal_get_path('theme', 'furnitheme') . '/lib/jscrollpane/jquery.jscrollpane.min.js');
		drupal_add_js(drupal_get_path('theme', 'furnitheme') . '/lib/jscrollpane/mwheelIntent.js');
		drupal_add_css(drupal_get_path('theme', 'furnitheme') . '/lib/jscrollpane/jquery.jscrollpane.css');

		//don't display title
		$vars['show_title'] = FALSE;		
	}
	
	//disable taxonomy tabs
	if (arg(0) == 'taxonomy' && arg(1) == 'term' && is_numeric(arg(2)) && arg(3) == "") {
		unset($vars['tabs']);
		
		//don't display title
		$vars['show_title'] = FALSE;
		$vars['show_promo'] = TRUE;
	}
	
	//set footer menu links	
	furnitheme_set_up_footer_menu($vars);
	
	//set up top menu
	furnitheme_set_up_top_menu($vars);	
	
	
	if (isset($vars['node']) && arg(2) != 'edit') {
		$node = $vars['node'];
		if ($node->type == 'item') {
			$content = $vars['page']['content']['system_main']['nodes'][$node->nid];

			preprocess_node_common_fields($content, 'page');
						
			$vars['page']['content']['system_main']['nodes'][$node->nid] = $content;			
			
				
		}
	}
	
	$vars['contact_page'] = FALSE;
	if (arg(0) == 'contact') {
		//contact us page
		$vars['contact_page'] = TRUE;
	}
	
}

function preprocess_node_common_fields(&$content, $hook) {
	

	if (!empty($content['field_show_add_to_cart']) && $content['field_show_add_to_cart']['#items'][0]['value'] == '0') {
		unset($content['add_to_cart']);
	}


	$content['list_price']['#title'] = "MSRP:";
	$content['sell_price']['#title'] = "Furnitalia:";

	
	$sale_price_set = !empty($content['field_sale_price']) && isset($content['field_sale_price']['#items']) && $content['field_sale_price']['#items'][0]['value'];
		
	if($sale_price_set) {
		$new_sale_price = array();
		
		$new_sale_price['#title'] = "Special:";
		$new_sale_price['#theme'] = "uc_product_price";
		$new_sale_price['#value'] = $content['field_sale_price']['#items'][0]['value'];
		$new_sale_price['#attributes'] = array('class' => array('sell-price'));
		
		$content['sale_price'] = $new_sale_price;
		$content['sell_price']['#attributes']['class'] = array('old-price');
	  	
	} else {
		
		
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
function furnitheme_preprocess_node(&$vars, $hook) {
	//dsm($vars);
	
	preprocess_node_common_fields($vars['content'], 'page');
	
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
 * Preprocess function for search results
 *
 */
function furnitheme_preprocess_search_result(&$vars) {
}

/**
 * Implements hook_form_alter().
 *
 * Alter search form
 */
function furnitheme_form_search_block_form_alter(&$form, &$form_state, $form_id) {
	
	global $base_path;
	global $theme_path;
	
	$path = $base_path . $theme_path;//drupal_get_path('theme', 'furnitheme');
	
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
	
	global $base_path;
	global $theme_path;
	
	$path = $base_path . $theme_path;//drupal_get_path('theme', 'furnitheme');
	
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

	global $base_path;
	global $theme_path;
	
	$path = $base_path . $theme_path;//drupal_get_path('theme', 'furnitheme');
	
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
  $output .= '<span class="product-info-label">' . t('Model') . ':</span> ';
  $output .= '<span class="product-info-value">' . check_plain($model) . '</span>';
  $output .= '</div>';

  return $output;
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

function furnitheme_webform_mail_headers($variables) {
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

	
  if (isset($form['sort_by'])) {
    // Combine sort drop-downs into one.
    $form['sorting'] = array(
      '#type' => 'select',
      '#id'   => 'sort',
      '#title' => $form['sort_by']['#title'],
      '#attributes' => array('class' => array('clearfix', 'option-set', 'dk', 'sortBy'), 'tabindex' => '1'),
    );
    foreach ($form['sort_by']['#options'] as $sort_by_key => $sort_by_title) {
      foreach ($form['sort_order']['#options'] as $sort_order_key => $sort_order_title) {
        $form['sorting']['#options'][$sort_by_key . '|' . $sort_order_key] = $sort_by_title . ' ' . $sort_order_title;
      }
    }

    // Get default value for combined sort.
    $sort_by_keys = array_keys($form['sort_by']['#options']);
    $form['sorting']['#default_value'] = $sort_by_keys[0] . '|' . $form['sort_order']['#default_value'];
  }

  // Explode combined sort field into two values that are appropriate for views.
  if (isset($form_state['input']['sorting'])) {
    $sorting = explode('|', $form_state['input']['sorting']);
    $form_state['input']['sort_by'] = $sorting[0];
    $form_state['input']['sort_order'] = $sorting[1];
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
