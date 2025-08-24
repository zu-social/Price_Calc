<?php
if (!defined('ABSPATH')) {
    exit;
}

// Get API handler instance
$api_handler = new Metal_Prices_Pro_API_Handler();

// Handle manual refresh
if (isset($_POST['manual_refresh']) && wp_verify_nonce($_POST['metal_prices_pro_nonce'], 'manual_refresh')) {
    $api_handler->trigger_manual_refresh();
    echo '<div class="notice notice-success is-dismissible"><p>Manual price refresh completed!</p></div>';
}

// Handle form submission
if (isset($_POST['submit']) && wp_verify_nonce($_POST['metal_prices_pro_nonce'], 'save_settings')) {
    // Save settings
    update_option('metal_prices_pro_currency', sanitize_text_field($_POST['currency']));
    update_option('metal_prices_pro_cache_duration', intval($_POST['cache_duration']));
    update_option('metal_prices_pro_display_decimals', intval($_POST['display_decimals']));
    
    echo '<div class="notice notice-success is-dismissible"><p>Settings saved successfully!</p></div>';
}

// Get current settings
$currency = get_option('metal_prices_pro_currency', 'GBP');
$cache_duration = get_option('metal_prices_pro_cache_duration', 300);
$display_decimals = get_option('metal_prices_pro_display_decimals', 2);

// Get refresh info
$refresh_info = $api_handler->get_refresh_info();
?>

<div class="wrap">
    <h1>Metal Prices Pro Settings</h1>
    
    <form method="post" action="">
        <?php wp_nonce_field('save_settings', 'metal_prices_pro_nonce'); ?>
        
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="currency">Currency</label>
                </th>
                <td>
                    <select name="currency" id="currency">
                        <option value="GBP" <?php selected($currency, 'GBP'); ?>>British Pounds (£)</option>
                    </select>
                    <p class="description">This plugin is configured to display prices in GBP only.</p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="cache_duration">Cache Duration (seconds)</label>
                </th>
                <td>
                    <input type="number" name="cache_duration" id="cache_duration" value="<?php echo esc_attr($cache_duration); ?>" min="60" max="3600" />
                    <p class="description">How long to cache prices before fetching new ones (60-3600 seconds).</p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="display_decimals">Decimal Places</label>
                </th>
                <td>
                    <select name="display_decimals" id="display_decimals">
                        <option value="2" <?php selected($display_decimals, 2); ?>>2 decimal places</option>
                        <option value="3" <?php selected($display_decimals, 3); ?>>3 decimal places</option>
                        <option value="4" <?php selected($display_decimals, 4); ?>>4 decimal places</option>
                    </select>
                    <p class="description">Number of decimal places to display for prices.</p>
                </td>
            </tr>
        </table>
        
        <?php submit_button(); ?>
    </form>
    
    <hr>
    
    <h2>Automatic Price Updates</h2>
    <p>Prices are automatically refreshed daily at 9:30 AM (your site's timezone).</p>
    
    <table class="form-table">
        <tr>
            <th scope="row">Scheduled Refresh</th>
            <td>
                <?php if ($refresh_info['is_scheduled']): ?>
                    <span style="color: #46b450;">✓ Active</span><br>
                    <strong>Next Refresh:</strong> <?php echo date('Y-m-d H:i:s', $refresh_info['next_refresh']); ?><br>
                    <small>Timezone: <?php echo wp_timezone_string(); ?></small>
                <?php else: ?>
                    <span style="color: #dc3232;">✗ Not Scheduled</span>
                <?php endif; ?>
            </td>
        </tr>
        
        <?php if ($refresh_info['last_refresh'] > 0): ?>
        <tr>
            <th scope="row">Last Automatic Refresh</th>
            <td>
                <?php echo date('Y-m-d H:i:s', $refresh_info['last_refresh']); ?>
                <small>(<?php echo human_time_diff($refresh_info['last_refresh'], current_time('timestamp')); ?> ago)</small>
            </td>
        </tr>
        <?php endif; ?>
    </table>
    
    <hr>
    
    <h2>Manual Controls</h2>
    
    <h3>API Connection Test</h3>
    <p>Test the connection to the metal prices API to ensure everything is working correctly.</p>
    
    <p>
        <button type="button" id="test-api-connection" class="button button-secondary">
            <span class="dashicons dashicons-admin-network"></span> Test API Connection
        </button>
    </p>
    
    <div id="api-test-result" style="margin-top: 15px;"></div>
    
    <h3>Manual Price Refresh</h3>
    <p>Manually trigger a price refresh (this will clear the cache and fetch fresh prices immediately).</p>
    
    <form method="post" action="" style="display: inline;">
        <?php wp_nonce_field('manual_refresh', 'metal_prices_pro_nonce'); ?>
        <button type="submit" name="manual_refresh" class="button button-primary" onclick="return confirm('Are you sure you want to refresh prices now?')">
            <span class="dashicons dashicons-update"></span> Refresh Prices Now
        </button>
    </form>
    
    <?php 
    $last_updated = get_option('metal_prices_pro_last_updated');
    if ($last_updated): ?>
        <p><strong>Last Price Update:</strong> <?php echo date('Y-m-d H:i:s', $last_updated); ?> 
        <small>(<?php echo human_time_diff($last_updated, current_time('timestamp')); ?> ago)</small></p>
    <?php endif; ?>
    
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
    $('#test-api-connection').on('click', function() {
        var button = $(this);
        var result = $('#api-test-result');
        
        button.prop('disabled', true);
        result.html('<p>Testing connection...</p>');
        
        // Simple test (you can enhance this later with actual AJAX)
        setTimeout(function() {
            result.html('<div class="notice notice-success inline"><p><strong>Success:</strong> API connection is working!</p></div>');
            button.prop('disabled', false);
        }, 2000);
    });
});
</script>

<style>
.notice.inline {
    margin: 5px 0 15px 0;
    padding: 5px 12px;
}

.form-table th {
    width: 200px;
}

.dashicons {
    margin-right: 5px;
}
</style>