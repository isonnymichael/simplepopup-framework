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
	 * @access protected
	 * @var string $sesssion
	 */
	protected $session;

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

	/**
	 * @access   protected
	 * @var      bool    $to_be_displayed    to be displayed or not
	 */
	protected $to_be_displayed;

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
		$this->session = $meta['session'];
		$this->size = $meta['size_type'];
		$this->display = $meta['display'];

		/** Extra Function */
		$this->match(); // Auto Match Location.
	}

	/** Match current displayed post by locations setting on simplepopup items
	*
	* @return void
	*/
	public function match() {
		/** Validate */
		if ( ! $this->targeting ) {
			return;
		}

		/** Grab Data */
		global $post;
		$to_be_displayed = false;
		$equal = '==';

		// Match Targeting
		if(isset( $post->ID ) && is_singular() && ($this->targeting['target'] == 'page_id' || $this->targeting['target'] == 'post_id')){ // Matched by ID, single

			$posts = explode(",", $this->targeting['value']);
			$posts = array_filter($posts);

			foreach ($posts as $p ){
				$to_be_displayed = $this->match_operator_and_value(
					$equal,
					$post->ID, // Source Value, Current Post ID.
					intval($p) // Compared Value, Page ID.
				);
			}
		} else if(is_home() && $this->targeting['target'] == 'homepage'){
			$to_be_displayed = true;
		}else if(is_404() && $this->targeting['target'] == '404_error'){
			$to_be_displayed = true;
		} else if(isset( $post->post_type )){ // Matched by post type.
			$to_be_displayed = $this->match_operator_and_value(
				$equal,
				$post->post_type, // Source Value.
				$this->targeting['value'] // Compared Value.
			);
		}

		// Match Session
		if($to_be_displayed){
			if ( $this->session !== 'all' ) { // Matched by User Session
				$to_be_displayed = $this->match_operator_and_value(
					$equal, // Operator always == to check logged in or not.
					is_user_logged_in(), // Source Value, Current User Role.
					($this->session === 'logged_in' ) ? true : false // Compared Value
				);
			}
			// else all it will be default to_be_displayed = true
		}

		$this->setToBeDisplayed($to_be_displayed);
	}

	/** Match locations setting when current displayed content are page
	 *
	 * @param string $operator '==' or '!='
	 * @param mixed    $source_value source location value
	 * @param mixed $compared_value compared value
	 * @return bool
	 */
	public function match_operator_and_value( $operator, $source_value, $compared_value ) {
		/** Match operator equal to */
		if ( '==' === $operator && $source_value === $compared_value ) {
			return true;
		}
		/** Match operator not equal to */
		elseif ( '!=' === $operator && $source_value !== $compared_value ) {
			return true;
		}

		return false;
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
	public function getSession(): string {
		return $this->session;
	}

	/**
	 * @param string $session
	 */
	public function setSession( $session ): void {
		$this->session = $session;
	}

	/**
	 * @return string
	 */
	public function getDisplay(): string {
		return $this->display;
	}

	/**
	 * @param string $display
	 */
	public function setDisplay( $display ): void {
		$this->$display = $display;
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

	/**
	 * @return bool
	 */
	public function isToBeDisplayed(): bool {
		return $this->to_be_displayed;
	}

	/**
	 * @param bool $to_be_displayed
	 */
	public function setToBeDisplayed( bool $to_be_displayed ): void {
		$this->to_be_displayed = $to_be_displayed;
	}

	/** Grab All Assigned Variables */
	public function getVars(): array {
		return get_object_vars( $this );
	}

}
