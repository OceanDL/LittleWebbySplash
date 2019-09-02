<?php
namespace Raven\Modules\Nav_Menu;

defined( 'ABSPATH' ) || die();

use Raven\Base\Module_base;

class Module extends Module_Base {

	public function get_widgets() {
		return [ 'nav-menu' ];
	}
}
