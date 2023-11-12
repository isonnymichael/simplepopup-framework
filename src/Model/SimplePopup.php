<?php
/**
 * Initiate plugins
 *
 * @package    SimplePopup
 * @subpackage SimplePopup/Model
 */

namespace SimplePopup\Model;

! defined( 'WPINC ' ) || die;

use SimplePopup\Model;
use SimplePopup\Wordpress\Hook\Action;
class SimplePopup extends Model {

	/**
	 * @var array   WordPress global $post variable.
	 */
	protected $post;

	/**
	 * Constructor
	 *
	 * @param \SimplePopup\Plugin $plugin
	 */
	public function __construct( \SimplePopup\Plugin $plugin ) {
		/** Create a post type */
		parent::__construct( $plugin );

        $this->args['labels'] = [
			'name' => $plugin->getName(),
	        'add_new_item' => __( "Add ".$plugin->getName() ),
			'add_new' => __( "Add ".$plugin->getName() ),
        ];
		$this->args['public'] = true;
		$this->args['publicly_queryable'] = true; /** Needed to enable Elementor */
		$this->args['menu_icon'] = 'dashicons-welcome-widgets-menus';
		$this->args['has_archive'] = false;
		$this->args['show_in_rest'] = true;
        $this->args['supports'] = array('title','editor');
		// Disable Gutenberg editor
		// TODO: the location is must not to be here
		add_filter('use_block_editor_for_post', '__return_false', 10);

		/** @backend */
		$action = new Action();
		$action->setComponent( $this );
		$action->setHook( 'save_post' );
		$action->setCallback( 'metabox_save_data' );
		$action->setMandatory( true );
		$action->setDescription( 'Save SIB Metabox Data' );
		$this->hooks[] = $action;

		/** @backend */
//		$action = clone $action;
//		$action->setHook( 'template_redirect' );
//		$action->setCallback( 'redirect_public_access' );
//		$action->setDescription( 'Redirect SIP Post Type Public Access' );
//		$this->hooks[] = $action;
	}

	/**
	 * Save metabox data when post is saving
	 *
	 * @return void
	 */
	public function metabox_save_data() {
		global $post;

		/** Check Correct Post Type, Ignore Trash */
		if ( ! isset( $post->ID ) || $post->post_type !== $this->name || $post->post_status === 'trash' ) {
			return;
		}

//		/** Save Metabox Setting */
//		if ( $this->checkInput( FABMetaboxSetting::$input ) ) {
//			$metabox = new FABMetaboxSetting();
//			$metabox->sanitize();
//			$metabox->setDefaultInput();
//			$metabox->save();
//		}
//
//		/** Save Metabox Design */
//		if ( $this->checkInput( FABMetaboxDesign::$input ) ) {
//			$metabox = new FABMetaboxDesign();
//			$metabox->sanitize();
//			$metabox->setDefaultInput();
//			$metabox->save();
//		}
//
//		/** Save Metabox Location */
//		if ( $this->checkInput( FABMetaboxLocation::$input ) ) {
//			$metabox = new FABMetaboxLocation();
//			$metabox->sanitize();
//			$metabox->setDefaultInput();
//			$metabox->save();
//		} else {
//			$this->WP->delete_post_meta( $post->ID, FABMetaboxLocation::$post_metas['locations']['meta_key'] );
//		}
//
//		/** Save Metabox Trigger */
//		if ( $this->checkInput( FABMetaboxTrigger::$input ) ) {
//			$metabox = new FABMetaboxTrigger();
//			$metabox->sanitize();
//			$metabox->setDefaultInput();
//			$metabox->save();
//		}
	}

	/**
	 * Return fabs item and fabs order
	 *
	 * @param array $args   Arguments.
	 * @return array
	 */
	public function get_lists_of_fab( $args = array() ) {
		/** Data */
		$order = array();
		$items = array();
		$custom = array(
            'readingbar' => false,
            'scrolltotop' => false
        );

		/** Grab Data - Ordered Data */
		$fab_order = $this->Plugin->getConfig()->options->fab_order;
		if ( $fab_order ) {
			$order = $fab_order;
			foreach ( $fab_order as $value ) {
				$items[] = get_post( $value );
			}
		}
		$order = array_flip( $order );

		/** Grab Data - Unordered */
		$items = array_merge(
			$items,
			get_posts(
				array(
					'posts_per_page' => -1,
					'post_type'      => $this->getName(),
					'post_status'    => array( 'publish' ),
					'post__not_in'   => empty( $fab_order ) ?
						array( 'empty' ) : $fab_order,
					'orderby'        => 'post_date',
					'order'          => 'DESC',
				)
			)
		);

		/** Filter by Location */
		$tmp = array();
		foreach ( $items as &$item ) {
            /** Data Validation */
			if ( ! isset( $item->ID ) ) { continue; }

            /** FAB Item */
			$item = new FABItem( $item->ID ); // Grab FAB Item.

            /** FAB Item Args Validation */
			if ( $item->getStatus() !== 'publish' ) { continue; }
			if ( isset( $args['builder'] ) && ! in_array( $item->getBuilder(), $args['builder'] ) ) { continue; }

            /** FAB Item Grab Custom Module */
            if( in_array($item->getType(), array_keys($custom)) ){
                $custom[ $item->getType() ] = $item;
                if ( isset( $args['filtercustommodule'] ) ) { continue; }
            }

            /** FAB Item Location */
			if ( isset( $args['validateLocation'] ) &&
				! empty( $item->getLocations() ) &&
				! $item->isToBeDisplayed()
			) {
				continue; // Check location rules.
			}

            /** Order */
			if ( ! isset( $order[ $item->getID() ] ) ) {
				$order[ $item->getID() ] = count( $order ); }

            /** Grab Location */
			$tmp[] = $item;
		}
		unset( $item );
		$items = $tmp;

		/** Filter by Type */
		if ( isset( $args['filterbyType'] ) ) {
			$tmp = array();
			foreach ( $items as $item ) {
				if ( in_array( $item->getType(), $args['filterbyType'] ) ) {
					$tmp[] = $item;
				}
			}
			$items = $tmp;
		}

		return array(
			'order' => array_flip( $order ),
			'items' => $items,
            'custom' => $custom
		);
	}

	/** Redirect public access */
	public function redirect_public_access() {
		global $post;
		if ( isset( $post->post_type ) && $post->post_type === 'fab' ) {
			$user  = ( is_user_logged_in() ) ? wp_get_current_user() : array();
			$roles = ( isset( $user->roles ) ) ? (array) $user->roles : array();
			if ( ! in_array( 'administrator', $roles ) ) {
				$url = sprintf( '%spost.php?post=%s&action=edit', admin_url(), $post->ID );
				wp_redirect( $url );
			}
		}
	}

	/** Check Input Exists */
	private function checkInput( $input, $input_exists = false ) {
		/** Get Parameters */
		$params = $_POST;

		/** Check Input Exists */
		foreach ( $input as $key => $value ) {
			if ( isset( $params[ $key ] ) ) {
				$input_exists = true;
				break; }
		}

		return $input_exists;
	}

}

