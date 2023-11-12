<?php

namespace SimplePopup\Controller\Metabox;

! defined( 'WPINC ' ) or die;

/**
 * Initiate plugins
 *
 * @package    SimplePopup
 * @subpackage SimplePopup/Controller
 */

use SimplePopup\Controller\Base;
use SimplePopup\Feature\Design;
use SimplePopup\Feature\Modal;
use SimplePopup\Helper\SimplePopup\SimplePopupItem;
use SimplePopup\View;
use SimplePopup\WordPress\Hook\Action;
use SimplePopup\WordPress\MetaBox;

class MetaboxDesign extends Base {

    /**
     * Admin constructor
     *
     * @return void
     * @var    object   $plugin     Plugin configuration
     * @pattern prototype
     */
    public function __construct( $plugin ) {
        parent::__construct( $plugin );

        /** @backend - Eneque scripts */
        $action = new Action();
        $action->setComponent( $this );
        $action->setHook( 'admin_enqueue_scripts' );
        $action->setCallback( 'backend_enequeue_metabox_design' );
        $action->setAcceptedArgs( 1 );
        $action->setMandatory( true );
        $action->setDescription( 'Enqueue backend metabox design' );
        $action->setFeature( $plugin->getFeatures()['core_backend'] );
        $this->hooks[] = $action;

        /** @backend - Add Designs metabox to SimplePopup CPT */
        $action = new Action();
        $action->setComponent( $this );
        $action->setHook( 'add_meta_boxes' );
        $action->setCallback( 'metabox_design' );
        $action->setMandatory( false );
        $action->setDescription( 'Add Design metabox to SimplePopup CPT' );
        $action->setFeature( $plugin->getFeatures()['core_backend'] );
        $action->setPremium( false );
        $this->hooks[] = $action;
    }

    /**
     * Eneque scripts @backend
     *
     * @return  void
     * @var     array   $hook_suffix     The current admin page
     */
    public function backend_enequeue_metabox_design( $hook_suffix ) {
        /** Grab Data */
        global $post;

//        $screen = $this->WP->getScreen();
//        $allowedPage = ['post.php', 'post-new.php'];
//        if ( !isset( $post->post_type ) || $post->post_type !== 'simplepopup' || !in_array($screen->pagenow, $allowedPage) ) {
//            return;
//        }
        /** Grab Data */
//        $simplepopup = new SimplePopupItem( $post->ID );
//        $simplepopup = $simplepopup->getVars();

        /** Add Inline Script */
//        $this->WP->wp_localize_script( 'simplepopup-local', 'SIMPLEPOPUP_METABOX_DESIGN', array(
//            'defaultOptions' => [
//                'size' => array( 'type' => Design::$size['type'] ),
//                'theme' => Modal::$theme,
//                'layout' => Modal::$layout,
//                'template' => Design::$template,
//            ],
//            'data' => compact('simplepopup')
//        ));

        /** Enqueue */
//        $this->WP->wp_enqueue_script( 'simplepopup-design', 'build/js/backend/metabox-design.min.js', array(), '', true );
//
//        /** Load Component */
//        $component = 'metabox-design';
//        $this->WP->wp_enqueue_style( sprintf('%s-component', $component), sprintf('build/components/%s/bundle.css', $component) );
//        $this->WP->wp_enqueue_script(sprintf('%s-component', $component), sprintf('build/components/%s/bundle.js', $component), array(), '1.0', true);
    }

    /**
     * Register metabox designs on custom post type SimplePopup
     *
     * @return      void
     */
    public function metabox_design() {
        $metabox = new MetaBox();
        $metabox->setScreen( 'simplepopup' );
        $metabox->setId( 'simplepopup-metabox-design' );
        $metabox->setTitle( 'Design' );
        $metabox->setCallback( array( $this, 'metabox_design_callback' ) );
        $metabox->setCallbackArgs( array( 'is_display' => false ) );
        $metabox->build();
    }


    /**
     * Metabox Design set view template
     *
     * @return      string              Html template string from view View/Template/backend/metabox_design.php
     * @param       object $post      global $post object
     */
    public function metabox_design_callback() {
        View::render('Backend.Metabox.design');
    }

}
