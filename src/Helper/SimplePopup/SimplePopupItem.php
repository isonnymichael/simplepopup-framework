<?php


namespace SimplePopup\Helper\SimplePopup;

! defined( 'WPINC ' ) or die;

/**
 * Plugin hooks in a backend
 * setComponent
 *
 * @package    SimplePopup
 * @subpackage SimplePopup/Controller
 */

use SimplePopup\Helper\SimplePopupMetabox\SimplePopupMetaboxDesign;
use SimplePopup\View;

class SimplePopupItem {

	/**
	 * @access   protected
	 * @var      int $ID ID
	 */
	protected $ID;

	/**
	 * @access   protected
	 * @var      string $title Title
	 */
	protected $title;

	/**
	 * @access   protected
	 * @var      array $slug slug
	 */
	protected $slug;

	/**
	 * @access   protected
	 * @var      string $status status
	 */
	protected $status;

	/**
	 * @access protected
	 * @var string $content content
	 */
	protected $content;

	/**
	 * @access protected
	 * @var string $trigger
	 */
	protected $trigger;

	/**
	 * @access protected
	 * @var array $targeting
	 */
	protected $targeting = array();

	/**
	 * @access   protected
	 * @var string  $size
	 */
	protected $size;

	/**
	 * @access   protected
	 * @var string  display
	 */
	protected $display;

	public function __construct( int $ID ) {
		/** Get Plugin Instance */
		$plugin       = \SimplePopup\Plugin::getInstance();

		$this->WP     = $plugin->getWP();
		$this->Helper = $plugin->getHelper();
		$options      = $plugin->getConfig()->options;

		/** Construct Class */
		$this->ID              = $ID;
		$this->title           = get_post_field( 'post_title', $this->ID );
		$this->slug            = get_post_field( 'post_name', $this->ID );
		$this->status          = get_post_field( 'post_status', $this->ID );
		$this->content         = get_post_field('post_content', $this->ID);

		/** Get Meta Post */
		$default = SimplePopupMetaboxDesign::$input['simplepopup_settings']['default'];
		$meta = $this->WP->get_post_meta( $this->ID, SimplePopupMetaboxDesign::$post_metas['simplepopup_settings']['meta_key'], true );
		$meta = ( $meta ) ? $this->Helper->ArrayMergeRecursive( (array) $default, (array) $meta ) : $default;
		$this->trigger = $meta['trigger'];
		$this->targeting = $meta['targeting'];
		$this->size = $meta['size_type'];
		$this->display = $meta['display'];
	}

	/**
	 * @return int
	 */
	public function getID(): int {
		return $this->ID;
	}

	/**
	 * @param int $ID
	 */
	public function setID( int $ID ): void {
		$this->ID = $ID;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle( $title ): void {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param string $status
	 */
	public function setStatus( $status ): void {
		$this->status = $status;
	}

	/**
	 * @return array
	 */
	public function getSlug() {
		return $this->slug;
	}

	/**
	 * @param array $slug
	 */
	public function setSlug( $slug ): void {
		$this->slug = $slug;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param string content
	 */
	public function setContent( $content ): void {
		$this->content = $content;
	}

	/**
	 * @return string
	 */
	public function getTrigger(): string {
		return $this->trigger;
	}

	/**
	 * @param string $trigger
	 */
	public function setTrigger( $trigger ): void {
		$this->trigger = $trigger;
	}

	/**
	 * @return array
	 */
	public function getTargeting(): array {
		return $this->targeting;
	}

	/**
	 * @param array $targeting
	 */
	public function setTargeting( array $targeting ): void {
		$this->targeting = $targeting;
	}

	/**
	 * @return string
	 */
	public function getSize(): string {
		return $this->size;
	}

	/**
	 * @param string $size
	 */
	public function setSize( $size ): void {
		$this->size = $size;
	}

	/** Grab All Assigned Variables */
	public function getVars(): array {
		return get_object_vars( $this );
	}

}
