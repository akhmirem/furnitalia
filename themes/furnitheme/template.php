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
	//drupal_add_css(drupal_get_path('theme', 'furnitheme') . '/css/ui/base/jquery.ui.slider.min.css', array('group' => -100, 'every_page' => TRUE));
	
	//drupal_add_library('system', 'ui');
	drupal_add_js('misc/jquery.form.js');
	drupal_add_library('system', 'drupal.ajax');
	
	drupal_add_library('system', 'ui.accordion');

	//jquery ui
	//drupal_add_library('system', 'ui.dialog');//, array('every_page' => TRUE));
	//drupal_add_library('system', 'ui.slider');
	drupal_add_js(drupal_get_path('module', 'webform_ajax') . '/js/webform_ajax.js', 'file');
	
	//item description page thumbnail gallery
	drupal_add_js(drupal_get_path('theme', 'furnitheme') . '/js/jquery.pikachoose.min.js');
	drupal_add_css(drupal_get_path('theme', 'furnitheme') . '/css/pikachoose.css');

	if (arg(0) == 'taxonomy') {
		drupal_add_js(drupal_get_path('theme', 'furnitheme') . '/js/jquery.dropkick-1.0.0.js');
		drupal_add_css(drupal_get_path('theme', 'furnitheme') . '/css/dropkick.css');
	}
	
	$vars['show_keyhole'] = FALSE;
	if (arg(0) == 'front') {
		$vars['show_keyhole'] = TRUE;
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
	}
	
	//set footer menu links	
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
	
	//top menu
	$top_menu = array();
	
	if ($user->uid == 0) {	
		$top_menu []= l("Sign in", 'user/login');
		$top_menu []= l("Register", 'user/register');
	} else {
		$user_fields = user_load($user->uid);
		$welcome_msg = 'Welcome, ' . $user_fields->field_first_name['und'][0]['value'];
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
 * Override or insert variables into the node templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("node" in this case.)
 */
function furnitheme_preprocess_node(&$variables, $hook) {
	//dsm($variables);
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
}*/


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
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_comment(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');
}
// */

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
