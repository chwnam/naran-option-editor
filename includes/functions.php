<?php
/**
 * includes/functions.php
 */

/**
 * Prevent direct access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! function_exists( 'noe' ) ) {
	function noe(): NOE_Container {
		return NOE_Container::get_instance();
	}
}


if ( ! function_exists( 'noe_meta' ) ) {
	function noe_meta(): NOE_Registerer_Meta {
		return NOE_Container::get_instance()->registerer->meta;
	}
}


if ( ! function_exists( 'noe_get_core_option_names' ) ) {
	/**
	 * Get all default core option names.
	 *
	 * @return array
	 * @see populate_options()
	 */
	function noe_get_core_option_names(): array {
		return [
			'siteurl',
			'home',
			'blogname',
			'blogdescription',
			'users_can_register',
			'admin_email',
			'start_of_week',
			'use_balanceTags',
			'use_smilies',
			'require_name_email',
			'comments_notify',
			'posts_per_rss',
			'rss_use_excerpt',
			'mailserver_url',
			'mailserver_login',
			'mailserver_pass',
			'mailserver_port',
			'default_category',
			'default_comment_status',
			'default_ping_status',
			'default_pingback_flag',
			'posts_per_page',
			'date_format',
			'time_format',
			'links_updated_date_format',
			'comment_moderation',
			'moderation_notify',
			'permalink_structure',
			'rewrite_rules',
			'hack_file',
			'blog_charset',
			'moderation_keys',
			'active_plugins',
			'category_base',
			'ping_sites',
			'comment_max_links',
			'gmt_offset',
			'default_email_category',
			'recently_edited',
			'template',
			'stylesheet',
			'comment_registration',
			'html_type',
			'use_trackback',
			'default_role',
			'db_version',
			'uploads_use_yearmonth_folders',
			'upload_path',
			'blog_public',
			'default_link_category',
			'show_on_front',
			'tag_base',
			'show_avatars',
			'avatar_rating',
			'upload_url_path',
			'thumbnail_size_w',
			'thumbnail_size_h',
			'thumbnail_crop',
			'medium_size_w',
			'medium_size_h',
			'avatar_default',
			'large_size_w',
			'large_size_h',
			'image_default_link_type',
			'image_default_size',
			'image_default_align',
			'close_comments_for_old_posts',
			'close_comments_days_old',
			'thread_comments',
			'thread_comments_depth',
			'page_comments',
			'comments_per_page',
			'default_comments_page',
			'comment_order',
			'sticky_posts',
			'widget_categories',
			'widget_text',
			'widget_rss',
			'uninstall_plugins',
			'timezone_string',
			'page_for_posts',
			'page_on_front',
			'default_post_format',
			'link_manager_enabled',
			'finished_splitting_shared_terms',
			'site_icon',
			'medium_large_size_w',
			'medium_large_size_h',
			'wp_page_for_privacy_policy',
			'show_comments_cookies_opt_in',
			'admin_email_lifespan',
			'disallowed_keys',
			'comment_previously_approved',
			'auto_plugin_theme_update_emails',
			'auto_update_core_dev',
			'auto_update_core_minor',
			'auto_update_core_major',
		];
	}
}


if ( ! function_exists( 'noe_get_options_list_url' ) ) {
	function noe_get_options_list_url(): string {
		return add_query_arg( 'page', 'noe', admin_url( 'tools.php' ) );
	}
}


if ( ! function_exists( 'noe_get_option_remove_url' ) ) {
	function noe_get_option_remove_url( int $option_id, string $return_url = '' ): string {
		if ( $option_id < 1 ) {
			return '';
		}

		return add_query_arg(
			[
				'option_id'  => $option_id,
				'_noe_nonce' => wp_create_nonce( 'noe_delete_option_' . $option_id ),
				'action'     => 'noe_delete_option',
				'return_url' => urlencode( $return_url ),
			],
			admin_url( 'admin-post.php' )
		);
	}
}
