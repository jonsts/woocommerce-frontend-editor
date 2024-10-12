<?php
/*
Plugin Name: WooCommerce Frontend Product Editor
Description: Edit main product information from the frontend for simple and variable products.
Version: 1.6
Author: github.com/jonsts
Text Domain: wc-frontend-product-editor
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'wc_fpe_enqueue_scripts' );
function wc_fpe_enqueue_scripts() {
    if ( is_product() && current_user_can( 'edit_products' ) ) {
        wp_enqueue_style( 'wc-fpe-styles', plugin_dir_url( __FILE__ ) . 'css/wc-fpe-styles.css' );
        wp_enqueue_script( 'wc-fpe-scripts', plugin_dir_url( __FILE__ ) . 'js/wc-fpe-scripts.js', array( 'jquery' ), '1.6', true );
        wp_localize_script( 'wc-fpe-scripts', 'wc_fpe_ajax', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
        ) );

        // Enqueue necessary editor scripts
        wp_enqueue_editor();

        // Enqueue polyfill for <dialog> element if needed
        wp_enqueue_script( 'dialog-polyfill', plugin_dir_url( __FILE__ ) . 'js/dialog-polyfill.min.js', array(), '0.5.6', true );
        wp_enqueue_style( 'dialog-polyfill-css', plugin_dir_url( __FILE__ ) . 'css/dialog-polyfill.min.css', array(), '0.5.6' );
    }
}

// Enqueue Dashicons in the frontend
add_action( 'wp_enqueue_scripts', 'wc_fpe_enqueue_dashicons' );
function wc_fpe_enqueue_dashicons() {
    if ( is_product() && current_user_can( 'edit_products' ) ) {
        wp_enqueue_style( 'dashicons' );
    }
}

// Display edit icon next to the product title and include the dialog
add_action( 'woocommerce_single_product_summary', 'wc_fpe_display_edit_icon', 6 );
function wc_fpe_display_edit_icon() {
    if ( ! is_product() || ! current_user_can( 'edit_products' ) ) {
        return;
    }

    global $product;

    // Display the edit icon for simple and variable products
    if ( $product->is_type( 'variable' ) || $product->is_type( 'simple' ) ) {
        $post_id    = $product->get_id();
        $title      = get_the_title( $post_id );
        $short_desc = $product->get_short_description();
        $long_desc  = $product->get_description();

        // Output the edit icon
        echo '<a href="#" id="wc-fpe-open-modal" style="margin-left: 10px; font-size: inherit; vertical-align: middle;">
                <span class="dashicons dashicons-edit"></span>
              </a>';

        // Output the dialog HTML
        ?>
        <dialog id="wc-fpe-modal">
            <form id="wc-fpe-form" method="dialog">
                <h3><?php esc_html_e( 'Edit Product Information', 'wc-frontend-product-editor' ); ?></h3>

                <p>
                    <label for="wc-fpe-title"><?php esc_html_e( 'Product Title', 'wc-frontend-product-editor' ); ?></label><br>
                    <input type="text" id="wc-fpe-title" name="wc_fpe_title" value="<?php echo esc_attr( $title ); ?>" />
                </p>

                <p>
                    <label for="wc-fpe-short-desc"><?php esc_html_e( 'Short Description', 'wc-frontend-product-editor' ); ?></label><br>
                    <?php
                    wp_editor( $short_desc, 'wc-fpe-short-desc', array(
                        'textarea_name' => 'wc_fpe_short_desc',
                        'textarea_rows' => 10,
                        'editor_height' => 200,
                        'media_buttons' => false,
                        'tinymce'       => array(
                            'toolbar1' => 'bold italic underline | bullist numlist | link unlink | undo redo',
                            'toolbar2' => '',
                        ),
                        'quicktags'     => false,
                    ) );
                    ?>
                </p>

                <p>
                    <label for="wc-fpe-long-desc"><?php esc_html_e( 'Description', 'wc-frontend-product-editor' ); ?></label><br>
                    <?php
                    wp_editor( $long_desc, 'wc-fpe-long-desc', array(
                        'textarea_name' => 'wc_fpe_long_desc',
                        'textarea_rows' => 15,
                        'editor_height' => 300,
                        'media_buttons' => false,
                        'tinymce'       => array(
                            'toolbar1' => 'bold italic underline | bullist numlist | link unlink | undo redo',
                            'toolbar2' => '',
                        ),
                        'quicktags'     => false,
                    ) );
                    ?>
                </p>

                <input type="hidden" name="wc_fpe_post_id" value="<?php echo esc_attr( $post_id ); ?>" />
                <?php wp_nonce_field( 'wc_fpe_save_product', 'wc_fpe_nonce' ); ?>

                <button type="submit" id="wc-fpe-submit"><?php esc_html_e( 'Save Changes', 'wc-frontend-product-editor' ); ?></button>
                <button type="button" id="wc-fpe-close"><?php esc_html_e( 'Close', 'wc-frontend-product-editor' ); ?></button>
                <span id="wc-fpe-spinner" style="display: none;"><img src="<?php echo esc_url( includes_url( 'images/spinner.gif' ) ); ?>" alt="<?php esc_attr_e( 'Loading...', 'wc-frontend-product-editor' ); ?>"></span>
                <div id="wc-fpe-message"></div>
            </form>
        </dialog>
        <?php
    }
}

// Handle form submission
add_action( 'wp_ajax_wc_fpe_save_product', 'wc_fpe_save_product' );
function wc_fpe_save_product() {
    check_ajax_referer( 'wc_fpe_save_product', 'wc_fpe_nonce' );

    if ( ! current_user_can( 'edit_products' ) ) {
        wp_send_json_error( __( 'You do not have permission to edit products.', 'wc-frontend-product-editor' ) );
    }

    $post_id    = intval( $_POST['wc_fpe_post_id'] );
    $title      = sanitize_text_field( $_POST['wc_fpe_title'] );
    $short_desc = wp_kses_post( $_POST['wc_fpe_short_desc'] );
    $long_desc  = wp_kses_post( $_POST['wc_fpe_long_desc'] );

    $post_data = array(
        'ID'           => $post_id,
        'post_title'   => $title,
        'post_excerpt' => $short_desc,
        'post_content' => $long_desc,
    );

    $updated = wp_update_post( $post_data, true );

    if ( is_wp_error( $updated ) ) {
        wp_send_json_error( $updated->get_error_message() );
    } else {
        wp_send_json_success( __( 'Product updated successfully.', 'wc-frontend-product-editor' ) );
    }
}
