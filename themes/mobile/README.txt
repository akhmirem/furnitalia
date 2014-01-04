Mobile theme HTML5 version for Drupal 7
---------------------------------------

## DESCRIPTION

Mobile theme is designed to present a clean, readable version of the website for consumption on mobile devices. It can be used by itself or as a base theme for a custom mobile theme you develop.

### Mobile Light

This is a minimalist, clean theme for mobile devices only. It has a light design.

### Mobile Dark

This is a minimalist, clean theme for mobile devices only. It has a dark design.

### Mobile

This is the base theme for Mobile Light and Mobile Dark -- or your own custom theme -- and is really stripped down. This is intended for use as a base theme only.

## INSTALLATION

Simply copy the Mobile theme folder and contents into your /sites/all/themes folder (or in the appropriate sites folder for a mobile subdomain URI, as appropriate) and enable the theme you want. If you are creating your own custom child theme, you don't need to enable Mobile at all, just include it in your code base.

## TIPS

### User scalable

Mobile is coded to allow users to zoom in. If you want to disable that in your theme, copy Mobile's html.tpl.php into your own theme, and edit the meta name viewport line to read:

  <meta name="viewport" content="width=device-width, target-densityDpi=160dpi, initial-scale=1, user-scalable=no">

Consider using Modernizr to keep your css clean while supporting various broswers. http://drupal.org/project/modernizr

## CREDITS

### Mobile Version 7.x-3.x 

Developer and Maintainer:
  Laura Scott
  http://drupal.org/user/18973
  Sponsored by http://pingv.com

### Mobile Version 6.x, 7.x-1.x 

Maintainer:
  Richard Eriksson
  http://justagwailo.com/

### Original Author of http://drupal.org/project/mobile

Bèr Kessels 
Sponsored by http://www.webschuur.com/
