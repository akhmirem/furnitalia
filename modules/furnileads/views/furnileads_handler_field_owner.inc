<?php

/**
 * @file
 * Definition of furnileads_handler_field_owner.
 */

/**
 * Field handler to allow linking to a user account or homepage.
 *
 * @ingroup views_field_handlers
 */
class furnileads_handler_field_owner extends views_handler_field {
  /**
   * Override init function to add uid and homepage fields.
   */
  function init(&$view, &$data) {
    parent::init($view, $data);
    $this->additional_fields['uid'] = 'uid';
  }

  function render_link($data, $values) {
    if (!empty($this->options['link_to_user'])) {
      $account = new stdClass();
      $account->uid = $this->get_value($values, 'uid');
      $account->name = $this->get_value($values);
      $account->homepage = $this->get_value($values, 'homepage');

      return theme('username', array(
        'account' => $account
      ));
    }
    else {
      return $data;
    }
  }

  function render($values) {
    $value = $this->get_value($values);
    
    dsm($values);
    
    $account = new stdClass();
	$account->uid = $this->get_value($values, 'uid');
	$account->name = $this->get_value($values);
	
	return theme('username', array(
		'account' => $account
	));
  }
}
