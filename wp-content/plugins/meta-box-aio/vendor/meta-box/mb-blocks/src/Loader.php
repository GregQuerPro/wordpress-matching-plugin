<?php
namespace MBBlocks;

class Loader {
	public function __construct() {
		add_filter( 'rwmb_meta_box_class_name', array( $this, 'meta_box_class_name' ), 10, 2 );
		add_filter( 'rwmb_meta_type', [ $this, 'change_meta_type' ], 10, 3 );
		add_action( 'init', [ $this, 'register_assets' ] );
	}

	/**
	 * Filter meta box class name.
	 *
	 * @param  string $class_name Meta box class name.
	 * @param  array  $meta_box   Meta box settings.
	 * @return string
	 */
	public function meta_box_class_name( $class_name, $meta_box ) {
		if ( empty( $meta_box['type'] ) || 'block' !== $meta_box['type'] ) {
			return $class_name;
		}
		return empty( $meta_box['storage_type'] ) ? __NAMESPACE__ . '\Block' : __NAMESPACE__ . '\BlockPostMeta';
	}

	/**
	 * Filter meta type from object type and object id.
	 *
	 * @param string $type        Meta type get from object type and object id.
	 * @param string $object_type Object type.
	 * @param string $object_id   Object ID.
	 *
	 * @return string
	 */
	public function change_meta_type( $type, $object_type, $object_id ) {
		return 'block' === $object_type ? $object_id : $type;
	}

	public function register_assets() {
		wp_register_style(
			'mb-blocks',
			MB_BLOCKS_URL . 'assets/blocks.css',
			[],
			MB_BLOCKS_VER
		);
		wp_register_script(
			'mb-blocks',
			MB_BLOCKS_URL . 'assets/blocks.js',
			[ 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-editor', 'wp-data', 'underscore', 'jquery' ],
			MB_BLOCKS_VER,
			true
		);
		wp_add_inline_script( 'mb-blocks', 'window.rwmb = window.rwmb || {}; rwmb.blocks = [];', 'before' );
		wp_localize_script( 'mb-blocks', 'MBBlocks', [
			'nonce' => wp_create_nonce( 'fetch' ),
		] );

		wp_set_script_translations( 'mb-blocks', 'mb-blocks', dirname( __DIR__ ) . '/languages' );
	}
}
