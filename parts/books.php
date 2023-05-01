<?php

if (isset($_GET['author'])) {
    var_dump($_GET['author']);
}

if (isset($_GET['published'])) {
    var_dump($_GET['published']);
}
die();

?>

<?php

$books = new WP_Query(['post_type' => 'book']);

/**
 * 
 * 
 * 
 * books
 */
?>

<?php while ($books->have_posts()) : $books->the_post(); ?>

    <?php
    $book_id = $books->post->ID;
    $book_author_ids = get_post_meta($book_id, 'book_authors', true);

    $book_authors_query = new WP_Query([
        'post_type' => 'author',
        'post__in' => $book_author_ids
    ]);

    $book_authors = $book_authors_query->get_posts();

    $categories = get_the_terms($book_id, 'book_category');
    $publication_dates = get_the_terms($book_id, 'book_publication_date');
    $book_file_id = get_post_meta($book_id, 'book_pdf_attachment', true);
    $book_download_link = null;
    if ($book_file_id) {
        $book_download_link = wp_get_attachment_url($book_file_id);
    }

    ?>

    <div style="margin-bottom: 10px;">
        <div>
            <strong>book id:</strong>
            <?php echo $book_id; ?>
        </div>
        <div>
            <strong>title:</strong>
            <?php the_title(); ?>
        </div>
        <div>
            <strong>book authors:</strong>
            <?php if (isset($book_authors) && is_array($book_authors)) : ?>
                <?php foreach ($book_authors as $author) : ?>

                    <?php echo $author->post_title; ?>

                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div>
            <strong>categories:</strong>
            <?php if (isset($categories) && is_array($categories)) : ?>
                <?php foreach ($categories as $idx => $category) : ?>

                    <?php echo ($idx > 0 ? ', ' : '') . $category->name; ?>

                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div>
            <strong>publication date:</strong>
            <?php if (isset($publication_dates) && is_array($publication_dates)) : ?>

                <?php foreach ($publication_dates as $publication_date) : ?>

                    <?php echo $publication_date->name; ?>

                <?php endforeach; ?>

            <?php endif; ?>
        </div>
        <div>
            <strong>download link:</strong>
            <?php if ($book_download_link) : ?>
                <a href="<?php echo $book_download_link; ?>" target="_blank"><?php _e('download book', 'livre'); ?> </a>
            <?php endif; ?>
        </div>
    </div>


    <?php wp_reset_postdata(); ?>

<?php endwhile; ?>