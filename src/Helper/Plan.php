<?php

namespace SimplePopup\Helper;

!defined('WPINC ') or die();

/**
 * Helper library for SimplePopup framework
 *
 * @package    SimplePopup
 * @subpackage SimplePopup\Includes
 */

trait Plan
{
	/**
	 * Get Premium Plan Info
	 * @return bool
	 */
	public function isPremiumPlan()
	{
		return true;
		/** Get Plan from config.json file */
		$plan = $this->Framework->getConfig()->premium;

		/** Freemius - Check Premium Plan */
		if (function_exists('simplepopup_freemius')) {
			if (simplepopup_freemius()->is__premium_only()) {
				if (simplepopup_freemius()->is_plan('pro')) {
					$plan = 'pro';
				}
			}
		}

		return $plan;
	}

	/**
	 * Get Upgrade URL
	 * @return string
	 */
	public function getUpgradeURL()
	{
		return function_exists('simplepopup_freemius')
			? simplepopup_freemius()->get_upgrade_url()
			: false;
	}
}
