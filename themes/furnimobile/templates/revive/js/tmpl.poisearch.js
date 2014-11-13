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
	POISearch2('.lmap');
});

//
// POISearch
//

function POISearch2(objs)
{
	objs = jQuery(objs);
	
	if(objs.length > 0)
	{		
		objs.each(function(index, obj)
		{
			obj			= jQuery(obj);
			var _map	= obj.find('.map');
			var _search	= obj.find('.search');			
			var url		= obj.data('url');
			var _class	= 'poi_search';
			var pois	= obj.data('pois');
						
			if(url
			&& _map.length > 0
			&& _search.length > 0)
			{
				var lmap = LMap(_map.prop('id'), pois);
				POISearchAjax2(obj, lmap, _map, _search, _class);
				
				obj.on('click', 'a.' + _class, function()
				{
					var _back		= jQuery(this).data('back') ? true : false;
					var continent	= jQuery(this).data('continent');
					var nation		= jQuery(this).data('nation');
					var natstate	= jQuery(this).data('natstate');
					var city		= jQuery(this).data('city');

					POISearchAjax2(obj, lmap, _map, _search, _class, _back, continent, nation, natstate, city);
					
					return false;
				});
			}
		});
	}
}

//
// POISearchAjax
//

function POISearchAjax2(obj, lmap, _map, _search, _class, _back, continent, nation, natstate, city)
{
	var url		= obj.data('url');
	var step	= 0;
		
	//
	
	if(continent)								step++;
	if(continent && nation)						step++;
	if(continent && nation && natstate)			step++;
	if(continent && nation && natstate && city)	step++;
	
	/*
	console.log('-');
	console.log('STEP ' + step);
	console.log('continent ' + continent);
	console.log('nation ' + nation);
	console.log('natstate ' + natstate);
	console.log('city ' + city);
	*/
	
	//
		
	if(url
	&& _map.length > 0
	&& _search.length > 0)
	{		
		jQuery.ajax(
		{
			url			: url,
			type		: 'post',
			data		:
			{
				continent	: continent,
				nation		: nation,
				natstate	: natstate,
				city		: city
			},
			dataType	: 'json',
			cache		: false,
			beforeSend	: function(e, data)
			{
				_search.find('.results').css('opacity', 0.3);
			},
			error		: function(e, data, thrown)
			{
				console.log(data + ' - ' + thrown);				
				POISearchAjax2(obj, lmap, _map, _search, _class);
			},
			success		: function(e, data)
			{				
				if(e.pois.length > 0)
				{
					var html	= '';
					var label	= '';
					var attrs	= '';
					var title	= e.title;
															
					//
										
					if(continent)
						title = e.pois[0].continent_label;
						
					if(nation)
						title = e.pois[0].nation_label;
					
					if(natstate)
						title = (e.pois[0].natstate != '*') ? e.pois[0].natstate : e.pois[0].nation_label;
								
					if(city)
						title = e.pois[0].city;
					
					//
					
					if(step == 2
					&& e.pois[0].natstate == '*')
					{
						POISearchAjax2(obj, lmap, _map, _search, _class, _back, continent, nation, e.pois[0].natstate);
					}
					else if(step == 4
					&& e.pois.length == 1)
					{
						Fancybox(e.pois[0].link, 'iframe', '100%', '100%', 0);
						_search.find('.results').css('opacity', 1);
					}
					else
					{
						_search.html('');
						_search.append('<div class="bg" />');
						_search.append('<div class="data" />')
						_search.find('.data').append('<div class="results" />');
						
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
									attrs+= ' data-natstate="' + e.pois[i].natstate + '"';
									
									label = e.pois[i].natstate;
									break;
								
								case 3:
									attrs+= ' class="' + _class + '"';
									attrs+= ' data-continent="' + e.pois[i].continent + '"';
									attrs+= ' data-nation="' + e.pois[i].nation + '"';
									attrs+= ' data-natstate="' + e.pois[i].natstate + '"';
									attrs+= ' data-city="' + e.pois[i].city + '"';
									
									label = e.pois[i].city;
									break;
								
								case 4:									
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
							attrs+= ' data-back="1"';
														
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
									
									if(e.pois[0].natstate != '*')
										attrs+= ' data-nation="' + nation + '"';
									break;
								
								case 4:
									attrs+= ' class="' + _class + '"';
									attrs+= ' data-continent="' + continent + '"';
									attrs+= ' data-nation="' + nation + '"';
									attrs+= ' data-natstate="' + natstate + '"';
									break;
							}							
							html+= '<p class="back"><a href="#"' + attrs + '>' + e.back + '</a></p>';
						}
						
						//
						
						_search.find('.results').html(html);
						_search.find('.results').css('opacity', 1);
						
						if(e.mappois.length > 0)
						{
							LMapPois(lmap, e.mappois);
						}
					}
				}
			}
		});
	}
}