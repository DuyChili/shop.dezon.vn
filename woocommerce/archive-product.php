<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 */

defined( 'ABSPATH' ) || exit;

get_header();

?>

<div class="space_product archive_product_page">
    <div class="container">
        
        <div class="row mb-5">
            <div class="col-12">
                <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                    <h1 class="fs-48 fw-medium title_group"><?php woocommerce_page_title(); ?></h1>
                <?php endif; ?>
                
                <?php
                /**
                 * Hook: woocommerce_archive_description.
                 * Used to output the page description.
                 */
                do_action( 'woocommerce_archive_description' );
                ?>
            </div>
        </div>

        <div class="row">
            <?php
            if ( have_posts() ) :
                while ( have_posts() ) : the_post();
                    
                    global $product; 

                    // --- LOGIC XỬ LÝ ẢNH (Lấy từ code mẫu của bạn) ---
                    // 1. Thử lấy ID ảnh đại diện
                    $thumb_id = $product->get_image_id(); // Đã sửa lại để lấy đúng ID featured image

                    // 2. Nếu KHÔNG có ảnh đại diện -> Lấy ảnh đầu tiên trong Gallery
                    if ( ! $thumb_id ) {
                        $gallery_ids = $product->get_gallery_image_ids();
                        if ( ! empty( $gallery_ids ) ) {
                            $thumb_id = $gallery_ids[0];
                        }
                    }

                    // 3. Lấy URL ảnh
                    $thumb_url = '';
                    if ( $thumb_id ) {
                        $thumb_url = wp_get_attachment_image_url( $thumb_id, 'large' );
                    } 

                    // 4. Placeholder nếu không có ảnh nào
                    if ( empty($thumb_url) ) {
                        $thumb_url = wc_placeholder_img_src();
                    }
                    ?>

                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                        <div class="item product_grid_item">
                            <figure>
                                <a href="<?php the_permalink(); ?>">
                                    <img src="<?php echo esc_url($thumb_url); ?>" class="img-fluid"
                                        alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                                </a>
                                
                                <div class="post_ask_ai">Ask AI</div>
                            </figure>
                            
                            <div class="meta_info">
                                <h4 class="fs-20 fw-semibold">
                                    <a href="<?php the_permalink(); ?>" rel="noopener noreferrer">
                                        <?php the_title(); ?>
                                    </a>
                                </h4>
                                <div class="price cl-gray500 fs-16 mt-1">
                                    <?php echo $product->get_price_html(); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endwhile; ?>
            <?php else : ?>
                <div class="col-12">
                    <p class="fs-18 cl-gray500">Chưa có sản phẩm nào trong danh mục này.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <div class="j_paging">
                    <?php
                    // Sử dụng WP-PageNavi nếu có cài, hoặc mặc định WP
                    if ( function_exists('wp_pagenavi') ) {
                        wp_pagenavi();
                    } else {
                        the_posts_pagination( array(
                            'mid_size'  => 2,
                            'prev_text' => '<img src="' . get_template_directory_uri() . '/assets/images/arrow-left.svg" alt="Prev">',
                            'next_text' => '<img src="' . get_template_directory_uri() . '/assets/images/arrow-right.svg" alt="Next">',
                        ) );
                    }
                    ?>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
get_footer();
?>