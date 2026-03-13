<?php
/**
 * Template part: Supplier Product Item (WooCommerce)
 */
global $product;

// Đảm bảo biến $product có dữ liệu
if ( empty( $product ) || ! is_object( $product ) ) {
    return;
}
?>

<div class="col-lg-3 col-md-4 col-6">
    <div class="product-card">
        <figure>
            <a href="<?php echo get_permalink( $product->get_id() ); ?>">
                <?php echo $product->get_image( 'woocommerce_thumbnail' ); ?>
            </a>
        </figure>
        <h4 class="prod-title">
            <a href="<?php echo get_permalink( $product->get_id() ); ?>" class="text-dark">
                <?php echo $product->get_name(); ?>
            </a>
        </h4>
        
        <?php
        // Lấy mô tả ngắn
        $short_desc = $product->get_short_description();

        if ( ! empty( $short_desc ) ) {
            echo '<div class="prod-short-desc">' . wp_strip_all_tags( $short_desc ) . '</div>';
        }
        ?>
    </div>
</div>