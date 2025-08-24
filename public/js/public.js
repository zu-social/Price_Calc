jQuery(document).ready(function($) {
    // Calculator functionality
    $('#calculate-btn').on('click', function() {
        var metalType = $('#metal-type').val();
        var weight = $('#weight').val();
        var unit = $('#unit').val();
        
        $.ajax({
            url: metal_prices_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'calculate_metal_value',
                metal_type: metalType,
                weight: weight,
                unit: unit,
                nonce: metal_prices_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#total-value').text(response.data.total_value);
                    $('#calculation-result').show();
                } else {
                    alert('Error calculating value');
                }
            }
        });
    });
});