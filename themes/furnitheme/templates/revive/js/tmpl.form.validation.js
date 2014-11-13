/**
 *
 * Copyright (C) 2011
 * Chimera | Creative web agency
 * All rights reserved.
 *
 * Website:		www.chimera.it
 * E-mail:		m.cellamare@chimera.it
 *
 */

jQuery(document).ready(function()
{
	if(jQuery('form.form').length > 0)
	{	
		jQuery('form.form').validate(
		{
			errorElement: 'span',
			errorClass: 'error',
			validClass: 'success',
			highlight: function(element, errorClass, validClass)
			{
				jQuery(element).addClass(errorClass);
			},
			unhighlight: function(element, errorClass, validClass)
			{
				jQuery(element).removeClass(errorClass);
			},
			errorPlacement: function(error, element)
			{
				jQuery(element).parents('.fi').append(error);
			}
		});	
	
		//
		//
		//
		
		// Sortable event
		
		jQuery('.jPlus_Sortable .sortable.rows').sortable(
		{
			handle		: '.handle',
			axis		: 'y',
			update		: function(event, ui)
			{
				jPlus_Sortable_Refresh(ui.item.parents('.jPlus_Sortable'));
			} 
		});
		
		// Refresh all files list at load
		
		jQuery('.jPlus_Sortable').each
		(
			function(index, element)
			{
				jPlus_Sortable_Refresh(jQuery(element));
			}
		);
		
		// Add file to files list from a select
		
		jQuery('.ftp select').change
		(
			function()
			{
				jPlus_Files_CloneModel(jQuery(this).parents('.jPlus_Sortable'), jQuery(this).val(), jQuery(this).val().split('.').pop().toLowerCase(), jQuery(this).siblings('input').first().val());
				jQuery(this).val('');
				jPlus_Graphix_Parser();
			}
		);
		
		//
		
		jPlus_Graphix_Parser();
	}
});

// jPlus_Graphix_Parser

function jPlus_Graphix_Parser()
{
	jQuery('.form .jPlus_Files').find('.qq-upload-button').addClass('jpbtn');
	jQuery('.form .jPlus_Files').find('.btn.remove a').addClass('jpbtn').addClass('mini');	
	jQuery('.form .jPlus_Files').find('.btn.upload, .head, .ordering, .thumb span, .title, .custom, .qq-upload-drop-area').addClass('hide');
}

//

// Init for files upload list

function jPlus_Files(id, path, action, ext, limit)
{
	console.log('jPlus_Files');
	var action	= action;
	var element	= id.children('.jupload');
	var file	= '';
	
	//
			
	var juploader = new qq.FileUploader(
	{
		debug					: false,
		element					: element.get(0),
		action					: action,
		onComplete				: function(id, filename, responseJSON)
		{
			if(responseJSON.success)
			{
				console.log('jPlus_Files :: Uploaded ' + responseJSON.filename);
				file = responseJSON.filename + '.' + responseJSON.ext;
				
				element.children('.qq-uploader').children('.qq-upload-list').children('li').each
				(
					function(index, element)
					{
						if(jQuery(element).children('.qq-upload-file').html() == filename)
							jQuery(element).children('.qq-upload-file').html(file);					
					}
				);
								
				element.siblings('.ftp').children('select').append(new Option(file));
				jPlus_Files_CloneModel(element.parents('.jPlus_Sortable'), file, responseJSON.ext, path);
			}
		}
	});
	jPlus_Graphix_Parser();
	
	//
	
	juploader.setParams(
	{
		allowedExtensions		: ext,
		params:
		{
			uploadPath			: path
		}
	});
		
	//
	
	jPlus_Files_HoverTrigger(id);
}

// Trigger hover for upload button

function jPlus_Files_HoverTrigger(id)
{
	id.children('.jupload').children('.qq-uploader').children('.primaryAction').children('input[type="file"]').hover
	(
		function()
		{
			id.children('.upload').children('a').addClass('hover');
		},
		function()
		{
			id.children('.upload').children('a').removeClass('hover');
		}
	)	
}

// Clone element from blank model	
	
function jPlus_Files_CloneModel(parent, file, ext, path)
{	
	var width;
	var height;
	var thumb;	
	var previewExt	= new Array('jpg', 'jpeg', 'gif', 'png');
	var icons = new Array('3gp','7z','ace','ai','aif','aiff','amr','asf','asx','bat','bin','bmp','bup','cab','cbr','cda','cdl','cdr','chm','dat','divx','dll','dmg','doc','dss','dvf','dwg','eml','eps','exe','fla','flv','gif','gz','hqx','htm','html','ifo','indd','iso','jar','jpeg','jpg','lnk','log','m4a','m4b','m4p','m4v','mcd','mdb','mid','mov','mp2','mp4','mpeg','mpg','msi','mswmm','ogg','pdf','png','pps','ps','psd','pst','ptb','pub','qbb','qbw','qxd','ram','rar','rm','rmvb','rtf','sea','ses','sit','sitx','ss','swf','tgz','thm','tif','tmp','torrent','ttf','txt','vcd','vob','wav','wma','wmv','wps','xls','xpi','zip');
		
	if(jQuery.inArray(ext, previewExt) >= 0)
	{
		width	= 120;
		height	= 45;
		thumb	= true;
		filename = file;
	}
	else
	{
		width	= 16;
		height	= 16;
		thumb	= false;
		path 	= '/components/com_jplus/images/filetypes/';

		if(jQuery.inArray(ext, icons) >= 0)
		{
			filename = ext + '.png';
		}
		else
			filename = 'bin.png';		
	}
	
	var index		= parent.children('.sortable.rows').children('.row').length;
	var model		= parent.children('.model').html().replace(/#KEY#/g, index).replace('#PATH#', path).replace('#FILENAME#', filename).replace('#FILE#', file).replace('#W#', width).replace('#H#', height).replace('<!--jPlus_Files>', '').replace('</jPlus_Files-->', '');
	
	//
	
	var newElement	= parent.children('.sortable.rows').append(model);
	newElement.children('.row').last().children('.file').val(file);
	newElement.children('.row').last().children('.preview').attr('title', file);
	
	//
	
	if(thumb)	
		newElement.children('.row').last().children('.preview').addClass('thumb');
	else	
		newElement.children('.row').last().children('.preview').addClass('icon');
	
	jPlus_Sortable_Refresh(parent);
}

// Remove an element

function jPlus_Files_Remove(element)
{
	var parent = element.parents('.jPlus_Files');
	element.remove();
	jPlus_Sortable_Refresh(parent);
}

//
//
//

// Refresh sort for all elements

function jPlus_Sortable_Refresh(element)
{
	var files;
	
	if(element.attr('class').indexOf('jPlus_Files') > -1)
		files = true;
	else
		files = false;
	
	element.children('.sortable.rows').children('.row').each
	(
		function(index, row)
		{	
			row = jQuery(row);
			
			var oldIndex = row.children('span.ordering').children('a').html() - 1;
			
			if(files) jPlus_Files_Refresh(index, oldIndex, row);
		
			row.children('span.ordering').children('a').html(index + 1);
			if(!files) row.children('input.ordering').val(index + 1);
			
			row.children('span.ordering').children('a').html(index + 1);					
			if(!files) row.children('input.ordering').val(index + 1);
		}
	);
	
	if(files) jPlus_Files_CheckLimit(element, element.children('.limit').html());
}

// Refresh sort for all elements
	
function jPlus_Files_Refresh(index, oldIndex, row)
{
	var newID		= row.attr('id').replace('_' + oldIndex, '_' + index);
	var newHref		= row.children('.remove').children('a').attr('href').replace('_' + oldIndex, '_' + index);	
	
	//
	
	row.children('input').each
	(
		function(i, input)
		{					
			input = jQuery(input);
			
			var newName = input.attr('name').replace('[' + oldIndex + ']', '[' + index + ']');
			input.attr('name', newName);
		}
	);
	
	//
	
	row.attr('id', newID);
	row.children('.remove').children('a').attr('href', newHref);
	
	//
	
	jPlus_Graphix_Parser();
}

//
//
//

// Check files limit

function jPlus_Files_CheckLimit(element, limit)
{
	var count = element.children('.sortable.rows').children('.row').size();
	
	//
	
	if(limit > 0)
	{
		if(count < limit)
			element.children('.btn.upload, .ftp, .jupload').removeClass('hide');
		else
			element.children('.btn.upload, .ftp, .jupload').addClass('hide');
		
		//
		
		if((element.children('.ftp').children('select').children('option').size() > 1)
		&& (count < limit))
			element.children('.ftp').removeClass('hide');
		else
			element.children('.ftp').addClass('hide');
	}
		
	//
	
	if(count > 0)
	{
		//element.children('.empty').addClass('hide');
		element.children('.head').removeClass('hide');
	}
	else
	{
		//element.children('.empty').removeClass('hide');
		element.children('.head').addClass('hide');
	}
	
	//
	
	jPlus_Graphix_Parser();
	jPlus_Files_HoverTrigger(element);
}

//
//
//

// Check if file exists

function jPlus_FileExists(file)
{
	jQuery.ajax(
	{
		url			: file,
		type		: 'HEAD',
		error		: function()
		{
			return false;
		},
		success: function()
		{
			return true;
		}
	});
}