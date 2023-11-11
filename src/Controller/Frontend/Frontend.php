<?php

namespace SimplePopup\Controller\Frontend;

! defined( 'WPINC ' ) or die();

// Plugin class import.
use SimplePopup\Controller;
use SimplePopup\View;
use SimplePopup\WordPress\Hook\Action;

/**
 * Frontend
 *
 * @since 0.3.0
 *
 * @package    SimplePopup
 * @subpackage SimplePopup/Controller
 */
class Frontend extends Controller {
	/**
	 * Constructor
	 *
	 * @param object $framework Framework instance.
	 *
	 * @return void
	 * @since 0.3.0
	 *
	 */
	public function __construct( $framework ) {
		parent::__construct( $framework );

		// Enqueue scripts @frontend.
		$action = new Action();
		$action->setComponent( $this );
		$action->setHook( 'wp_enqueue_scripts' );
		$action->setCallback( 'frontend_enqueue' );
		$action->setMandatory( true );
		$action->setDescription( __( 'Enqueue scripts in frontend', 'simplepopup' ) );
		$this->hooks[] = $action;

		$action = clone $action;
		$action->setHook( 'wp_footer' );
		$action->setCallback( 'modal_dom' );
		$action->setDescription( __( 'Enqueue scripts in frontend', 'simplepopup' ) );
		$this->hooks[] = $action;
	}

	/**
	 * Enqueue scripts @frontend
	 *
	 * @return  void
	 * @since 0.3.0
	 *
	 */
	public function frontend_enqueue() {

		// Load Plugin Assets.
		$this->WP->wp_enqueue_style(
			'sip-style',
			'build/css/frontend.min.css'
		);
		$this->WP->wp_enqueue_script(
			'sip-script',
			'build/js/frontend/frontend.min.js',
			[],
			'0.0.1',
			true
		);
	}

	/**
	 * Modal DOM
	 *
	 * @return  void
	 */
	public function modal_dom(){

		// Sections
		$sections = array();
		$sections['Frontend.modal-component'] = array();

		// Load view.
		$view = new View( $this->Framework );
		$view->setTemplate( 'frontend.blank' );
		$view->setSections( $sections );
		$view->setOptions( array( 'shortcode' => true ) );
		$view->build();
	}
}
