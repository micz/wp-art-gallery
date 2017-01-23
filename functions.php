<?php
/* Copyright 2017 Mic (email: m@micz.it)
Plugin Info: http://micz.it/wordpress-plugin-art-gallery/

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


function wpmz_get_attachment( $attachment_id ) {
	$attachment = get_post( $attachment_id );
	return array(
		'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
		'caption' => $attachment->post_excerpt,
		'description' => $attachment->post_content,
		//'href' => get_permalink( $attachment->ID ),
		//'src' => $attachment->guid,
		'title' => $attachment->post_title
	);
}

function wpmz_is_parent_page($page){
	$post = get_post();
	$anc = get_post_ancestors( $post->ID );
	$match=array_intersect($page,$anc);
	return !empty($match);
}
?>