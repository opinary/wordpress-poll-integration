<?php

namespace Opinary\PollIntegration;

require_once OPINARY_PLUGIN_PATH . 'includes/class-invalid-tag-attribute-exception.php';
require_once OPINARY_PLUGIN_PATH . 'includes/class-tag-attributes.php';

/**
 *
 */
class Embed_Code_Generator {
	const OPINARY_POLL_URL_TEMPLATE = 'https://compass.pressekompass.net/compasses/%s/%s';
	const OPINARY_STATIC_SCRIPT_URL = '//compass.pressekompass.net/static/opinary.js';

	/**
	 * @param string $attributes_string
	 *
	 * @return string
	 * @throws Invalid_Tag_Attribute_Exception
	 */
	public function create( $attributes_string ) {
		$tag_attributes = $this->extract_tag_attributes( $attributes_string );

		wp_enqueue_script( 'opinary_static_opinary', self::OPINARY_STATIC_SCRIPT_URL );

		return $this->create_embed_code( $tag_attributes );
	}

	/**
	 * @param string $attributes_string
	 *
	 * @return Tag_Attributes
	 * @throws Invalid_Tag_Attribute_Exception
	 */
	protected function extract_tag_attributes( $attributes_string ) {
		// we create pseudo html tag from the string to simplify parsing
		$pseudo_html_tag = sprintf( '<div %s></div>', $attributes_string );

		$doc = new \DOMDocument();
		$doc->loadHTML( $pseudo_html_tag );
		$tags = $doc->getElementsByTagName( 'div' );

		$customer = '';
		$poll_id  = '';

		foreach ( $tags as $tag ) {
			/** @var \DOMElement $tag */
			$customer     = $tag->getAttribute( 'customer' );
			$poll_id      = $tag->getAttribute( 'poll' );
			$is_automated = $tag->hasAttribute( 'automated' );

			$customer = str_replace( [ '”', '“' ], '', $customer );
			$poll_id  = str_replace( [ '”', '“' ], '', $poll_id );

			break; // we're only expecting 1 pseudo div element here
		}

		return new Tag_Attributes( $customer, $poll_id, $is_automated );
	}

	/**
	 * @param Tag_Attributes $tag_attributes
	 *
	 * @return string
	 */
	protected function create_embed_code( Tag_Attributes $tag_attributes ) {
		if ( $tag_attributes->is_automated() ) {
			$embed_code = $this->create_ebmed_code_automated( $tag_attributes );
		} else {
			$embed_code = $this->create_ebmed_code_normal( $tag_attributes );
		}

		return $embed_code;
	}

	/**
	 * @param Tag_Attributes $opinary_tag_attributes
	 *
	 * @return string
	 */
	protected function create_ebmed_code_normal( Tag_Attributes $opinary_tag_attributes ) {
		$poll_url = sprintf(
			self::OPINARY_POLL_URL_TEMPLATE,
			$opinary_tag_attributes->get_customer(),
			$opinary_tag_attributes->get_poll_id()
		);
		$poll_url = esc_url( $poll_url );

		$embed_code = <<<EMBED_CODE
<div class="opinary-widget-wrapper" style="width: 100%; max-width: 500px; height:100%; margin:0 auto;">
  <div class="opinary-widget" style="position:relative; padding-top: 100%;">
    <iframe class="opinary-iframe" width="100%" height="400px" src="${poll_url}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%" frameborder="0"> </iframe>
  </div>
</div>
EMBED_CODE;

		return $embed_code;
	}

	/**
	 * @param Tag_Attributes $tag_attributes
	 *
	 * @return string
	 */
	protected function create_ebmed_code_automated( Tag_Attributes $tag_attributes ) {
		$script_name = sprintf( 'opinary_automated_%s', rawurlencode( $tag_attributes->get_customer() ) );
		$script_url  = sprintf( 'https://widgets.opinary.com/a/%s.js', $tag_attributes->get_customer() );
		wp_enqueue_script( $script_name, $script_url, [], null );

		$embed_code = <<<EMBED_CODE
<div id="opinary-automation-placeholder"></div>
EMBED_CODE;

		return $embed_code;
	}
}
