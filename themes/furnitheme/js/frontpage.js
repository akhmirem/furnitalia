ANIM_FRAME_WIDTH = 542;
backgroundXShift = 0;
var timer;
var timer2;


(function($) {
	Drupal.behaviors.frontpage = {
          attach: function(context, settings) {
          	
			  var img = new Image();
  
			  // wrap our new image in jQuery, then:
			  $(img)
			    // once the image has loaded, execute this code
			    .load(function () {
			    
					// insert loaded image into the div 
					$('#menu-pic')
						// remove the loading class (so no background spinner), 
						.removeClass('loading')
						.css("background-image", "url(" +  Drupal.settings.basePath + "sites/all/themes/furnitheme/images/bg.jpg)");
					
					//$("#menu-pic-wrapper").css({"background-image":"url(images/bg.jpg)", "background-position":"300px 0"});
					
					timer = window.setTimeout(AnimateBackground, 500);
			
			    })
			    
			    // if there was an error loading the image, react accordingly
			    .error(function () {
			      // notify the user that the image could not be loaded
			    })
			    
			    // *finally*, set the src attribute of the new image to our image
			    .attr('src', Drupal.settings.basePath + 'sites/all/themes/furnitheme/images/bg.jpg');
			    
			    
				//enter furnitalia link animation
				$("#enterFurnitalia").click(function(){
					//var ajax = new Drupal.ajax("#contact-us", $('#contact-us')[0], element_settings);
					//ANIM_FRAME_WIDTH = 200;
					window.clearTimeout(timer);
					
					$('#menu-pic').hide();
					
					$("#front-left").animate({width:'0px'}, 1000, function() {
						//$(this).css({width:900}).find("#main-nav").removeClass("hidden").accordion({icons:false,collapsible: true });
						//$("#top-menu").removeClass("front");
					});
					$("#front-right").animate({left:'900px', right:'5px', width:'0px'}, 1000, function() {
						//$(this).removeClass("front");
					});
					
					//$('#menu-pic').hide().delay(1000).css({width:ANIM_FRAME_WIDTH, left:920, "background-image":"url(\"images/bg-gallery.png\")"}).show();
					
					$("#front-overlay").addClass("loading").delay(1000).hide(1);
					
					timer = window.setTimeout(AnimateKeyholeBackgroundCategoryPage, 3500);
					
				});          	
        }
	}
	
	function AnimateKeyholeBackgroundCategoryPage() {
		$('#menu-pic-wrapper').css({
			'position':'absolute',
			'width':253,
			'left':'auto',
			'right':19,
			'z-index':'-2'
		}).insertBefore($('.keyhole'));
		$('#menu-pic').css({'width':253, 'left':0}).show();		
		
		timer = window.setTimeout(AnimateBackground, 500);
	}
	
	function AnimateBackground() {


		$('#menu-pic').animate({backgroundPosition:"(" + backgroundXShift + "px 0)"}, {
			duration:1500,
			easing:'linear',
			complete:function() {				
				if (loop) {
					timer = window.setTimeout(AnimateBackground, 50);
				} else {
					window.clearTimeout(timer);
				}
			}
		});
		backgroundXShift -= 100;
		if (backgroundXShift < -2710) {
			backgroundXShift = ANIM_FRAME_WIDTH - 100;
			$('#menu-pic').stop().css({"background-position": backgroundXShift + "px 0"});
		}

	}
})(jQuery);