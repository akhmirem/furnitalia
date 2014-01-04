<?php
// Responsive design support settings
function mobile_form_system_theme_settings_alter(&$form, &$form_state) {
  // Workaround for core bug http://drupal.org/node/943212 affecting admin themes.
  if (isset($form_id)) {
    return;
  }

// Fieldgroup: Browser support widget settings
$form['html5_browser_settings'] = array(
  '#type'   => 'fieldset',
  '#title'  => t('Web App Mode'),
  '#weight' => '-45',
);

  $form['html5_browser_settings']['mobilewebapp'] = array(
      '#type'          => 'checkbox',
      '#title'         => t('Allow iOS Safari to operate in "mobile web app" mode.'),
      '#default_value' => theme_get_setting('mobilewebapp'),
      '#description'   => t('Set the browser on iOS to run in full-screen mode. Be sure you want to do this! This setting will hide browser chrome in iOS. For more info, read <a href="!link">Apple Developer documentation on Apple-Specific Meta Tag Keys</a>.', array('!link' => 'https://developer.apple.com/library/safari/#documentation/appleapplications/reference/SafariHTMLRef/Articles/MetaTags.html')),
  );

}
