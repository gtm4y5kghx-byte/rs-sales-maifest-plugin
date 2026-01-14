<?php
/**
 * Builds the content manifest response
 */

if (! defined('ABSPATH')) {
    exit;
}

class RS_Sales_Manifest_Builder
{

    public function build()
    {
        return [
            'version'    => $this->get_version(),
            'categories' => $this->get_categories(),
            'items'      => $this->get_items(),
            'totalSize'  => $this->get_total_size(),
        ];
    }

    private function get_version()
    {
        $latest = get_posts([
            'post_type'      => 'rs_sales_asset',
            'posts_per_page' => 1,
            'orderby'        => 'modified',
            'order'          => 'DESC',
            'fields'         => 'ids',
        ]);

        if (empty($latest)) {
            return '0';
        }

        return get_post_modified_time('U', true, $latest[0]);
    }

    private function get_categories()
    {
        $terms = get_terms([
            'taxonomy'   => 'rs_asset_category',
            'hide_empty' => false,
        ]);

        if (is_wp_error($terms)) {
            return [];
        }

        return array_map(function ($term) {
            return [
                'id'   => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
            ];
        }, $terms);
    }

    private function get_items()
    {
        $posts = get_posts([
            'post_type'      => 'rs_sales_asset',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ]);

        $items = array_map([$this, 'format_item'], $posts);

        return array_values(array_filter($items));
    }

    private function format_item($post)
    {
        $attachment_id = get_field('asset_file', $post->ID);

        if (! $attachment_id) {
            return null;
        }

        $file_path = get_attached_file($attachment_id);
        $file_url  = wp_get_attachment_url($attachment_id);
        $file_size = $file_path && file_exists($file_path) ? filesize($file_path) : 0;
        $modified  = get_post_modified_time('U', true, $post->ID);

        // Checksum: MD5 of file path + modified timestamp
        $checksum = md5($file_path . $modified);

        $terms       = wp_get_post_terms($post->ID, 'rs_asset_category', ['fields' => 'ids']);
        $category_id = ! empty($terms) ? $terms[0] : null;

        $file_type = $this->get_file_type($file_path);

        // For images, use the attachment's medium size as thumbnail
        // For other types, use the post's featured image
        if ($file_type === 'image') {
            $thumbnail = wp_get_attachment_image_url($attachment_id, 'medium');
        } else {
            $thumbnail = get_the_post_thumbnail_url($post->ID, 'medium');
        }

        return [
            'id'          => $post->ID,
            'title'       => $post->post_title,
            'description' => $post->post_content,
            'url'         => $file_url,
            'thumbnail'   => $thumbnail ?: null,
            'type'        => $file_type,
            'fileSize'    => $file_size,
            'checksum'    => $checksum,
            'categoryId'  => $category_id,
        ];
    }

    private function get_file_type($file_path)
    {
        if (! $file_path) {
            return 'unknown';
        }

        $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

        $types = [
            'pdf'  => 'pdf',
            'jpg'  => 'image',
            'jpeg' => 'image',
            'png'  => 'image',
            'gif'  => 'image',
            'webp' => 'image',
        ];

        return $types[$ext] ?? 'document';
    }

    private function get_total_size()
    {
        $posts = get_posts([
            'post_type'      => 'rs_sales_asset',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'fields'         => 'ids',
        ]);

        $total = 0;
        foreach ($posts as $post_id) {
            $attachment_id = get_field('asset_file', $post_id);
            if ($attachment_id) {
                $file_path = get_attached_file($attachment_id);
                if ($file_path && file_exists($file_path)) {
                    $total += filesize($file_path);
                }
            }
        }

        return $total;

    }

}
