<?php global $base_path, $theme_path; ?>

<div id="container">
	<header role="banner">
	
	  <?php if ($logo): ?>
	    <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo">
	      <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
	    </a>
	  <?php endif; ?>
	
	  <hgroup>
	    <?php if ($site_name): ?>
	      <h1 id="site-name">
	        <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><span><?php print $site_name; ?></span></a>
	      </h1>
	    <?php endif; ?>
	
	    <?php if ($site_slogan): ?>
	      <h2 id="site-slogan"><?php print $site_slogan; ?></h2>
	    <?php endif; ?>
	  </hgroup>
	  
	  <section id="header-phone">
        <a href="tel:+18883874825" class="main-phone furn-red">1-888-387-4825</a>
        <!-- &nbsp; | &nbsp; 
		<a href="tel:+19164840333">916-484-0333</a> <a href="<?php print url('stores') ?>"> (Sacramento, CA)</a> &nbsp; | &nbsp;
		<a href="tel:+19167427900">916-742-7900</a> <a href="<?php print url('stores') ?>"> (Roseville, CA)</a>		-->
	  </section>
	  
	  <?php print render($page['page_top']); ?>
	
	</header>
	
	
	<nav id="main-nav" class="accordion clearfix" role="navigation">
		<img src="<?php print $base_path . $theme_path ?>/images/icons_logos/furnitalia_logo_white.png" alt="Furnitalia"/>
		<?php print render($page['nav']); ?>
		
		<nav id="footer-info-menu"><?php print render($footer_info_menu); ?></nav>
		<nav id="footer-user-menu"><?php print render($footer_user_menu); ?></nav>
		<nav id="footer-policy-menu"><?php print render($footer_policy_menu); ?></nav>	
		<?php print  l("Catalogs", "catalogs", array('attributes' => array('id' => 'catalogs'))); ?>  
		<?php print  l("Full Site", "http://www.furnitalia.local:8888/?desktop=1", array('attributes' => array('id' => 'full-site'))); ?>  		
	</nav>
	
	<main role="main" id="main-content">
	
	  <?php if ($page['highlighted']): ?><div id="highlighted"><?php print render($page['highlighted']); ?></div><?php endif; ?>
	  
	  <?php print $messages; ?>
	  
	  <?php if ($breadcrumb): ?>
		<nav id="breadcrumb"><?php print $breadcrumb; ?></nav>
	  <?php endif; ?>
	
	  <!--<?php if ($tabs): ?><div class="tabs"><?php print render($tabs); ?></div><?php endif; ?>-->
	
	  <a id="main-content"></a>
	
	  <?php if ($title && $show_title): ?>
	    <?php
	    // open article tag if page is a node
	    if (($page) && (arg(0) == 'node')): ?>
	      <article role="article">
	    <?php endif; ?>
	  <?php print render($title_prefix); ?>
	    <h1 class="title furn-red furn-ucase furn-e2-a" id="page-title"><?php print $title; ?></h1>
	  <?php endif; ?>
	  <?php print render($title_suffix); ?>
	
	  <?php print render($page['help']); ?>
	
	  <?php if ($action_links): ?><nav><ul class="action-links"><?php print render($action_links); ?></ul></nav><?php endif; ?>
	
	  <?php print render($page['content']); ?>
		
	</main><!--/main -->
	
	<?php if ($page['supplementary']): ?>
	  <aside id="supplementary" class="column sidebar" role="complementary">
	  <?php print render($page['supplementary']); ?>
	  </aside>
	<?php endif; ?>
	
	  <?php if ($page['footer']): ?>
	    <footer role="contentinfo" id="footer">
	      	<?php print render($page['footer']); ?>
	      	
	      	<section id="email-us">
	      		<a href="mailto:admin@furnitalia.com?Subject=Inquiry%20from%20Furnitalia%20website" target="_top" title="E-mail us for further assistance"><img src="<?php print $base_path . $theme_path; ?>/images/icons_logos/email_icon_31x34.png" alt="E-Mail Us"/><span class="furn-grey furn-ucase">E-mail us for further assistance</span></a>
	      	</section>
	      	
		  	<!-- AddThis Follow BEGIN -->
			<div class="addthis_toolbox addthis_32x32_style addthis_default_style" id="social-icons">
			<a class="addthis_button_facebook_follow" addthis:userid="pages/Furnitalia/65283884934"></a>
			<a class="addthis_button_twitter_follow" addthis:userid="furnitalia"></a>
			<a class="addthis_button_youtube_follow" addthis:userid="Furnitalia"></a>
			<a class="addthis_button_flickr_follow" addthis:userid="furnitalia/"></a>
			</div>
			<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-522150ba58af72c3"></script>
			<!-- AddThis Follow END -->
			
	    </footer>
	  <?php endif; ?>
</div>  
