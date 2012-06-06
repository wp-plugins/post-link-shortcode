<?php
/*
Plugin Name: Post link shortcode
Plugin URI: http://blog.scur.pl/wordpress-post-link-shortcode/
Description: Adds [post] shortcode that returns either a URL or anchor to a post
Version: 1.0
Author: Michał Ochman
Author URI: http://scur.pl/
License: GPLv2 or later
*/
/*  Copyright 2012 Michał Ochman

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/**
 * Adds [post] shortcode that returns either a URL or anchor to a post
 * 
 * Takes one or more of the following attributes:
 * - id - int - numeric post ID you want to link to, defaults to current (inside Loop)
 * - anchor - string, "yes" or "no" - indicates whether to return just a permalink or full anchor
 * - title - string - replaces default 'Permalink to %s' anchor title; can include a single %s to replace for post title; can only be used when anchor is set to "yes"
 * - text - string - replaces anchor inner text; can include a single %s to replace for post title; can only be used when anchor is set to "yes"
 * - class - string - adds custom class or classes to anchor element; can only be used when anchor is set to "yes"
 *
 * Usage examples:
 * - [post]
 * - [post id="42"]
 * - [post id="42" anchor="yes" class="extra-link-class"]
 * - [post id="42" anchor="yes" title="My post"]
 * - [post id="42" anchor="yes" title="See %s"]
 * - [post id="42" anchor="yes" title="The other post" text="click here"]
 * - [post id="42" anchor="yes" text="Visit %s"]
 *
 * @param array An array of user defined shortcode attributes
 * @return string Either a URL or anchor to a post
 */
if ( function_exists( 'add_shortcode' ) && ! function_exists( 'post_link_shortcode' ) ) {
	function post_link_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'id' => '',
			'anchor' => '',
			'title' => '',
			'text' => '',
			'class' => '',
		), $atts ) );

		if ( empty( $id ) ) {
			global $post;
			$id = $post->ID;
		}

		$permalink = get_permalink( $id );
		if ( empty( $permalink ) ) {
			return '';
		}
		if ( 'yes' !== $anchor ) {
			return $permalink;
		}

		if ( empty( $title ) ) {
			$title = sprintf( 'Permalink to %s', get_the_title( $id ) );
		} elseif ( 1 === substr_count ( $title, '%s' ) ) {
			$title = sprintf( $title, get_the_title( $id ) );
		}
		if ( empty( $text ) ) {
			$text = get_the_title( $id );
		}	 elseif ( 1 === substr_count ( $text, '%s' ) ) {
			$text = sprintf( $text, get_the_title( $id ) );
		}

		$anchor = sprintf( '<a href="%s" title="%s" class="%s">%s</a>', $permalink, $title, $class, $text );

		return $anchor;
	}
	add_shortcode( 'post', 'post_link_shortcode' );
}