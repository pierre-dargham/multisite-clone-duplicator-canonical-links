<?php
/**
 * Plugin Name:         MultiSite Clone Duplicator - Canonical links
 * Plugin URI:          https://github.com/pierre-dargham/multisite-clone-duplicator-canonical-links
 * Description:         Add canonical links to duplicated contents in headers of new sites
 * Author:              Pierre DARGHAM
 * Author URI:          https://github.com/pierre-dargham/
 *
 * Version:             0.1.0
 * Requires at least:   3.5.0
 * Tested up to:        4.2.2
 */

if ( ! function_exists( 'mucd_save_cloned_url' ) ) {
	function mucd_save_cloned_url( $from_site_id, $to_site_id ) {
		update_blog_option( $to_site_id, 'mucd_duplicated_siteurl', get_blog_option( $from_site_id, 'siteurl' ) );
	}
	add_action( 'mucd_after_copy_data', 'mucd_save_cloned_url', 10, 2 );
}

if ( ! function_exists( 'mucd_rel_canonical' ) ) {
	function mucd_rel_canonical() {

		$from_site_url = apply_filters( 'mucd_from_site_url', get_option( 'mucd_duplicated_siteurl', '' ) );

		if ( empty($from_site_url) ) {
			return;
		}

		$current_site_url = apply_filters( 'mucd_current_site_url', get_option( 'siteurl' ) );

		$current_url = apply_filters( 'mucd_get_current_url', mucd_get_current_url() );

		remove_action('wp_head', 'rel_canonical');

		$link = str_replace ( $current_site_url, $from_site_url, $current_url );

		$link = mucd_get_resolved_url( $link );

		$link = apply_filters( 'mucd_canonical_link', $link, $current_site_url, $from_site_url, $current_url );

		echo "<link rel='canonical' href='$link' />\n";

	}
	add_action('wp_head', 'mucd_rel_canonical');
}

if ( ! function_exists( 'mucd_get_current_url' ) ) {
	function mucd_get_current_url() {
		global $wp;
		return add_query_arg( $_SERVER['QUERY_STRING'], '', home_url( $wp->request ) );
	}
}

if ( ! function_exists( 'mucd_get_resolved_url' ) ) {
	function mucd_get_resolved_url( $url ) {
		$ch = curl_init( $url );
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_Setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		$response = curl_exec($ch);

		$info = curl_getinfo($ch);
		return $info['url'];
	}
}
