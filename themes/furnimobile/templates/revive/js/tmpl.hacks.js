/**
 *
 * Copyright (C) 2014
 * Chimera | Web Agency
 * All rights reserved.
 *
 * Website:		www.chimera.it
 * E-mail:		m.cellamare@chimera.it
 *
 */

jQuery(document).ready(function()
{	
	AbsCenter('.abscenter');	
	AbsCenter('.squared', 'HV');
	Table('.table');
	Alertbox();
		
	//
	
	jQuery('.colors a[href="#"]').on('click', function()
	{
		return false;
	});
	
	jQuery('select.languages').selectOrDie();
	
	//
	
	Accordion('ul.accordion');
	AccordionHTML('div.accordion-html');
	PromoAccordion('.promo-container')
	RatioResize('.video-inline, .respect-ratio');
	VideoPlay('.video-inline');	
	Details('.details');
	POISearch('.gmap .search');
	
	OnePage('.onepage', '.page');
	AnchorTop('.anchortop');
	ScrollRevealer('.groups ul.group', 'li', 40, -300);
});

//
//
//

function PromoAccordion(objs)
{
	objs		= jQuery(objs);
	var active	= 'active';
	
	if(objs.length > 0)
	{
		objs.each(function(index, obj)
		{
			obj					= jQuery(obj);
			var obj_container	= obj.find('.promos');
			var obj_container_h	= obj_container.height();
			var obj_openclose	= obj.find('a.openclose');
			var obj_openclose_span = obj_openclose.find('span');
			var obj_openclose_text = obj_openclose_span.text();
			
			jQuery(window).on('resize', function()
			{
				obj_container_h	= obj_container.height();
			});
			
			//			
						
			obj_openclose.on('click', function()
			{
				if(obj.hasClass(active))
				{
					obj_container.animate
					({
						height			: 0,
						'padding-top'	: 0,
						'border-top-width': 1,
						'border-bottom-width': 0
					},
					{
						queue		: false,
						duration	: 200,
						complete	: function()
						{
							AnchorTop('.anchortop');
						}
					});
					obj.removeClass(active);
					obj_openclose.removeClass('inverse');
					obj_openclose_span.text('+');
				}
				else
				{
					obj_container.animate
					({
						height			: obj_container_h,
						'padding-top'	: 20,
						'border-top-width': 2,
						'border-bottom-width': 2
					},
					{
						queue		: false,
						duration	: 200,
						complete	: function()
						{
							obj_container.height('');
							AnchorTop('.anchortop');
						}
					});
					obj.addClass(active);
					obj_openclose.addClass('inverse');
					obj_openclose_span.text(obj_openclose_text);
				}
				return false;
			});
		});
	}
}

//
// Alertbox
//

function Alertbox()
{
	var alertbox = jQuery('.alertbox');
	
	if(alertbox.length > 0)
	{
		jQuery(document).on('scroll', function()
		{			
			alertbox.fadeOut();
		});
	}
}

//
// POISearch
//

function POISearch(objs)
{
	objs = jQuery(objs);
	
	if(objs.length > 0)
	{		
		objs.each(function(index, obj)
		{
			obj			= jQuery(obj);
			var url		= obj.data('url');
			var _class	= 'poi_search';
			
			if(url)
			{
				POISearchAjax(obj, _class);
				
				obj.on('click', 'a.' + _class, function()
				{
					var continent	= jQuery(this).data('continent');
					var nation		= jQuery(this).data('nation');
					var city		= jQuery(this).data('city');

					POISearchAjax(obj, _class, continent, nation, city);
					
					return false;
				});
			}
		});
	}
}

//
// POISearchAjax
//

function POISearchAjax(obj, _class, continent, nation, city)
{
	obj			= jQuery(obj);	
	var url		= obj.data('url');
	//var pois	= obj.siblings('.map').data('pois');
	var step	= 0;
		
	if(continent)	step++;
	if(nation)		step++;
	if(city)		step++;
	
	//
		
	if(url)
	{
		jQuery.ajax(
		{
			url			: url,
			type		: 'post',
			data		:
			{
				continent	: continent,
				nation		: nation,
				city		: city
			},
			dataType	: 'json',
			cache		: false,
			beforeSend	: function(e, data)
			{
				obj.find('.results').css('opacity', 0.3);
			},
			error		: function(e, data, thrown)
			{
				console.log(data + ' - ' + thrown);				
				POISearchAjax(obj, _class);
			},
			success		: function(e, data)
			{				
				if(e.pois.length > 0)
				{
					var html	= '';
					var label	= '';
					var attrs	= '';
					var title	= e.title;
										
					if(continent)
						title = e.pois[0].continent_label;
						
					if(nation)
						title = e.pois[0].nation_label;
					
					if(city)
						title = e.pois[0].city;
					
					//
										
					if(step == 3
					&& e.pois.length == 1)
					{
						Fancybox(e.pois[0].link, 'iframe', '100%', '100%', 0);
					}
					else
					{
						obj.html('');
						obj.append('<div class="bg" />');
						obj.append('<div class="data" />')
						obj.find('.data').append('<div class="results" />');
						
						//
						
						html+= '<h2>' + title + '</h2>';
						html+= '<ul>';
					
						for(var i = 0; i < e.pois.length; i++)
						{
							attrs = '';
							
							switch(step)
							{
								default:
								case 0:
									attrs+= ' class="' + _class + '"';
									attrs+= ' data-continent="' + e.pois[i].continent + '"';
									
									label = e.pois[i].continent_label;
									break;
								
								case 1:
									attrs+= ' class="' + _class + '"';
									attrs+= ' data-continent="' + e.pois[i].continent + '"';
									attrs+= ' data-nation="' + e.pois[i].nation + '"';
									
									label = e.pois[i].nation_label;
									break;
								
								case 2:
									attrs+= ' class="' + _class + '"';
									attrs+= ' data-continent="' + e.pois[i].continent + '"';
									attrs+= ' data-nation="' + e.pois[i].nation + '"';
									attrs+= ' data-city="' + e.pois[i].city + '"';
									
									label = e.pois[i].city;
									break;
								
								case 3:									
									attrs+= ' onclick="javascript:Fancybox(\'' + e.pois[i].link + '\', \'iframe\', \'100%\', \'100%\', 0);return false;"';
																		
									var zip				= (e.pois[i].zip) ? e.pois[i].zip + ' ' : '';
									var address			= (e.pois[i].address) ? e.pois[i].address : '';
									var housenumber		= (e.pois[i].housenumber) ? ' ' + e.pois[i].housenumber : '';
									
									label = zip + address + housenumber;
									break;
							}
							html+= '<li><a href="#"' + attrs + '><span>' + label + '</span></a></li>';
						}
						html+= '</ul>';
					
						//
						
						if(step > 0)
						{
							attrs = '';
							
							switch(step)
							{
								default:
								case 0:
								case 1:
									attrs+= ' class="' + _class + '"';
									break;
														
								case 2:
									attrs+= ' class="' + _class + '"';
									attrs+= ' data-continent="' + continent + '"';
									break;
								
								case 3:
									attrs+= ' class="' + _class + '"';
									attrs+= ' data-continent="' + continent + '"';
									attrs+= ' data-nation="' + nation + '"';
									break;
							}
							html+= '<p class="back"><a href="#"' + attrs + '>' + e.back + '</a></p>';
						}
						
						//
						
						obj.find('.results').html(html);
					}
					obj.find('.results').css('opacity', 1);
				}
			}
		});
	}
}

//
// JTextAjax
//

function JTextAjax(txt)
{
	/*
	var url = '/website/revive/site_new/templates/apps/widgets/jtext.ajax.php';
	
	jQuery.ajax(
	{
		url			: url,
		type		: 'post',
		data		:
		{
			txt		: txt
		},
		dataType	: 'html',
		cache		: false,
		async		: false,
		success		: function(e, data)
		{
			txt = e;
		}
	});		
	*/
	return txt;
}

//
// AccordionAccordionHTML
//

function AccordionHTML(objs)
{
	objs = jQuery(objs);
	
	if(objs.length > 0)
	{
		var active = 'active';
		
		objs.each(function(index, obj)
		{
			obj					= jQuery(obj);
			var obj_title		= obj.find('h2').children('a');
			var obj_title_icon	= obj_title.find('.openclose');
			var obj_mask		= obj.find('.mask');
			var obj_content		= obj_mask.find('.content');
			var obj_close		= obj_content.find('.close');
			
			obj_title_icon.children('span').html('+');
			obj_mask.height(0);
			
			//
			
			obj_title.on('click', function()
			{
				var h;
				
				if(!obj_mask.height())
				{
					obj.addClass(active);
					obj_title.addClass(active);
					obj_title_icon.children('span').html('&times;');
					
					h = obj_content.outerHeight();
				}
				else
				{
					obj.removeClass(active);
					obj_title.removeClass(active);
					obj_title_icon.children('span').html('+');
					
					h = 0;
				}				
				obj_mask.animate
				({
					height		: h
				},
				{
					queue		: false,
					duration	: 200,
					complete	: function()
					{
						if(h > 0)
							obj_mask.height('auto');
					}
				});				
				return false;
			});
			obj_close.on('click', function()
			{
				obj_title.trigger('click');
				return false;
			});
		});
	}
}

//
// Accordion
//

function Accordion(objs)
{
	objs = jQuery(objs);
	
	if(objs.length > 0)
	{
		objs.each(function(index, obj)
		{
			obj					= jQuery(obj);
			var obj_caller		= obj.find('a');
			var obj_caller_h	= obj_caller.outerHeight();
			
			jQuery(this).find('.squared').css('border-color', 'transparent');
			
			obj_caller.on('mouseenter', function()
			{
				jQuery(this).animate
				({
					height		: 200
				},
				{
					queue		: false,
					duration	: 200
				});				
				jQuery(this).find('.squared').css('border-color', '');
			});
			obj_caller.on('mouseleave', function()
			{
				jQuery(this).animate
				({
					height		: obj_caller_h
				},
				{
					queue		: false,
					duration	: 200
				});
				jQuery(this).find('.squared').css('border-color', 'transparent');
			});
			obj_caller.on('click', function()
			{
				return false;
			});
		});
	}
}

//
// OnePage
//

function OnePage(menus, pages)
{
	menus = jQuery(menus);
	pages = jQuery(pages);
	
	if(menus.length > 0
	&& pages.length > 0)
	{
		var _doc		= jQuery(document).add(window);
		var _events		= 'load scroll resize';
		var active		= 'active';
		var hover		= false;
				
		pages.each(function(index, page)
		{			
			page = jQuery(page);
						
			_doc.on(_events, function()
			{
				if(!hover)
				{
					var scollpos		= jQuery(this).scrollTop();
					var page_h			= page.outerHeight();
					var page_ystart		= page.offset().top - menus.outerHeight();
					var page_yend		= page_ystart + page_h;
					var page_id			= page.data('id') ? page.data('id') : page.prop('id');
					
					if(scollpos >= page_ystart
					&& scollpos < page_yend)
					{
						menus.find('li').removeClass(active);
						menus.find('a').filter('[href="#' + page_id + '"]').parent().addClass(active);
					}
				}
			});
			_doc.trigger(_events);
		});		
		menus.on('click', 'a', function()
		{
			var id		= jQuery(this).attr('href');
			var page	= jQuery(id);
					
			if(page.length > 0)
			{
				var page_ystart = page.offset().top;
				
				jQuery(this).parent().siblings().removeClass(active);
				jQuery(this).parent().addClass(active);
				
				jQuery('html, body').animate(
				{
					scrollTop: page.offset().top - menus.outerHeight() + 1 // +1 => Hack per bug offset
				},
				{
					queue		: false,
					duration	: 500
				});
				return false;
			}
		});
		menus.on('mouseenter', 'a', function()
		{
			hover = true;
			menus.find('li').removeClass(active);
		});
		menus.on('mouseleave', 'a', function()
		{
			hover = false;
			_doc.trigger('scroll');
		});
		_doc.on('ready load hashchange', function()
		{
			var hash = window.location.hash;
			
			if(jQuery(hash).length > 0)
				_doc.scrollTop(jQuery(hash).offset().top - menus.outerHeight());
		});
	}
}

//
// Details
//

function Details(objs)
{
	var objs_caller		= jQuery(objs).filter('ol');
	var objs_receiver	= jQuery(objs).filter('ul');
	var objs_galleries	= jQuery(objs).filter('div');
	
	if(objs_caller.length > 0
	&& objs_receiver.length > 0
	&& objs_galleries.length > 0)	
	{
		var _doc		= jQuery(document).add(window);
		var _events		= 'load';
		var active		= 'active';
		
		var active_off_css	= { opacity: 0.2 };
		var active_on_css	= { opacity: 1 };
		
		objs_caller.each(function(index, obj_caller)
		{			
			obj_caller				= jQuery(obj_caller);
			var obj_caller_id		= obj_caller.data('id');
			var obj_caller_childs	= obj_caller.children();			
			var obj_receiver_childs	= objs_receiver.filter('[data-id="' + obj_caller_id + '"]').children();			
			var obj_gallery			= objs_galleries.filter('[data-id="' + obj_caller_id + '"]');
			var obj_gallery_slides	= obj_gallery.children('ul').children('li');
			
			obj_gallery.jcarousel(
			{
				center	: true,
				wrap	: 'both'
			});
						
			if(obj_caller_childs.length > 0
			&& obj_receiver_childs.length > 0
			&& obj_gallery.length > 0)
			{
				obj_caller_childs.on('click', 'a', function()
				{
					if(!jQuery(this).hasClass(active))
					{					
						var hash = jQuery(this).attr('href').replace(/#/g, '');
						
						obj_caller_childs.find('a').removeClass(active);
						jQuery(this).addClass(active);
						
						obj_receiver_childs.fadeOut();
						obj_receiver_childs.filter('.' + hash).fadeIn();
												
						//
						
						var obj_gallery_slide = obj_gallery_slides.filter('.' + hash);
						
						obj_gallery.jcarousel('scroll', obj_gallery_slide);
												
						obj_gallery_slides.animate(active_off_css, { queue: false, duration: 200 }).removeClass(active);						
						obj_gallery_slide.animate(active_on_css, { queue: false, duration: 200 }).addClass(active);
					}					
					return false;
				});
				obj_gallery.children('ol').on('click', 'a', function()
				{
					var li			= jQuery(this).parent();
					var target		= obj_gallery_slides.filter('.' + active);
					var next_target	= [];
					var index		= target.index();
					var _try		= 0;
					
					do
					{
						_try+= 1;
						
						if(li.hasClass('prev'))
						{						
							index = index - 1;
							
							if(index <= 0)
								index = obj_gallery_slides.length - 1;
						}
						else if(li.hasClass('next'))
						{
							index = index + 1;
							
							if(index >= obj_gallery_slides.length - 1)
								index = 0;
						}
						next_target = obj_gallery_slides.eq(index);
													
						//
						
						if(_try >= 20)
						{
							console.log('TOO MANY ATTEMPTS');
							break;
						}
					}
					while(next_target.hasClass('detail_none'));
					
					//
					
					if(next_target.length > 0)
						obj_caller_childs.children('a').filter('[href="#' + next_target.prop('class') + '"]').trigger('click');
						
					return false;
				});
				
				//
				
				_doc.on('resize', function()
				{
					obj_gallery.find('li').width(parseInt(jQuery(document).width() / 3));
				});
				_doc.on(_events, function()
				{
					var obj_caller_childs_first = obj_caller_childs.first();
					var first_hash = obj_caller_childs_first.find('a').attr('href').replace(/#/g, '');
					
					obj_caller_childs.find('a').removeClass(active);					
					obj_caller_childs_first.find('a').addClass(active);
					
					obj_receiver_childs.hide();					
					obj_receiver_childs.first().fadeIn();
					
					//
															
					obj_gallery.children('ul').css('left', 0);					
					obj_gallery_slides.filter('.' + first_hash).css(active_on_css).addClass('active');
				});
				_doc.trigger(_events);
				_doc.trigger('resize');
			}
		});
	}
}

//
// AnchorTop
//

function AnchorTop(objs)
{
	var _class = objs.replace('.', '');
	objs = jQuery(objs);
	
	if(objs.length > 0)
	{
		var _doc		= jQuery(document).add(window);
		var _events		= 'load scroll resize';
		
		var class_fixed	= 'fixed';
		var class_top	= 'top';
		var class_bottom= 'bottom';
		
		objs.each(function(index, obj)
		{			
			obj					= jQuery(obj);	
			var obj_h			= obj.outerHeight();
			//var obj_ystart		= obj.offset().top;
			//var obj_yend		= obj_ystart + obj_h;
			
			var obj_wrapper		= obj.parent().find('.' + _class + '-wrapper');
			
			if(obj_wrapper.length == 0)
				obj.before('<div class="' + _class + '-wrapper" style="height: ' + obj_h + 'px;"/>');
			
			obj_wrapper			= obj.parent().find('.' + _class + '-wrapper');
			
			//
			
			if(!(/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase())))
			{
				_doc.on(_events, function()
				{				
					var scollpos		= jQuery(this).scrollTop();
					var obj_ystart		= obj_wrapper.offset().top;
					var obj_yend		= obj_ystart + obj_h;					
					
					//
					
					obj.removeClass(class_fixed).removeClass(class_top).removeClass(class_bottom);
					
					if(jQuery(window).height() > obj_h)
					{
						if(scollpos >= obj_ystart)
						{
							obj.addClass(class_fixed).addClass(class_top).removeClass(class_bottom);
						}
						else if(scollpos >= obj_yend)
						{
							obj.removeClass(class_fixed).removeClass(class_top).addClass(class_bottom);
						}
					}
				});
				_doc.trigger(_events);
			}
		});
	}
}

//
// ScrollRevealer
//

function ScrollRevealer(objs, slide, margin_perc, offset)
{
	objs = jQuery(objs);
		
	if(objs.length > 0)
	{
		objs.each(function(index, obj)
		{
			obj			= jQuery(obj);
			var slides	= obj.find(slide);
			
			if(slides.length > 1)
			{
				obj.addClass('scroller');
				
				var _doc				= jQuery(document).add(window);
				var _events				= 'load scroll';
				
				var	active				= 'active';
				var steps				= slides.length;
																
				if(!margin_perc
				|| margin_perc >= 50)	margin_perc = 0;				
				if(!offset)				offset = 0;
				
				slides.each(function(index, s)
				{
					s = jQuery(s);
					
					var slides_h		= s.outerHeight();
					var slides_margin	= parseInt(slides_h * margin_perc / 100);
						slides_h		= slides_h - (slides_margin * 2);
					var slides_ystart	= s.offset().top + slides_margin + offset;
					var slides_yend		= slides_ystart + slides_h;
					
					var slides_step		= parseInt(slides_h / steps);
					var slides_steps	= [];
					
					for(var i = 0; i < steps; i++)
					{
						slides_steps[i] = slides_ystart + (slides_step * i);
					}
					
					//
					
					_doc.on(_events, function()
					{				
						var scollpos = jQuery(this).scrollTop();
												
						if(scollpos >= slides_ystart
						&& scollpos < slides_yend)
						{						
							for(var i = 0; i < slides_steps.length; i++)
							{
								if(scollpos >= slides_steps[i]
								&& scollpos < slides_steps[i] + slides_step)
								{
									slides.removeClass(active);
									slides.eq(i).addClass(active);
								}
							}
						}
						else if(scollpos < slides_ystart)
						{
							slides.removeClass(active);
							slides.eq(0).addClass(active);
						}
						else if(scollpos >= slides_yend)
						{
							slides.removeClass(active);
							slides.eq(slides.length - 1).addClass(active);
						}								
					});
					_doc.trigger(_events);
				});
			}
		});
	}
}

//
// VideoPlay
//

function VideoPlay(containers)
{
	containers = jQuery(containers);
	
	if(containers.length > 0)
	{
		containers.each(function(index, container)
		{
			container = jQuery(container);
			
			container.on('click', function()
			{
				container.html('<iframe width="100%" height="100%" src="' + container.prop('href') + '" frameborder="0" allowfullscreen></iframe>');			
				
				container.replaceWith(function()
				{					
					return jQuery('<div/>',
					{
						css:
						{
							width		: container.prop('style').width,
							height		: container.prop('style').height
						},
						'class'			: container.prop('class'),
						'data-height'	: container.data('height'),
						'data-width'	: container.data('width'),
						html			: this.innerHTML
					});
				});
								
				RatioResize(jQuery('div').filter('.' + container.prop('class')));
				return false;
			});
		});
	}
}

//
// RatioResize
//

function RatioResize(containers, w, h)
{
	var containers = jQuery(containers);
	
	if(containers.length > 0)
	{
		var _doc		= jQuery(document).add(window);
		var _events		= 'load resize';
		
		containers.each(function(index, container)
		{
			container = jQuery(container);
									
			_doc.on(_events, function()
			{
				var screen_w = jQuery(window).width();
				if(!w)	w = container.data('width');
				if(!h)	h = container.data('height');
				
				var r = w / h;
				var w = Math.round(screen_w);
				var h = Math.round(w / r);
				
				container.css('width', w + 'px');
				container.css('height', h + 'px');
			});
			_doc.trigger(_events);
		});
	}
}

//
// JPBoxHeights
//

function JPBoxHeights(where, obj1, obj2)
{
	obj1 = (obj1) ? jQuery(where + ' ' + obj1) : '';
	obj2 = (obj2) ? jQuery(where + ' ' + obj2) : '';
	
	var _row	= 0;
	var _item	= 0;
	var _items	= 2;
	var rows	= new Array();
	rows[0]		= new Array();
	
	if(obj1.length > 0)
	{
		obj1.find('.JPBox').each(function(index, element)
		{
			element		= jQuery(element);
			_items		= (element.hasClass('_300')) ? 2 : 1;
			_items		= (!obj2) ? 3 : _items;
			
			rows[_row][_item] = element;
			_item++;
			
			if(_item >= _items)
			{
				_row++;
				_item = 0;
				
				rows[_row] = new Array();
			}
		});
	}
	
	if(obj2.length > 0)
	{
		obj2.find('.JPBox').each(function(r, element)
		{			
			if(rows[r]
			&& rows[r].length)
			{
				element = jQuery(element);
				rows[r][rows[r].length] = element;
			}
		});
	}
	
	//
	
	var totalrows = rows.length;
		
	if(totalrows > 0)
	{
		for(var r = 0; r < totalrows; r++)
		{
			var totalitems	= rows[r].length;
			var height		= 0;
			
			for(var i = 0; i < totalitems; i++)
			{
				rows[r][i].addClass('row' + r);
							
				if(rows[r][i].height() > height)
				{
					height = rows[r][i].height();
				}
			}
			
			if(obj1.length > 0)
			{	
				obj1.find('.JPBox.row' + r).css('height', height + 'px');
				obj1.find('.JPBox.row' + r).children('.frame').css('height', parseInt(height - 10) + 'px');
			}
			if(obj2.length > 0)
			{
				obj2.find('.JPBox.row' + r).css('height', height + 'px');
				obj2.find('.JPBox.row' + r).children('.frame').css('height', parseInt(height - 10) + 'px');
			}
		}
	}
}

//
// AbsCentered
//

function AbsCenter(obj, type)
{
	obj = jQuery(obj);
	if(!type) type = 'H';
	
	if(obj.length > 0)
	{
		var _doc		= jQuery(document).add(window);
		var _events		= 'load';
		
		obj.each(function(index, element)
		{
			element = jQuery(element);
			
			_doc.on(_events, function()
			{				
				var css = {};
				css['position'] = 'absolute';
				
				if(type == 'H'
				|| type == 'HV')
				{			
					css['margin-left']	= '-' + Math.round(element.outerWidth() / 2).toString() + 'px';
					css['left']			= '50%';
				}
				if(type == 'V'
				|| type == 'HV')
				{			
					css['margin-top']	= '-' + Math.round(element.outerHeight() / 2).toString() + 'px';
					css['top']			= '50%';
				}
				element.css(css);
			});
			_doc.trigger(_events);
		});
	}
}

//
// AddCorners
//

function AddCorners(obj, _type)
{
	obj = jQuery(obj);
	
	if(obj.length > 0)
	{
		_class = 'corner';
		
		if(_type)
			_class += '-' + _type;			
			
		obj.each(function(index, element)
		{
			element = jQuery(element);
			
			if(!element.hasClass(_class))
				jQuery(element).addClass(_class);
		});		
	}
}

//
// AddPNGCorners
//

function AddPNGCorners(obj)
{
	obj = jQuery(obj);
	
	if(obj.length > 0)
	{	
		Append(obj.selector + ':first', '<span class="crn crn-tl"></span>');
		Append(obj.selector + ':first', '<span class="crn crn-tr"></span>');
		Append(obj.selector + ':last', '<span class="crn crn-bl"></span>');
		Append(obj.selector + ':last', '<span class="crn crn-br"></span>');
	}
}

//
// Corners
//

function SetCorners(obj, _type, _corner)
{
	obj = jQuery(obj);
	
	if(obj.length > 0)
	{		
		if(!_corner)
			_corner = '4px';
		
		if(_type)
			_corner = _type + ' ' + _corner;
		
		obj.each(function(index, element)
		{
			jQuery(element).corner(_corner);
		});	
	}
}

//
// Append
//

function Append(obj, _html)
{
	obj = jQuery(obj);
	
	if(obj.length > 0)
	{
		obj.each(function(index, element)
		{
			jQuery(element).append(_html);			
		});
	}
}

//
// Table
//

function Table(table)
{
	var table = jQuery('table').filter(table);
	
	if(table.length > 0)
	{
		table.each(function(index, element)
		{
			var element = jQuery(element);
			
			element.addClass('jptable');
			element.attr('cellpadding', 0);
			element.attr('cellspacing', 0);
			element.attr('border', 0);
			
			//
			
			element.find('tbody').each(function(i, e)
			{
				var e = jQuery(e);
				
				e.children('tr:visible:even').addClass('even'); 
				e.children('tr:visible:odd').addClass('odd');
				e.children('tr:visible:first').addClass('first');
				e.children('tr:visible:last').addClass('last');
			});				
			element.find('tbody tr:visible td, thead tr:visible th').each(function(i, e)
			{
				var e = jQuery(e);
				
				e.attr('valign', 'top');
				e.addClass('col' + e.index());
			});			
			element.find('tr:visible').each(function(i, e)
			{
				var e = jQuery(e);
				
				e.addClass('row' + e.index());
				e.children(':first').addClass('first');
				e.children(':last').addClass('last');
			});
			element.find('tbody tr:visible').hover
			(
				function()
				{
					jQuery(this).addClass('active');
				},
				function()
				{
					jQuery(this).removeClass('active');
				}
			);
		});
	}
}
