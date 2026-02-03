<?php
/**
 * Builds the app content response for homepage and sales pages
 */

if (! defined('ABSPATH')) {
	exit;
}

class RS_Sales_App_Content_Builder
{

	public function build()
	{
		return [
			'version'  => $this->get_version(),
			'homepage' => $this->get_homepage(),
			'pages'    => $this->get_pages(),
		];
	}

	private function get_version()
	{
		$timestamps = [];

		// Options page doesn't have a modified time, so use a hash of the content
		$hero_title = get_field('app_hero_title', 'option') ?: '';
		$faqs       = get_field('app_faqs', 'option') ?: [];
		$timestamps[] = md5(serialize([$hero_title, $faqs]));

		// Latest modified sales page
		$latest = get_posts([
			'post_type'      => 'rs_sales_page',
			'posts_per_page' => 1,
			'orderby'        => 'modified',
			'order'          => 'DESC',
			'fields'         => 'ids',
		]);

		if (! empty($latest)) {
			$timestamps[] = get_post_modified_time('U', true, $latest[0]);
		}

		return md5(implode('|', $timestamps));
	}

	private function get_homepage()
	{
		return [
			'hero'           => $this->get_hero(),
			'faqs'           => $this->get_faqs(),
			'footerTagline'  => get_field('app_footer_tagline', 'option') ?: '',
		];
	}

	private function get_hero()
	{
		$link_page = get_field('app_hero_link_page', 'option');

		return [
			'title'       => get_field('app_hero_title', 'option') ?: '',
			'description' => get_field('app_hero_description', 'option') ?: '',
			'image'       => $this->get_image_data('app_hero_image', 'option'),
			'linkText'    => get_field('app_hero_link_text', 'option') ?: 'View Resource',
			'linkSlug'    => $link_page ? $link_page->post_name : null,
		];
	}

	private function get_faqs()
	{
		$rows = get_field('app_faqs', 'option');

		if (! $rows) {
			return [];
		}

		return array_values(array_map(function ($row) {
			return [
				'question' => $row['question'] ?? '',
				'answer'   => $row['answer'] ?? '',
			];
		}, $rows));
	}

	private function get_pages()
	{
		$posts = get_posts([
			'post_type'      => 'rs_sales_page',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		]);

		return array_values(array_map([$this, 'format_page'], $posts));
	}

	private function format_page($post)
	{
		$id = $post->ID;

		return [
			'slug'         => $post->post_name,
			'title'        => $post->post_title,
			'hero'         => [
				'title'       => get_field('page_hero_title', $id) ?: '',
				'description' => get_field('page_hero_description', $id) ?: '',
				'image'       => $this->get_image_data('page_hero_image', $id),
			],
			'applications' => $this->get_applications($id),
			'video'        => [
				'title'       => get_field('page_video_title', $id) ?: '',
				'url'         => get_field('page_video_url', $id) ?: '',
				'description' => get_field('page_video_description', $id) ?: '',
			],
			'features'     => $this->get_features($id),
			'caseStudies'  => $this->get_case_studies($id),
		];
	}

	private function get_applications($post_id)
	{
		$rows = get_field('page_applications', $post_id);

		if (! $rows) {
			return [];
		}

		return array_values(array_map(function ($row) {
			return [
				'title'       => $row['title'] ?? '',
				'description' => $row['description'] ?? '',
				'image'       => $this->get_image_data_from_id($row['image'] ?? null),
			];
		}, $rows));
	}

	private function get_features($post_id)
	{
		$rows = get_field('page_features', $post_id);

		if (! $rows) {
			return [];
		}

		return array_values(array_map(function ($row) {
			return [
				'title'       => $row['title'] ?? '',
				'description' => $row['description'] ?? '',
				'image'       => $this->get_image_data_from_id($row['image'] ?? null),
				'specs'       => [
					'poleLength'   => $row['pole_length'] ?? '',
					'poleStrength' => $row['pole_strength'] ?? '',
					'voltageLevel' => $row['voltage_level'] ?? '',
					'applications' => $row['applications'] ?? '',
				],
			];
		}, $rows));
	}

	private function get_case_studies($post_id)
	{
		$rows = get_field('page_case_studies', $post_id);

		if (! $rows) {
			return [];
		}

		$studies = array_map(function ($row) {
			$asset = $row['asset'] ?? null;

			if (! $asset) {
				return null;
			}

			return [
				'assetId'   => $asset->ID,
				'title'     => $asset->post_title,
				'summary'   => $row['summary'] ?? '',
				'thumbnail' => get_the_post_thumbnail_url($asset->ID, 'medium') ?: null,
			];
		}, $rows);

		return array_values(array_filter($studies));
	}

	/**
	 * Get image data from an ACF field that returns an attachment ID
	 */
	private function get_image_data($field_name, $post_id)
	{
		$attachment_id = get_field($field_name, $post_id);
		return $this->get_image_data_from_id($attachment_id);
	}

	/**
	 * Get image data from an attachment ID
	 */
	private function get_image_data_from_id($attachment_id)
	{
		if (! $attachment_id) {
			return null;
		}

		return [
			'url'       => wp_get_attachment_url($attachment_id),
			'thumbnail' => wp_get_attachment_image_url($attachment_id, 'medium'),
			'alt'       => get_post_meta($attachment_id, '_wp_attachment_image_alt', true) ?: '',
		];
	}
}
