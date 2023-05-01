<?php

register_taxonomy(
    'book_publication_date',
    'book',
    [
        'labels' => [
            'name' => __('Publication Dates', 'livre'),
            // General name for the taxonomy, usually plural. The same as and overridden by $tax->label. Default 'Tags'/'Categories'.

            'singular_name' => __('Publication Date', 'livre'),
            // Name for one object of this taxonomy. Default 'Tag'/'Category'.

            // 'search_items' => '',
            // Default 'Search Tags'/'Search Categories'.

            // 'popular_items' => '',
            // This label is only used for non-hierarchical taxonomies.

            // Default 'Popular Tags'.
            // 'all_items' => '',
            // Default 'All Tags'/'All Categories'.

            // 'parent_item' => '',
            // This label is only used for hierarchical taxonomies. Default 'Parent Category'.

            // 'parent_item_colon' => '',
            // The same as parent_item, but with colon : in the end.

            // 'name_field_description' => '',
            // Description for the Name field on Edit Tags screen.

            // Default 'The name is how it appears on your site'.
            // 'slug_field_description' => '',
            // Description for the Slug field on Edit Tags screen.

            // Default 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens'.
            // 'parent_field_description' => '',
            // Description for the Parent field on Edit Tags screen.

            // Default 'Assign a parent term to create a hierarchy.
            // The term Jazz, for example, would be the parent of Bebop and Big Band'.
            // 'desc_field_description' => '',
            // Description for the Description field on Edit Tags screen.

            // Default 'The description is not prominent by default; however, some themes may show it'.
            // 'edit_item' => '',
            // Default 'Edit Tag'/'Edit Category'.

            // 'view_item' => '',
            // Default 'View Tag'/'View Category'.

            // 'update_item' => '',
            // Default 'Update Tag'/'Update Category'.

            // 'add_new_item' => '',
            // Default 'Add New Tag'/'Add New Category'.

            // 'new_item_name' => '',
            // Default 'New Tag Name'/'New Category Name'.

            // 'separate_items_with_commas' => '',
            // This label is only used for non-hierarchical taxonomies. Default 'Separate tags with commas', used in the meta box.

            // 'add_or_remove_items' => '',
            // This label is only used for non-hierarchical taxonomies. Default 'Add or remove tags', used in the meta box when JavaScript is disabled.

            // 'choose_from_most_used' => '',
            // This label is only used on non-hierarchical taxonomies. Default 'Choose from the most used tags', used in the meta box.

            // 'not_found' => '',
            // Default 'No tags found'/'No categories found', used in the meta box and taxonomy list table.

            // 'no_terms' => '',
            // Default 'No tags'/'No categories', used in the posts and media list tables.

            // 'filter_by_item' => '',
            // This label is only used for hierarchical taxonomies. Default 'Filter by category', used in the posts list table.

            // 'items_list_navigation' => '',
            // Label for the table pagination hidden heading.

            // 'items_list' => '',
            // Label for the table hidden heading.

            // 'most_used' => '',
            // Title for the Most Used tab. Default 'Most Used'.

            // 'back_to_items' => '',
            // Label displayed after a term has been updated.

            // 'item_link' => '',
            // Used in the block editor. Title for a navigation link block variation.

            // Default 'Tag Link'/'Category Link'.
            // 'item_link_description' => '',
            // Used in the block editor. Description for a navigation link block variation. Default 'A link to a tag'/'A link to a category'.
        ],
        'description' => '',
        'public' => true,
        'publicaly_queryable' => true,
        'hiearchical' => false,
        'show_in_rest' => true,
        'query_var' => 'published',

    ]
);
