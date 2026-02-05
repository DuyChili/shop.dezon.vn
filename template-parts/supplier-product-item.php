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
        $brands = get_the_terms( $product->get_id(), 'product_brand' ); // Thay 'product_brand' bằng slug taxonomy brand thực tế của bạn
        if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) {
            $brand_name = $brands[0]->name;
            echo '<p class="prod-brand">' . esc_html( $brand_name ) . '</p>';
        }
        ?>
    </div>
</div>