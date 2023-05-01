<?php


/**
 * 
 * 
 * 
 * registration
 */

register_post_type('book', [
    'label' => 'Livre',
    'description' => 'Livre Post',
    'public' => true,
    'publicaly_queryable' => true,
    'menu_icon' => 'dashicons-book',
    'supports' => [
        'title',
        'editor',
        'thumbnail',
        'custom_fields',
        'revisions'
    ],
    'taxonomies' => [
        'book_category',
        'book_publication_date'
    ],
    'has_archive' => true,
    'query_var' => 'book',
    'can_export' => true
]);


/**
 * 
 * 
 * 
 * fields
 */


// *******************************************************************************************************************


function register_cutsom_relation_field()
{
    register_meta('book', 'book_authors', ['type' => 'array', 'single' => true, 'show_in_rest' => true]);
}

add_action('init', 'register_cutsom_relation_field');



add_action('add_meta_boxes', 'add_meta_relation_boxes');
function add_meta_relation_boxes()
{
    add_meta_box('book_authors', 'Book Authors', 'book_authors_field', 'book', 'normal');
}

function book_authors_field()
{
    global $post;
    $selected_authors = get_post_meta($post->ID, 'book_authors', true);
    if (!is_array($selected_authors)) {
        $selected_authors = [];
    }


    $all_authors = get_posts(array(
        'post_type' => 'author',
        'numberposts' => -1,
        'orderby' => 'post_title',
        'order' => 'ASC'
    ));
    // var_dump($selected_authors);
    // die();
?>
    <input type="hidden" name="author_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>" />
    <table class="form-table">
        <tr valign="top">
            <td>
                <div>
                    <?php foreach ($all_authors as $author) : ?>
                        <label for="<?php echo $author->ID; ?>">
                            <span><?php echo $author->post_title; ?></span>
                            <input type="checkbox" name="author[]" value="<?php echo $author->ID; ?>" id="<?php echo $author->ID; ?>" <?php echo (in_array($author->ID, $selected_authors)) ? ' checked="checked"' : ''; ?>>
                        </label>
                    <?php endforeach; ?>
                </div>
            </td>
        </tr>
    </table>
<?php
}

add_action('save_post', 'save_authors_field');
function save_authors_field($post_id)
{

    // only run this for author
    if ('book' != get_post_type($post_id))
        return $post_id;

    // verify nonce
    if (empty($_POST['author_nonce']) || !wp_verify_nonce($_POST['author_nonce'], basename(__FILE__)))
        return $post_id;

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;

    // check permissions
    if (!current_user_can('edit_post', $post_id))
        return $post_id;

    if (isset($_POST['author'])) {

        // save
        update_post_meta($post_id, 'book_authors', array_map('intval', $_POST['author']));
    }


    // // Assume we have a custom taxonomy called "book_publication_date".
    // $taxonomy = 'book_publication_date';

    // // Get the custom taxonomy value from the request.
    // $term_value = $_POST['book_publication_date'];

    // // Validate that $term_value is a year.
    // if (date('Y', strtotime($term_value)) !== $term_value) {
    //     // Handle validation error.
    //     wp_die('Invalid year format');
    // } else {
    //     // Year is valid, set it to the post.
    //     wp_set_object_terms($post_id, $term_value, $taxonomy);
    // }
}


// ****************************************************************************************************************


function my_file_attachment_meta_box()
{
    add_meta_box('book_pdf_attachment', 'Pdf attachment', 'pdf_attachement_callback', 'book', 'normal', 'default');
}
add_action('add_meta_boxes', 'my_file_attachment_meta_box');


function pdf_attachement_callback($post)
{
    // wp_nonce_field('my_file_attachment_nonce', 'my_file_attachment_nonce');

    $file_id = get_post_meta($post->ID, 'book_pdf_attachment', true);
    $file = null;
    if ($file_id) {
        // $file = get_attached_file($file_id, false);
        $attachment_metadata = wp_get_attachment_metadata($file_id);
        $attachment_url = wp_get_attachment_url($file_id);
        $filename = wp_basename($attachment_url);
        $filesize = size_format($attachment_metadata['filesize']);
        // var_dump($filesize);
        // var_dump($filename);
    }
    // $file_id = attachment_url_to_postid($file_url);

?>

    <!-- <input type="file" name="pdf_attachment_id" id="pdf_attachment_id"> -->
    <!-- <input type="text" name="_my_file_attachment_url" id="pdf_attachment_url" value="' . esc_attr($file_url) . '"> -->
    <div>
        <input type="hidden" name="pdf_attachment_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>" />
        <input type="hidden" name="pdf_attachment_id" id="pdf_attachment_id" value="<?php echo (isset($file_id) && $file_id ? esc_attr($file_id) : ''); ?>">

        <button type="button" class="button button-primary <?php echo (isset($file_id) && $file_id ? 'hidden' : ''); ?>" id="upload_attachment_button"><?php _e('Upload File', 'livre'); ?></button>
    </div>

    <?php if (isset($file_id) && $file_id) : ?>
        <div id="attachment_preview">
            <div id="attachemnt_card">
                <div id="attachment_info">
                    <a href="<?php echo $attachment_url; ?>"><?php echo $filename; ?></a>
                    <div><?php echo $filesize; ?></div>
                </div>
                <a href="#" id="remove_attachment">remove</a>
            </div>
        </div>
    <?php else : ?>
        <div id="attachment_preview"></div>
    <?php endif; ?>


<?php
}


function attachment_meta_box_scripts()
{
    wp_enqueue_media();

    wp_register_script('attachment-meta-box', get_template_directory_uri() . '/assets/js/attachment-meta-box.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('attachment-meta-box');

    wp_localize_script('attachment-meta-box', 'metaBoxVars', array(
        'title' => __('Choose or upload a file', 'livre'),
        'button' => __('Use this file', 'livre'),
    ));
}
add_action('admin_enqueue_scripts', 'attachment_meta_box_scripts');



add_action('save_post', 'save_pdf_attachment_id_field');
function save_pdf_attachment_id_field($post_id)
{

    // only run this for author
    if ('book' != get_post_type($post_id))
        return $post_id;

    // verify nonce
    if (empty($_POST['pdf_attachment_nonce']) || !wp_verify_nonce($_POST['pdf_attachment_nonce'], basename(__FILE__)))
        return $post_id;

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;

    // check permissions
    if (!current_user_can('edit_post', $post_id))
        return $post_id;

    $attachment_id = null;

    // save pdf file attachment
    if (isset($_POST['pdf_attachment_id']) && $_POST['pdf_attachment_id']) {
        $attachment_id = intval($_POST['pdf_attachment_id']);
    }
    update_post_meta($post_id, 'book_pdf_attachment', $attachment_id);
}
