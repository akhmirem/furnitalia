<?php
/**
 * My custom filter handler
 */
class furnileads_handler_filter_uid_select extends views_handler_filter_in_operator {

  /**
   * Override parent get_value_options() function.
   *
   * @return
   *   Return the stored values in $this->value_options if someone expects it.
   */
  function get_value_options() {
    $users_list = entity_load('user');

    foreach ($users_list as $user) {
      $users[$user->uid] = $user->name;
    }
	
    // Sort by username.
    natsort($users);

    $this->value_options = $users;

    return $users;
  }
}