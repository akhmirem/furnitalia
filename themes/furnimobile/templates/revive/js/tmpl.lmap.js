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
});

//
// LMap
//

function LMap(id, pois)
{
	selector = jQuery('#' + id);
		
	if(selector.length > 0)
	{
		var map = L.map(id, { scrollWheelZoom: false }).setView([41.060127, 16.704862], 2);
						
		//L.tileLayer('https://{s}.tiles.mapbox.com/v3/{id}/{z}/{x}/{y}.png', { id: 'marcocellamare.ii6mamob' }).addTo(map);				
		L.tileLayer('http://{s}.www.toolserver.org/tiles/bw-mapnik/{z}/{x}/{y}.png', { attribution: '' }).addTo(map);
						
		if(!pois)
			var pois = selector.data('pois');
		
		if(pois)
		{
			var data = [];
			var pois_length	= 0;
			
			jQuery.each(pois, function(i, poi)
			{
				data.push(
				{
					id			: poi.id,
					lat			: poi.lat,
					lng			: poi.lng,
					icon		: poi.icon,
					link		: poi.link,
					data		: poi
				});				
				pois_length++;
			});			
			if(pois_length > 0)
			{
				var Marker = L.Icon.extend(
				{
					options:
					{
						iconSize	: [24, 38],
						iconAnchor	: [12, 38]
					}
				});		
				var markers = [];
				var group;
				var k = 0;
				
				//
				
				jQuery.each(data,function(i, d)
				{					
					if(d.lat != ''
					&& d.lng != '')
					{
						var popup;
						
						markers[k] = new L.marker([d.lat, d.lng], { icon: new Marker({ iconUrl: d.icon }) });
						map.addLayer(markers[k]);
						
						markers[k].on('mouseover', function(e)
						{
							popup = new L.popup({ offset: [0, -40] }).setLatLng([d.lat, d.lng]).setContent(d.data.info).openOn(map);
						});
						markers[k].on('click', function(e)
						{
							Fancybox(d.link, d.data.type, d.data.width, d.data.height, d.data.padding);
							return false;
						});
						k++;
					}
				});
			}
		}		
		return map;
	}
	return false;
}

//
// LMapPois
//

function LMapPois(map, markers)
{
	if(markers.length > 0)
	{
		map.fitBounds(L.latLngBounds(markers), { paddingTopLeft: [20, 40], paddingBottomRight: [320, 40] });
		return true;
	}
	return false;
}

