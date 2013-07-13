ANIM_FRAME_WIDTH = 542;
ANIM_BG_TOTAL_WIDTH = 2710;
FRONTPAGE_TRANSITION_DURATION = 500;
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
					
					timer = window.setTimeout(AnimateFrontPageBackground, 500);
			
			    })
			    
			    // if there was an error loading the image, react accordingly
			    .error(function () {
			      // notify the user that the image could not be loaded
			    })
			    
			    // *finally*, set the src attribute of the new image to our image
			    .attr('src', Drupal.settings.basePath + 'sites/all/themes/furnitheme/images/bg.jpg');
			    
			    
				//enter furnitalia link animation
				$("#enterFurnitalia").click(function(){
					
					//stop front page slider animation
					$('#menu-pic').stop().hide();
					clearTimeout(timer);
					
					//move away left and right keyhole parts
					$("#front-left").animate({width:'0px'}, FRONTPAGE_TRANSITION_DURATION);
					$("#front-right").animate({left:'880px', right:'5px'}, FRONTPAGE_TRANSITION_DURATION);
					
					
					//hide top overlay
					$("#front-overlay").addClass("loading").delay(FRONTPAGE_TRANSITION_DURATION).hide(1);
					
					
					//set up keyhole animation for categories
					SetUpCategoryAnimKeyHole();					
					
					//hover events for category links
					$('#bg1').hover(function(e){
						$("#category-image-pane .category-image").stop().css('display', 'none');						
						$(".category1").fadeIn({duration:500});
						KeyHoleScroll(1);
					}, function(){});
						
					$('#bg2').hover(function(e){
						$("#category-image-pane .category-image").stop().css('display', 'none');							
						$(".category2").fadeIn({duration:500});
						KeyHoleScroll(2);
					}, function(){});
					
					$('#bg3').hover(function(e){
						$("#category-image-pane .category-image").stop().css('display', 'none');						
						$(".category3").fadeIn({duration:500});
						KeyHoleScroll(3);
					}, function(){});
					
					$('#bg4').hover(function(e){
						$("#category-image-pane .category-image").stop().css('display', 'none');						
						$(".category4").fadeIn({duration:500});
						KeyHoleScroll(4);
					}, function(){});
					
					
					
				});          	
        }
	}
	
	function SetUpCategoryAnimKeyHole() {
	
		ANIM_FRAME_WIDTH = 253;
		ANIM_BG_TOTAL_WIDTH = 253 * 4;

	
		var keyHoleImg = Drupal.settings.basePath + 'sites/all/themes/furnitheme/images/bg-categores-keyhole.jpg';
		var img = new Image();
		
		$('#menu-pic').addClass('loading');
		
		$(img)
	    .load(function () {
	    
	    	$('#menu-pic-wrapper').css({
				'position':'absolute',
				'width':253,
				'left':'auto',
				'right':19,
				'z-index':'-2'
			}).insertBefore($('.keyhole'));
			
			// insert loaded image into the div 
			$('#menu-pic')
				// remove the loading class (so no background spinner), 
				.removeClass('loading')
				.css("background-image", "url(" +  keyHoleImg + ")")
				.css({'width':253, 'left':0}).show()
				.addClass("ready");
	
	    })
	    .attr('src', keyHoleImg);
				
	}
	
	function KeyHoleScroll(index) {
		if (!$('#menu-pic').hasClass("ready")) {
			return;
		}
		
		var backgroundXShift = -ANIM_FRAME_WIDTH * (index - 1);
		$('#menu-pic').stop().animate({backgroundPosition:"(" + backgroundXShift + "px 0)"}, {
			duration:500,
			easing:'linear',
		});
	}
	
	function AnimateFrontPageBackground() {

		$('#menu-pic').animate({backgroundPosition:"(" + backgroundXShift + "px 0)"}, {
			duration:1500,
			easing:'linear',
			complete:function() {				
				timer = setTimeout(AnimateFrontPageBackground, 50);
			}
		});
		backgroundXShift -= 100;
		if (backgroundXShift < -ANIM_BG_TOTAL_WIDTH) {
			backgroundXShift = ANIM_FRAME_WIDTH - 100;
			$('#menu-pic').stop().css({"background-position": backgroundXShift + "px 0"});
		}

	}
})(jQuery);