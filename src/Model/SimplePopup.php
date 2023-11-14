<?php
/**
 * Initiate plugins
 *
 * @package    SimplePopup
 * @subpackage SimplePopup/Model
 */

namespace SimplePopup\Model;

! defined( 'WPINC ' ) || die;

use SimplePopup\Helper\SimplePopup\SimplePopupItem;
use SimplePopup\Helper\SimplePopupMetabox\SimplePopupMetaboxDesign;
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
			'edit_item' => __( "Edit ".$plugin->getName() ),
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

	}

	/**
	 * Return simplepopups item and simplepopups order
	 *
	 * @param array $args   Arguments.
	 * @return array
	 */
	public function get_lists_of_simplepopup( $args = array() ) {

		/** Grab Data */
		$items = get_posts(
			array(
				'posts_per_page' => -1,
				'post_type'      => $this->getName(),
				'post_status'    => array( 'publish' ),
				'post__not_in'   => empty( $fab_order ) ?
					array( 'empty' ) : $fab_order,
				'orderby'        => 'post_date',
				'order'          => 'DESC',
			)
		);


		$tmp = array();
		foreach ( $items as &$item ) {
			/** Data Validation */
			if ( ! isset( $item->ID ) ) { continue; }

			/** SimplePopup Item */
			$item = new SimplePopupItem( $item->ID ); // Grab FAB Item.

			/** SimplePopup Item Args Validation */
			if ( $item->getStatus() !== 'publish' ) { continue; }

			/** SimplePopup Item Location */
			if ( isset( $args['validateLocation'] ) &&
			     ! empty( $item->getTargeting() ) &&
			     ! $item->isToBeDisplayed()
			) {
				continue; // Check location rules.
			}

			$tmp[] = $item;
		}
		unset( $item );
		$items = $tmp;

		return array(
			'items' => $items,
		);
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
		/** Save Metabox Design */
		if ( $this->checkInput( SimplePopupMetaboxDesign::$post_metas ) ) {
			$metabox = new SimplePopupMetaboxDesign();
			$metabox->sanitize();
			$metabox->setDefaultInput();
			$metabox->save();
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

