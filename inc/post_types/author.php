<?php


/**
 * 
 * 
 * registration
 */

register_post_type('author', [
    'label' => 'Auteur',
    'description' => 'Auteur Post',
    'public' => true,
    'publicaly_queryable' => true,
    'menu_icon' => 'dashicons-businessman',
    'supports' => [
        'title',
        'editor',
        'thumbnail',
        'custom_fields',
        'revisions'
    ],
    'has_archive' => true,
    'query_var' => 'author',
    'can_export' => true
]);


/**
 * 
 * 
 * 
 * fields
 */















// *******************************************************************************************************************


function register_cutsom_author_books_relation_field()
{
    register_meta('author', 'author_books', ['type' => 'array', 'single' => true, 'show_in_rest' => true]);
}

add_action('init', 'register_cutsom_relation_field');



add_action('add_meta_boxes', 'add_meta_author_books_relation_boxes');
function add_meta_author_books_relation_boxes()
{
    add_meta_box('author_books', 'Author Books', 'author_books_field', 'author', 'normal');
}

function author_books_field()
{
    global $post;

    $args = array(
        'post_type' => 'book',
        'meta_query' => array(
            array(
                'key' => 'book_authors',
                'value' => $post->ID,
                'compare' => 'LIKE'
            )
        )
    );
    $books = get_posts($args);
    $selected_books = [];
    if (isset($books) && is_array($books)) {
        foreach ($books as $book) {
            $selected_books[] = $book->ID;
        }
    }

    // var_dump($selected_books);
    // die();

    $all_books = get_posts(array(
        'post_type' => 'book',
        'numberposts' => -1,
        'orderby' => 'post_title',
        'order' => 'ASC'
    ));
?>
    <input type="hidden" name="book_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>" />
    <table class="form-table">
        <tr valign="top">
            <td>
                <div>
                    <?php foreach ($all_books as $book) : ?>
                        <label for="<?php echo $book->ID; ?>">
                            <span><?php echo $book->post_title; ?></span>
                            <input type="checkbox" name="book[]" value="<?php echo $book->ID; ?>" id="<?php echo $book->ID; ?>" <?php echo (in_array($book->ID, $selected_books)) ? ' checked="checked"' : ''; ?>>
                        </label>
                    <?php endforeach; ?>
                </div>
            </td>
        </tr>
    </table>
<?php
}

add_action('save_post', 'save_author_books_field');
function save_author_books_field($post_id)
{

    // var_dump($_POST['book']);
    // die();

    // only run this for author
    if ('author' != get_post_type($post_id))
        return $post_id;

    // verify nonce
    if (empty($_POST['book_nonce']) || !wp_verify_nonce($_POST['book_nonce'], basename(__FILE__)))
        return $post_id;

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;

    // check permissions
    if (!current_user_can('edit_post', $post_id))
        return $post_id;

    if (isset($_POST['book']) && is_array($_POST['book'])) {


        $book_ids = $_POST['book'];
        foreach ($book_ids as $book_id) {
            $saved_authors = get_post_meta($book_id, 'book_authors', true);

            if (is_array($saved_authors)) {
                if (!in_array(intval($post_id), $saved_authors)) {
                    $saved_authors[] = $post_id;
                }
            } else {
                $saved_authors = [];
            }

            if (isset($saved_authors) && is_array($saved_authors)) {
                // save
                update_post_meta($book_id, 'book_authors', $saved_authors);
            }
        }
    }
}
