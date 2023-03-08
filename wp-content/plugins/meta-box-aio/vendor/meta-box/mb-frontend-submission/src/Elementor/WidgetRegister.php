<?php
namespace MBFS\Elementor;

class WidgetRegister {

	public function __construct() {
		add_action( 'elementor/widgets/register', [ $this, 'register' ] );
	}

	public function register( $widgets_manager ) {
		$widgets_manager->register( new Widget );
	}
}
