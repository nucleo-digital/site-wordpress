<?php

require_once get_stylesheet_directory().'/utilidades/ApiRede.php';

require_once get_stylesheet_directory().'/utilidades/RsProvider.php';

require_once get_stylesheet_directory().'/includes/regionalization.php';

require_once get_stylesheet_directory().'/includes/payment.php';

require_once get_stylesheet_directory().'/includes/redesign.php';

function wpr_remove_custom_actions() {
    remove_action( 'after_setup_theme', 'pinbin_options_init' );
    /* remove_action( 'admin_init', 'pinbin_options_setup' ); */
    remove_action('admin_menu', 'pinbin_menu_options');
    remove_action('admin_print_styles-appearance_page_pinbin-settings', 'pinbin_options_enqueue_scripts');
    /* remove_action( 'admin_init', 'pinbin_options_settings_init' ); */
    remove_theme_support( 'custom-background' );

    if (!is_admin()) {
		remove_action( 'wp_enqueue_scripts', 'pinbin_scripts' );
	}
}

// post thumbnails
add_theme_support( 'post-thumbnails' );
add_image_size('summary-image', 300, 9999);
add_image_size('detail-image', 675, 9999);

add_action('init','wpr_remove_custom_actions');

add_filter('show_admin_bar', '__return_false');

function frontend_scripts_method() {
	wp_enqueue_style( 'style', get_stylesheet_uri(), array(), '20140526' );
	wp_register_style('rs_rrssb', get_stylesheet_directory_uri() . '/css/rrssb.min.css');
    //	wp_enqueue_style('rs_rrssb');

	wp_enqueue_script( 'require', get_stylesheet_directory_uri() . '/assets/bower_components/requirejs/require.js', array( 'jquery' ), '20130609', true );
	wp_enqueue_script( 'site-main', get_stylesheet_directory_uri() . '/assets/js/source/Site/main.js', array( 'require' ), '20130609', true );

	load_theme_textdomain( 'rede-sustentabilidade', get_template_directory() . '/languages' );
}

add_action( 'wp_enqueue_scripts', 'frontend_scripts_method' ); // wp_enqueue_scripts action hook to link only on the front-end

// Register Custom Post Type
function custom_post_type() {

	$labels = array(
		'name'                => _x( 'Participações', 'Post Type General Name', 'rede-sustentabilidade' ),
		'singular_name'       => _x( 'Participação', 'Post Type Singular Name', 'rede-sustentabilidade' ),
		'menu_name'           => __( 'Participação', 'rede-sustentabilidade' ),
		'parent_item_colon'   => __( 'Participação filha', 'rede-sustentabilidade' ),
		'all_items'           => __( 'Todas Participações', 'rede-sustentabilidade' ),
		'view_item'           => __( 'Ver Participações', 'rede-sustentabilidade' ),
		'add_new_item'        => __( 'Adicionar nova Participação', 'rede-sustentabilidade' ),
		'add_new'             => __( 'Nova Participação', 'rede-sustentabilidade' ),
		'edit_item'           => __( 'Editar Participação', 'rede-sustentabilidade' ),
		'update_item'         => __( 'Atualizar Participação', 'rede-sustentabilidade' ),
		'search_items'        => __( 'Procurar Participações', 'rede-sustentabilidade' ),
		'not_found'           => __( 'Nenhuma Participação encontrada', 'rede-sustentabilidade' ),
		'not_found_in_trash'  => __( 'Nenhuma Participação encontrada no lixo', 'rede-sustentabilidade' )
	);
	$args = array(
		'label'               => __( 'participacao', 'rede-sustentabilidade' ),
		'description'         => __( 'Participação popular sobre os temas ', 'rede-sustentabilidade' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'comments', ),
		// 'taxonomies'          => array( 'category'),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => false,
		'menu_position'       => 5,
		'menu_icon'           => '',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		'rewrite' 			  => array(
			'slug'			=>	'mudando-o-brasil/desafio',
			'with_front'	=>	true
		)
	);
	register_post_type( 'participacao', $args );

}

// Hook into the 'init' action
add_action( 'init', 'custom_post_type', 0 );

// Categorias para Participação
function my_taxonomies_participacao() {
	$labels = array(
		'name'              => _x( 'Categorias', 'taxonomy general name' ),
		'singular_name'     => _x( 'Categoria', 'taxonomy singular name' ),
		'search_items'      => __( 'Procurar categoria' ),
		'all_items'         => __( 'Todas as categorias' ),
		'parent_item'       => __( 'Parent Categoria' ),
		'parent_item_colon' => __( 'Parent Categoria:' ),
		'edit_item'         => __( 'Editar Categoria' ),
		'update_item'       => __( 'Atualizar Categoria' ),
		'add_new_item'      => __( 'Adicionar nova Categoria' ),
		'new_item_name'     => __( 'Nova Categoria' ),
		'menu_name'         => __( 'Categorias' ),
	);
	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
		'query_var' => true,
		'rewrite' => array(
			'slug' => 'mudando-o-brasil/desafios',
			'with_front' => true
        ),
        'capabilities' => array (
            'manage_terms' => 'manage_options', //by default only admin
            'edit_terms' => 'manage_options',
            'delete_terms' => 'manage_options',
            'assign_terms' => 'edit_posts'  // means administrator', 'editor', 'author', 'contributor'
        )
	);
	register_taxonomy( 'participacao_category', 'participacao', $args );

}
add_action( 'init', 'my_taxonomies_participacao', 0 );


// Sugestão da sideTabs

// Register Custom Post Type
function custom_post_type_sugestao() {

	$labels = array(
		'name'                => _x( 'Sugestões', 'Post Type General Name', 'rede-sustentabilidade' ),
		'singular_name'       => _x( 'Sugestão', 'Post Type Singular Name', 'rede-sustentabilidade' ),
		'menu_name'           => __( 'Sugestão', 'rede-sustentabilidade' ),
		'parent_item_colon'   => __( 'Sugestão filha', 'rede-sustentabilidade' ),
		'all_items'           => __( 'Todas Sugestões', 'rede-sustentabilidade' ),
		'view_item'           => __( 'Ver Sugestões', 'rede-sustentabilidade' ),
		'add_new_item'        => __( 'Adicionar nova Sugestão', 'rede-sustentabilidade' ),
		'add_new'             => __( 'Nova Sugestão', 'rede-sustentabilidade' ),
		'edit_item'           => __( 'Editar Sugestão', 'rede-sustentabilidade' ),
		'update_item'         => __( 'Atualizar Sugestão', 'rede-sustentabilidade' ),
		'search_items'        => __( 'Procurar Sugestões', 'rede-sustentabilidade' ),
		'not_found'           => __( 'Nenhuma Sugestão encontrada', 'rede-sustentabilidade' ),
		'not_found_in_trash'  => __( 'Nenhuma Sugestão encontrada no lixo', 'rede-sustentabilidade' ),
	);
	$args = array(
		'label'               => __( 'sugestao', 'rede-sustentabilidade' ),
		'description'         => __( 'Sugestão popular sobre os temas ', 'rede-sustentabilidade' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'comments', ),
		//'taxonomies'          => array( 'category' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => false,
		'menu_position'       => 5,
		'menu_icon'           => '',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		//'rewrite' => array( 'slug' => 'participe')
	);
	register_post_type( 'sugestao', $args );

}

// Hook into the 'init' action
add_action( 'init', 'custom_post_type_sugestao', 0 );

if (isset($_COOKIE['usuario'])) {
	$usuario = json_decode(stripslashes($_COOKIE['usuario']));
}

////////////////////
function new_wp_login_url() {
    return home_url();
}
add_filter('login_headerurl', 'new_wp_login_url');

/**
 * Sort by custom fields.
 * mt1 refers to meta_1, mt2 to meta_2 and mt3 to meta_3
 *
 * @param $orderby original order by string
 * @return custom order by string
 */
function double_meta_posts_orderby($orderby) {
  global $wpdb;
  return " {$wpdb->postmeta}.meta_value+0 DESC";
}

function remove_category_consulta( $wp_query ) {

    // Add the category to an array of excluded categories. In this case, though,
    // it's really just one.
    $excluded = array( '-59' );

    // Note that this is a cleaner way to write: $wp_query->set('category__not_in', $excluded);
    if (is_home()) {
    	set_query_var( 'category__not_in', $excluded );
    }
}
add_action( 'pre_get_posts', 'remove_category_consulta' );

// wp-login inside iframe
remove_action( 'login_init', 'send_frame_options_header' );
remove_action( 'admin_init', 'send_frame_options_header' );

/// comentario duplicado
add_filter( 'wp_die_handler', 'my_wp_die_handler_function', 9 ); //9 means you can unhook the default before it fires

function my_wp_die_handler_function($function) {
    return 'my_skip_dupes_function'; //use our "die" handler instead (where we won't die)
}

//check to make sure we're only filtering out die requests for the "Duplicate" error we care about
function my_skip_dupes_function( $message, $title, $args ) {
    if (strpos( $message, 'Duplicate comment detected' ) === 0 ) { //make sure we only prevent death on the $dupe check
        remove_filter( 'wp_die_handler', '_default_wp_die_handler' ); //don't die
    }
    return; //nothing will happen
}

add_action('wp_logout','go_home');
function go_home(){
    wp_redirect( home_url() );
    exit();
}

function rs_login_logo() {
    echo '
        <style type="text/css">
        body.login div#login h1 {
        display: none !important;
        }
        </style>
    ';
}

add_action('login_enqueue_scripts', 'rs_login_logo');

function rs_login_redirect($redirect_to, $request, $user) {
    if (!empty($user->roles) && is_array($user->roles)
            && count(array_intersect(array('filiado', 'subscriber'), $user->roles))
            && strpos($redirect_to, 'wp-admin') !== false) {
        return home_url();
    }
    return $redirect_to;
}

add_filter('login_redirect', 'rs_login_redirect', 10, 3);

/*
Plugin Name: Disable Emojis
Plugin URI: https://geek.hellyer.kiwi/plugins/disable-emojis/
Description: Disable Emojis
Version: 1.5.1
Author: Ryan Hellyer
Author URI: https://geek.hellyer.kiwi/
License: GPL2

------------------------------------------------------------------------
Copyright Ryan Hellyer

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*/


/**
 * Disable the emoji's
 */
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param    array  $plugins
 * @return   array             Difference betwen the two arrays
 */
function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}
