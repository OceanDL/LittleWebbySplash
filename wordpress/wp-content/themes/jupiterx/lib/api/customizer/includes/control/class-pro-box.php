<?php
/**
 * PRO Box UI control.
 *
 * @package JupiterX\Framework\API\Customizer
 *
 * @since 1.3.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PRO Box control class.
 *
 * @since 1.3.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Control_PRO_Box extends JupiterX_Customizer_Base_Control {

	/**
	 * Control's type.
	 *
	 * @since 1.3.0
	 *
	 * @var string
	 */
	public $type = 'jupiterx-pro-box';

	/**
	 * Control's title.
	 *
	 * @since 1.3.0
	 *
	 * @var string
	 */
	public $title = '';

	/**
	 * Control's description.
	 *
	 * @since 1.3.0
	 *
	 * @var string
	 */
	public $description = '';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.3.0
	 */
	public function to_json() {
		parent::to_json();

		// Title.
		if ( empty( $this->title ) ) {
			$this->title = esc_html__( 'Upgrade to unlock this feature', 'jupiterx' );

			if ( jupiterx_is_premium() ) {
				$this->title = esc_html__( 'Activate Jupiter X', 'jupiterx' );
			}
		}

		$this->json['title'] = $this->title;

		// Description.
		if ( empty( $this->description ) ) {
			$this->description = esc_html__( 'You can unlock more customization options.', 'jupiterx' );

			if ( jupiterx_is_premium() ) {
				$this->description = esc_html__( 'To unlock this feature you must activate Jupiter X', 'jupiterx' );
			}
		}

		$this->json['description'] = $this->description;
	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @since 1.3.0
	 * @SuppressWarnings(PHPMD.ElseExpression)
	 */
	protected function content_template() {
		$btn_label = __( 'Upgrade to Jupiter X Pro', 'jupiterx' );

		if ( jupiterx_is_premium() ) {
			$btn_label = __( 'Activate Now', 'jupiterx' );
		}
		?>
		<div class="jupiterx-control jupiterx-pro-box-control">
			<?php if ( jupiterx_is_premium() ) : ?>
				<img class="jupiterx-control-pro-badge" src="<?php echo esc_url( JUPITERX_ADMIN_ASSETS_URL . 'images/lock-badge-dark.svg' ); ?>">
			<?php else : ?>
				<span class="jupiterx-icon-pro"></span>
			<?php endif; ?>
			<div class="jupiterx-pro-box-control-title">{{ data.title }}</div>
			<div class="jupiterx-pro-box-control-description">{{ data.description }}</div>
			<a class="jupiterx-pro-box-control-button jupiterx-upgrade-modal-trigger" href="#" data-upgrade-link="<?php echo esc_attr( jupiterx_upgrade_link( 'customizer' ) ); ?>">
				<?php echo esc_html( $btn_label ); ?>
			</a>
		</div>
		<?php
	}
}
