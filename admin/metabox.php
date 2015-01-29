<?php
/**
* Registering Portfolio meta boxes
*
* @package    birds-portfolio
* @subpackage birds-portfolio/admin
* @since      1.0.0
*/

add_action( 'add_meta_boxes', 'birds_add_meta_boxes' );
add_action( 'save_post', 'birds_meta_boxes_save', 10, 2 );

/**
 * Registers meta boxes
 */
function birds_add_meta_boxes() {

    if ( 'birds_portfolio' != get_current_screen()->post_type ) {
        return;
    }

    add_meta_box(
        'birds-metaboxes-portfolio',
        __( 'Project Informations', 'birds-portfolio'),
        'birds_metaboxes_display',
        'birds_portfolio',
        'normal',
        'high'
    );

}

/**
 * Displays the content of the meta boxes.
 */
function birds_metaboxes_display( $post ) {

    wp_nonce_field( basename( __FILE__ ), 'birds-metaboxes-portfolio-nonce' ); ?>

    <div id="birds-block">

        <div class="birds-label">
            <label for="birds-portfolio-short-desc">
                <strong><?php _e( 'Short Description', 'birds-portfolio' ); ?></strong><br />
                <span class="description"><?php _e( 'A short description of the project.', 'birds-portfolio' ); ?></span>
            </label>
        </div>

        <div class="birds-input">
            <textarea name="birds-portfolio-short-desc" id="birds-portfolio-short-desc" cols="30" rows="3" style="width: 99%;"><?php echo esc_html( get_post_meta( $post->ID, 'birds_portfolio_short_desc', true ) ); ?></textarea>
        </div>

    </div><!-- #birds-block -->

    <div id="birds-block">

        <div class="birds-label">
            <label for="birds-portfolio-client">
                <strong><?php _e( 'Client Name', 'birds-portfolio' ); ?></strong><br />
                <span class="description"><?php _e( 'Enter the name of your Client.', 'birds-portfolio' ); ?></span>
            </label>
        </div>

        <div class="birds-input">
            <input type="text" name="birds-portfolio-client" id="birds-portfolio-client" value="<?php echo sanitize_text_field( get_post_meta( $post->ID, 'birds_portfolio_client', true ) ); ?>" size="30" style="width: 99%;" placeholder="<?php esc_attr_e( 'Company Name', 'birds-portfolio' ); ?>" />
        </div>

    </div><!-- #birds-block -->

<div id="birds-block">

    <div class="birds-label">
        <label for="birds-portfolio-testimonial">
            <strong><?php _e( 'Testimonial', 'birds-portfolio' ); ?></strong><br />
            <span class="description"><?php _e( 'Enter a testimonial from your Client to be displayed on the single Project page', 'birds-portfolio' ); ?></span>
        </label>
    </div>

    <div class="birds-input">
        <textarea name="birds-portfolio-testimonial" id="birds-portfolio-testimonial" cols="30" rows="3" style="width: 99%;"><?php echo esc_html( get_post_meta( $post->ID, 'birds_portfolio_testimonial', true ) ); ?></textarea>
    </div>

</div><!-- #birds-block -->

<div id="birds-block">

        <div class="birds-label">
            <label for="birds-portfolio-url">
                <strong><?php _e( 'Project Link', 'birds-portfolio' ); ?></strong><br />
                <span class="description"><?php _e( 'Enter an URL to link this Portfolio Project to your Client Website.', 'birds-portfolio' ); ?></span>
            </label>
        </div>

        <div class="birds-input">
            <input type="text" name="birds-portfolio-url" id="birds-portfolio-url" value="<?php echo esc_url( get_post_meta( $post->ID, 'birds_portfolio_link', true ) ); ?>" size="30" style="width: 99%;" placeholder="<?php echo esc_attr( 'http://' ); ?>" />
        </div>

    </div><!-- #birds-block -->

    <div id="birds-block">

        <div class="birds-label">
            <label for="birds-portfolio-country">
                <strong><?php _e( 'Country', 'birds-portfolio' ); ?></strong><br />
                <span class="description"><?php _e( 'Enter the country where this project has been realised.', 'birds-portfolio' ); ?></span>
            </label>
        </div>

        <div class="birds-input">
            <input type="text" name="birds-portfolio-country" id="birds-portfolio-country" value="<?php echo sanitize_text_field( get_post_meta( $post->ID, 'birds_portfolio_country', true ) ); ?>" size="30" style="width: 99%;" placeholder="<?php echo esc_attr( 'Country' ); ?>" />
        </div>

    </div><!-- #birds-block -->

    <div id="birds-block">

        <div class="birds-label">
            <label for="birds-portfolio-projmanager">
                <strong><?php _e( 'Project Manager', 'birds-portfolio' ); ?></strong><br />
                <span class="description"><?php _e( 'Enter the name of the Project Manager.', 'birds-portfolio' ); ?></span>
            </label>
        </div>

        <div class="birds-input">
            <input type="text" name="birds-portfolio-projmanager" id="birds-portfolio-projmanager" value="<?php echo sanitize_text_field( get_post_meta( $post->ID, 'birds_portfolio_projmanager', true ) ); ?>" size="30" style="width: 99%;" placeholder="" />
        </div>

    </div><!-- #birds-block -->

    <div id="birds-block">

    <div class="birds-label">
        <label for="birds-portfolio-gallery">
            <strong><?php _e( 'Project Gallery', 'birds-portfolio' ); ?></strong><br />
            <span class="description"><?php _e( 'Upload the images related to this project.', 'birds-portfolio' ); ?><br /><?php _e('The Featured Image won\'t appear in the slider', 'birds-portfolio'); ?><br /><?php _e( 'You can drag and drop the images to reorder them.', 'birds-portfolio' ); ?></span>
        </label>
    </div>

    <div class="birds-input">

        <a href="#" class="birds-open-media button" title="<?php esc_attr_e( 'Add Images', 'birds-portfolio' ); ?>"><?php _e( 'Add Images', 'birds-portfolio' ); ?></a>

        <?php $image_id = get_post_meta( $post->ID, 'birds_image_ids', true ); ?>
        <?php $ids = array_filter( explode( ',', $image_id ) ); ?>

        <ul id="birds-images-list">
            <?php if ( $ids ) { ?>
            <?php foreach ( $ids as $id ) { ?>
            <li class="birds-image" data-image-id="<?php echo $id; ?>">
                <?php echo wp_get_attachment_image( $id, 'thumbnail' ); ?>
                <a href="#" class="birds-delete" title="<?php esc_attr_e( 'Remove image', 'birds-portfolio' ); ?>"><div class="dashicons dashicons-no"></div></a>
            </li>
            <?php } ?>
            <?php } ?>
        </ul>

        <input type="hidden" name="birds-portfolio-gallery-ids" id="birds-portfolio-gallery-ids" value="<?php echo get_post_meta( $post->ID, 'birds_image_ids', true ); ?>" />

    </div>

</div><!-- #birds-block -->

    <div id="birds-block">

        <div class="birds-label">
            <label for="birds-portfolio-video">
                <strong><?php _e( 'Video Embedded Code', 'birds-portfolio' ); ?></strong><br />
                <span class="description"><?php _e( 'Insert a video into your project page.', 'birds-portfolio' ); ?><br><?php _e( 'The video will appear before the Image Gallery in the slideshow.', 'birds-portfolio' ); ?></span>
            </label>
        </div>

        <div class="birds-input">
            <textarea name="birds-portfolio-video" id="birds-portfolio-video" cols="30" rows="10" style="width: 99%;"><?php echo esc_html( get_post_meta( $post->ID, 'birds_video_embed_portfolio', true ) ); ?></textarea>
        </div>

    </div><!-- #birds-block -->

    <?php
}

/**
 * Saves the metadatas.
 */
function birds_meta_boxes_save( $post_id, $post ) {

    if ( ! isset( $_POST['birds-metaboxes-portfolio-nonce'] ) || ! wp_verify_nonce( $_POST['birds-metaboxes-portfolio-nonce'], basename( __FILE__ ) ) ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $meta = array(
        'birds_image_ids'             => wp_filter_post_kses( $_POST['birds-portfolio-gallery-ids'] ),
        'birds_portfolio_short_desc'  => wp_filter_post_kses( $_POST['birds-portfolio-short-desc'] ),
        'birds_portfolio_client'      => wp_filter_post_kses( $_POST['birds-portfolio-client'] ),
        'birds_portfolio_testimonial' => wp_filter_post_kses( $_POST['birds-portfolio-testimonial'] ),
        'birds_portfolio_link'        => esc_url( $_POST['birds-portfolio-url'] ),
        'birds_portfolio_country'     => wp_filter_post_kses( $_POST['birds-portfolio-country'] ),
        'birds_portfolio_projmanager' => wp_filter_post_kses( $_POST['birds-portfolio-projmanager'] ),
        'birds_video_embed_portfolio' => stripslashes( esc_textarea( addslashes( $_POST['birds-portfolio-video'] ) ) )
    );

    foreach ( $meta as $meta_key => $new_meta_value ) {

        /* Get the meta value of the custom field key. */
        $meta_value = get_post_meta( $post_id, $meta_key, true );

        /* If there is no new meta value but an old value exists, delete it. */
        if ( current_user_can( 'delete_post_meta', $post_id, $meta_key ) && '' == $new_meta_value && $meta_value )
            delete_post_meta( $post_id, $meta_key, $meta_value );

        /* If a new meta value was added and there was no previous value, add it. */
        elseif ( current_user_can( 'add_post_meta', $post_id, $meta_key ) && $new_meta_value && '' == $meta_value )
            add_post_meta( $post_id, $meta_key, $new_meta_value, true );

        /* If the new meta value does not match the old value, update it. */
        elseif ( current_user_can( 'edit_post_meta', $post_id, $meta_key ) && $new_meta_value && $new_meta_value != $meta_value )
            update_post_meta( $post_id, $meta_key, $new_meta_value );
    }

}
