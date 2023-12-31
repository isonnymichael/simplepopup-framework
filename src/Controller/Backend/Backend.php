<?php

namespace SimplePopup\Controller\Backend;

!defined('WPINC ') or die();

// Plugin class import.
use SimplePopup\Controller;
use SimplePopup\WordPress\Hook\Action;
use SimplePopup\WordPress\Hook\Filter;

/**
 * Backend
 *
 * @since 0.3.0
 *
 * @package    SimplePopup
 * @subpackage SimplePopup/Controller
 */
class Backend extends Controller
{
	/**
	 * Constructor
	 *
	 * @since 0.3.0
	 *
	 * @param object $framework Framework instance.
	 *
	 * @return void
	 */
	public function __construct($framework)
	{
		parent::__construct($framework);

		// Enqueue scripts @backend.
		$action = new Action();
		$action->setComponent($this);
		$action->setHook('admin_enqueue_scripts');
		$action->setCallback('backend_enqueue');
		$action->setMandatory(true);
		$action->setDescription(__('Enqueue scripts in backend', 'simplepopup'));
		$this->hooks[] = $action;

		// Custom post page
		/** @backend */
		$action = clone $action;
		$action->setHook( 'enter_title_here' );
		$action->setCallback( 'backend_custom_label' );
		$action->setDescription( 'Custom label backend post' );
		$this->hooks[] = $action;

	}

	/**
	 * Enqueue scripts @backend
	 *
	 * @since 0.3.0
	 *
	 * @return  void
	 */
	public function backend_enqueue()
	{
		global $post;

		// Define Variables.
		define('SIMPLEPOPUP_SCREEN', json_encode($this->WP->getScreen()));
		$config = $this->Framework->getConfig()->options;
		$screen = $this->WP->getScreen();
		$screen->base = str_replace(' ', '-', $screen->base);
		$slug = sprintf('%s-setting', $this->Framework->getSlug());
		$screens = [sprintf('settings_page_%s', $slug)];
		$allowedPage = ['post.php', 'post-new.php'];

		// Load Vendors.
		if (
			in_array($screen->base, $screens) ||
			(isset($post->post_type) &&
				$post->post_type === 'simplepopup' &&
				in_array($screen->pagenow, $allowedPage))
		) {
			// Load Core Vendors.
			wp_enqueue_script('jquery');

			$this->WP->enqueue_assets($config->simplepopup_assets->backend);
			$this->WP->wp_enqueue_style(
				'animatecss',
				'vendor/animatecss/animate.min.css'
			);

			// Load var local data
			$this->WP->wp_enqueue_script( 'simplepopup-local', 'local/simplepopup.js', array(), '', true );

			// Load Plugin Assets.
			$this->WP->wp_enqueue_style('simplepopup', 'build/css/backend.min.css');
			$this->WP->wp_enqueue_script(
				'simplepopup',
				'build/js/backend/backend.min.js',
				[],
				'',
				true
			);


		}

	}

	/**
	 * @return void
	 * Custom Label backend Title and Placeholder
	 */
	public function backend_custom_label()
	{
		global $post;
		$screen = $this->WP->getScreen();
		$screen->base = str_replace(' ', '-', $screen->base);
		$slug = sprintf('%s-setting', $this->Framework->getSlug());
		$screens = [sprintf('settings_page_%s', $slug)];
		$allowedPage = ['post.php', 'post-new.php'];
		$title = "";
		// Load Vendors.
		if (
			in_array($screen->base, $screens) ||
			(isset($post->post_type) &&
			 $post->post_type === 'simplepopup' &&
			 in_array($screen->pagenow, $allowedPage))
		) {
			$title = 'Enter '.$this->Framework->getName().' Title';
		}

		return $title;
	}
}
