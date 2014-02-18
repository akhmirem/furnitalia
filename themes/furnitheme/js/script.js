/**
 * @file
 * A JavaScript file for the theme.
 *
 * In order for this JavaScript to be loaded on pages, see the instructions in
 * the README.txt next to this file.
 */
 
ANIM_FRAME_WIDTH = 540;
ANIM_BG_TOTAL_WIDTH = ANIM_FRAME_WIDTH * 3;
FRONTPAGE_TRANSITION_DURATION = 500;
backgroundXShift = 0;
var timer;
var timer2;
var skipAnimation = false;
var isHandHeldDevice = false;

 (function($) {
 	
 	var frontBgImg = 'sites/all/themes/furnitheme/images/front-keyhole-bg.jpg';
 	
	Drupal.behaviors.furnitalia = {
		attach: function(context, settings) {
          	
          	var params = [];
          	if (!jQuery().deparam) {
				params = $.deparam.querystring( true );
				//console.log(JSON.stringify( params, null, 2 ));
			}
			
			if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
			 	isHandHeldDevice = true;
			}

			if ("noanim" in params || isHandHeldDevice) {
				//skip animation
				skipAnimation = true;
				console.log("animation on front page is skipped");
			}
		  
			if (!skipAnimation && $("#front-overlay").length) {

			  $("#front-overlay").once(function() {
				var img = new Image();

				// wrap our new image in jQuery, then:
				$(img)
					// once the image has loaded, execute this code
					.load(function () {
					
						// insert loaded image into the div 
						$('#menu-pic')
							// remove the loading class (so no background spinner), 
							.removeClass('loading')
							.css("background-image", "url(" +  Drupal.settings.basePath + frontBgImg + ")");

						$("#stitch").hide();
						$("div.keyhole").hide();
						timer = window.setTimeout(AnimateFrontPageBackground, 500);
						if ( $.browser.msie ) {
							$("#promo").css("padding-top", "15px");
						}

					})				
				// if there was an error loading the image, react accordingly
				.error(function () {
				  // notify the user that the image could not be loaded
				})
				
				// *finally*, set the src attribute of the new image to our image
				.attr('src', Drupal.settings.basePath + frontBgImg);
				
				
				$("#warning").css('display', 'none');  
			  })
				
			}			
			    
			    
			//enter furnitalia link animation
			$("#enterFurnitalia").click(function(){
				
				clearTimeout(timer);

				//stop front page slider animation
				$('#menu-pic').stop().hide();
				$(this).hide();
								
				//move away left and right keyhole parts
				$("#front-left").animate({width:'0px'}, FRONTPAGE_TRANSITION_DURATION);
				$("#front-right").animate({left:'880px', right:'5px'}, FRONTPAGE_TRANSITION_DURATION);
				
				
				$("#warning").css('display', 'block');
				
				//hide top overlay
				$("#front-overlay").addClass("loading").delay(FRONTPAGE_TRANSITION_DURATION).hide(1);
				$("#stitch").delay(FRONTPAGE_TRANSITION_DURATION).fadeIn(FRONTPAGE_TRANSITION_DURATION);
				
				$("div.keyhole").fadeIn();
				
				//set up keyhole animation for categories
				InitRightKeyHole();	

				//start first category slideshow
				InitFeaturedSlideShow();
				
				$('#menu-pic').stop().css("background-position" , "0 0");				

					
				return false;
					
			});
			
			InitCategoryPageAnimation();
			
			if (skipAnimation || $("#front-overlay").length == 0) {
				//set up keyhole animation for categories
				InitRightKeyHole();	
			}
						
			if (skipAnimation) {
				//hide top overlay
				$("#front-overlay").hide();
				
				//start first category slideshow
				InitFeaturedSlideShow();
				$('#menu-pic').stop().css("background-position" , "0 0");
				
			}          
          
			$(".furn-gallery .gallery-item").each(function() {
				var $galleryItem = $(this);
				$galleryItem.mouseenter(
					function() {
						$galleryItem.find(".item-details").css({"display":"block"});	
					}
				).mouseleave(
					function() {
						$galleryItem.find(".item-details").css("display","none");	
					}
				).click(
					function() {
						window.location = $galleryItem.find('a.title').attr('href');
					}
				);
			});
			
			//mark li active if link inside has class 'active'
			$('a.active-menu').closest('li').addClass('active-menu');
			
			
			if(jQuery().dropkick) {
			
				$('#content select').each(function( index ) {
					//var id = $(this).attr('id');
					var sel = $(this);
					$(this).attr('tabindex', index).dropkick({
						change: function (value, label) {
							sel.trigger('change');
						}
					});
					
				});
				
				$('article.node select').each(function( index ) {
					//var id = $(this).attr('id');
					var sel = $(this);
					$(this).attr('tabindex', index).dropkick({
						change: function (value, label) {
							sel.trigger('change');
						}
					});
					
				});
				
			}
					
			
			// ------------ SET UP REQUEST/CONTACT POPUP AJAX EVENTS
			var element_settings = {};
			element_settings.event = "click";
			element_settings.progress = {type: false};
			
			$('#contact-us').once(function() {
				element_settings.url = Drupal.settings.basePath + 'contact/ajax/';
				
				var ajax = new Drupal.ajax("#contact-us", $('#contact-us')[0], element_settings);
			});
			$('article a.appointment').once(function() {
				element_settings.url = Drupal.settings.basePath + 'contact/ajax/';
				
				var ajax = new Drupal.ajax("article a.appointment", $('article a.appointment')[0], element_settings);
			});
			$('a.contact').once(function() {
				element_settings.url = Drupal.settings.basePath + 'contact/ajax/';
				
				var ajax = new Drupal.ajax("a.contact", $('a.contact')[0], element_settings);
			});
			
			$('#request-quote').once(function() {
				element_settings.url = $(this).attr('href');
				
				var ajax = new Drupal.ajax("#request-quote", $('#request-quote')[0], element_settings);
				
			});
			
						
			//item description page thumbnail gallery			
			// build fancybox group
			$("#pikame").once(function() {
				fancyGallery = [];
				$(this).find("a").each(function(i){
					// build fancybox gallery
				    fancyGallery[i] = {"href" : this.href, "title" : this.title};
				}).end().PikaChoose({
				    autoPlay : false, // optional
				    showCaption:false,
				    // bind fancybox to big images element after pikachoose is built
				    buildFinished: fancy
				}); // PikaChoose
			});
			
			$("#zoom-in").click(function(e){
				$("div.pika-stage a").trigger('click');
				return false;
			});
			
			// ----------- SCROLL TO TOP LINK --------------------
			$("#scroll-top a").click(function() {
				
				var offset = $("#main-content").offset();
				var scrollTarget = $("#main-content");
				while ($(scrollTarget).scrollTop() == 0 && $(scrollTarget).parent()) {
					scrollTarget = $(scrollTarget).parent();
				}
				// Only scroll upward
				if (offset.top - 10 < $(scrollTarget).scrollTop()) {
					$(scrollTarget).animate({scrollTop: (offset.top - 10)}, 500);
				}
				
				return false;

			});
			
			$("#item-video-img").unbind('click').click(function() {
				$.fancybox.open($('div.embedded-video'), {
					width:800,
					height:600,
					closeBtn:true,
					closeClick:false,
					mouseWheel:true,
				});
			});
			
			$("article a.promo-link").on("click", function(e) {
				var msg = '<div style="width:400px; height:300px"><p style="font-size:1.3em;line-height:135%" class="furn-ucase furn-red">We move, you save!</p><p>Furnitalia is going through exciting renovations and we are launching MOVING SALE starting <span class="furn-red">January 23, 2014</span>. Now is the time to shop and save!</p> <p style="font-size:1.2em" class="furn-ucase furn-red">SALE 20% - 70% OFF IN STORE ONLY.</p> <p>Click <a href="/moving-sale" style="text-decoration:underline;color:blue;">here</a> for more info</p></div>'

				$.fancybox.open(msg,
				{
					closeBtn:true,
					closeClick:false,
					autoDimensions:false,
					width:300,
					height:200
				});
				
				e.stopPropagation();
				e.preventDefault();

			});
			
			/*$("#promo img").click(function() {
				openPromoImg();
				return false;
			});*/
			
			/*$("#chat-online").click(function() {
				$.fancybox.open('<p style="">LiveHelp is unavailable at this moment. Please call us at 1-800-387-4825 or 916-484-0333 (Sacramento, CA); 916-742-7900 (Roseville, CA)</p>',
				{
					closeBtn:true,
					closeClick:false,
				});

				return false;
			});*/
			
			$("#block-webform-client-block-33 input.email").focus(function() {
				$(this).val("");
			}).blur(function() {
				if ($(this).val().trim() == "") {
					$(this).val("EMAIL");
				}				
			});
			
        }
	}
	
	var fancyGallery = []; // fancybox gallery group
	var fancy = function (self) {
	    // bind click event to big image
	    self.anchor.on("click", function(e){
			// find index of corresponding thumbnail
			var pikaindex = $("#pikame").find("li.active").index();
	      // open fancybox gallery starting from corresponding index
	      $.fancybox(fancyGallery,{
	        // fancybox options
	        "cyclic": true, // optional for fancybox v1.3.4 ONLY, use "loop" for v2.x
	        "index": pikaindex // start with the corresponding thumb index
	      });
	      return false; // prevent default and stop propagation
	     }); // on click
	     
	     if ($('#pikame li').length > 4) {
		     $("#pikame").wrap($('<div id="pikawrapper"></div>'));
		     $("#pikawrapper").jScrollPane({
			     verticalDragMinHeight: 25,
				 verticalDragMaxHeight: 25,
				 horizontalDragMinWidth: 25,
				 horizontalDragMaxWidth: 25
		     });
	     }
	 }
	 
	 
	 function InitCategoryPageAnimation() {
	 
		 if (!jQuery().accordion) {
		 	return;
		 }

	 	//main navigation menu accordeon
		$(".accordion").once(function() {
			
			$(".accordion-inner").accordion({
				icons:false,
				collapsible: true,
				active: false,
				heightStyle:'content',
				animate:300
			});
			
			$("div.ui-accordion-content div.item-list div.accordion-inner").each(function(index, val) {
				if ($(this).find('a.active-menu').length) {
					$(this).accordion("option", "active", 0);
				}
			});	

		});
		
	}
	
	function InitFeaturedSlideShow() {
		//Featured Slide Show images list		
		//var imgPathPrefix = Drupal.settings.basePath + "sites/default/files/promo/black-friday/";
		var imgPathPrefix = Drupal.settings.basePath + "sites/default/files/promo/moving_sale/";
		var link = Drupal.settings.basePath + "moving-sale?utm_source=main&utm_medium=featured&utm_campaign=moving-sale";
		var featuredImgs = [
			/*{'image':imgPathPrefix + "Natuzzi_Editions-B645-Stefano-Sectional.png"},
			{'image':imgPathPrefix + "Exstra_TulipTable.png"},
			{'image':imgPathPrefix + "Natuzzi_Italia-Soud_Chair.png"},				
			{'image':imgPathPrefix + "Natuzzi-Italia_Bateau.png"},				
			{'image':imgPathPrefix + "Natuzzi_Editions-B815_Pascal_Chair.png"},			
			{'image':imgPathPrefix + "Natuzzi_Italia-Surround.png"},								
			{'image':imgPathPrefix + "Natuzzi_Editions-B520-Valeria-Sofa.png"},
			{'image':imgPathPrefix + "Italsofa_Twister.png"},
			{'image':imgPathPrefix + "Natuzzi_Editions-B725_Enzo_Sofa.png"}*/
			{'image':imgPathPrefix + "moving-sale-500x235.gif", link:link}
		]
		
		//!Promo
		$('#category-image-pane').html('').PikaChoose({
			transition:[5],
			showCaption:false, 
			showTooltips:false, 
			data:featuredImgs, 
			autoPlay:true, 
			speed:3000 /*, 
			buildFinished: function() {

				$('#category-image-pane div.pika-stage').click(function(){					
					openPromoImg();
					return false;
				});
			}*/
		});
	}
	
	function openPromoImg() {
		var promoImgPath = Drupal.settings.basePath + "sites/default/files/promo/moving-sale/";
		var img = new Image();

		$.fancybox.showLoading();
		
		// wrap our new image in jQuery, then:
		$(img)
			// once the image has loaded, execute this code
			.load(function () {
			
				$.fancybox.hideLoading();
				$.fancybox.open('<img src="'+ promoImgPath +'" alt="Holiday sale"/>',
				{
					closeBtn:true,
					closeClick:false,
					mouseWheel:true,
					openEffect	: 'none',
					closeEffect	: 'none'
				});
				
				
			})					
			// *finally*, set the src attribute of the new image to our image
			.attr('src', promoImgPath);
	}
	
	function InitRightKeyHole() {
	
		ANIM_FRAME_WIDTH = 250;
		
		$('#menu-pic-wrapper').once(function() {
			$(this).css({
				'position':'absolute',
				'width':ANIM_FRAME_WIDTH,
				'left':'auto',
				'right':0,
				'z-index':'1'
			}).insertBefore($('div.keyhole'));
			$('#menu-pic').stop().hide();
		})
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
			backgroundXShift = ANIM_FRAME_WIDTH;
			$('#menu-pic').stop().css({"background-position": backgroundXShift + "px 0"});
			timer = setTimeout(AnimateFrontPageBackground, 50);
		}

	}	
	
	//-------------------------------------------------------------------------------------------
	/**
	 * Ajax delivery command to switch among tabs by ID.
	 */
	if (Drupal.ajax) {
	Drupal.ajax.prototype.commands.openPopup = function (ajax, response, status) {
	 	// response.data is a value setted in 'data' key of command on PHP side.
	  
	  	
	  	// Get information from the response. If it is not there, default to
	    // our presets.
	    var wrapper = response.selector ? $(response.selector) : $(ajax.wrapper);
	    var method = response.method || ajax.method;
	    var effect = ajax.getEffect(response);
	
	    // We don't know what response.data contains: it might be a string of text
	    // without HTML, so don't rely on jQuery correctly iterpreting
	    // $(response.data) as new HTML rather than a CSS selector. Also, if
	    // response.data contains top-level text nodes, they get lost with either
	    // $(response.data) or $('<div></div>').replaceWith(response.data).
	    var new_content_wrapped = $('<div></div>').html(response.data);
	    var new_content = new_content_wrapped.contents();
	
	    // For legacy reasons, the effects processing code assumes that new_content
	    // consists of a single top-level element. Also, it has not been
	    // sufficiently tested whether attachBehaviors() can be successfully called
	    // with a context object that includes top-level text nodes. However, to
	    // give developers full control of the HTML appearing in the page, and to
	    // enable Ajax content to be inserted in places where DIV elements are not
	    // allowed (e.g., within TABLE, TR, and SPAN parents), we check if the new
	    // content satisfies the requirement of a single top-level element, and
	    // only use the container DIV created above when it doesn't. For more
	    // information, please see http://drupal.org/node/736066.
	    if (new_content.length != 1 || new_content.get(0).nodeType != 1) {
	      new_content = new_content_wrapped;
	    }
	
	    // If removing content from the wrapper, detach behaviors first.
	    switch (method) {
	      case 'html':
	      case 'replaceWith':
	      case 'replaceAll':
	      case 'empty':
	      case 'remove':
	        var settings = response.settings || ajax.settings || Drupal.settings;
	        Drupal.detachBehaviors(wrapper, settings);
	    }
	
	    // Add the new content to the page.
	    wrapper[method](new_content);
	
	    // Immediately hide the new content if we're using any effects.
	    if (effect.showEffect != 'show') {
	      new_content.hide();
	    }
	
	    // Determine which effect to use and what content will receive the
	    // effect, then show the new content.
	    if ($('.ajax-new-content', new_content).length > 0) {
	      $('.ajax-new-content', new_content).hide();
	      new_content.show();
	      $('.ajax-new-content', new_content)[effect.showEffect](effect.showSpeed);
	    }
	    else if (effect.showEffect != 'show') {
	      new_content[effect.showEffect](effect.showSpeed);
	    }
	
	    // Attach all JavaScript behaviors to the new content, if it was successfully
	    // added to the page, this if statement allows #ajax['wrapper'] to be
	    // optional.
	    if (new_content.parents('html').length > 0) {
	      // Apply any settings from the returned JSON if available.
	      var settings = response.settings || ajax.settings || Drupal.settings;
	      Drupal.attachBehaviors(new_content, settings);
	    }
	    
	    //open pop-up dialog
	    //console.log("attempting to display popup");
		$.fancybox.open($('#dialog-form'), {
			//width:800,
			//height:600,
			closeBtn:true,
			closeClick:false,
			mouseWheel:true,
			openEffect	: 'none',
			closeEffect	: 'none'
		});
	  
	  
	};
	}

})(jQuery);

