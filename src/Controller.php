<?php

namespace SimplePopup;

!defined('WPINC ') or die();

/**
 * Controller
 *
 * @package    SimplePopup
 * @subpackage SimplePopup/Controller
 */
class Controller
{
	/**
	 * @access   protected
	 * @var      array    $hook    Lists of hooks to register within controller
	 */
	protected $hooks;

	/**
	 * Admin constructor
	 *
	 * @return void
	 * @param    object $plugin     Framework configuration
	 * @pattern prototype
	 */
	public function __construct(\SimplePopup\Plugin $plugin)
	{
		$this->Framework = $plugin;
		$this->Helper = $plugin->getHelper();
		$this->WP = $plugin->getWP();
		$this->hooks = [];
	}

	/**
	 * Overloading Method, for multiple arguments
	 *
	 * @method  loadModel           _ Load model @var string name
	 * @method  loadController      _ Load controller @var string name
	 */
	public function __call($method, $arguments)
	{
		if (in_array($method, ['loadModel', 'loadController'])) {
			$list = $method == 'loadModel' ? $this->Framework->getModels() : [];
			$list =
				$method == 'loadController'
					? $this->Framework->getControllers()
					: $list;
			if (count($arguments) == 1) {
				$this->{$arguments[0]} = $list[$arguments[0]];
			}
			if (count($arguments) == 2) {
				$this->{$arguments[0]} = $list[$arguments[1]];
			}
		}
	}

	/**
	 * @return array
	 */
	public function getHooks()
	{
		return $this->hooks;
	}

	/**
	 * @param array $hooks
	 */
	public function setHooks($hooks)
	{
		$this->hooks = $hooks;
	}
}
