<?php
if (!defined('ABSPATH')) {
    exit;
}

// Get current prices (you might need to adjust this based on your existing code)
$api_handler = new Metal_Prices_Pro_API_Handler();
$prices = $api_handler->get_cached_prices();

$metal_icons = array(
    'gold' => 'https://zu-media.co.uk/wp-content/uploads/2025/08/gold.png',
    'silver' => 'https://zu-media.co.uk/wp-content/uploads/2025/08/silver.png',
    'platinum' => 'https://zu-media.co.uk/wp-content/uploads/2025/08/platinum.jpg',
    'palladium' => 'https://zu-media.co.uk/wp-content/uploads/2025/08/palladium.png'
);
?>

<div class="wrap metal-prices-admin">
    <h1>Metal Prices Pro Dashboard</h1>
    
    <div class="metal-prices-admin-cards">
        <?php foreach (['gold', 'silver', 'platinum', 'palladium'] as $metal): ?>
            <?php 
            $spot_price = isset($prices[$metal]) ? $prices[$metal] : 0;
            $icon_url = $metal_icons[$metal];
            ?>
            <div class="admin-metal-card <?php echo $metal; ?>-card">
                <img src="<?php echo esc_url($icon_url); ?>" alt="<?php echo ucfirst($metal); ?> icon" class="admin-metal-icon" />
                <h3><?php echo ucfirst($metal); ?></h3>
                <div class="admin-spot-price">
                    Spot Price: £<?php echo number_format($spot_price, 2); ?>
                </div>
                <div class="admin-last-updated">
                    Last Updated: <?php echo get_option('metal_prices_pro_last_updated', 'Never'); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="admin-info-section">
        <h2>Quick Statistics</h2>
        <div class="admin-stats">
            <div class="stat-box">
                <h4>Cache Duration</h4>
                <p><?php echo get_option('metal_prices_pro_cache_duration', 300); ?> seconds</p>
            </div>
            <div class="stat-box">
                <h4>Currency</h4>
                <p><?php echo get_option('metal_prices_pro_currency', 'GBP'); ?></p>
            </div>
            <div class="stat-box">
                <h4>Last Daily Refresh</h4>
                <p><?php echo get_option('metal_prices_pro_last_daily_refresh', 'Never'); ?></p>
            </div>
        </div>
    </div>
    
    <div class="admin-shortcodes-preview">
        <h2>Shortcode Preview</h2>
        <p>Here's how your shortcodes will appear on the frontend:</p>
        
        <h3>Cards Layout</h3>
        <div class="shortcode-example">
            <code>[metal_prices layout="cards"]</code>
            <?php 
            if (class_exists('Metal_Prices_Pro_Shortcodes')) {
                $shortcodes = new Metal_Prices_Pro_Shortcodes();
                echo $shortcodes->display_metal_prices(array('layout' => 'cards'));
            }
            ?>
        </div>
    </div>
</div>

<style>
.metal-prices-admin-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.admin-metal-card {
    background: #fff;
    border: 1px solid #ccd0d4;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.admin-metal-card.gold-card {
    border-left: 4px solid #DAA520;
}

.admin-metal-card.silver-card {
    border-left: 4px solid #C0C0C0;
}

.admin-metal-card.platinum-card {
    border-left: 4px solid #E5E4E2;
}

.admin-metal-card.palladium-card {
    border-left: 4px solid #808080;
}

.admin-metal-icon {
    width: 100px !important;
    height: 100px !important;
    object-fit: contain;
    margin: 0 auto 15px auto;
    display: block;
    border-radius: 4px;
    max-width: 100px;
    max-height: 100px;
}

.admin-metal-card h3 {
    margin: 0 0 10px 0;
    font-size: 18px;
}

.admin-spot-price {
    font-size: 16px;
    font-weight: bold;
    color: #333;
    margin-bottom: 8px;
}

.admin-last-updated {
    font-size: 12px;
    color: #666;
}

.admin-info-section {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    margin: 30px 0;
}

.admin-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.stat-box {
    background: #fff;
    padding: 15px;
    border-radius: 6px;
    border: 1px solid #ddd;
    text-align: center;
}

.stat-box h4 {
    margin: 0 0 8px 0;
    color: #333;
}

.stat-box p {
    margin: 0;
    font-size: 14px;
    color: #666;
}

.admin-shortcodes-preview {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    margin: 30px 0;
}

.shortcode-example {
    background: #fff;
    padding: 15px;
    border-radius: 6px;
    border: 1px solid #ddd;
    margin-top: 10px;
}

.shortcode-example code {
    display: block;
    background: #f1f1f1;
    padding: 8px 12px;
    border-radius: 4px;
    margin-bottom: 15px;
    font-family: monospace;
}
</style>