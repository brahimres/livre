<?php


$authors = new WP_Query(['post_type' => 'author']);

/**
 * 
 * 
 * 
 * authors
 */
?>

<?php while ($authors->have_posts()) : $authors->the_post(); ?>

    <?php
    $author_id = $authors->post->ID;

    $author_books_query = new WP_Query([
        'post_type' => 'book',
        'meta_query' => [
            [
                'key' => 'book_authors',
                'value' => $author_id,
                'compare' => 'LIKE'
            ]
        ]
    ]);

    $author_books = $author_books_query->get_posts();

    ?>

    <div style="margin-bottom: 10px;">
        <div>
            <strong>author:</strong>
            <?php the_title(); ?>
        </div>
        <div>
            <strong>author books:</strong>
            <?php foreach ($author_books as $author_book) : ?>

                <?php echo $author_book->post_title; ?>

            <?php endforeach; ?>
        </div>
    </div>


    <?php wp_reset_postdata(); ?>

<?php endwhile; ?>