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
					$(this).hide();
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
						SetUpCategorySlider("bg1");
						KeyHoleScroll(1);
						
					}, function(){});
					
					$('#bg2').hover(function(e){
						SetUpCategorySlider("bg2");
						KeyHoleScroll(2);
						
					}, function(){});		
								
					$('#bg3').hover(function(e){
						SetUpCategorySlider("bg3");
						KeyHoleScroll(3);
						
					}, function(){});					
					
					
				});          	
        }
	}
	
	function SetUpCategorySlider(category) {
		//Category Slide Show images list
		var imgPathPrefix = Drupal.settings.basePath + "sites/all/themes/furnitheme/images/cat-images/";
		var catPreviewInfo = {
			'bg1':[{'image':imgPathPrefix + 'all/gilda_lounge.png'}, {'image':imgPathPrefix + 'all/victor_dining_table.png'}, {'image':imgPathPrefix + 'all/vivian_chair.png'}, {'image':imgPathPrefix + 'all/zina_bed.png'}],
			'bg2':[{'image':imgPathPrefix + 'italia/surround_sectional.png'}, {'image':imgPathPrefix + 'italia/sound_chair.png'},  {'image':imgPathPrefix + 'italia/samuel_table.png'}, {'image':imgPathPrefix + 'italia/tribeca_dining_table.png'}],
			'bg3':[{'image':imgPathPrefix + 'editions/B520_valeria_sofa.png'}, {'image':imgPathPrefix + 'editions/B537_sophia_recliner.png'},  {'image':imgPathPrefix + 'editions/A399_nina_sofa.png'}, {'image':imgPathPrefix + 'editions/B815_pascal_chair.png'}]
		}
		
		$('#category-image-pane').html('').PikaChoose({showCaption:false, showTooltips:false, data:catPreviewInfo[category], autoPlay:true, speed:1000});
	}
	
	function SetUpCategoryAnimKeyHole() {
	
		ANIM_FRAME_WIDTH = 250;
		ANIM_BG_TOTAL_WIDTH = 250 * 4;

	
		var keyHoleImg = Drupal.settings.basePath + 'sites/all/themes/furnitheme/images/bg-categores-keyhole.jpg';
		var img = new Image();
		
		$('#menu-pic').addClass('loading');
		
		$(img)
	    .load(function () {
	    
	    	$('#menu-pic-wrapper').css({
				'position':'absolute',
				'width':ANIM_FRAME_WIDTH,
				'left':'auto',
				'right':0,
				'z-index':'-2'
			}).insertBefore($('.keyhole'));
			
			// insert loaded image into the div 
			$('#menu-pic')
				// remove the loading class (so no background spinner), 
				.removeClass('loading')
				.css("background-image", "url(" +  keyHoleImg + ")")
				.css({'width':ANIM_FRAME_WIDTH, 'left':0}).show()
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