/**
 * @file
 * A JavaScript file for the theme.
 *
 * In order for this JavaScript to be loaded on pages, see the instructions in
 * the README.txt next to this file.
 */

 (function($) {
 
 		/**
		 * Ajax delivery command to switch among tabs by ID.
		 */
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
		    console.log("attempting to display popup");
			$.fancybox.open($('#dialog-form'), {
				width:800,
				height:600,
				closeBtn:true,
				closeClick:false,
				mouseWheel:true,
				openEffect	: 'none',
				closeEffect	: 'none'
			});
		  
		  
		};

        Drupal.behaviors.furnitalia = {
          attach: function(context, settings) {
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
				);
			});
			
			//mark li active if link inside has class 'active'
			$('a.active-menu').closest('li').addClass('active-menu');
			
			
			if(jQuery().dropkick) {
				$('#edit-sort-by').attr('tabindex', '1').dropkick({
					 change: function (value, label) {
					 	$('#edit-sort-by').trigger('change');
					 }
				});
				
				$('#edit-brand').attr('tabindex', '2').dropkick({
					 change: function (value, label) {
						$('#edit-brand').trigger('change');
					 }
				});
			}
					
			
			if ($('#contact-us').length && !$('#contact-us').hasClass('.ajax-processed')) {
				$('#contact-us').addClass('.ajax-processed');
				var element_settings = {};
				element_settings.url = Drupal.settings.basePath + 'contact/ajax/';
				element_settings.event = "click";
				element_settings.progress = {type: false};
				
				var ajax = new Drupal.ajax("#contact-us", $('#contact-us')[0], element_settings);
			}
			
			if ($('#request-quote').once(function() {
				var element_settings = {};
				element_settings.url = $(this).attr('href');
				element_settings.event = "click";
				element_settings.progress = {type: false};
				
				var ajax = new Drupal.ajax("#request-quote", $('#request-quote')[0], element_settings);
				
			}));

			
			//main navigation menu accordeon
			$("#main-nav").accordion({
				icons:false,
				collapsible: true,
				active: false,
				heightStyle:'content'
			});
			
			//set active menu link in accordion
			var foundActiveCat = false;
			$("div.ui-accordion-content").each(function(index, val) {
				if ($(this).find('a.active-menu').length) {
					foundActiveCat = true;
					$("#main-nav").accordion("option", "active", index);
				}
			});

			if (!foundActiveCat) {
				$("#main-nav").accordion("option", "active", 0); //make All categories active by default
			}
			
			$("#bg4").click(function() {
			    window.location.href = Drupal.settings.basePath + "interior-design";
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
	     
	     $("#pikame").wrap($('<div id="pikawrapper"></div>'));
	     $("#pikawrapper").jScrollPane({
		     verticalDragMinHeight: 25,
			 verticalDragMaxHeight: 25,
			 horizontalDragMinWidth: 25,
			 horizontalDragMaxWidth: 25
	     });
	 }

})(jQuery);

