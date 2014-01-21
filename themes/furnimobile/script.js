
 (function($) {
 	
 	var menuStatus;
 	var galleryView = "grid";
 				
	Drupal.behaviors.furnitalia = {
		attach: function(context, settings) {
		
			$("header div.region-page-top ul.menu").once(function() {
				$menuElem = $("<li><a href=\"#\" title=\"menu\" id=\"menu-toggle\" class=\"furn-grey\"><img src=\"" + Drupal.settings.basePath + "sites/all/themes/furnimobile/images/buttons/menu.png" + "\"/></a></li>");
				$(this).find('li:first').removeClass("first");
				$(this).prepend($menuElem.addClass("first"));
			});
			
			$mainNav = $('#main-nav');
			$container = $("#container");
			$mainNav.addClass("menu-left").insertBefore("#container");
			$mainNav.height(Math.max($("#container").height(), $mainNav.height()));
	
			$("#menu-toggle").click(function(){
				
				if(menuStatus != true) {
					$mainNav.css("visibility", "visible");					
					$container.animate({
						left: $mainNav.outerWidth(),
					}, 300, function(){menuStatus = true});
					console.log("menu expand"); 
					return false;
					
				} else {
					$container.animate({
						left: "0px",
					}, 300, function(){menuStatus = false; $mainNav.css("visibility", "hidden");});
					console.log("menu closed"); 
					return false;
				}
			});
		
			/*$('body').live("swipeleft", function(e){
				console.log($(e.target).parents("#image-gallery").length);
				if ($(e.target).parents("#image-gallery").length == 0) {
				if (menuStatus){	
					$container.animate({
						marginLeft: "0px",
					}, 300, function(){menuStatus = false; $mainNav.css("visibility", "hidden");});
				}
				}
			});
			
			$('body').live("swiperight", function(e){
				console.log($(e.target).parents("#image-gallery").length);
				if ($(e.target).parents("#image-gallery").length == 0) {
				if (!menuStatus){	
					$mainNav.css("visibility", "visible");
					$container.animate({
						marginLeft: $mainNav.outerWidth(),
					}, 300, function(){menuStatus = true});
				}
				}
			}); */
			
			InitAccordonMenu();
			
			//$(this).once(function() {
				InitItemPageGallery();	
			//});
			
			InitDropDownMenu();
			
			
			InitGalleryControls();
			
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
			
			var element_settings = {};
			element_settings.event = "click";
			element_settings.progress = {type: false};

			$("#request-quote").once(function() {
				element_settings.url = $(this).attr('href');
				
				var ajax = new Drupal.ajax("#request-quote", $('#request-quote')[0], element_settings);
				
				/*$(this).click(function(e) {
					$("#product-additional").append('<h3 class="furn-ucase furn-red">REQUEST QUOTE</h3><div id="request-data"></div>').accordion('destroy');
					
					e.preventDefault();
				});*/
				
			});
			
			ProcessTxtToTruncate();
			
			$("article a.promo-link").on("click", function(e) {
				/*$.fancybox.open($('div.embedded-video'), {
					width:800,
					height:600,
					closeBtn:true,
					closeClick:false,
					mouseWheel:true,
				});*/
				$.fancybox.open('<p style="">We move, you save!<br/>Furnitalia is going through exciting renovations and we are launching MOVING SALE starting January 28, 2014. Now is the time to shop and save! 20% - 70% off. Click <a href="#" style="text-decoration:underline">here</a> for more info</p>',
				{
					closeBtn:true,
					closeClick:false,
					width:200,
					height:100
				});
				
				e.preventDefault();

			});
	
		}
	}
	
	 
	function InitAccordonMenu() {
	 
		 if (!jQuery().accordion) {
		 	return;
		 }
	
	 	//main navigation menu accordeon
		$("#supplementary").once(function() {
			
			$(".accordion-inner").accordion({
				icons:false,
				collapsible: true,
				active: false,
				heightStyle:'content',
				animate:300
			});
			
			$("div.accordion-inner", $(this)).each(function(index, val) {
				if ($(this).find('a.active-menu').length) {
					$(this).accordion("option", "active", 0);
				}
			});	
	
		});
		
		$("#product-additional").once(function(){
			$(this).accordion({
				icons:false,
				collapsible: true,
				active: false,
				heightStyle:'content',
				animate:300,
				active:0
			});
		});
		
	}
	
	function InitItemPageGallery() {
		if ($('#image-gallery').length > 0) {
			var $example = $('#image-gallery');
			$frame = $('.frame', $example);
			
			var $holder = $example;
	
			$tabsbar = $('#thumbs');
			$pagesbar = $('ul.gal-pager');

			var pagesOn = 1;
			if ($example.hasClass('no-pager')) {
				pagesOn = 0;
			}
	
			function calculator(width){
				var percent = '25%';
				if (width <= 480) {
					percent = '80%';
				}
				else if (width <= 768) {
					percent = '50%';
				}
				else {
					percent = 0;
				}
	
				if (percent !== 0) {
					$holder.addClass('mSMobile');
				}
				else {
					$holder.removeClass('mSMobile');
					$('li', $tabsbar).css('width', 'auto');
				}
	
				return percent;
			};
	
			var thumbnailSize = calculator($(window).width());		
		
			//$frame.data("mightyslider", "width:" + $(window).width());
			$frame.mightySlider({
				speed: 1000,
				easing: 'easeOutExpo',
				autoScale: 1,
				//autoResize: 1,
				viewport: 'stretch', //'fill',
				
				// Navigation options
				navigation: {
					slideSize: '100%'
				},
	
				// Dragging
				dragging: {
					swingSpeed:    0.1,
					touchDragging: 0
				},
	
				// Pages
				pages: {
					activateOn: 'click',
					pagesBar: $pagesbar[0]
				},
	
				// Commands
				commands: {
					pages: pagesOn,
					buttons: pagesOn,
					thumbnails: pagesOn 
				},
				
				thumbnails: {
					thumbnailsBar: $tabsbar,
					thumbnailNav:'centered'
				}/*,
	
				// Cycling
				cycling: {
					cycleBy: 'pages'
				}*/
			}, 
			{
				load: function() {
					if (pagesOn) {
						$pagesbar.addClass('mSPages').css('visibility', 'visible');
					} else {
						$pagesbar.css('visibility','hidden');
					}
				}
			});
		}
	}
	
	function InitDropDownMenu() {
			
		if(jQuery().dropkick) {
		
			$('main select').each(function( index ) {
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

	}
	
	function InitGalleryControls() {
		$("#view-grid").click(function(e) {
			if (galleryView != "grid") {
				console.log("Switched to grid view");
				galleryView = "grid";
				$('body').removeClass("gallery-list").addClass("gallery-grid");
			}
			e.preventDefault();
		});
		
		$("#view-list").click(function(e) {
			if (galleryView != "list") {
				console.log("Switched to list view");
				galleryView = "list";
				$('body').removeClass("gallery-grid").addClass("gallery-list");
			}
			e.preventDefault();
		});
	}
	
	function ProcessTxtToTruncate() {
		var maxheight=218;
		var showText = "Expand";
		var hideText = "Show Less";
		
		$('.furn-truncate').each(function () {
			var text = $(this);
			maxheight = (!isNaN($(this).data('maxheight')) ? $(this).data('maxheight') : maxheight);
			
			if (text.height() > maxheight){
		    	text.css({ 'overflow': 'hidden','height': maxheight + 'px' });
		
				var link = $('<a href="#" style="color:#981b1e;border-bottom:1px dashed #981b1e;float:right">' + showText + '</a>');
				var linkDiv = $('<div class="furn-ucase"></div>');
				linkDiv.append(link);
				$(this).after(linkDiv);
		
				link.click(function (event) {
					event.preventDefault();
					if (text.height() > maxheight) {
						$(this).html(showText);
						text.css('height', maxheight + 'px');
					} else {
						$(this).html(hideText);
						text.css('height', 'auto');
					}
				});
			}       
		});
	}
	
	//-------------------------------------------------------------------------------------------
	/**
	 * Ajax delivery command to switch among tabs by ID.
	 */
	if (Drupal.ajax) {
	Drupal.ajax.prototype.commands.furnAjax = function (ajax, response, status) {
	 	// response.data is a value setted in 'data' key of command on PHP side.
	  
	 	//don't process response multiple times
	 	if($("#request-data").length > 0) {
		 	return;
	 	}
	  	
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
	    //var new_content_wrapped = $('<div></div>').html(response.data);
	    //var new_content = new_content_wrapped.contents();
	    var new_content = $(response.data);
	
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
	    //if (new_content.length != 1 || new_content.get(0).nodeType != 1) {
	    //  new_content = new_content_wrapped;
	    //}
	
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
	    
   		//$("#product-additional").append('<h3 class="furn-ucase furn-red">REQUEST QUOTE</h3><div id="request-data">' + response.data + '</div>').accordion('destroy').accordion();
   		$("#product-additional").accordion('destroy').accordion({
			icons:false,
			collapsible: true,
			active: false,
			heightStyle:'content',
			animate:300,
			active:2
		});
	
	    // Attach all JavaScript behaviors to the new content, if it was successfully
	    // added to the page, this if statement allows #ajax['wrapper'] to be
	    // optional.
	    if (new_content.parents('html').length > 0) {
	      // Apply any settings from the returned JSON if available.
	      var settings = response.settings || ajax.settings || Drupal.settings;
	      Drupal.attachBehaviors(new_content, settings);
	    }	  
	    
	    //scroll to newly inserted content
	    var offset = $("#request-data").offset();
		var scrollTarget = $("#request-data");
		while ($(scrollTarget).scrollTop() == 0 && $(scrollTarget).parent()) {
			scrollTarget = $(scrollTarget).parent();
		}
		// Only scroll upward
		//if (offset.top - 10 < $(scrollTarget).scrollTop()) {
			$(scrollTarget).animate({scrollTop: (offset.top - 10)}, 500);
		//}
	  
	};
	}
	
	

})(jQuery);
	
