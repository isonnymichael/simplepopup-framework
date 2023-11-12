<?php

namespace SimplePopup\Feature;

use SimplePopup\Feature;

! defined( 'WPINC ' ) or die;

/**
 * Initiate plugins
 *
 * @package    SimplePopup
 * @subpackage SimplePopup\Includes
 */

class Design extends Feature {
	/** SimplePopup Default Size Type */
	public static $size = array(
		'type' => array(
            array(
                'id'   => 'xsmall',
                'text' => 'XSmall',
            ),
            array(
                'id'   => 'small',
                'text' => 'Small',
            ),
            array(
                'id'   => 'medium',
                'text' => 'Medium',
            ),
            array(
                'id'   => 'large',
                'text' => 'Large',
            ),
            array(
                'id'   => 'xlarge',
                'text' => 'XLarge',
            )
        )
	);


	/**
	 * Feature construect
	 *
	 * @return void
	 * @var    object   $plugin     Feature configuration
	 * @pattern prototype
	 */
	public function __construct( $plugin ) {
		$this->WP          = $plugin->getWP();
		$this->key         = 'core_design';
		$this->name        = 'Design';
		$this->description = 'Simple Popup Design';
	}

	/**
	 * Sanitize input
	 */
	public function sanitize() {
		/** Grab Data */
		$this->params = $_POST;
		$this->params = $this->params['simplepopup_design'];

		/** Sanitize Text Field */
		$this->params = (object) $this->WP->sanitizeTextField( $this->params );
	}

}
