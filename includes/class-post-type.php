<?php
/**
 * Custom post type registration for sales assets
 */

if (! defined('ABSPATH')) {
    exit;
}

class RS_Sales_Post_Type
{

    public static function register()
    {
        self::register_post_type();
        self::register_taxonomy();
        self::register_sales_page_post_type();
    }

    private static function register_post_type()
    {
        $labels = [
            'name'               => 'Sales Assets',
            'singular_name'      => 'Sales Asset',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Asset',
            'edit_item'          => 'Edit Asset',
            'new_item'           => 'New Asset',
            'view_item'          => 'View Asset',
            'search_items'       => 'Search Assets',
            'not_found'          => 'No assets found',
            'not_found_in_trash' => 'No assets found in trash',
            'menu_name'          => 'Sales Assets',
        ];

        $args = [
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 20,
            'menu_icon'          => 'dashicons-media-document',
            'supports'           => ['title', 'thumbnail'],
            'show_in_rest'       => false,
        ];

        register_post_type('rs_sales_asset', $args);
    }

    private static function register_taxonomy()
    {
        $labels = [
            'name'          => 'Asset Categories',
            'singular_name' => 'Asset Category',
            'search_items'  => 'Search Categories',
            'all_items'     => 'All Categories',
            'edit_item'     => 'Edit Category',
            'update_item'   => 'Update Category',
            'add_new_item'  => 'Add New Category',
            'new_item_name' => 'New Category Name',
            'menu_name'     => 'Categories',
        ];

        $args = [
            'labels'            => $labels,
            'hierarchical'      => true,
            'public'            => false,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_rest'      => false,
        ];

        register_taxonomy('rs_asset_category', 'rs_sales_asset', $args);
    }

    private static function register_sales_page_post_type()
    {
        $labels = [
            'name'               => 'Sales Pages',
            'singular_name'      => 'Sales Page',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Sales Page',
            'edit_item'          => 'Edit Sales Page',
            'new_item'           => 'New Sales Page',
            'view_item'          => 'View Sales Page',
            'search_items'       => 'Search Sales Pages',
            'not_found'          => 'No sales pages found',
            'not_found_in_trash' => 'No sales pages found in trash',
            'menu_name'          => 'Sales Pages',
        ];

        $args = [
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => 'edit.php?post_type=rs_sales_asset',
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'supports'           => ['title'],
            'show_in_rest'       => false,
        ];

        register_post_type('rs_sales_page', $args);
    }
}
