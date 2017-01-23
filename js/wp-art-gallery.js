/* Copyright 2017 Mic (email: m@micz.it)
Plugin Info: http://micz.it

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/
var wpartg_hidden=false;
var wpartg_current_index=0;

jQuery(document).ready(function(){

    //jQuery('div#wpartgnojs').remove();
});

function wpartg_enter_gallery(img_array){
	jQuery.swipebox(img_array,{
		afterOpen:function(index){wpartg_add_html(index);},
		afterSetSlide:function(index){wpartg_change_slide(index);},
		afterSetTitle:function(index){wpartg_current_index=index;},
		afterMedia:function(index){setTimeout(function(){if((index==0)&&(wpartg_current_index==index)){wpartg_set_adaptive_colors(index);}},10);},
		afterClose:function(){wpartg_hidden=false;},
		showTitle:false,
		hideBarsDelay:1000,
	});
}

function wpartg_add_html(index){
	jQuery('#swipebox-container').append('<div id="wpartg-title" class="wpartg-img-text"></div>');
	jQuery('#swipebox-container').append('<div id="wpartg-alt" class="wpartg-img-text"></div>');
	jQuery('#swipebox-container').append('<div id="wpartg-caption" class="wpartg-img-text"></div>');
	jQuery('#swipebox-container').append('<div id="wpartg-desc" class="wpartg-img-text"></div>');
	jQuery('#wpartg-title').text(wpartg_img_array[index]['title']);
	jQuery('#wpartg-alt').text(wpartg_img_array[index]['alt']);
	jQuery('#wpartg-caption').text(wpartg_img_array[index]['caption']);
	jQuery('#wpartg-desc').text(wpartg_img_array[index]['desc']);
	jQuery('#swipebox-close').after('<a id="wpartg-hide-text"></a>');
	jQuery('#wpartg-hide-text').bind('touchend click',function(){wpartg_toggle_text();});
}

function wpartg_set_adaptive_colors(index){
	if(wpartg_options["adaptive_color_force"]){
		let curr_img=jQuery('#swipebox-slider .slide').eq(index).find('img')[0];
		if(curr_img){
			let vibrant=new Vibrant(curr_img);
			let swatches = vibrant.swatches();
			jQuery('#wpartg-title').css('background-color',swatches[wpartg_options["adaptive_color_type"]].getHex());
			jQuery('#wpartg-title').css('color',swatches[wpartg_options["adaptive_color_type"]].getTitleTextColor());
			jQuery('#wpartg-alt').css('background-color',swatches[wpartg_options["adaptive_color_type"]].getHex());
			jQuery('#wpartg-alt').css('background-color',swatches[wpartg_options["adaptive_color_type"]].getHex());
			jQuery('#wpartg-caption').css('background-color',swatches[wpartg_options["adaptive_color_type"]].getHex());
			jQuery('#wpartg-caption').css('background-color',swatches[wpartg_options["adaptive_color_type"]].getHex());
			jQuery('#wpartg-desc').css('background-color',swatches[wpartg_options["adaptive_color_type"]].getHex());
			jQuery('#wpartg-desc').css('background-color',swatches[wpartg_options["adaptive_color_type"]].getHex());
		}
	}
}

function wpartg_change_slide(index){
	jQuery('#wpartg-title').text(wpartg_img_array[index]['title']);
	jQuery('#wpartg-alt').text(wpartg_img_array[index]['alt']);
	jQuery('#wpartg-caption').text(wpartg_img_array[index]['caption']);
	jQuery('#wpartg-desc').text(wpartg_img_array[index]['desc']);
	if(wpartg_img_array[index]['title']==''){jQuery('#wpartg-title').hide();}else{if(!wpartg_hidden){jQuery('#wpartg-title').show();}}
	if(wpartg_img_array[index]['alt']==''){jQuery('#wpartg-alt').hide();}else{if(!wpartg_hidden){jQuery('#wpartg-alt').show();}}
	if(wpartg_img_array[index]['caption']==''){jQuery('#wpartg-caption').hide();}else{if(!wpartg_hidden){jQuery('#wpartg-caption').show();}}
	if(wpartg_img_array[index]['desc']==''){jQuery('#wpartg-desc').hide();}else{if(!wpartg_hidden){jQuery('#wpartg-desc').show();}}
	wpartg_set_adaptive_colors(index);
}

function wpartg_toggle_text(){
	if(wpartg_hidden){
		if(jQuery('#wpartg-title').text()!='')jQuery('#wpartg-title').show();
		if(jQuery('#wpartg-alt').text()!='')jQuery('#wpartg-alt').show();
		if(jQuery('#wpartg-caption').text()!='')jQuery('#wpartg-caption').show();
		if(jQuery('#wpartg-desc').text()!='')jQuery('#wpartg-desc').show();
		wpartg_hidden=false;
	}else{
		jQuery('#wpartg-title').hide();
		jQuery('#wpartg-alt').hide();
		jQuery('#wpartg-caption').hide();
		jQuery('#wpartg-desc').hide();
		wpartg_hidden=true;
	}
}