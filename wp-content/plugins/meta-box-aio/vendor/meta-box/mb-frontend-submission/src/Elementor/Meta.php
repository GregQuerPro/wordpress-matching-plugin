<?php
namespace MBFS\Elementor;

class Meta {
	public function get_attributes( int $post_id ) : array {
		$data     = get_post_meta( $post_id, '_elementor_data', true ) ?: '';
		$data     = json_decode( $data, true ) ?: [];
		$settings = $this->get_submit_form_settings( $data );

		return $settings ? $this->format( $settings, $post_id ) : [];
	}

	private function get_submit_form_settings( array $data ) {
		foreach ( $data as $widget ) {
			$type = $widget['widgetType'] ?? '';
			if ( $type === 'frontend_submission' ) {
				return $widget['settings'];
			}
			if ( empty( $widget['elements'] ) ) {
				continue;
			}
			$settings = $this->get_submit_form_settings( $widget['elements'] );
			if ( ! empty( $settings ) ) {
				return $settings;
			}
		}

		return [];
	}

	private function format( array $settings, int $post_id ) : array {
		$attributes              = [
			'url' => get_permalink( $post_id ),
		];
		$attributes['id']        = $settings['group_ids'] ?? '';
		$attributes['post_type'] = $settings['post_type'] ?? 'post';

		return $attributes;
	}
}
