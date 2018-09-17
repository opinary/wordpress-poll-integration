<?php

namespace Opinary\PollIntegration;

require_once OPINARY_PLUGIN_PATH . 'includes/class-embed-code-generator.php';
require_once OPINARY_PLUGIN_PATH . 'includes/class-invalid-tag-attribute-exception.php';
require_once OPINARY_PLUGIN_PATH . 'includes/class-tag-attributes.php';

/**
 *
 */
class Content_Filter {

	public function register() {
		add_filter( 'the_content', [ $this, 'convert_polls' ] );
	}

	public function convert_polls( $content ) {
		// find all [opinary ..] tags
		$tag_regex  = '!\[\s*opinary\s+(.*)\s*\]!i';
		$found_tags = preg_match_all( $tag_regex, $content, $tag_matches, PREG_PATTERN_ORDER );

		if ( ! $found_tags ) {
			return $content;
		}

		$full_matches          = $tag_matches[0];
		$attribute_tag_matches = $tag_matches[1];

		$embed_code_generator = new Embed_Code_Generator();

		foreach ( $attribute_tag_matches as $index => $attribute_tag_match ) {
			// replace all [opinary ..] tags with the real embed code
			try {
				$embed_code = $embed_code_generator->create( $attribute_tag_match );
			} catch ( Invalid_Tag_Attribute_Exception $exception ) {
				$embed_code_error = sprintf(
					__( "<pre>ERROR: %1\$s in:\n%2\$s</pre>", 'opinary_poll_integration' ),
					$exception->getMessage(),
					$full_matches[ $index ]
				);

				$content = str_replace( $full_matches[ $index ], $embed_code_error, $content );

				continue;
			}

			$content = str_replace( $full_matches[ $index ], $embed_code, $content );
		}

		return $content;
	}
}
