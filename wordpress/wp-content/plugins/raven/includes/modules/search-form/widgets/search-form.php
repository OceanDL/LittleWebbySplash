<?php
namespace Raven\Modules\Search_Form\Widgets;

use Raven\Base\Base_Widget;
use Raven\Modules\Search_Form\Skins;

defined( 'ABSPATH' ) || die();

class Search_Form extends Base_Widget {

	protected $_has_template_content = false;

	protected function _register_skins() {
		$this->add_skin( new Skins\Classic( $this ) );
		$this->add_skin( new Skins\Full( $this ) );
	}

	public function get_name() {
		return 'raven-search-form';
	}

	public function get_title() {
		return __( 'Search Form', 'raven' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-search';
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'raven' ),
			]
		);

		$this->add_control(
			'placeholder',
			[
				'label' => __( 'Placeholder', 'raven' ),
				'type' => 'text',
				'default' => 'Search...',
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => __( 'Choose Icon', 'raven' ),
				'type' => 'icon',
				'default' => 'fa fa-search',
			]
		);

		$this->end_controls_section();

		$this->update_control(
			'_skin',
			[
				'frontend_available' => 'true',
			]
		);
	}

	protected function render() {}
}
