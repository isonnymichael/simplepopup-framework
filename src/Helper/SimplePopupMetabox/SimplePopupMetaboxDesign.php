<?php

namespace SimplePopup\Helper\SimplePopupMetabox;

! defined( 'WPINC ' ) or die;

/**
 * Plugin hooks in a backend
 *
 * @package    SimplePopup
 * @subpackage SimplePopup/Controller
 */

use SimplePopup\WordPress\Model\Metabox;

class SimplePopupMetaboxDesign extends Metabox {

	/** $_POST input */
	public static $input = array(
        'simplepopup_settings'   => array(
	        'default' => array(
				'trigger' => '0',
				'targeting' => array(
					'target' => 'home',
					'value' => 'home' // value for custom post id, or pages
				),
		        'size_type' => 'medium',
		        'display' => 'center'
	        ),
        ), // Size.
	);

	/** SimplePopup Metabox Post Metas */
	public static $post_metas = array(
		'simplepopup_settings'   => array( 'meta_key' => 'simplepopup_settings' ), // Size.
	);

	/** Constructor */
	public function __construct() {
		$plugin   = \SimplePopup\Plugin::getInstance();
		$this->WP = $plugin->getWP();
	}

	/** Sanitize */
	public function sanitize() {
		$input = self::$input;

		/** Sanitized input */
		$params = array();
		foreach ( $_POST as $key => $value ) {
			if ( isset( $input[ $key ] ) && $value ) {
				$params[ $key ] = $value;
			}
		}
		$this->params = $params;
	}

	/** SetDefaultInput */
	public function setDefaultInput() {
		/** Default Input Function */
		$input = self::$input;
		foreach ( $input as $key => $value ) {
			$this->params[ $key ] = isset( $this->params[ $key ] ) ? $this->params[ $key ] : $value['default'];
			if ( is_array( $this->params[ $key ] ) ) {
				$this->params[ $key ] += $value['default'];
			}
		}
	}

	/** Save data to database */
	public function save() {
		global $post;
		foreach ( $this->params as $key => $value ) {
			$this->WP->update_post_meta( $post->ID, $key, $value );
		}
	}
}
