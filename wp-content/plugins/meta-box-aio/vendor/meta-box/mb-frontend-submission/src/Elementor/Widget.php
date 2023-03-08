<?php
namespace MBFS\Elementor;

use MBFS\FormFactory;
use MBFS\Dashboard;
use Elementor\Controls_Manager;
use MetaBox\Support\Data;

class Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'frontend_submission';
	}

	public function get_title() {
		return esc_html__( 'Frontend Submission', 'mb-frontend-submission' );
	}

	public function get_icon() {
		return 'eicon-code';
	}

	public function get_categories() {
		return [ 'basic' ];
	}

	public function get_keywords() {
		return [ 'frontend', 'dashboard' ];
	}

	public function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'mb-frontend-submission' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'type'               => Controls_Manager::TEXT,
				'label'              => esc_html__( 'Title', 'mb-frontend-submission' ),
				'placeholder'        => esc_html__( 'Enter the form title', 'mb-frontend-submission' ),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'type',
			[
				'type'    => Controls_Manager::SELECT,
				'label'   => esc_html__( 'Type', 'mb-frontend-submission' ),
				'options' => [
					'create_post'    => esc_html__( 'Submission form', 'mb-frontend-submission' ),
					'user_dashboard' => esc_html__( 'User dashboard', 'mb-frontend-submission' ),
				],
				'default' => 'create_post',
			]
		);

		$post_types = wp_list_pluck( Data::get_post_types(), 'name' );
		$this->add_control(
			'post_type',
			[
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Post type', 'mb-frontend-submission' ),
				'options'   => $post_types,
				'default'   => 'publish',
				'condition' => [
					'type' => 'create_post',
				],
			]
		);

		$this->add_control(
			'post_fields',
			[
				'type'        => Controls_Manager::SELECT2,
				'label'       => esc_html__( 'Post fields', 'mb-frontend-submission' ),
				'multiple'    => true,
				'options'     => [
					'title'     => esc_html__( 'Title', 'mb-frontend-submission' ),
					'content'   => esc_html__( 'Content', 'mb-frontend-submission' ),
					'excerpt'   => esc_html__( 'Excerpt', 'mb-frontend-submission' ),
					'thumbnail' => esc_html__( 'Thumbnail', 'mb-frontend-submission' ),
					'date'      => esc_html__( 'Date', 'mb-frontend-submission' ),
				],
				'description' => esc_html__( 'Choose post fields to show on the form', 'mb-frontend-submission' ),
				'default'     => [ 'title', 'content' ],
				'condition'   => [
					'type' => 'create_post',
				],
			]
		);

		$post_statuses = get_post_statuses();
		$this->add_control(
			'post_status',
			[
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Post status', 'mb-frontend-submission' ),
				'options'   => $post_statuses,
				'default'   => 'publish',
				'condition' => [
					'type' => 'create_post',
				],
			]
		);

		$this->add_control(
			'confirmation',
			[
				'label'       => esc_html__( 'Confirmation message', 'mb-frontend-submission' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => esc_html__( 'Your post has been successfully submitted. Thank you.', 'mb-frontend-submission' ),
				'placeholder' => esc_html__( 'Type your description here', 'mb-frontend-submission' ),
				'description' => esc_html__( 'The text for the confirmation message when the form is successfully submitted.', 'mb-frontend-submission' ),
				'condition'   => [
					'type' => 'create_post',
				],
			]
		);

		$this->add_control(
			'recaptcha',
			[
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Use Google reCaptcha', 'mb-frontend-submission' ),
				'label_on'     => esc_html__( 'True', 'mb-frontend-submission' ),
				'label_off'    => esc_html__( 'False', 'mb-frontend-submission' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'type' => 'create_post',
				],
			]
		);

		$this->add_control(
			'recaptcha_key',
			[
				'type'               => Controls_Manager::TEXT,
				'label'              => esc_html__( 'reCaptcha key', 'mb-frontend-submission' ),
				'frontend_available' => true,
				'condition'          => [
					'type'      => 'create_post',
					'recaptcha' => 'yes',
				],
			]
		);

		$this->add_control(
			'recaptcha_secret',
			[
				'type'               => Controls_Manager::TEXT,
				'label'              => esc_html__( 'reCaptcha secret', 'mb-frontend-submission' ),
				'frontend_available' => true,
				'condition'          => [
					'type'      => 'create_post',
					'recaptcha' => 'yes',
				],
			]
		);

		$this->add_control(
			'group_ids',
			[
				'label'       => esc_html__( 'Field group ID(s)', 'mb-frontend-submission' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'If multiple field groups, enter their IDs separated by commas.', 'mb-frontend-submission' ),
				'condition'   => [
					'type' => 'create_post',
				],
			]
		);

		$this->add_control(
			'ajax',
			[
				'label'        => esc_html__( 'Ajax', 'mb-frontend-submission' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'True', 'mb-frontend-submission' ),
				'label_off'    => esc_html__( 'False', 'mb-frontend-submission' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'type' => 'create_post',
				],
			]
		);

		$this->add_control(
			'redirect',
			[
				'label'     => esc_html__( 'Custom Redirect URL', 'mb-frontend-submission' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => [
					'type' => 'create_post',
				],
			]
		);

		$pages = get_pages();
		if ( isset( $pages ) ) {
			$args = [];
			foreach ( $pages as $item ) {
				$args [ $item->ID ] = $item->post_title;
			}

			$this->add_control(
				'dashboard_edit_page',
				[
					'type'        => Controls_Manager::SELECT,
					'label'       => esc_html__( 'Edit page', 'mb-frontend-submission' ),
					'multiple'    => true,
					'options'     => $args,
					'description' => esc_html__( 'Choose the edit page, where users can edit/submit posts.', 'mb-frontend-submission' ),
					'condition'   => [
						'type' => 'user_dashboard',
					],
				]
			);
		}

		$this->add_control(
			'columns',
			[
				'type'        => Controls_Manager::SELECT2,
				'label'       => esc_html__( 'Columns', 'mb-frontend-submission' ),
				'multiple'    => true,
				'options'     => [
					'title'  => esc_html__( 'Title', 'mb-frontend-submission' ),
					'date'   => esc_html__( 'Date', 'mb-frontend-submission' ),
					'status' => esc_html__( 'Status', 'mb-frontend-submission' ),
				],
				'default'     => [ 'title', 'date', 'status' ],
				'description' => esc_html__( 'List of columns to be displayed in the dashboard, separated by comma.', 'mb-frontend-submission' ),
				'condition'   => [
					'type' => 'user_dashboard',
				],
			]
		);

		$this->add_control(
			'show_welcome_message',
			[
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Show welcome message', 'mb-frontend-submission' ),
				'label_on'     => esc_html__( 'True', 'mb-frontend-submission' ),
				'label_off'    => esc_html__( 'False', 'mb-frontend-submission' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'type' => 'user_dashboard',
				],
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		$atts     = [];
		$settings = $this->get_settings_for_display();

		if ( isset( $settings['title'] ) ) {
			echo '<h3>' . esc_html( $settings['title'] ) . '</h3>';
		}

		if ( $settings['type'] === 'create_post' ) {

			if ( $settings['post_type'] ) {
				$atts['post_type'] = $settings['post_type'];
			}

			if ( $settings['post_fields'] ) {
				$atts['post_fields'] = implode( ',', $settings['post_fields'] );
			}

			if ( $settings['post_status'] ) {
				$atts['post_status'] = $settings['post_status'];
			}

			if ( $settings['confirmation'] ) {
				$atts['confirmation'] = trim( $settings['confirmation'] );
			}

			if ( $settings['recaptcha'] === 'yes' && ! empty( $settings['recaptcha_key'] ) && ! empty( $settings['recaptcha_secret'] ) ) {
				$atts['recaptcha_key']    = $settings['recaptcha_key'];
				$atts['recaptcha_secret'] = $settings['recaptcha_secret'];
			}

			if ( ! empty( $settings['group_ids'] ) ) {
				$atts['id'] = trim( $settings['group_ids'] );
			}

			if ( $settings['ajax'] === 'yes' ) {
				$atts['ajax'] = 'true';
			}

			if ( ! empty( $settings['redirect'] ) ) {
				$atts['redirect'] = trim( $settings['redirect'] );
			}

			$form = FormFactory::make( $atts );
			$form->render();
		}

		if ( $settings['type'] === 'user_dashboard' ) {
			$atts = [];

			if ( $settings['dashboard_edit_page'] ) {
				$atts['edit_page'] = $settings['dashboard_edit_page'];
			}

			if ( $settings['columns'] ) {
				$atts['columns'] = $settings['columns'];
			}

			if ( $settings['show_welcome_message'] === 'yes' ) {
				$atts['show_welcome_message'] = $settings['show_welcome_message'];
			}

			$dashboard = new Dashboard();
			echo $dashboard->shortcode( $atts );
		}
	}
}
