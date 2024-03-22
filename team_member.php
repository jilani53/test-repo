<?php

/**
 * Plugin Name: Team Member
 */

class TeamMember {

    function __construct() {
        add_action( 'init', [ $this, 'cpt_team' ] );
        add_action( 'admin_menu', [ $this, 'add_metabox' ] );
        add_action( 'save_post', [ $this, 'save_metabox' ] );
        add_shortcode( 'members', [ $this, 'render_member_shortcode' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_assets' ] );
    }

    public function admin_enqueue_assets( $screen ) {

        /**
         * Regirster admin scripts
         */
        wp_register_script( 'main-script', plugin_dir_url(__FILE__) . 'admin.js', [ 'jquery' ], '', true );
        wp_enqueue_script( 'main-script' );

    }

    /**
	 *
	 * Register team post type
	 *
	 * @since 1.0.0
	 */
	public function cpt_team(){

		/**
		 * Post Type: Teams.
		 *
		 * @since 1.0.0
		 */
		$labels = [
			'name'          						=> esc_html__( 'Team Member', 'team_member' ),
			'all_items'     						=> esc_html__( 'All Teams', 'team_member' ),
			'singular_name' 						=> esc_html__( 'Team', 'team_member' ),
			'add_new' 								=> esc_html__( 'Add New Team', 'team_member' ),
			'add_new_item' 							=> esc_html__( 'Add New Team', 'team_member' ),
		];

		$args = [
			'label' 								=> esc_html__( 'Teams', 'team_member' ),
			'labels' 								=> $labels,
			'description' 							=> '',
			'public' 								=> true,
			'publicly_queryable' 					=> true,
			'show_ui' 								=> true,
			'show_in_rest' 							=> true, // It should true for Gutenberg compatibility
			// 'rest_base' 							=> '',
			// 'rest_controller_class' 				=> 'WP_REST_Posts_Controller',
			'has_archive' 							=> true,
			'show_in_menu' 							=> true,
			'show_in_nav_menus' 					=> true,
			'delete_with_user' 						=> false,
			'exclude_from_search' 					=> false,
			'capability_type' 						=> 'post',
			'map_meta_cap' 							=> true,
			'hierarchical' 							=> false,
			'rewrite' 								=> [
				'slug'			=> 'teams',
				'with_front'	=> true
			],
			'query_var' 							=> true,
			'supports' 								=> [
				'title',
				'editor',
				'thumbnail',
				'excerpt',
				'trackbacks',
				'custom-fields',
				'comments',
				'author'
			],
			'menu_icon'								=> 'dashicons-admin-users'
		];

		register_post_type( 'teams', $args );
	}


    /**
	 * Add meta id
	 *
	 * @since    1.0.0
	 */
	public function add_metabox() {
		add_meta_box(
			'member_meta_id',
			__( 'Member Fields', 'domain-name' ),
			[ $this, 'print_meta_fields' ],
			'teams',
			'normal',
			'high'
		);
	}

	/**
	 * Print meta fields
	 *
	 * @since    1.0.0
	 */
	public function print_meta_fields( $post ) {

		// Get meta by ID
		$get_meta = get_post_meta( $post->ID );

		$member_name = get_post_meta( $post->ID, 'member_name', true );

        $member_photo_id = get_post_meta( $post->ID, 'member_photo_id', true );
		$member_photo_url = get_post_meta( $post->ID, 'member_photo_url', true );


		$member_bio = get_post_meta( $post->ID, 'member_bio', true );
		$member_email = get_post_meta( $post->ID, 'member_email', true );
		$member_phone = get_post_meta( $post->ID, 'member_phone', true );
		$member_birth = get_post_meta( $post->ID, 'member_birth', true );

        ?>

		<!-- Video source -->
		<table class="form-table">

			<tr>
				<th scope="row">
					<label for="member_name"><?php esc_html_e( 'Memeber Name', 'domain-name' ); ?></label>
				</th>
				<td>
					<input type="text" id="member_name" name="member_name" value="<?php if ( $member_name ): echo esc_attr( $member_name ); endif; ?>" class="regular-text" />
				</td>
			</tr>

            <tr>

                <tr>
                    <th scope="row">
                        <label for="member_photo_url"><?php esc_html_e( 'Video Thumbnail','vidiow' ); ?></label>
                    </th>
                    <td>
                        <div id="thumbnail_display"></div>
                        <?php echo $member_photo_url; ?><br>
                        <input type="hidden" name="member_photo_id" id="member_photo_id" value="<?php if ( $member_photo_id ): echo esc_attr( $member_photo_id );  endif; ?>"/>
                        <input type="hidden" name="member_photo_url" id="member_photo_url" value="<?php if ( $member_photo_url ): echo esc_attr( $member_photo_url ); endif; ?>" class="regular-text" />
                        <button class="button-primary" id="upload_thumbnail"><?php esc_html_e( 'Upload Member Image','vidiow' ); ?></button>
                    </td>
                </tr>


			</tr>

            <tr>
				<th scope="row">
					<label for="member_bio"><?php esc_html_e( 'Memeber Bio', 'domain-name' ); ?></label>
				</th>
				<td>
                    <textarea name="member_bio" id="member_bio" cols="10" rows="3" class="regular-text"><?php if ( $member_bio ): echo wp_kses_post( $member_bio ); endif; ?></textarea>
                </td>
			</tr>

            <tr>
				<th scope="row">
					<label for="member_email"><?php esc_html_e( 'Memeber Email', 'domain-name' ); ?></label>
				</th>
				<td>
					<input type="email" id="member_email" name="member_email" value="<?php if ( $member_email ): echo esc_attr( $member_email ); endif; ?>" class="regular-text" />
				</td>
			</tr>

            <tr>
				<th scope="row">
					<label for="member_phone"><?php esc_html_e( 'Memeber Phone', 'domain-name' ); ?></label>
				</th>
				<td>
					<input type="number" id="member_phone" name="member_phone" value="<?php if ( $member_phone ): echo esc_attr( $member_phone ); endif; ?>" class="regular-text" />
				</td>
			</tr>

            <tr>
				<th scope="row">
					<label for="member_birth"><?php esc_html_e( 'Memeber Date of Birth', 'domain-name' ); ?></label>
				</th>
				<td>
					<input type="date" id="member_birth" name="member_birth" value="<?php if ( $member_birth ): echo esc_attr( $member_birth ); endif; ?>" class="regular-text" />
				</td>
			</tr>

		</table>

	<?php
	}

	/**
	 * Update meta fields
	 *
	 * @since    1.0.0
	 */
	public function save_metabox( $post_id ) {

		update_post_meta( $post_id, 'member_name', sanitize_text_field( $_POST['member_name'] ));


		update_post_meta( $post_id, 'member_photo', sanitize_text_field( $_POST['member_photo'] ));

        update_post_meta( $post_id, 'member_photo_id', sanitize_text_field( $_POST['member_photo_id'] ));
		update_post_meta( $post_id, 'member_photo_url', sanitize_text_field( $_POST['member_photo_url'] ));


		update_post_meta( $post_id, 'member_bio', sanitize_text_field( $_POST['member_bio'] ));
		update_post_meta( $post_id, 'member_email', sanitize_text_field( $_POST['member_email'] ));
		update_post_meta( $post_id, 'member_phone', sanitize_text_field( $_POST['member_phone'] ));
		update_post_meta( $post_id, 'member_birth', sanitize_text_field( $_POST['member_birth'] ));

	}


    /**
     * Render member
     */
    public function render_member_shortcode( $atts, $content = '' ) {

        extract(
            shortcode_atts( [
                'per-page' => 5
            ],
            $atts, 'members' )
        );

        ob_start();

            $args = [
                'post_type' => 'teams',
                'posts_per_page' => -1,
                'post_status' => [ 'publish' ]
            ];

            $wp_query = new WP_Query( $args );

            if ( $wp_query->have_posts() ) { ?>

                <table>

                <?php

                while ( $wp_query->have_posts() ) :
                    $wp_query->the_post(); ?>

                    <tr>
                        <?php echo get_post_meta( get_the_ID(), 'member_name', true ); ?>
                        <img style="max-width: 100px;" src="<?php echo esc_url( get_post_meta( get_the_ID(), 'member_photo_url', true ) ); ?>" alt="" srcset="">
                    </tr><br>

                <?php endwhile; ?>
                <!-- End the loop & table -->

                </table>

                <?php
                // Restore original post data
                wp_reset_postdata();

            } else {
                esc_html_e( 'There has no member found.', 'text-domain'  );
            }

            ?>

        <?php return ob_get_clean();
    }

}

new TeamMember();

