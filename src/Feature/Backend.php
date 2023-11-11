<?php
namespace SimplePopup\Feature;

use SimplePopup\Feature;

!defined( 'WPINC ' ) or die;

/**
 * Initiate plugins
 *
 * @package    SimplePopup
 * @subpackage SimplePopup\Includes
 */

class Backend extends Feature {

    /**
     * Feature construect
     * @return void
     * @var    object   $plugin     Feature configuration
     * @pattern prototype
     */
    public function __construct($plugin){
        $this->key = 'core_backend';
        $this->name = 'Backend';
        $this->description = 'Handles plugin backend core function';
    }

}
