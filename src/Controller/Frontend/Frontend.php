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

		/** @frontend */
		$action = clone $action;
		$action->setHook( 'wp_footer' );
		$action->setCallback( 'simplepopup_loader' );
		$action->setDescription( 'Display the html element from view Frontend' );
		$action->setPriority( 10 );
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
		/** Default Variables */
		define( 'SIMPLEPOPUP_SCREEN', json_encode( $this->WP->getScreen() ) );
		$default = $this->Framework->getConfig()->default;
		$config  = $this->Framework->getConfig()->options;
		$options = (object) ( $this->Helper->ArrayMergeRecursive( (array) $default, (array) $config ) );

		/** Get SimplePopup for JS Manipulation */
		$simplepopup_to_display = $this->Framework->getModels()['SimplePopup'];
		$simplepopup_to_display = $simplepopup_to_display->get_lists_of_simplepopup( array(
			'validateLocation' => true
		) )['items'];
		foreach($simplepopup_to_display as &$simplepopup){
			$simplepopup = $simplepopup->getVars();
		}

		/** Load Inline Script */
		$this->WP->wp_enqueue_script( 'simplepopup-local', 'local/simplepopup.js', array(), '', true );
		$this->WP->wp_localize_script(
			'simplepopup-local',
			'SIMPLEPOPUP_PLUGIN',
			array(
				'name'    => SIMPLEPOPUP_NAME,
				'version' => SIMPLEPOPUP_VERSION,
				'screen'  => SIMPLEPOPUP_SCREEN,
				'path'    => SIMPLEPOPUP_PATH,
				'rest_url'=> esc_url_raw( rest_url() ),
				'options' => $options,
				'to_display' => $simplepopup_to_display
			)
		);

		/** Load WP Core jQuery */
		wp_enqueue_script( 'jquery' );

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

		/** Load Plugin Components */
//		$components = ['simplepopup'];
//		foreach($simplepopup_to_display as $component){
//			var_dump($component);
//			die();
//			$this->WP->wp_enqueue_style( sprintf('fab-%s-component', $component), sprintf('build/components/%s/bundle.css', $component) );
//			$this->WP->wp_enqueue_script(sprintf('fab-%s-component', $component), sprintf('build/components/%s/bundle.js', $component), array(), '1.0', true);
//		}

	}

	/**
	 * Display the html element from view Frontend
	 *
	 * @return  void
	 */
	public function simplepopup_loader() {
		global $post;

		/** Ignore in Pages */
		if ( is_singular() && isset( $post->post_type ) && $post->post_type === 'simplepopup' ) {
			return;
		}

		/** Grab Data */
		$SimplePopup = $this->Framework->getModels()['SimplePopup'];
		$args = array(
			'validateLocation' => true,
		);
		$lists = $SimplePopup->get_lists_of_simplepopup( $args );
		$simplepopup_to_display = $lists['items'];

		/** Show Modal - Only Default */
		if ( ! is_admin() && ( $simplepopup_to_display ) ) {
			$args['builder'] = array( 'default' );
			$simplepopup_to_display  = $SimplePopup->get_lists_of_simplepopup( $args )['items'];

			View::render('Frontend.popup',
				compact( 'post', 'simplepopup_to_display' )
			);
		}
	}
}
