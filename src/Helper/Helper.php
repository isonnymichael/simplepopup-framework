<?php

namespace SimplePopup\Helper;

!defined('WPINC ') or die();

/**
 * Helper library for SimplePopup framework
 *
 * @package    SimplePopup
 * @subpackage SimplePopup\Helper
 */
class Helper
{
	// Load Traits.
	use File;
	use Directory;
	use Text;
	use Option;
	use Plan;

	/**
	 * Define const which will be used within the framework
	 * @param   object   $framework     WordPress theme object
	 * @return void
	 */
	public function defineConst($framework)
	{
		define('SIMPLEPOPUP_NAME', $framework->getName());
		define('SIMPLEPOPUP_VERSION', $framework->getVersion());
		define('SIMPLEPOPUP_PRODUCTION', $framework->isProduction());
		define('SIMPLEPOPUP_PATH', json_encode($framework->getPath()));
	}

	/**
	 * Extract templates from config files
	 * @var     array   $config         Lists of config templates
	 * @var     array   $templates      Lists of templates, to return
	 */
	public function getTemplatesFromConfig($config, $templates = [])
	{
		foreach ($config as $template) {
			foreach ($template->children as $children) {
				$templates[$children->id] = $children;
			}
		}
		return $templates;
	}
}
