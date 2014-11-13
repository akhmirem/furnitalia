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
	Cycle2('.groups', 'ul.group', true, true);
});

//
// Cycle2
//

function Cycle2(cycles, slide, pager, paused, timeout, speed, manualSpeed)
{
	cycles = jQuery(cycles);
	
	if(!timeout)		timeout		= 1000;
	if(!speed)			speed		= 800;
	if(!manualSpeed)	manualSpeed	= 400;
	
	if(cycles.length > 0)
	{
		cycles.each(function(index, cycle)
		{
			cycle		= jQuery(cycle);
			var slides	= cycle.find(slide);
						
			if(slides.length > 1)
			{			
				if(pager)
					cycle.parent().append('<ul class="pager pager' + index + ' abscenter"></ul>');
								
				cycle.cycle(
				{
					log					: false,
					swipe				: true,
					slides				: slide,
					slideActiveClass	: 'active',
					pager				: '.pager.pager' + index,
					pagerTemplate		: '<li><a href="#"><span>&bull;</span></a></li>',
					pagerActiveClass	: 'active',
					paused				: paused,
					timeout				: timeout,
					speed				: speed,
					manualSpeed			: manualSpeed
				});
			}
		});
	}
}