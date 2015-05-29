<?php

// 1. ON DUPLICATION ---------------------------------

	// SAVE ORIGINAL SITE ID


// 2. DUPLICATION SEO OPTIONS ---------------------------------

	// HOME PAGE :
		// [X] use original site home_page as canonical
		// [ ] default wordpress canonical

	// FOR EACH POST TYPE PRINT OPTION :
		// [X] use original site permalinks as canonical
		// [ ] default wordpress canonical

	// FOR EACH ARCHIVES TYPES PRINT OPTION :
		// [X] use original site permalinks as canonical
		// [ ] default wordpress canonical

	// FOR EACH TAXONOMY TERM PRINT OPTION :
		// [X] use original site permalinks as canonical
		// [ ] default wordpress canonical

	// FOR EACH SPECIFIC CANONICAL DEFINED BY PLUGINS PRINT OPTION :
		// [X] SAVE seo-yoast / all-in-one-seo canonical
		// [ ] remove this canonical
		// [ ] set new custom canonical : __________________


function get_home_page_canonical();
function get_original_home_page_permalink();

function get_post_canonical();
function get_original_post_permalink();

function get_archive_canonical();
function get_original_archive_permalink();

function get_term_canonical();
function get_original_term_permalink();

function get_default_wordpress_canonical();

function get_home_page_canonical() {

	if( has_explicit_canonical() ) {
		return get_explicit_canonical();
	}

	if( is_home() ) {
		return get_home_page_canonical();
	}

	if( is_singular() ) {
		return get_post_canonical();
	}

	if( is_archive() ) {
		return get_post_type_archive_canonical();
	}

	if( is_tax() || is_category() || is_tag() ) {
		return get_term_canonical();
	}

	return get_default_wordpress_canonical();

}

function get_FOO_canonical() {
	$permalink = get_original_FOO_permalink();

	if ( empty( $permalink ) ) {
		$permalink = get_default_wordpress_canonical();
	}

	return $permalink;
}