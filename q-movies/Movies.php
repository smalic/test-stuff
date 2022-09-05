<?php

namespace Q;

Class Movies {
    public function __construct() {
        // Let's not use the constructor here. We might want to run some other methods from this object, and to avoid mixing static and non-static methods,
        // let's simply init the features in a separate method.
    }

    public function instantiate(): void {
        add_action( 'init', [ $this, 'post_type' ] );
        add_action( 'init', [ $this, 'register_blocks' ] );

        add_action( 'wp_enqueue_scripts', [ $this, 'assets' ] );
        add_action( 'enqueue_block_assets', [ $this, 'register_block_assets' ] );

        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
        add_action( 'save_post', [ $this, 'save_post' ] );

        add_filter( 'single_template', [ $this, 'set_template' ] );
    }

    public function post_type(): void {
        $labels = array(
            'name'                  => _x( 'Movies', 'Post Type General Name', 'q-agency' ),
            'singular_name'         => _x( 'Movie', 'Post Type Singular Name', 'q-agency' ),
            'menu_name'             => __( 'Movies', 'q-agency' ),
            'name_admin_bar'        => __( 'Movie', 'q-agency' ),
            'archives'              => __( 'Movie Archives', 'q-agency' ),
            'attributes'            => __( 'Movie Attributes', 'q-agency' ),
            'parent_item_colon'     => __( 'Parent Movie:', 'q-agency' ),
            'all_items'             => __( 'All Movies', 'q-agency' ),
            'add_new_item'          => __( 'Add New Movie', 'q-agency' ),
            'add_new'               => __( 'Add New', 'q-agency' ),
            'new_item'              => __( 'New Movie', 'q-agency' ),
            'edit_item'             => __( 'Edit Movie', 'q-agency' ),
            'update_item'           => __( 'Update Movie', 'q-agency' ),
            'view_item'             => __( 'View Movie', 'q-agency' ),
            'view_items'            => __( 'View Movies', 'q-agency' ),
            'search_items'          => __( 'Search Movie', 'q-agency' ),
            'not_found'             => __( 'Not found', 'q-agency' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'q-agency' ),
            'featured_image'        => __( 'Featured Image', 'q-agency' ),
            'set_featured_image'    => __( 'Set featured image', 'q-agency' ),
            'remove_featured_image' => __( 'Remove featured image', 'q-agency' ),
            'use_featured_image'    => __( 'Use as featured image', 'q-agency' ),
            'insert_into_item'      => __( 'Insert into movie', 'q-agency' ),
            'uploaded_to_this_item' => __( 'Uploaded to this movie', 'q-agency' ),
            'items_list'            => __( 'Movie list', 'q-agency' ),
            'items_list_navigation' => __( 'Movie list navigation', 'q-agency' ),
            'filter_items_list'     => __( 'Filter movie list', 'q-agency' ),
        );
        $args = array(
            'label'                 => __( 'Movie', 'q-agency' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'custom-fields' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'page',
            'show_in_rest'          => true,
            'rest_base'             => 'movies',
        );

        register_post_type( 'movies', $args );
    }

    public function add_meta_boxes() {
        add_meta_box(
            'movies',
            'Movies',
            [ $this, 'add_meta_box_callback' ],
            'movies',
        );
    }

    public function save_post( $post_id ) {
        $field_id = 'q-movie-title';

        if ( isset( $_POST[ $field_id ] ) ) {
            update_post_meta( $post_id, $field_id, sanitize_text_field( $_POST[ $field_id ] ) );
        }
    }

    public function add_meta_box_callback() {
        global $post;
        ?><table class="form-table" role="presentation">
        <tbody>
        <tr>
            <th scope="row">Movie title:</th>
            <td>
                <label for="">
                    <input class="regular-text" name="q-movie-title" id="q-movie-title" type="text" value="<?php echo get_post_meta( $post->ID, 'q-movie-title', true ) ?? '' ?>">
                </label>
            </td>
        </tr>
        </tbody>
        </table><?php
    }

    public function set_template( string $template ): string {
        global $post;

        if ( $post->post_type === 'movies' ) {
            $template = Q_MOVIES_PLUGIN . 'templates/single-movies.php';
        }

        return $template;
    }

    public function assets(): void {
        wp_enqueue_style( 'q-agency-movie', Q_MOVIES_PLUGIN_URL . 'assets/style.css', [], '1.0.0', 'all' );
    }

    public function register_block_assets(): void {
        wp_enqueue_script( 'q-agency-movie', Q_MOVIES_PLUGIN_URL . 'assets/script.js', ['wp-editor', 'wp-dom'], '1.0.0' );
    }

    public function register_blocks(): void {
        register_block_type(
            'q-movies/favorite-movie-quote',
            [
                'render_callback' => function( $block_attributes, $content ) {
                    return "<blockquote>{$content}</blockquote>";
                }
            ]
        );
    }
}
