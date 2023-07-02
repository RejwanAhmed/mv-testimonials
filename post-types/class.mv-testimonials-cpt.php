<?php

if ( ! class_exists( 'MV_Testimonials_Post_Type' ) ) {
	class MV_Testimonials_Post_Type {
		public function __construct() {
			add_action( 'init', array( $this, 'create_post_type' ) );

			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

			add_action( 'save_post', array( $this, 'save_post' ) );
		}

		public function create_post_type() {
			register_post_type(
				'mv-testimonials',
				array(
					'label' => 'Testimonial',
					'desciption' => 'Testimonials',
					'labels' => array(
						'name' => 'Testimonials',
						'singular_name' => 'Testimonial'
					),
					'public' => true,
					'supports' => array( 'title', 'editor', 'thumbnail' ),
					'hierarchical' => false,
					'show_ui' => true,
					'show_in_menu' => true, // true for showing in admin sidebar
					'menu_position' => 5,
					'show_in_admin_bar' => true,
					'show_in_nav_menu' => true,
					'can_export' => true,
					'has_archive' => true,
					'exclude_from_search' => false,
					'publicly_queryable' => true,
					'show_in_rest' => true,
					'menu-icon' => 'dashicons-testimonial'
				)
			);
		}

		public function add_meta_boxes() {
			add_meta_box(
				// screen = where this meta box will show, we have typed our custom post type mv-testimonial so that it shows into our custom post type

				'mv_testimonials_meta_box',
				'Testimonials Options',
				array( $this, 'add_inner_meta_boxes' ),
				'mv-testimonials',
				'normal',
				'high'
			);
		}

		public function add_inner_meta_boxes( $post ) {
			// here we will write the html code that will appear in our custom post type as a metabox

			require_once( MV_TESTIMONIALS_PATH . 'views/mv-testimonials_metabox.php' );
		}

		public function save_post( $post_id ) {
			// start: validation
			if ( isset( $_POST['mv_testimonials_nonce'] ) ) {
				if ( ! wp_verify_nonce( $_POST['mv_testimonials_nonce'], 'mv_testimonials_nonce' ) ) {
					return ;
				}
			}

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return ;
			}

			if ( isset($_POST['post_type']) && $_POST['post_type'] === 'mv-testimonials' ) {
				if ( !current_user_can( 'edit_page', $post_id ) ) {
					return ;
				} else if ( !current_user_can('edit_post', $post_id) ) {
					return ;
				}
			}
			// End Validation

			if ( isset( $_POST['action'] ) && $_POST['action'] == 'editpost' ) {
				$old_occupation = get_post_meta( $post_id, 'mv_testimonials_occupation', true );
				$new_occupation = $_POST['mv_testimonials_occupation'];
				$old_company = get_post_meta( $post_id, 'mv_testimonials_company', true );
				$new_company= $_POST['mv_testimonials_company'];
				$old_user_url = get_post_meta( $post_id, 'mv_testimonials_user_url', true );
				$new_user_url = $_POST['mv_testimonials_user_url'];

				update_post_meta( $post_id, 'mv_testimonials_occupation', sanitize_text_field( $new_occupation ), $old_occupation );
				update_post_meta( $post_id, 'mv_testimonials_company', sanitize_text_field( $new_company ), $old_company );
				update_post_meta( $post_id, 'mv_testimonials_user_url', esc_url_raw( $new_user_url ), $old_user_url );
			}
		}
	}
}