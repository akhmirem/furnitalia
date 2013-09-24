<?php
/**
 * @file
 * Field handler for lead status
 *
 * @ingroup views_field_handlers
 */
class furnileads_handler_field_status extends views_handler_field {
  
	public static $lead_status_options = array(
		'N' => 'Open',
		'P' => 'Pending',
		'S' => 'Sold',
		'X' => 'Closed',
	);
	
	/**
	* Render the field.
	*
	* @param $values
	*   The values retrieved from the database.
	*/
	function render($values) {
		$value = $this->get_value($values);
		return $this->sanitize_value(furnileads_handler_field_status::$lead_status_options[$value]);
	}

}
