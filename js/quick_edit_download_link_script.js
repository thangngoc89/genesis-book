function set_book_download_link_value(fieldValue, nonce) {
        // refresh the quick menu properly
        inlineEditPost.revert();
        console.log(fieldValue);
        jQuery('#book_download_link').val(fieldValue);
}
