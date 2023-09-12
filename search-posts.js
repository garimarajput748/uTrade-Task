
jQuery(document).ready(function($) {
    $('#search-button').on('click', function() {
        var searchTerm = $('#search-input').val();

        $.ajax({
            url: ajax_post_search_params.ajax_url,
            type: 'POST',
            data: {
                action: 'search_posts',
                search_term: searchTerm
            },
            success: function(response) {
                // Display the search results
                $('#search-results').html(response);
            },
            error: function(error) {
                // Handle errors here, e.g., display an error message
                console.log('AJAX Error:', error);
            }
        });
    });
});
