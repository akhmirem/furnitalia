<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01+RDFa 1.1//EN"
   "http://www.w3.org/MarkUp/DTD/html401-rdfa11-1.dtd">
<html lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>"<?php print $rdf_namespaces; ?>>

<head profile="<?php print $grddl_profile; ?>">
	<?php print $head; ?>
	<title><?php print $head_title; ?></title>
  <!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
  <meta name="MobileOptimized" content="width">
  <meta name="HandheldFriendly" content="true">
  <!--<meta name="viewport" content="width=device-width, height=device-height, initial-scale=0.68, user-scalable=yes">-->
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=yes">
  <meta name="robots" content="noindex, nofollow">
  <meta http-equiv="cleartype" content="on">
  <?php if ($meta_mobilewebapp): ?>
  <meta name="apple-mobile-web-app-capable" content="yes">
  <?php endif; ?>
  <?php print $styles; ?>
  <?php print $scripts; ?>
</head>

<body class="<?php print $classes; ?>" <?php print $attributes;?>>
  <div id="skip-link">
    <a href="#main-content" class="element-invisible element-focusable"><?php print t('Skip to main content'); ?></a>
  </div>
  <?php print $page_top; ?>
  <?php print $page; ?>

  <script language='JavaScript1.1' src='http://pixel.mathtag.com/event/js?mt_id=411755&mt_adid=119881&v1=&v2=&v3=&s1=&s2=&s3='></script>

  <?php print $page_bottom; ?>
</body>
</html>
