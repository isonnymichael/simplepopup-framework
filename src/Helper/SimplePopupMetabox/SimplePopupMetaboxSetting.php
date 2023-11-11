<?php

namespace SimplePopup\Helper\SimplePopupMetabox;

! defined( 'WPINC ' ) or die;

/**
 * Plugin hooks in a backend
 *
 * @package    SimplePopup
 * @subpackage SimplePopup/Controller
 */

use SimplePopup\Wordpress\Model\Metabox;

class SimplePopupMetaboxSetting extends Metabox {

	/** FAB Metabox Settings */
	public static $types = array(
        array(
            'text' => 'Bar & Button',
            'children' => array(
                array(
                    'id'   => 'print',
                    'text' => 'Print',
                ),
                array(
                    'id'   => 'readingbar',
                    'text' => 'Reading Bar',
                ),
                array(
                    'id'   => 'scrolltotop',
                    'text' => 'Scroll To Top',
                ),
            )
        ),
        array(
            'text' => 'Link',
            'children' => array(
                array(
                    'id'   => 'link',
                    'text' => 'Link',
                ),
                array(
                    'id'   => 'anchor_link',
                    'text' => 'Anchor Link',
                ),
                array(
                    'id'   => 'latest_post_link',
                    'text' => 'Latest Post',
                ),
            )
        ),
        array(
            'text'     => 'Modal & Popup',
            'children' => array(
                array(
                    'id'   => 'auth_login',
                    'text' => 'Login',
                ),
                array(
                    'id'   => 'auth_logout',
                    'text' => 'Logout',
                ),
                array(
                    'id'   => 'modal',
                    'text' => 'Simple Modal',
                ),
                array(
                    'id'   => 'search',
                    'text' => 'Search',
                ),
            ),
        ),
		array(
			'text'     => 'Widget',
			'children' => array(
				array(
					'id'   => 'modal_widget',
					'text' => 'Modal + Widget',
				),
				array(
					'id'   => 'widget',
					'text' => 'Widget',
				),
			),
		),
	);

	/** FAB Metabox Settings */
	public static $triggers = array(
		array(
			'id'   => 'exit_intent',
			'text' => 'Exit Intent',
		),
		array(
			'id'   => 'time_delay',
			'text' => 'Time Delay',
		),
	);

	/** $_POST input */
	public static $input = array(
		'simplepopup_setting_type'          => array( 'default' => '' ),

        /** Link */
		'simplepopup_setting_link'          => array( 'default' => '' ),
		'simplepopup_setting_link_behavior' => array( 'default' => '' ),

        /** Print */
		'simplepopup_setting_print_target' => array( 'default' => '' ),
	);

	/** FAB Metabox Post Metas */
	public static $post_metas = array(
		'type'          => array( 'meta_key' => 'simplepopup_setting_type' ),

        /** Link */
		'link'          => array( 'meta_key' => 'simplepopup_setting_link' ),
		'link_behavior' => array( 'meta_key' => 'simplepopup_setting_link_behavior' ),

        /** Print */
		'print_target' => array( 'meta_key' => 'simplepopup_setting_print_target' ),
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
				$params[ $key ] = sanitize_text_field( $value );
			}
		}

		$this->params = $params;
	}

	/** SetDefaultInput */
	public function setDefaultInput() {
		/** Default Input Function */
		$input = self::$input;
		foreach ( $input as $key => $value ) {
			if ( ! isset( $this->params[ $key ] ) ) {
				$this->params[ $key ] = $value['default'];
			}
		}

		/** Transform Data */
		$this->params['simplepopup_setting_link'] = ( $this->params['simplepopup_setting_link'] ) ? $this->params['simplepopup_setting_link'] : '#';
		$this->params['simplepopup_setting_link_behavior'] = ( $this->params['simplepopup_setting_link_behavior'] === 'true' ) ? 1 : 0;
		$this->params['simplepopup_setting_link_behavior'] = ( $this->params['simplepopup_setting_type'] === 'link' ) ? $this->params['simplepopup_setting_link_behavior'] : 0;
	}

	/** Save data to database */
	public function save() {
		global $post;
		foreach ( $this->params as $key => $value ) {
			$this->WP->update_post_meta( $post->ID, $key, $value );
		}
	}

}
