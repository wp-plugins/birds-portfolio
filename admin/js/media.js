/**
* Media Uploader Script
*
* @package    birds-portfolio
* @subpackage birds-portfolio/admin/js
* @since      1.0.0
*/

jQuery(document).ready(function ($) {
    'use strict';

    var birds_media_frame,
        $image_gallery_ids = $('#birds-portfolio-gallery-ids'),
        $gallery_images = $('#birds-images-list');

    // Bind to our click event in order to open up the new media experience.
    $(document.body).on('click.birdsOpenMediaManager', '.birds-open-media', function (e) {

        var attachment_ids = $image_gallery_ids.val();

        // Prevent the default action from occuring.
        e.preventDefault();

        // If the frame already exists, re-open it.
        if (birds_media_frame) {
            birds_media_frame.open();
            return;
        }

        birds_media_frame = wp.media.frames.birds_media_frame = wp.media({

            className: 'media-frame birds-media-frame',
            frame: 'select',
            multiple: true,
            title: birds_media.title,
            library: {
                type: 'image'
            },
            button: {
                text: birds_media.button
            }
        });

        birds_media_frame.on('select', function () {

            var selection = birds_media_frame.state().get('selection');

            selection.map(function (attachment) {

                attachment = attachment.toJSON();

                if (attachment.id) {

                    attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

                    $gallery_images.append('\
                        <li class="birds-image" data-image-id="' + attachment.id + '">\
                            <img src="' + attachment.sizes.thumbnail.url + '" />\
                            <a href="#" class="birds-delete" title="' + birds_media.attr + '"><div class="dashicons dashicons-no"></div></a>\
                        </li>');

                }

            });

            $image_gallery_ids.val(attachment_ids);

        });

        // Now that everything has been set, let's open up the frame.
        birds_media_frame.open();
    });

    // Image ordering
    $gallery_images.sortable({
        items: 'li.birds-image',
        cursor: 'move',
        scrollSensitivity: 40,
        forcePlaceholderSize: true,
        helper: 'clone',
        opacity: 0.65,
        placeholder: 'birds-sortable-placeholder',
        start: function (event, ui) {
            ui.item.css('background-color', '#f6f6f6');
        },
        stop: function (event, ui) {
            ui.item.removeAttr('style');
        },
        update: function (event, ui) {
            var attachment_ids = '';

            $('li.birds-image').css('cursor', 'default').each(function () {
                var attachment_id = jQuery(this).attr('data-image-id');
                attachment_ids = attachment_ids + attachment_id + ',';
            });

            $image_gallery_ids.val(attachment_ids);
        }
    });

    // Remove images
    $gallery_images.on('click', 'a.birds-delete', function (e) {

        e.preventDefault();

        $(this).closest('li.birds-image').remove();

        var attachment_ids = '';

        $('li.birds-image').css('cursor', 'default').each(function () {
            var attachment_id = jQuery(this).attr('data-image-id');
            attachment_ids = attachment_ids + attachment_id + ',';
        });

        $image_gallery_ids.val(attachment_ids);

    });

});
