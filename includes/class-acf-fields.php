<?php
/**
 * ACF field group registration for app settings and sales pages
 *
 * Requires ACF Pro for repeater, post object, and options page features.
 */

if (! defined('ABSPATH')) {
	exit;
}

class RS_Sales_ACF_Fields
{

	public static function register()
	{
		add_action('acf/init', [__CLASS__, 'register_options_page']);
		add_action('acf/init', [__CLASS__, 'register_field_groups']);
	}

	public static function register_options_page()
	{
		if (! function_exists('acf_add_options_sub_page')) {
			return;
		}

		acf_add_options_sub_page([
			'page_title'  => 'App Settings',
			'menu_title'  => 'App Settings',
			'parent_slug' => 'edit.php?post_type=rs_sales_asset',
			'capability'  => 'manage_options',
			'menu_slug'   => 'rs-app-settings',
		]);
	}

	public static function register_field_groups()
	{
		if (! function_exists('acf_add_local_field_group')) {
			return;
		}

		self::register_app_settings_fields();
		self::register_sales_page_fields();
	}

	/**
	 * Options page fields: hero banner, FAQs, footer
	 */
	private static function register_app_settings_fields()
	{
		acf_add_local_field_group([
			'key'      => 'group_rs_app_settings',
			'title'    => 'App Settings',
			'fields'   => [
				// Hero Section
				[
					'key'   => 'field_rs_hero_tab',
					'label' => 'Hero Banner',
					'type'  => 'tab',
				],
				[
					'key'          => 'field_rs_hero_title',
					'label'        => 'Hero Title',
					'name'         => 'app_hero_title',
					'type'         => 'text',
					'required'     => 1,
					'placeholder'  => 'Main Sales Deck',
				],
				[
					'key'          => 'field_rs_hero_description',
					'label'        => 'Hero Description',
					'name'         => 'app_hero_description',
					'type'         => 'textarea',
					'rows'         => 3,
				],
				[
					'key'           => 'field_rs_hero_image',
					'label'         => 'Hero Image',
					'name'          => 'app_hero_image',
					'type'          => 'image',
					'return_format' => 'id',
					'preview_size'  => 'medium',
				],
				[
					'key'           => 'field_rs_hero_link_text',
					'label'         => 'Button Text',
					'name'          => 'app_hero_link_text',
					'type'          => 'text',
					'default_value' => 'View Resource',
				],
				[
					'key'           => 'field_rs_hero_link_page',
					'label'         => 'Link to Sales Page',
					'name'          => 'app_hero_link_page',
					'type'          => 'post_object',
					'post_type'     => ['rs_sales_page'],
					'return_format' => 'object',
					'allow_null'    => 1,
				],

				// FAQ Section
				[
					'key'   => 'field_rs_faq_tab',
					'label' => 'FAQs',
					'type'  => 'tab',
				],
				[
					'key'          => 'field_rs_faqs',
					'label'        => 'Frequently Asked Questions',
					'name'         => 'app_faqs',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Add FAQ',
					'sub_fields'   => [
						[
							'key'      => 'field_rs_faq_question',
							'label'    => 'Question',
							'name'     => 'question',
							'type'     => 'text',
							'required' => 1,
						],
						[
							'key'      => 'field_rs_faq_answer',
							'label'    => 'Answer',
							'name'     => 'answer',
							'type'     => 'textarea',
							'rows'     => 4,
							'required' => 1,
						],
					],
				],

				// Footer Section
				[
					'key'   => 'field_rs_footer_tab',
					'label' => 'Footer',
					'type'  => 'tab',
				],
				[
					'key'         => 'field_rs_footer_tagline',
					'label'       => 'Footer Tagline',
					'name'        => 'app_footer_tagline',
					'type'        => 'text',
					'placeholder' => 'Optional tagline text',
				],
			],
			'location' => [
				[
					[
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'rs-app-settings',
					],
				],
			],
		]);
	}

	/**
	 * Sales page fields: hero, applications, video, features, case studies
	 */
	private static function register_sales_page_fields()
	{
		acf_add_local_field_group([
			'key'      => 'group_rs_sales_page',
			'title'    => 'Sales Page Content',
			'fields'   => [
				// Page Hero
				[
					'key'   => 'field_rs_page_hero_tab',
					'label' => 'Page Hero',
					'type'  => 'tab',
				],
				[
					'key'      => 'field_rs_page_hero_title',
					'label'    => 'Hero Title',
					'name'     => 'page_hero_title',
					'type'     => 'text',
					'required' => 1,
				],
				[
					'key'   => 'field_rs_page_hero_description',
					'label' => 'Hero Description',
					'name'  => 'page_hero_description',
					'type'  => 'textarea',
					'rows'  => 3,
				],
				[
					'key'           => 'field_rs_page_hero_image',
					'label'         => 'Hero Image',
					'name'          => 'page_hero_image',
					'type'          => 'image',
					'return_format' => 'id',
					'preview_size'  => 'medium',
				],

				// Applications
				[
					'key'   => 'field_rs_page_apps_tab',
					'label' => 'Applications',
					'type'  => 'tab',
				],
				[
					'key'          => 'field_rs_page_applications',
					'label'        => 'Applications',
					'name'         => 'page_applications',
					'type'         => 'repeater',
					'max'          => 3,
					'layout'       => 'block',
					'button_label' => 'Add Application',
					'sub_fields'   => [
						[
							'key'      => 'field_rs_page_app_title',
							'label'    => 'Title',
							'name'     => 'title',
							'type'     => 'text',
							'required' => 1,
						],
						[
							'key'   => 'field_rs_page_app_description',
							'label' => 'Description',
							'name'  => 'description',
							'type'  => 'textarea',
							'rows'  => 3,
						],
						[
							'key'           => 'field_rs_page_app_image',
							'label'         => 'Image',
							'name'          => 'image',
							'type'          => 'image',
							'return_format' => 'id',
							'preview_size'  => 'medium',
						],
					],
				],

				// Video
				[
					'key'   => 'field_rs_page_video_tab',
					'label' => 'Video',
					'type'  => 'tab',
				],
				[
					'key'   => 'field_rs_page_video_title',
					'label' => 'Video Title',
					'name'  => 'page_video_title',
					'type'  => 'text',
				],
				[
					'key'           => 'field_rs_page_video_file',
					'label'         => 'Video File',
					'name'          => 'page_video_file',
					'type'          => 'file',
					'return_format' => 'id',
					'mime_types'    => 'mp4,webm,mov',
				],
				[
					'key'           => 'field_rs_page_video_poster',
					'label'         => 'Video Poster Image',
					'name'          => 'page_video_poster',
					'type'          => 'image',
					'return_format' => 'id',
					'preview_size'  => 'medium',
				],
				[
					'key'   => 'field_rs_page_video_description',
					'label' => 'Video Description',
					'name'  => 'page_video_description',
					'type'  => 'textarea',
					'rows'  => 2,
				],

				// Features
				[
					'key'   => 'field_rs_page_features_tab',
					'label' => 'Features',
					'type'  => 'tab',
				],
				[
					'key'          => 'field_rs_page_features',
					'label'        => 'Features',
					'name'         => 'page_features',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Add Feature',
					'sub_fields'   => [
						[
							'key'      => 'field_rs_page_feature_title',
							'label'    => 'Title',
							'name'     => 'title',
							'type'     => 'text',
							'required' => 1,
						],
						[
							'key'   => 'field_rs_page_feature_description',
							'label' => 'Description',
							'name'  => 'description',
							'type'  => 'textarea',
							'rows'  => 4,
						],
						[
							'key'           => 'field_rs_page_feature_image',
							'label'         => 'Image',
							'name'          => 'image',
							'type'          => 'image',
							'return_format' => 'id',
							'preview_size'  => 'medium',
						],
						[
							'key'         => 'field_rs_page_feature_pole_length',
							'label'       => 'Pole Length',
							'name'        => 'pole_length',
							'type'        => 'text',
							'placeholder' => '20-100 Ft. [6.1-30.5 M]',
						],
						[
							'key'         => 'field_rs_page_feature_pole_strength',
							'label'       => 'Pole Strength',
							'name'        => 'pole_strength',
							'type'        => 'text',
							'placeholder' => 'Class 6-H6',
						],
						[
							'key'         => 'field_rs_page_feature_voltage_level',
							'label'       => 'Voltage Level',
							'name'        => 'voltage_level',
							'type'        => 'text',
							'placeholder' => '1kv < 69kv',
						],
						[
							'key'         => 'field_rs_page_feature_applications',
							'label'       => 'Applications',
							'name'        => 'applications',
							'type'        => 'text',
							'placeholder' => 'Cellular, Broadband, Radio',
						],
					],
				],

				// Case Studies
				[
					'key'   => 'field_rs_page_cs_tab',
					'label' => 'Case Studies',
					'type'  => 'tab',
				],
				[
					'key'          => 'field_rs_page_case_studies',
					'label'        => 'Case Studies',
					'name'         => 'page_case_studies',
					'type'         => 'repeater',
					'max'          => 3,
					'layout'       => 'block',
					'button_label' => 'Add Case Study',
					'sub_fields'   => [
						[
							'key'           => 'field_rs_page_cs_asset',
							'label'         => 'Asset',
							'name'          => 'asset',
							'type'          => 'post_object',
							'post_type'     => ['rs_sales_asset'],
							'return_format' => 'object',
							'required'      => 1,
						],
						[
							'key'   => 'field_rs_page_cs_summary',
							'label' => 'Summary',
							'name'  => 'summary',
							'type'  => 'textarea',
							'rows'  => 2,
						],
					],
				],
			],
			'location' => [
				[
					[
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'rs_sales_page',
					],
				],
			],
		]);
	}
}
