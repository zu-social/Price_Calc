jQuery(document).ready(function($) {
    // Refresh prices button
    $('#refresh-prices').on('click', function() {
        var button = $(this);
        button.prop('disabled', true).text('Refreshing...');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'refresh_metal_prices',
                nonce: '<?php echo wp_create_nonce("metal_prices_nonce"); ?>'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Failed to refresh prices');
                    button.prop('disabled', false).text('Refresh Prices');
                }
            },
            error: function() {
                alert('Error refreshing prices');
                button.prop('disabled', false).text('Refresh Prices');
            }
        });
    });
});