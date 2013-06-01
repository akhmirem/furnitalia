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
		  /*
if (response.data) {
		  		//$('#dialog-form').html(response.data).dialog( "open" ).parents(".ui-dialog").css("z-index", "1000");
		  		$("#pagination").html(response.data);
		  		Drupal.attachBehaviors($(document), Drupal.settings);
		  }
*/
		  
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
		    
		    $('#dialog-form').dialog( "open" ).parents(".ui-dialog").css("z-index", "1000");  
		  
		  
		};

        Drupal.behaviors.furnitalia = {
          attach: function(context, settings) {
			$("#gallery-container .gallery-item").each(function() {
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
			
			$("#gallery-container").isotope({
				itemSelector : '.gallery-item',
				//layoutMode : 'fitColumns',
				layoutMode : 'masonryHorizontal',
				//masonry : {
				//	columnWidth:200
				//},
				resizesContainer : false,
				resizable :	false,
				getSortData : {
					clearance : function( $elem ) {
						return $elem.attr('data-clearance') == "true" ? 1 : 2;
					},
					name : function ($item) {
						return $item.find('.title').text();
					},
					index : function($item) {
						return $item.index();
					},
					price: function($item) {
						var price = $item.find('.sell-price span.uc-price').text();
						return Number(price.replace(/[^0-9\.]+/g,""))
					}
				},
				//sortBy: 'name',
				onLayout: function( $elems, instance ) {
					$elems.each(function(index, value) {
						console.log($(this).find('title').text());
						if ((index + 1) % 4 ==  0) {
							$(this).css("top", "-35px");	
							//console.log($(this).text());
						}	
					});
				}
			});
		

		
			$('#sort-by').change(function () {
				var $selSortOption = $("#sort-by option:selected").eq(0);
				//console.log($selSortOption.attr('data-option-value'));
		  
				// make option object dynamically, i.e. { filter: '.my-filter-class' }
				var options = {},
				key = 'sortBy',
				value = $selSortOption.attr('data-option-value');
				
				// parse 'false' as false boolean
				value = value === 'false' ? false : value;
				options[ key ] = value;
			
				// apply new options
				$("#gallery-container").isotope( options );
			 
				 return false;
			});

		
			// filter items when filter link is clicked
			$('#filters').change(function(){
				var $selOption = $("#filters option:selected").eq(0);
				var selector = $selOption.attr('data-filter');
				console.log(selector);
				$("#gallery-container").isotope({ filter: selector });
			
				return false;
			});

			if ($("#pagination").length) {
				$("#pagination").once('pagination').jui_pagination({
					currentPage: 1,
					visiblePageLinks: 1,
					totalPages: Math.ceil($("#gallery-container").find("article").length / 6),
					showNavButtons: true,
					showNavPages: false,
					showPreferences: true,
					navPagesMode: 'continuous',
					containerClass: 'container1',		 
					useSlider: true,
					sliderInsidePane: true,
					sliderClass: 'slider1',		 
					disableSelectionNavPane: false,
					navRowsPerPageClass: 'rows-per-page1  ui-state-default ui-corner-all',
					navGoToPageClass: 'goto-page1 ui-state-default ui-corner-all',
				 
					onChangePage: function(event, page_num) {
						console.log("attempt to change page");
					  if(isNaN(page_num) || page_num <= 0) {
						alert('Invalid page' + ' (' + page_num + ')');
					  } else {
						$("#result").html('Page changed to: ' + page_num);
						$("#gallery-container").animate({left: -610 * (page_num - 1) + "px"}, "slow");
					  }
					},
					onSetRowsPerPage: function(event, rpp) {
					  if(isNaN(rpp) || rpp <= 0) {
						alert('Invalid rows per page' + ' (' + rpp + ')');
					  } else {
						alert('rows per page successfully changed' + ' (' + rpp + ')');
						$(this).jui_pagination({
						  rowsPerPage: rpp
						})
					  }
					},
					onDisplay: function() {
					  var showRowsInfo = $(this).jui_pagination('getOption', 'showRowsInfo');
					  if(showRowsInfo) {
						var prefix = $(this).jui_pagination('getOption', 'nav_rows_info_id_prefix');
						$("#" + prefix + $(this).attr("id")).text('Total rows: XXX');
					  }
					}
				});
			 
				$("#result").html('Current page is: ' + $("#pagination").jui_pagination('getOption', 'currentPage'));
			}
			
			$('#sort-by').dropkick({
				 change: function (value, label) {
				 	$("#sort-by option").filter(function() {
					    return $(this).text() == label; 
					}).attr('selected', true);
					$('#sort-by').trigger('change');
				 }
			});
				 
			$('#filters').dropkick({
				 change: function (value, label) {
				  	$("#filters option").filter(function() {
					    return $(this).text() == label; 
					}).attr('selected', true);
					$('#filters').trigger('change');
				 }
			});
			
			$( "#dialog-form" ).dialog({
				autoOpen: false,
				height: 600,
				width: 800,
				modal: true
		    });

			
			if ($('#contact-us').length && !$('#contact-us').hasClass('.ajax-processed')) {
				$('#contact-us').addClass('.ajax-processed');
				var element_settings = {};
				element_settings.url = Drupal.settings.basePath + 'contact/ajax/';
				element_settings.event = "click";
				element_settings.progress = {type: false};
				
				var ajax = new Drupal.ajax("#contact-us", $('#contact-us')[0], element_settings);
			}
			
			//item description page thumbnail gallery
			$("#pikame").PikaChoose({
				showCaption:false,
				carousel:false
			});
			
        }
	}
})(jQuery);

