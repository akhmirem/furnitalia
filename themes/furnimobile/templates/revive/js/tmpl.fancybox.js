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
	FancyboxInit();
});
jQuery(window).load(function()
{
	FancyboxInit();
});

//

function FancyboxInit()
{
	jQuery.extend(jQuery.fancybox.defaults,
	{
		margin		: 0,
		padding		: 0,
		fitToView	: true,
		autoSize	: true,
		closeClick	: false,
		aspectRatio	: true,
		helpers		:
		{
			overlay :
			{
				css :
				{
					'background' : 'rgba(0,0,0,0.85)'
				}
			}
		},
		beforeShow	: function()
		{
            jQuery('.fancybox-skin').css(
			{
                'background-color'	: '#FFF',
                'box-shadow'		: 'none'
			});
        }
	});
	
	//
	
	var fancyboxs = jQuery('a.fancybox');
	
	if(fancyboxs.length > 0)
	{
		fancyboxs.filter(':not(.iframe)').fancybox(
		{
			type: 'image'
		});
		
		//
		
		fancyboxs.filter('.iframe').each(function(index, fancybox)
		{
			fancybox = jQuery(fancybox);
						
			var w = fancybox.data('width');
			var h = fancybox.data('height');
			var p = fancybox.data('padding');
			
			if(typeof p === 'undefined') p = 50;
			if(!w) w = 900;
			if(!h) h = 600;						
			
			fancybox.fancybox(
			{
				type		: 'iframe',
				width		: (typeof w === 'number') ? w - (p * 2) : w,
				height		: (typeof h === 'number') ? h - (p * 2) : h,
				padding		: p,
				autoSize	: false,
				aspectRatio	: false
			});
			fancybox.filter('.video').fancybox(
			{
				type		: 'iframe',
				width		: w,
				height		: h,
				padding		: 0,
				aspectRatio	: true
			});
		});
	}
	
	//
	
	jQuery('.jplus.content.stores.full a.close').click(function()
	{
		parent.jQuery.fancybox.close(true);
		return false;
	});
}
function Fancybox(link, type, width, height, padding)
{
	switch(type)
	{
		case 'image':
			jQuery.fancybox.open([link],
			{
				type: 'image'
			});
			break;
		
		case 'iframe':
			var w = width;
			var h = height;
			var p = padding;
													
			if(typeof p === 'undefined') p = 50;
			if(!w) w = 900;
			if(!h) h = 600;
												
			jQuery.fancybox.open(
			{
				href		: link,
				type		: 'iframe',
				width		: (typeof w === 'number') ? w - (p * 2) : w,
				height		: (typeof h === 'number') ? h - (p * 2) : h,
				padding		: p,
				autoSize	: false											
			})
			break;
		
		default:
			window.location.href = link;
			break;
	}
	return false;
}