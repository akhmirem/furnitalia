<?php 
  $files_dir = base_path() . variable_get('file_public_path', conf_path() . '/files'); 
  $theme_dir = base_path() . path_to_theme(); 
?>
<style>
  @import url(http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Open+Sans:600);
</style>
<style>
  #moving-sale {
    font-size:1.2em; 
    line-height:1.5em;
    width:100%;
  }
  #article-main {
    text-align: center;
  }
  #reg-form {
    background-color:#981b1e;
    padding:2em 0 1em 1em;
    text-align: left;
  }
  #reg-form img {
    float: left;
    margin-right: 2em;
  }
  #reg-form #edit-submitted-email {
    text-align: left;
    color:black;
    font-weight: normal;
    border:none;
    width: 15em;
    bottom:0;
  }
  #reg-form .form-item label {
    margin:0;
    color:white;
    text-transform: none;
    font-size:.75em;
  }
  #reg-form #webform-component-first-name, #reg-form #webform-component-last-name {
    float: left;
    margin:0;
    clear:none;
  }
  #reg-form #webform-component-last-name {
    margin: 1.45em 0 0 .5em;
  }
  #reg-form #webform-component-email {
    float: left;
  }
  #reg-form .form-item .description {
    font-size: 0.7em;
    color: white;
    font-weight: bold;
  }
  #reg-form #edit-actions {
    clear: left;
    bottom: 2em;
    position: relative;
    left: 15em;
    padding: 0;
    margin: 0;
  }
  #desc {
    font-family: 'Open Sans', sans-serif;
    font-weight: 600;
    padding:0 1em;
  }
  #sub-desc {
    font-family: 'Open Sans Condensed', sans-serif;
    font-weight: 300;
    padding:0 1em;
  }
  @media only screen 
  and (min-device-width : 320px) 
  and (max-device-width : 480px) {
    #reg-form #edit-actions {
      position:initial;
    }
  }
</style>

<script>
  (function($) {
    var resizePage = function() {
      var winWidth = $(window).width();
      if (winWidth < 620) {
        $("#moving-sale").width(winWidth - 30); //30px - padding for container
      }
    }
    $(window).load(resizePage);
    $(window).resize(resizePage);
    
  })(jQuery);
</script>

<article id="moving-sale" style="">
  <div id="article-top">
    <img src="<?php print $theme_dir;?>/images/landing/header_info.jpg" alt="Save 20-70% OFF on everything in showroom"/>
  </div>
  <div id="article-main" class="clearfix">
    <div id="info">
      <p id="desc" class="furn-red"><span>Sacramento’s two premiere destinations for contemporary
    	furniture, Furnitalia and Copenhagen, are
    	going through exciting renovations and will be
    	exchanging showrooms. Together we are running a</span>
    	<span>Fantastic Moving Sale!</span></p>
      	
    	<p id="sub-desc">Much of our combined inventory must be sold off the floor
    	immediately! Floor models from both Natuzzi and Stressless
    	brands, are priced at a fraction of the retail cost. With over
    	30,000 square feet of premium home furnishings made in
    	Italy, Norway, and Denmark there is a great deal for every
    	room in your house!</p>

    </div>
    <section id="reg-form">
      <img src="<?php print $theme_dir;?>/images/landing/discount_10_perc.jpg"/>
      <?php print $webform; ?>
    </section>

   	<img src="<?php print $theme_dir;?>/images/landing/bottom.jpg" alt=""/>
   	
  </div>
</article>
