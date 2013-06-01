ANIM_FRAME_WIDTH = 542;
backgroundXShift = 0;
var timer;

function AnimateBackground() {

	$('#menu-pic').stop().animate({backgroundPosition:"(" + backgroundXShift + "px 0)"}, 300);
	backgroundXShift -= 20;
	if (backgroundXShift < -2710) {
		backgroundXShift = ANIM_FRAME_WIDTH - 100;
		$('#menu-pic').stop().css({"background-position": backgroundXShift + "px 0"});
	}
	timer = window.setTimeout(AnimateBackground, 100);
}

// when the DOM is ready
$(function () {
  var img = new Image();
  
  // wrap our new image in jQuery, then:
  $(img)
    // once the image has loaded, execute this code
    .load(function () {
    
		// insert loaded image into the div 
		$('#menu-pic')
			// remove the loading class (so no background spinner), 
			.removeClass('loading')
			.css("background-image", "url(images/bg.jpg)");
		
		//$("#menu-pic-wrapper").css({"background-image":"url(images/bg.jpg)", "background-position":"300px 0"});
		
		timer = window.setTimeout(AnimateBackground, 500);
    
	  /*// apply animation effect on hover
	  $('#main-nav li a').mouseover(function(e){
			
		var index = $(this).parent().index(); //index of li element
		var xShift = index * ANIM_FRAME_WIDTH;
	
		$('#menu-pic').stop().animate({backgroundPosition:"(-" + xShift + "px 0)"}, 300);
	  });*/

    })
    
    // if there was an error loading the image, react accordingly
    .error(function () {
      // notify the user that the image could not be loaded
    })
    
    // *finally*, set the src attribute of the new image to our image
    .attr('src', 'images/bg.jpg');


	//toggle menu
	$("#toggle").on("click", function() {
		//$('#content').css({width:'960px', 'border':'1px dashed red'});
		//$("#left").hide("slide", {direction:"left"}, 1000).stop().removeClass("front").show().find("#main-nav").removeClass("hidden");

		ANIM_FRAME_WIDTH = 200;
		window.clearTimeout(timer);

		$("#left").animate({width:'200px'}, 1000, function() {
			$(this).removeClass("front").css({width:900}).find("#main-nav").removeClass("hidden").accordion({icons:false,collapsible: true });
			$("#top-menu").removeClass("front");
		});
		$("#right").animate({left:'900px', right:'5px', width:'250px'}, 1000, function() {
			$(this).removeClass("front");
		});
		
		$('#menu-pic').hide().delay(1000).css({width:ANIM_FRAME_WIDTH, left:920, "background-image":"url(\"images/bg-gallery.png\")"}).show();

		// $('#content-gallery').BlocksIt({
			// numOfCol: 3,
			// offsetX: 5,
			// offsetY: 5,
			// blockElement: 'div'

		// });
		
		// $('#content-gallery').mosaicflow({
			// itemSelector: '.mosaicflow__item',
			// minItemWidth: 300
		// }); 
		
		$("#gallery-container").isotope({
			itemSelector : '.gallery-item',
			//layoutMode : 'fitColumns',
			layoutMode : 'masonryHorizontal',
			//masonry : {
			//	columnWidth:200
			//},
			resizesContainer : false,
			getSortData : {
				clearance : function( $elem ) {
					return $elem.attr('data-clearance') == "true" ? 1 : 2;
				},
				name : function ($item) {
					return $item.find('.title').text();
				},
				index : function($item) {
					return $item.index();
				}
			},
			sortBy: 'title',
			onLayout: function( $elems, instance ) {
				$elems.each(function(index, value) {
					console.log($(this).text());
					if ((index + 1) % 4 ==  0) {
						$(this).css("top", "-50px");	
						console.log($(this).text());
					}	
				});
			}
		});
		

		
		$('#sort-by').change(function () {
			var $selSortOption = $("#sort-by option:selected").eq(0);
			console.log($selSortOption.attr('data-option-value'));
		  
			// make option object dynamically, i.e. { filter: '.my-filter-class' }
			var options = {},
				key = 'sortBy',
				value = $selSortOption.attr('data-option-value');
				
			// parse 'false' as false boolean
			value = value === 'false' ? false : value;
			options[ key ] = value;
			console.log(options);
			
			// apply new options
			 $("#gallery-container").isotope( options );
			 
			 return false;
		});

		
		// filter items when filter link is clicked
		$('#filters').change(function(){
			var $selOption = $("#filters option:selected").eq(0);
			var selector = $selOption.attr('data-filter');
			$("#gallery-container").isotope({ filter: selector });
			
			return false;
		});
		
		/*// filter items when filter link is clicked
		$('#filters a').click(function(){
			var selector = $(this).attr('data-filter');
			$("#gallery-container").isotope({ filter: selector });
			return false;
		});*/
		  

		
		$("#pagination").jui_pagination({
			currentPage: 1,
			visiblePageLinks: 1,
			totalPages: 4,
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
		
		
		$(this).hide();
		
		$('#main-nav li a').mouseover(function(e){			
			var index = $(this).parent().index(); //index of li element
			var xShift = index * ANIM_FRAME_WIDTH;
		
			$('#menu-pic').stop().animate({backgroundPosition:"(-" + xShift + "px 0)"}, 300);
		});
		
		//$("#logo").appendTo($("#content")).css({'z-index':'100','top':'0px'}).hide("slide", {direction:"left"}, 1000);
		//$("#logo-menu").appendTo($("#content")).css({'position':'absolute','z-index':'150','top':'0px'}).hide("slide", {direction:"left"}, 1000);
		
		
		//$("#frontright").hide("slide", {direction:"right", distance:"300"}, 10000);
		//$("#left-menu").hide().appendTo($("footer")).show();
		
	});
	
	$('.scrolled-content').sbscroller({
		//handleImage:'/images/misc/scrollbar-handle-middle.png',
		//handleTopImage:'/images/misc/scrollbar-handle-top.png',
		//handleBottomImage:'/images/misc/scrollbar-handle-bottom.png',
		//handleGripImage:'/images/misc/scrollbar-handle-grip.png',
		//autohide:false
	});
});

/*$(document).ready(function() {
	$('#main-nav li a').mouseover(function(e){
		
		var index = $(this).parent().index(); //index of li element
		var xShift = index * 542;
		
		$('#menu-pic').stop().animate({backgroundPosition:"(-" + xShift + "px 0)"},300);
	});
})*/
