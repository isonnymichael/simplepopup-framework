<?php

namespace SimplePopup\Api;

use SimplePopup\API;
use SimplePopup\WordPress\Hook\Action;
use SimplePopup\WordPress\Hook\Filter;
use WP_Error;

class BackendAPI extends API {
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

		// Register rest api route
		$action = new Action();
		$action->setComponent($this);
		$action->setHook('rest_api_init');
		$action->setCallback('register_popup_rest_route');
		$action->setMandatory(true);
		$action->setDescription(__('Register rest api route', 'simplepopup'));
		$this->hooks[] = $action;

		// Restrict API Access
		$filter = new Filter();
		$filter->setComponent($this);
		$filter->setHook('rest_authentication_errors');
		$filter->setCallback('restrict_api_access');
		$filter->setMandatory(true);
		$filter->setDescription(__('Restrict API Access', 'simplepopup'));
		$this->hooks[] = $filter;
	}

	/**
	 * @return void
	 * Register Popup Rest API Route
	 */
	public function register_popup_rest_route(){
		register_rest_route('artistudio/v1', '/popup/', array(
			'methods' => 'GET',
			'callback' => array($this, 'get_popup_data'),
		));
	}

	/**
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 * Get Popup Data from REST API with method GET
	 */
	public function get_popup_data() {
		$SimplePopup = $this->Framework->getModels()['SimplePopup'];
		$lists = $SimplePopup->get_lists_of_simplepopup();
		$popups = $lists['items'];

		// Formatting to array
		$popups = array_map(function ($popup) {
			return array(
				'title' => $popup->getTitle(),
				'content' => $popup->getContent(),
				'trigger' => $popup->getTrigger(),
				'targeting' => $popup->getTargeting(),
				'session' => $popup->getSession(),
				'display' => $popup->getDisplay(),
				'size' => $popup->getSize()
			);
		}, $popups);

		return rest_ensure_response($popups);
	}

	/**
	 * @return WP_Error|null
	 * Restrict API Access just for User Logged In
	 */
	function restrict_api_access() {
		// Check if the current request is a REST API request
		if (did_action('rest_api_init')) {
			// Check if the user is not logged in
			if (!is_user_logged_in()) {
				// Return a response indicating unauthorized access
				return new WP_Error(
					'rest_not_logged_in',
					__('You must be logged in to access this resource.', 'simplepopup'),
					array('status' => 401)
				);
			}
		}

		return null; // Continue with the request
	}
}
