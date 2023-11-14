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
	/** SimplePopup Trigger */
	public static $trigger = array(
		'trigger' => '0'
	);

	/** SimplePopup  Targeting */
	public static $targeting = array(
		'type' => array(
			array(
				'id' => 'homepage',
				'text' => 'Homepage'
			),
			array(
				'id' => 'search_result',
				'text' => 'Search Result'
			),
			array(
				'id' => '404_error',
				'text' => '404 Error Page'
			),
			array(
				'id' => 'post',
				'text' => 'All Posts'
			),
			array(
				'id' => 'post_id',
				'text' => 'Post: Selected'
			),
			array(
				'id' => 'page',
				'text' => 'All Pages'
			),
			array(
				'id' => 'page_id',
				'text' => 'Page: Selected'
			),
		)
	);

	/** SimplePopup User Session */
	public  static $session = array(
		'session' => array(
			array(
				'id' => 'all',
				'text' => 'All Users'
			),
			array(
				'id' => 'logged_in',
				'text' => 'Logged In Users'
			),
			array(
				'id' => 'non_users',
				'text' => 'Non Users Login'
			)
		)
	);

	/** SimplePopup Display */
	public static $display = array(
		'position' => array(
			array(
				'id'   => 'left_bottom',
				'text' => 'Left Bottom',
			),
			array(
				'id'   => 'left_top',
				'text' => 'Left Top',
			),
			array(
				'id'   => 'center',
				'text' => 'Center',
			),
			array(
				'id'   => 'right_bottom',
				'text' => 'Right Bottom',
			),
			array(
				'id'   => 'right_top',
				'text' => 'Right Top',
			),
		)
	);

	/** SimplePopup Size Type */
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
