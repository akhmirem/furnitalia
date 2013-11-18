<?php
/**
 * @file
 * Zen theme's implementation to display a single Drupal page.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $secondary_menu_heading: The title of the menu used by the secondary links.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['header']: Items for the header region.
 * - $page['navigation']: Items for the navigation region, below the main menu (if any).
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['footer']: Items for the footer region.
 * - $page['bottom']: Items to appear at the bottom of the page below the footer.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see zen_preprocess_page()
 * @see template_process()
 */
?>

<div id="page" class="clearfix">

  <header id="header" role="banner">

    <?php if ($logo): ?>
      <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo"><img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" /></a>
    <?php endif; ?>

    <?php if ($site_name || $site_slogan): ?>
      <hgroup id="name-and-slogan">
        <?php if ($site_name): ?>
          <h1 id="site-name">
            <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><span><?php print $site_name; ?></span></a>
          </h1>
        <?php endif; ?>

        <?php if ($site_slogan): ?>
          <h2 id="site-slogan"><?php print $site_slogan; ?></h2>
        <?php endif; ?>
      </hgroup><!-- /#name-and-slogan -->
    <?php endif; ?>

    <?php print render($page['header']); ?>
    <section id="header-phone">
        <a href="tel:+18003874825" class="main-phone">1-800-387-4825</a> &nbsp; | &nbsp; 
		<a href="tel:+19164840333">916-484-0333</a> <a href="<?php print url('stores') ?>"> (Sacramento, CA)</a> &nbsp; | &nbsp;
		<a href="tel:+19167427900">916-742-7900</a> <a href="<?php print url('stores') ?>"> (Roseville, CA)</a>		
    </section>

  </header>

  <div id="main" class="clearfix">

    <div id="content" class="column" role="main">
    
      <?php print render($page['content_top']); ?>
      
      <?php print $breadcrumb; ?>
      
      <a id="main-content"></a>
      
      <?php print render($title_prefix); ?>
      
      <?php if ($title && !$is_front && $show_title): ?>
        <h1 class="title" id="page-title"><?php print $title; ?></h1>
        <hr class="title" />
      <?php endif; ?>
            
      <?php print render($title_suffix); ?>
      
	  <?php if ($contact_page) : ?>
	    <aside class="contact sidebars clearfix contact-page">
			<a href="#contact" title="Contact" id="contact-us">Contact us</a>
			<a href="#chat" title="Chat online" id="chat-online">Chat online</a>
	    </aside>
	  <?php endif; ?>
      
      <?php print $messages; ?>
      <?php print render($tabs); ?>
      <?php print render($page['help']); ?>
      
      <?php if ($action_links): ?>
        <ul class="action-links"><?php print render($action_links); ?></ul>
      <?php endif; ?>
      
      <?php print render($page['content']); ?>
      
    </div><!-- /#content -->
	
	<section id="left-section">
	    <?php
	      // Render the sidebars to see if there's anything in them.
	      $left  = render($page['left']['furn_menu']);
	    ?>
	
	    <?php if ($left): ?>
	      <aside class="sidebars menu-left">
	        <?php print $left; ?>
			<?php print render($page['left']); ?>
	      </aside><!-- /.sidebars -->
	    <?php endif; ?>
	    
	    <?php if (!$contact_page) : ?>
	    <aside class="contact sidebars">
			<a href="#chat" title="Chat online" id="chat-online">Chat online</a>
			<a href="#contact" title="Contact" id="contact-us">Contact us</a>
     		
     		<!--<DIV id="craftysyntax_4"><script type="text/javascript" src="http://www.furnitalia.com/livehelp/livehelp_js.php?eo=0&amp;department=4&amp;serversession=1&amp;pingtimes=10&amp;dynamic=Y&amp;creditline=W"></script></DIV>-->
     		
	    </aside>
	    <?php endif; ?>
	    
	    <div id="dialog-form" style="display:none">
		  <p>Javascript must be enabled in your browser to submit a request.</p>
		</div>
	
	</section>	
	
	<?php if ($show_promo) : ?>
	<section id="promo" class="clearfix">
		<!-- <img src="<?php print $base_path . path_to_theme(); ?>/images/dummy-promo.gif"/> -->
		<?php include_once("promo.tpl.php"); ?>
	</section>
	<?php endif; ?>

  </div><!-- /#main -->

  <footer>
	<nav id="footer-info-menu"><?php print render($footer_info_menu); ?></nav>
	
	<!-- AddThis Follow BEGIN -->
	<div class="addthis_toolbox addthis_32x32_style addthis_default_style">
		<a class="addthis_button_facebook_follow" addthis:userid="pages/Furnitalia/65283884934"></a>
		<a class="addthis_button_twitter_follow" addthis:userid="furnitalia"></a>
		<a class="addthis_button_youtube_follow" addthis:userid="Furnitalia"></a>
		<a class="addthis_button_flickr_follow" addthis:userid="furnitalia/"></a>
	</div>
	<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-522150ba58af72c3"></script>
	<!-- AddThis Follow END -->


	<nav id="footer-user-menu"><?php print render($footer_user_menu); ?></nav>
	<nav id="footer-policy-menu"><?php print render($footer_policy_menu); ?></nav>	
	<?php print  l("Catalogs", "catalogs", array('attributes' => array('id' => 'catalogs'))); ?>  
	<?php print render($page['footer']['webform_client-block-33']); ?>	  
  </footer>
  
  <?php if ($show_keyhole) : ?>
  	<div class="keyhole">&nbsp;</div>
  <?php endif; ?>
  <div id="stitch">&nbsp;</div>
</div><!-- /#page -->

<?php print render($page['bottom']); ?>
