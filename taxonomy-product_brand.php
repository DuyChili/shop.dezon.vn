<?php
/**
 * Template Name: Archive Brand Custom
 */
defined( 'ABSPATH' ) || exit;

get_header(); 
?>

<div class="space_product archive_product_page" style="padding-top: 50px; padding-bottom: 100px;">
    <div class="container">
        
        <div class="row mb-3">
            <div class="col-12">
                <h1 class="fs-48 fw-medium title_group">
                   Thương hiệu: <?php single_term_title(); ?>
                </h1>
                
                <?php if ( term_description() ) : ?>
                    <div class="archive-description mt-3 fs-18 cl-gray500">
                        <?php echo term_description(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ( have_posts() ) : ?>
            
            <div class="row">
                <?php while ( have_posts() ) : the_post(); 
                    global $product;
                    
                    // Tôi đã tối ưu lại đoạn lấy ảnh này để ưu tiên lấy ảnh đại diện (Featured Image) trước,
                    // nếu không có mới lấy ảnh đầu tiên trong thư viện (Gallery).
                    $thumb_id = $product->get_image_id();
                    
                    if ( ! $thumb_id ) {
                        $gallery_ids = $product->get_gallery_image_ids();
                        if ( ! empty( $gallery_ids ) ) {
                            $thumb_id = $gallery_ids[0];
                        }
                    }
                    
                    $thumb_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'large' ) : wc_placeholder_img_src();
                ?>
                    
                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                        <div class="item item_post product_grid_item">
                            <figure>
                                <a href="<?php the_permalink(); ?>">
                                    <img src="<?php echo esc_url($thumb_url); ?>" class="img-fluid" 
                                         alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                                </a>
                            </figure>
                            
                            <div class="meta_info mt-3">
                                <h4 class="fs-20 fw-semibold post_title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h4>
                                <div class="price cl-gray500 fs-16 fw-bold">
                                    <?php echo $product->get_price_html(); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endwhile; ?>
            </div>

            <?php 
                global $wp_query; 
                // Fix lỗi lấy biến phân trang: Bắt cả 'paged' và 'page'
                $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 ); 
                
                if ( $wp_query->max_num_pages > 1 ) : 
                    $big = 999999999; // Một số ngẫu nhiên thật lớn
            ?>
                <div class="j_paging mt-4" style="position: relative; z-index: 99;">
                    <div class="wp-pagenavi" role="navigation" onclick="event.stopPropagation();">
                        <?php
                            $links = paginate_links( array(
                                'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                                'format'    => '?paged=%#%',
                                'current'   => max( 1, $paged ),
                                'total'     => $wp_query->max_num_pages,
                                'prev_next' => true,
                                'prev_text' => '<img src="' . get_template_directory_uri() . '/assets/images/first.svg" class="img-fluid" alt="Previous">',
                                'next_text' => '<img src="' . get_template_directory_uri() . '/assets/images/last.svg" class="img-fluid" alt="Next">',
                                'type'      => 'plain',
                                'end_size'  => 1,
                                'mid_size'  => 2,
                            ) );
                            
                            // Replace các class y như giao diện cũ của bạn
                            if ( $links ) {
                                $links = str_replace("page-numbers", "page larger", $links);
                                $links = str_replace("prev page larger", "prevpostslink", $links);
                                $links = str_replace("next page larger", "nextpostslink", $links);
                                $links = str_replace("page larger current", "current", $links);
                                echo $links;
                            }
                        ?>
                    </div>
                </div>
            <?php endif; ?>

        <?php else : ?>
            <div class="alert alert-warning text-center p-5">
                <p class="fs-20">Không tìm thấy sản phẩm nào của thương hiệu này.</p>
                <a href="<?php echo home_url('/'); ?>" class="btn btn-black mt-3">Quay lại Cspace</a>
            </div>
        <?php endif; ?>

    </div>
</div>
<?php get_footer(); ?>