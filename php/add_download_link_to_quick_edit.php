<?php
// Read the article
// http://www.janes.co.za/add-to-custom-post-type-quick-edit-on-wordpress/
add_filter('manage_books_posts_columns', 'kn_add_post_columns');

function kn_add_post_columns($columns) {
    $columns['my_field'] = 'Book Download Link';
    return $columns;
}

// Add to our admin_init function
add_action('manage_posts_custom_column', 'kn_render_post_columns', 10, 2);

function kn_render_post_columns($column_name, $id) {
    switch ($column_name) {
    case 'my_field':
        // show my_field
        $my_fieldvalue = get_post_meta( $id, 'book_download_link', TRUE);
        echo $my_fieldvalue;
    }
}

// Add to our admin_init function
add_action('quick_edit_custom_box',  'kn_add_quick_edit', 10, 2);

function kn_add_quick_edit($column_name, $post_type) {
    if ($column_name != 'my_field') return;
    ?>
    <fieldset class="inline-edit-col-left">
        <div class="inline-edit-col">
            <span class="title">Book Download Link</span>
            <input id="book_download_link_noncename" type="hidden" name="book_download_link_noncename" value="" />
            <input id="book_download_link" type="text" name="book_download_link" value="" style="width: 100%" />
        </div>
    </fieldset>
     <?php
}

// Add to our admin_init function
add_action('save_post', 'kn_save_quick_edit_data');

function kn_save_quick_edit_data($post_id) {
  // verify if this is an auto save routine.
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
      return $post_id;
  // Check permissions
  if ( '' == $_POST['post_type'] ) {
    if ( !current_user_can( 'edit_page', $post_id ) )
      return $post_id;
  } else {
    if ( !current_user_can( 'edit_post', $post_id ) )
    return $post_id;
  }
  // Authentication passed now we save the data
  if (isset($_POST['book_download_link']) && ($post->post_type != 'revision')) {
        $my_fieldvalue = esc_attr($_POST['book_download_link']);
        if ($my_fieldvalue)
            update_post_meta( $post_id, 'book_download_link', $my_fieldvalue);
        else
            delete_post_meta( $post_id, 'book_download_link');
    }
    return $my_fieldvalue;
}


// Add to our admin_init function
add_filter('post_row_actions', 'kn_expand_quick_edit_link', 10, 2);
function kn_expand_quick_edit_link($actions, $post) {
    global $current_screen;
    if (($current_screen->post_type != 'books'))
        return $actions;
    $nonce = wp_create_nonce( 'book_download_link_'.$post->ID);
    $myfielvalue = get_post_meta( $post->ID, 'book_download_link', TRUE);
    $actions['inline hide-if-no-js'] = '<a href="#" class="editinline" title="';
    $actions['inline hide-if-no-js'] .= esc_attr( __( 'Edit this item inline' ) ) . '"';
    $actions['inline hide-if-no-js'] .= " onclick=\"set_book_download_link_value('{$myfielvalue}')\" >";
    $actions['inline hide-if-no-js'] .= __( 'Quick Edit' );
    $actions['inline hide-if-no-js'] .= '</a>';
    return $actions;
}
add_action( 'admin_enqueue_scripts', 'kn_admin_enqueue' );

function kn_admin_enqueue($hook) {
    if ( 'edit.php' != $hook ) {
        return;
    }

    wp_enqueue_script( 'my_custom_script', get_stylesheet_directory_uri() . '/js/quick_edit_download_link_script.js' );
}
