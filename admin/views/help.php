<?php
if (!defined('ABSPATH')) {
    exit;
}

$metal_icons = array(
    'gold' => 'https://zu-media.co.uk/wp-content/uploads/2025/08/gold.png',
    'silver' => 'https://zu-media.co.uk/wp-content/uploads/2025/08/silver.png',
    'platinum' => 'https://zu-media.co.uk/wp-content/uploads/2025/08/platinum.jpg',
    'palladium' => 'https://zu-media.co.uk/wp-content/uploads/2025/08/palladium.png'
);
?>

<div class="wrap metal-prices-admin">
    <h1>Metal Prices Pro - Help & Shortcodes</h1>
    
    <div class="help-intro">
        <div class="help-metals-overview">
            <h2>Supported Metals</h2>
            <div class="help-metals-grid">
                <?php foreach (['gold', 'silver', 'platinum', 'palladium'] as $metal): ?>
                    <div class="help-metal-item">
                        <img src="<?php echo esc_url($metal_icons[$metal]); ?>" alt="<?php echo ucfirst($metal); ?>" class="help-metal-icon" />
                        <h4><?php echo ucfirst($metal); ?></h4>
                        <?php if ($metal === 'gold'): ?>
                            <p>9, 14, 18, 22 carat<br>Krugerrand, Full Sovereign</p>
                        <?php elseif ($metal === 'silver'): ?>
                            <p>Pure Silver (99.9%)<br>Sterling Silver (92.5%)</p>
                        <?php elseif ($metal === 'platinum'): ?>
                            <p>90% Platinum</p>
                        <?php else: ?>
                            <p>Spot price per troy ounce</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <div class="help-section">
        <h2>Main Shortcodes</h2>
        <table class="widefat striped">
            <thead>
                <tr>
                    <th>Shortcode</th>
                    <th>Description</th>
                    <th>Example</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>[metal_prices]</code></td>
                    <td>Display all metal prices in default layout</td>
                    <td><code>[metal_prices]</code></td>
                </tr>
                <tr>
                    <td><code>[metal_prices layout="cards"]</code></td>
                    <td>Display prices in beautiful card format</td>
                    <td><code>[metal_prices layout="cards"]</code></td>
                </tr>
                <tr>
                    <td><code>[metal_prices layout="grid"]</code></td>
                    <td>Display prices in grid format</td>
                    <td><code>[metal_prices layout="grid"]</code></td>
                </tr>
                <tr>
                    <td><code>[metal_prices metal="gold"]</code></td>
                    <td>Display only gold prices</td>
                    <td><code>[metal_prices metal="gold"]</code></td>
                </tr>
                <tr>
                    <td><code>[metal_calculator]</code></td>
                    <td>Display interactive metal calculator</td>
                    <td><code>[metal_calculator]</code></td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="help-section">
        <h2>Individual Price Shortcodes</h2>
        <div class="individual-prices-grid">
            <div class="price-category">
                <img src="<?php echo esc_url($metal_icons['gold']); ?>" alt="Gold" class="category-icon" />
                <h3>Gold Shortcodes</h3>
                <ul>
                    <li><code>[gold_spot_price]</code> - Gold spot price per troy oz</li>
                    <li><code>[gold_9_carat]</code> - 9 carat gold per gram</li>
                    <li><code>[gold_14_carat]</code> - 14 carat gold per gram</li>
                    <li><code>[gold_18_carat]</code> - 18 carat gold per gram</li>
                    <li><code>[gold_22_carat]</code> - 22 carat gold per gram</li>
                    <li><code>[gold_krugerrand]</code> - Krugerrand per gram</li>
                    <li><code>[gold_full_sovereign]</code> - Full Sovereign per gram</li>
                </ul>
            </div>
            
            <div class="price-category">
                <img src="<?php echo esc_url($metal_icons['silver']); ?>" alt="Silver" class="category-icon" />
                <h3>Silver Shortcodes</h3>
                <ul>
                    <li><code>[silver_spot_price]</code> - Silver spot price per troy oz</li>
                    <li><code>[silver_pure]</code> - Pure silver (99.9%) per gram</li>
                    <li><code>[silver_925]</code> - Sterling silver (92.5%) per gram</li>
                </ul>
            </div>
            
            <div class="price-category">
                <img src="<?php echo esc_url($metal_icons['platinum']); ?>" alt="Platinum" class="category-icon" />
                <h3>Platinum Shortcodes</h3>
                <ul>
                    <li><code>[platinum_spot_price]</code> - Platinum spot price per troy oz</li>
                    <li><code>[platinum_90]</code> - 90% Platinum per gram</li>
                </ul>
            </div>
            
            <div class="price-category">
                <img src="<?php echo esc_url($metal_icons['palladium']); ?>" alt="Palladium" class="category-icon" />
                <h3>Palladium Shortcodes</h3>
                <ul>
                    <li><code>[palladium_spot_price]</code> - Palladium spot price per troy oz</li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="help-section">
        <h2>Elementor Widgets</h2>
        <?php if (did_action('elementor/loaded')): ?>
            <div class="elementor-status elementor-active">
                <h3>✅ Elementor is Active!</h3>
                <p>You can use our custom widgets in the <strong>"Metal Prices Pro"</strong> category in Elementor:</p>
                <ul>
                    <li><strong>Metal Prices Cards</strong> - Beautiful responsive card display</li>
                    <li><strong>Metal Price Calculator</strong> - Interactive calculator widget</li>
                    <li><strong>Individual Metal Price</strong> - Single price display widget</li>
                </ul>
            </div>
        <?php else: ?>
            <div class="elementor-status elementor-inactive">
                <h3>ℹ️ Elementor Not Detected</h3>
                <p>Install Elementor to unlock additional widgets for even better metal price displays!</p>
                <a href="<?php echo admin_url('plugin-install.php?s=elementor&tab=search&type=term'); ?>" class="button button-secondary">Install Elementor</a>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="help-section">
        <h2>Usage Examples</h2>
        <div class="usage-examples">
            <div class="example-box">
                <h4>Basic Display</h4>
                <code>[metal_prices]</code>
                <p>Shows all metal prices in a simple list format</p>
            </div>
            
            <div class="example-box">
                <h4>Cards Layout</h4>
                <code>[metal_prices layout="cards"]</code>
                <p>Beautiful card display with icons and detailed pricing</p>
            </div>
            
            <div class="example-box">
                <h4>Individual Price</h4>
                <code>Current 18 carat gold: [gold_18_carat]</code>
                <p>Embed individual prices within your content</p>
            </div>
            
            <div class="example-box">
                <h4>Calculator</h4>
                <code>[metal_calculator]</code>
                <p>Let visitors calculate their metal values</p>
            </div>
        </div>
    </div>
</div>

<style>
.help-metals-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.help-metal-item {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.help-metal-icon, .category-icon {
    width: 100px !important;
    height: 100px !important;
    object-fit: contain;
    margin: 0 auto 15px auto;
    display: block;
    border-radius: 4px;
    max-width: 100px;
    max-height: 100px;
}

.help-metal-item h4 {
    margin: 0 0 10px 0;
    font-size: 18px;
    color: #333;
}

.help-metal-item p {
    font-size: 14px;
    color: #666;
    margin: 0;
}

.help-section {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
}

.individual-prices-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.price-category {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
}

.price-category h3 {
    margin: 0 0 15px 0;
    display: flex;
    align-items: center;
    gap: 15px;
}

.price-category ul {
    margin: 0;
    padding-left: 20px;
}

.price-category li {
    margin-bottom: 8px;
    font-size: 14px;
}

.price-category code {
    background: #f1f1f1;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: monospace;
    font-size: 12px;
}

.elementor-status {
    padding: 20px;
    border-radius: 8px;
    margin-top: 15px;
}

.elementor-active {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.elementor-inactive {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    color: #856404;
}

.usage-examples {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.example-box {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 15px;
}

.example-box h4 {
    margin: 0 0 10px 0;
    color: #333;
}

.example-box code {
    display: block;
    background: #f8f9fa;
    padding: 8px 12px;
    border-radius: 4px;
    margin: 8px 0;
    font-family: monospace;
    font-size: 13px;
    border: 1px solid #e9ecef;
}

.example-box p {
    margin: 8px 0 0 0;
    font-size: 14px;
    color: #666;
}

.widefat code {
    background: #f1f1f1;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: monospace;
}
</style>