<?php
/*
Plugin Name:  Opinary Poll Integration
Plugin URI:   https://developer.wordpress.org/plugins/opinary-poll-integration/
Description:  Allows to integrate Opinary.com polls directly within content of posts and pages (This requires an account at Opinary.com)
Version:	  0.1.0
Author:		  Opinary.com
Author URI:   https://opinary.com/

License:	 GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html

{Plugin Name} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

{Plugin Name} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with {Plugin Name}. If not, see {License URI}.
*/

defined( 'ABSPATH' ) or die( 'This is a plugin. Direct execution not allowed!' );
define( 'OPINARY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

require_once( OPINARY_PLUGIN_PATH . 'class-opinary-embed-code-generator.php' );


/**
 * @param string $content
 *
 * @return string
 */
function filter_opinary_poll_integration_tags( $content ) {
	// find all [opinary ..] tags
	$tag_regex = '!\[\s*opinary\s+(.*)\s*\]!i';
	$found_tags = preg_match_all( $tag_regex, $content, $tag_matches, PREG_PATTERN_ORDER );

	if ( ! $found_tags ) {
		return $content;
	}

	$full_matches = $tag_matches[0];
	$attribute_tag_matches = $tag_matches[1];

	$embed_code_generator = new Opinary_Embed_Code_Generator();

	foreach ( $attribute_tag_matches as $index => $attribute_tag_match ) {
		// replace all [opinary ..] tags with the real embed code
		try {
			$embed_code = $embed_code_generator->create( $attribute_tag_match );
		} catch ( \Opinary_Invalid_Tag_Attribute_Exception $exception ) {
			$embed_code_error = sprintf(
				__( "<pre>ERROR: %s in:\n%s</pre>", 'opinary_poll_integration' ),
				$exception->getMessage(),
				$full_matches[$index]
			);
			$content = str_replace( $full_matches[$index], $embed_code_error, $content );
			continue;
		}

		$content = str_replace( $full_matches[$index], $embed_code, $content );
	}

	return $content;
}

add_filter( 'the_content', 'filter_opinary_poll_integration_tags' );
