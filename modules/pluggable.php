<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Manages product includes folder
 *
 * Here all plugin includes folder is defined and managed.
 *
 * @version        1.0.0
 * @package        attributes-table/modules
 * @author        Norbert Dreszer
 */
if ( ! function_exists( 'ic_save_global' ) ) {

	/**
	 * Saves implecode global
	 *
	 * @param string $name
	 * @param type $value
	 *
	 * @return boolean
	 * @global array $implecode
	 */
	function ic_save_global( $name, $value ) {
		global $implecode;
		if ( ! empty( $name ) ) {
			$implecode[ $name ] = $value;

			return true;
		}

		return false;
	}

}

if ( ! function_exists( 'ic_delete_global' ) ) {

	/**
	 * Deletes implecode global
	 *
	 * @param type $name
	 *
	 * @return string
	 * @global type $implecode
	 */
	function ic_delete_global( $name = null ) {
		global $implecode;
		if ( ! empty( $name ) ) {
			unset( $implecode[ $name ] );
		} else {
			unset( $implecode );
		}
	}

}

if ( ! function_exists( 'ic_get_global' ) ) {

	/**
	 * Returns implecode global
	 *
	 * @param type $name
	 *
	 * @return string
	 * @global type $implecode
	 */
	function ic_get_global( $name = null ) {
		global $implecode;
		if ( ! empty( $name ) ) {
			if ( isset( $implecode[ $name ] ) ) {
				return $implecode[ $name ];
			} else {
				return false;
			}
		} else {
			return $implecode;
		}
	}

}

if ( ! function_exists( 'ic_get_template_file' ) ) {

	/**
	 * Manages template files paths
	 *
	 * @param type $file_path
	 *
	 * @return type
	 */
	function ic_get_template_file( $file_path, $base_path = AL_BASE_TEMPLATES_PATH ) {
		$folder    = get_custom_templates_folder();
		$file_name = basename( $file_path );
		if ( file_exists( $folder . $file_name ) ) {
			return $folder . $file_name;
		} else if ( file_exists( $base_path . '/templates/template-parts/' . $file_path ) ) {
			return $base_path . '/templates/template-parts/' . $file_path;
		} else {
			return false;
		}
	}

}

if ( ! function_exists( 'ic_show_template_file' ) ) {

	/**
	 * Includes template file
	 *
	 * @param type $file_path
	 *
	 * @return type
	 */
	function ic_show_template_file( $file_path, $base_path = AL_BASE_TEMPLATES_PATH ) {
		$path = ic_get_template_file( $file_path, $base_path );
		if ( $path ) {
			include $path;
		}

		return;
	}

}

if ( ! function_exists( 'get_custom_templates_folder' ) ) {

	/**
	 * Returns custom templates folder in theme directory
	 *
	 * @return type
	 */
	function get_custom_templates_folder() {
		return get_stylesheet_directory() . '/implecode/';
	}

}

if ( ! function_exists( 'is_ic_new_product_screen' ) ) {

	/**
	 * Checks if new entry screen is being displayed
	 *
	 * @return boolean
	 */
	function is_ic_new_product_screen() {
		if ( is_admin() ) {
			$screen = get_current_screen();
			if ( $screen->action == 'add' ) {
				return true;
			}
		}

		return false;
	}

}

if ( ! function_exists( 'product_post_type_array' ) ) {

	function product_post_type_array() {
		$array = apply_filters( 'product_post_type_array', array( 'al_product' ) );

		return $array;
	}

}
if ( ! function_exists( 'get_current_screen_post_type' ) ) {

	function get_current_screen_post_type() {
		$obj       = get_queried_object();
		$post_type = apply_filters( 'current_product_post_type', 'al_product' );
		if ( isset( $obj->post_type ) && strpos( $obj->post_type, 'al_product' ) !== false ) {
			$post_type = $obj->post_type;
		} else if ( isset( $obj->name ) && strpos( $obj->name, 'al_product' ) !== false ) {
			$post_type = $obj->name;
		} else if ( isset( $_GET['post_type'] ) && strpos( $_GET['post_type'], 'al_product' ) !== false ) {
			$post_type = $_GET['post_type'];
		}

		return apply_filters( 'ic_current_post_type', $post_type );
	}

}

if ( ! function_exists( 'is_ic_ajax' ) ) {

	function is_ic_ajax( $action = null ) {
		if ( ! is_admin() ) {
			return false;
		}

		$return = false;
		if ( function_exists( 'wp_doing_ajax' ) ) {
			$doing = wp_doing_ajax();
			if ( $doing ) {
				$return = true;
			}
		} else if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$return = true;
		}
		if ( $return && isset( $_POST['action'] ) && $_POST['action'] === 'heartbeat' ) {
			$return = false;
		}
		if ( $return && ! empty( $action ) ) {
			if ( ! isset( $_POST['action'] ) || ( isset( $_POST['action'] ) && $_POST['action'] !== $action ) ) {
				$return = false;
			}
		}

		return $return;
	}

}

if ( ! function_exists( 'is_ic_admin' ) ) {

	function is_ic_admin() {
		if ( is_admin() && ! is_ic_ajax() ) {
			return true;
		}

		return false;
	}

}
if ( ! function_exists( 'ic_get_terms' ) ) {

	function ic_get_terms( $params = array() ) {
		global $wp_version;
		if ( version_compare( $wp_version, 4.5 ) < 0 ) {
			if ( ! empty( $params['taxonomy'] ) ) {
				$terms = get_terms( $params['taxonomy'], $params );
			}
		} else {
			$terms = get_terms( $params );
		}

		return $terms;
	}

}

if ( ! function_exists( 'ic_string_contains' ) ) {

	function ic_string_contains( $string, $contains, $case_sensitive = true ) {
		if ( ! is_string( $string ) ) {
			return false;
		}
		if ( ! is_string( $contains ) ) {
			if ( is_array( $contains ) ) {
				return false;
			}
			$contains = strval( $contains );
		}
		if ( $case_sensitive && strpos( $string, $contains ) !== false ) {
			return true;
		} else if ( ! $case_sensitive && stripos( $string, $contains ) !== false ) {
			return true;
		}

		return false;
	}

}

if ( ! function_exists( 'ic_is_page' ) ) {

	function ic_is_page( $page_id ) {
		return is_page( $page_id );
	}

}

if ( ! function_exists( 'is_ic_catalog_page' ) ) {

	function is_ic_catalog_page() {
		return false;
	}

}
if ( ! function_exists( 'ic_get_product_id' ) ) {

	function ic_get_product_id() {
		$product_id = ic_get_global( 'product_id' );
		if ( ! $product_id ) {
			do_action( 'ic_catalog_set_product_id' );
			$product_id = get_the_ID();
			if ( function_exists( 'is_ic_product' ) && is_ic_product( $product_id ) ) {
				ic_save_global( 'product_id', $product_id );
			}
		}

		return $product_id;
	}

}
if ( ! function_exists( 'ic_sanitize' ) ) {
	function ic_sanitize( $data, $strict = true ) {
		if ( is_array( $data ) ) {
			return array_map( 'ic_sanitize', $data, array( $strict ) );
		}
		if ( $strict ) {
			return sanitize_text_field( $data );
		} else {
			return addslashes( wp_kses( stripslashes( $data ), 'implecode' ) );
		}
	}
}
if ( ! function_exists( 'ic_visible_product_status' ) ) {

	function ic_visible_product_status( $check_current_user = true ) {
		$visible_status = array( 'publish' );
		if ( $check_current_user && current_user_can( 'read_private_products' ) ) {
			$visible_status[] = 'private';
		}

		return apply_filters( 'ic_visible_product_status', $visible_status, $check_current_user );
	}

}

if ( ! function_exists( 'ic_select_page' ) ) {

	function ic_select_page(
		$option_name, $first_option, $selected_value, $buttons = false, $custom_view_url = false,
		$echo = 1, $custom = false, $custom_content = '', $create_new_button = false
	) {
		if ( ( empty( $selected_value ) || $selected_value === 'noid' ) && ! empty( $create_new_button ) && is_array( $create_new_button ) ) {
			if ( ! empty( $_GET['ic_create_new_page_for_settings'] ) && urldecode( $_GET['ic_create_new_page_for_settings'] ) === $option_name ) {
				$selected_value = ic_create_page_for_settings( $create_new_button['title'], $create_new_button['content'], $create_new_button['option'], $create_new_button['option_sub'] );
			}
			if ( ( empty( $_GET['ic_create_new_page_for_settings'] ) || ( ! empty( $_GET['ic_create_new_page_for_settings'] ) && urldecode( $_GET['ic_create_new_page_for_settings'] ) !== $option_name ) ) && ( empty( $selected_value ) || $selected_value === 'noid' ) ) {
				$custom_content .= ' <a class="button button-small" style="vertical-align: middle;" href="' . esc_url( add_query_arg( 'ic_create_new_page_for_settings', $option_name ) ) . '">' . __( 'Create New', 'ecommerce-product-catalog' ) . '</a>';
			}
		}
		$args       = array(
			'orderby'        => 'title',
			'order'          => 'asc',
			'post_type'      => 'page',
			'post_status'    => array( 'publish', 'private' ),
			'posts_per_page' => - 1,
		);
		$pages      = get_posts( apply_filters( 'ic_settings_select_page_args', $args ) );
		$select_box = '<div class="select-page-wrapper"><select id="' . $option_name . '" name="' . $option_name . '"><option value = "noid">' . $first_option . '</option>';
		foreach ( $pages as $page ) {
			$select_box .= '<option name="' . $option_name . '[' . $page->ID . ']" value="' . $page->ID . '" ' . selected( $page->ID, $selected_value, 0 ) . '>' . $page->post_title . '</option>';
		}
		if ( $custom ) {
			$select_box .= '<option value="custom"' . selected( 'custom', $selected_value, 0 ) . '>' . __( 'Custom URL', 'ecommerce-product-catalog' ) . '</option>';
		}
		$select_box .= '</select>';
		if ( $buttons && ( $selected_value != 'noid' || $custom_view_url != '' ) ) {
			$edit_link  = get_edit_post_link( $selected_value );
			$front_link = $custom_view_url ? $custom_view_url : get_permalink( $selected_value );
			if ( ! empty( $edit_link ) ) {
				$select_box .= ' <a class="button button-small" style="vertical-align: middle;" href="' . $edit_link . '">' . __( 'Edit' ) . '</a>';
			}
			if ( ! empty( $front_link ) ) {
				$select_box .= ' <a class="button button-small" style="vertical-align: middle;" href="' . $front_link . '">' . __( 'View Page' ) . '</a>';
			}
		}
		$select_box .= $custom_content;
		$select_box .= '</div>';
		if ( function_exists( 'ic_register_setting' ) ) {
			ic_register_setting( $first_option, $option_name );
		}

		return echo_ic_setting( $select_box, $echo );
	}

}
if ( ! function_exists( 'main_helper' ) ) {

	function main_helper() {
		$helper = '<div class="doc-helper main"><div class="doc-item">
		<div class="doc-name green-box">' . __( 'Need Help?', 'ecommerce-product-catalog' ) . '</div>
		<div class="doc-description">
			<form role="search" method="get" class="search-form" action="https://implecode.com/docs/">
				<label>
					<span class="screen-reader-text">Search for:</span>
					<input type="hidden" value="al_doc" name="post_type">
					<input type="search" class="search-field" placeholder="Search Docs …" value="" name="s" title="Search for:">
				</label>
				<input type="submit" class="button-primary" value="Search">
			</form>
		</div>
		</div></div>';
		echo $helper;
	}
}
if ( ! function_exists( 'doc_helper' ) ) {

	function doc_helper( $title, $url, $class = null ) {
		$helper = '<div class="doc-helper ' . $class . '"><div class="doc-item">
		<div class="doc-name green-box">' . sprintf(
				__( '%s Settings in Docs', 'ecommerce-product-catalog' ), ic_ucfirst( $title ) ) . '</div>
		<div class="doc-description">' . sprintf(
			          __( 'See %s configuration tips in the impleCode documentation', 'ecommerce-product-catalog' ), $title ) . '.</div>
		<div class="doc-button"><a href="https://implecode.com/docs/ecommerce-product-catalog/' . $url . '/#cam=catalog-docs-box&key=' . $url . '"><input class="doc_button classic-button" type="button" value="' . esc_attr( __( 'See in Docs', 'ecommerce-product-catalog' ) ) . '"></a></div>
		<a title="' . __( 'Click the button to visit impleCode documentation', 'ecommerce-product-catalog' ) . '" href="https://implecode.com/docs/ecommerce-product-catalog/' . $url . '/#cam=catalog-docs-box&key=' . $url . '" class="background-url"></a>
		</div></div>';
		echo $helper;
	}
}
if ( ! function_exists( 'ic_ucfirst' ) ) {

	function ic_ucfirst( $string ) {
		if ( ic_is_multibyte( $string ) ) {
			$firstChar = mb_substr( $string, 0, 1 );
			$then      = mb_substr( $string, 1, null );

			return mb_strtoupper( $firstChar ) . $then;
		} else if ( function_exists( 'ucfirst' ) ) {
			return ucfirst( $string );
		} else {
			$string['0'] = strtoupper( $string['0'] );

			return $string;
		}
	}
}

if ( ! function_exists( 'ic_is_multibyte' ) ) {

	function ic_is_multibyte( $string ) {
		if ( function_exists( 'mb_check_encoding' ) ) {
			return ! mb_check_encoding( $string, 'ASCII' ) && mb_check_encoding( $string, 'UTF-8' );
		}

		return false;
	}
}