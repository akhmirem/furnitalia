/**
 *
 * Copyright (C) 2012
 * Chimera | Creative web agency
 * All rights reserved.
 *
 * Website:		www.chimera.it
 * E-mail:		m.cellamare@chimera.it
 *
 */
 
jQuery(document).ready(function()
{	
	// Fix IE8 anchors	
	
	jQuery('a[name]').html('&nbsp;').css({'position' : 'absolute'});
});

//
// PopUp
//

function PopUp(page, name)
{
	var w = 600;
	var h = 450;
	var l = Math.floor((screen.width - w) / 2);
	var t = Math.floor((screen.height - h) / 2);

	window.open(page, name, "left=" + l + ", top=" + t + ", width=" + w + ", height=" + h + ", status=no, directories=no, location=no, menubar=no, toolbar=no, scrollbars=no, resizable=no");
};

function PopUpClose(name)
{
	window.close(name);
};

//
// ShowHide
//

function ShowHide(obj)
{
	jQuery(obj).toggle();
}

//
// AnimatedShowHide
//

function AnimatedShowHide(obj, p, h, o)
{
	jQuery(obj).animate(
	{
		'padding-top': p,
		'padding-bottom': p,
		height: h,
		opacity: o	
	},
	400, function()
	{
		jQuery(obj).toggle();
	});
}

//
// AutoFocus
//

function AutoFocus(obj)
{
	jQuery(obj).focus();
}

//
// SelectSubmit
//

function SelectSubmit(obj)
{
	jQuery(obj).closest("form").submit();
}