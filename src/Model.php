<?php

namespace SimplePopup;

!defined('WPINC ') or die();

/**
 * Initiate framework
 *
 * @package    SimplePopup
 * @subpackage SimplePopup/Model
 */

use SimplePopup\Model\loadController;
use SimplePopup\Model\loadModel;
use SimplePopup\WordPress\Model\Type;

class Model extends Type
{
	/**
	 * Construct type
	 * @return void
	 * @var    object $framework Framework configuration
	 * @pattern prototype
	 */
	public function __construct($framework)
	{
		$this->name = substr(strrchr(get_called_class(), '\\'), 1);
		$this->name = strtolower($this->name);
		$this->Framework = $framework;
		$this->Helper = $framework->getHelper();
		$this->WP = $framework->getWP();
		$this->taxonomies = [];
		$this->hooks = [];
		$this->metas = [];
		$this->args = [];
		$this->args['public'] = true;
		$this->args['labels'] = ['name' => ucwords($this->name)];
	}

	/**
	 * Overloading Method, for multiple arguments
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
}
