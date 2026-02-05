<?php
/**
 * Post Type: supplier
 * OPTIMIZED VERSION
 */

get_header();

if (have_posts()) :
    while (have_posts()) : the_post();
        
    if($_GET['debug'] == 1): ?>


<div class="supplier_page">
    <?php
        $info = get_field('info'); 

        $is_premium  = isset($info['is_premium']) ? $info['is_premium'] : false;
        $website_url = isset($info['supplier_website_url']) ? $info['supplier_website_url'] : '';
        $facebook_url = isset($info['link_facebook']) ? $info['link_facebook'] : ''; 
        $phone_number = isset($info['phone']) ? $info['phone'] : '';
        ?>

    <div class="supplier_header">
        <div class="container">
            <div class="row justify-content-between">

                <div class="col-lg-6">
                    <div class="brand_intro">
                        <div class="row">
                            <div class="col-md-2 col-3">
                                <?php if ( has_post_thumbnail() ): ?>
                                <img src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'full' ); ?>"
                                    class="img-fluid" alt="<?php the_title(); ?>">
                                <?php else: ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-dezon-black.jpeg"
                                    class="img-fluid" alt="Default Logo">
                                <?php endif; ?>
                            </div>

                            <div class="col-md-10 col-9">
                                <div>
                                    <div class="d-flex align-items-center">
                                        <span class="text-uppercase me-2 fw-600"><?php the_title(); ?></span>

                                        <?php if( $is_premium ): ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/premium.png"
                                            style="width: auto; height: 30px;" alt="Premium Member">
                                        <?php endif; ?>
                                    </div>

                                    <p class="my-2 fs-20 fw-500" style="color: #80828D;">
                                        <?php 
                                            if ( has_excerpt() ) {
                                                echo get_the_excerpt(); 
                                            } else {
                                                echo 'Leading provider of furniture and technology solutions';
                                            }
                                            ?>
                                    </p>

                                    <?php 
                                        $terms = get_the_terms( get_the_ID(), 'supplier_expertise' ); 
                                        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : 
                                        ?>
                                    <ul>
                                        <?php foreach ( $terms as $term ) : ?>
                                        <li>
                                            <a href="<?php echo esc_url( get_term_link( $term ) ); ?>">
                                                <?php echo esc_html( $term->name ); ?>
                                            </a>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <?php endif; ?>

                                    <div class="mt-2 d-flex">
                                        <?php if( $facebook_url ): ?>
                                        <div class="me-3">
                                            <a href="<?php echo esc_url($facebook_url); ?>" target="_blank"
                                                rel="nofollow">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <g clip-path="url(#clip0_360_36694)">
                                                        <path
                                                            d="M24 12C24 5.37258 18.6274 0 12 0C5.37258 0 0 5.37258 0 12C0 17.9895 4.3882 22.954 10.125 23.8542V15.4688H7.07812V12H10.125V9.35625C10.125 6.34875 11.9166 4.6875 14.6576 4.6875C15.9701 4.6875 17.3438 4.92188 17.3438 4.92188V7.875H15.8306C14.34 7.875 13.875 8.80008 13.875 9.75V12H17.2031L16.6711 15.4688H13.875V23.8542C19.6118 22.954 24 17.9895 24 12Z"
                                                            fill="#80828D" />
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_360_36694">
                                                            <rect width="24" height="24" rx="12" fill="white" />
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                            </a>
                                        </div>
                                        <?php endif; ?>

                                        <?php if( $website_url ): ?>
                                        <div>
                                            <a href="<?php echo esc_url($website_url); ?>" target="_blank"
                                                rel="nofollow">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                                    viewBox="0 0 22 22" fill="none">
                                                    <path
                                                        d="M5.74805 11.7578C5.84265 14.2425 6.28402 16.5096 6.97656 18.2461C7.36805 19.2276 7.86012 20.0898 8.45703 20.7227C8.74969 21.033 9.08399 21.3047 9.45703 21.501C4.43215 20.8996 0.467159 16.8367 0 11.7578H5.74805ZM21.5 11.7578C21.0328 16.8367 17.0679 20.8996 12.043 21.501C12.416 21.3047 12.7503 21.033 13.043 20.7227C13.6399 20.0898 14.1319 19.2276 14.5234 18.2461C15.216 16.5095 15.6582 14.2425 15.7529 11.7578H21.5ZM13.7422 11.7578C13.6484 14.0304 13.242 16.0343 12.6582 17.498C12.3227 18.339 11.9483 18.9526 11.584 19.3389C11.2217 19.723 10.9395 19.8154 10.75 19.8154C10.5605 19.8154 10.2783 19.723 9.91602 19.3389C9.55172 18.9526 9.1773 18.339 8.8418 17.498C8.25801 16.0343 7.85157 14.0304 7.75781 11.7578H13.7422ZM10.75 1.6875C10.9395 1.6875 11.2217 1.77987 11.584 2.16406C11.9482 2.55034 12.3228 3.16306 12.6582 4.00391C13.2421 5.4677 13.6484 7.47243 13.7422 9.74512H7.75781C7.85157 7.47243 8.25795 5.4677 8.8418 4.00391C9.17723 3.16307 9.55179 2.55034 9.91602 2.16406C10.2783 1.77989 10.5605 1.68751 10.75 1.6875ZM9.45703 0C9.08395 0.196288 8.74971 0.467955 8.45703 0.77832C7.86017 1.41125 7.36804 2.27249 6.97656 3.25391C6.28392 4.99045 5.84266 7.25831 5.74805 9.74316H0C0.467158 4.66431 4.43216 0.601428 9.45703 0ZM12.043 0C17.0679 0.6014 21.0328 4.66428 21.5 9.74316H15.7529C15.6582 7.25831 15.2161 4.99045 14.5234 3.25391C14.132 2.2725 13.6398 1.41125 13.043 0.77832C12.7503 0.467946 12.4161 0.196287 12.043 0Z"
                                                        fill="#80828D" />
                                                </svg>
                                            </a>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="">
                        <?php
                        $fetcher_stats = new Dezon_Remote_Project_Fetcher();
                        $supplier_id_stats = get_the_ID();
                        $total_projects = $fetcher_stats->count_total_projects( $supplier_id_stats );
                        $total_partners = $fetcher_stats->count_total_partners( $supplier_id_stats );
                        $product_args = get_supplier_product_args( $supplier_id_stats, 0, 1, 1 ); 
                        $product_query_stats = new WP_Query( $product_args );
                        $total_products = $product_query_stats->found_posts;
                        wp_reset_postdata();
                    ?>

                    <div class="my-3">
                        <div>
                            <p class="cl-gray-2 mb-1">
                                Dự án hoàn tất: 
                                <span class="cl-black fw-bold"><?php echo number_format_i18n($total_projects); ?></span> 
                                (<?php echo number_format_i18n($total_partners); ?> đối tác tham gia)
                            </p>
                            <p class="cl-gray-2 mb-0">
                                Sản phẩm: 
                                <span class="cl-black fw-bold"><?php echo number_format_i18n($total_products); ?></span>
                            </p>
                        </div>
                    </div>
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <a href=""><img
                                        src="<?php echo get_template_directory_uri(); ?>/assets/images/dezon-ai.png"
                                        style="width: auto; height: 40px;" alt=""></a>
                            </div>
                            <div class="btn-style d-none me-2">
                                <a class="d-flex align-items-center" href="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                                        fill="none">
                                        <path
                                            d="M8.95703 0C13.873 0.000104835 17.9131 3.79635 17.9131 8.54492C17.913 13.2934 13.873 17.0888 8.95703 17.0889C7.78438 17.0889 6.66248 16.8738 5.63379 16.4814L2.56152 17.8604C2.07397 18.0788 1.55164 17.6321 1.69141 17.1162L2.43066 14.3867C0.942853 12.8372 4.92236e-05 10.8369 0 8.54492C0 3.79629 4.04093 0 8.95703 0ZM8.95703 1.25098C4.67041 1.25098 1.25 4.54619 1.25 8.54492C1.25005 10.5824 2.13079 12.3677 3.5625 13.751L3.82812 14.0078L3.23828 16.1855L5.60938 15.1221L5.85938 15.2256C6.80597 15.6194 7.85348 15.8389 8.95703 15.8389C13.2435 15.8388 16.663 12.5434 16.6631 8.54492C16.6631 4.54625 13.2435 1.25108 8.95703 1.25098ZM6.45996 9.79199H4.78516V8.125H6.45996V9.79199ZM9.79004 9.79199H8.11523V8.125H9.79004V9.79199ZM13.1201 9.79199H11.4453V8.125H13.1201V9.79199Z"
                                            fill="#1E1F24" />
                                    </svg>
                                    <span class="d-md-block d-none ms-1">Nhắn tin</span>
                                </a>
                            </div>

                            <?php if( $phone_number ): ?>
                            <div class="btn-style me-2">
                                <a href="tel:<?php echo esc_attr($phone_number); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17"
                                        fill="none">
                                        <path
                                            d="M7.1875 4.47656L5.10254 6.97852C5.64983 8.21049 6.1045 8.96974 6.69238 9.55762C7.28026 10.1455 8.03951 10.6002 9.27148 11.1475L11.7734 9.0625L16.25 11.0518V16.25H15.625C10.5287 16.25 6.59117 14.9759 3.93262 12.3174C1.27407 9.65883 2.48436e-09 5.72134 0 0.625V0H5.19824L7.1875 4.47656ZM1.25684 1.25C1.35748 5.83274 2.57659 9.19183 4.81738 11.4326C7.05817 13.6734 10.4173 14.8925 15 14.9932V11.8643L11.9766 10.5205L9.48047 12.6006L9.12891 12.4492C7.65391 11.8171 6.62432 11.2591 5.80762 10.4424C4.99091 9.62568 4.43292 8.59609 3.80078 7.12109L3.64941 6.76953L5.72949 4.27344L4.38574 1.25H1.25684ZM8.95801 0C12.9851 0 16.25 3.26492 16.25 7.29199H15C15 3.95527 12.2947 1.25 8.95801 1.25V0ZM9.375 2.91699C11.5611 2.91699 13.333 4.68887 13.333 6.875H12.083C12.083 5.37923 10.8708 4.16699 9.375 4.16699V2.91699Z"
                                            fill="#1E1F24" />
                                    </svg>
                                </a>
                            </div>
                            <?php endif; ?>

                            <div class="btn-style me-2">
                                <a href="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="17" viewBox="0 0 18 17"
                                        fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M8.95737 1.24809C8.94527 1.23834 8.93295 1.22849 8.92041 1.21855C8.61253 0.974531 8.16275 0.669156 7.59368 0.425069C6.43888 -0.0702455 4.80704 -0.302804 2.94702 0.694775C0.628252 1.9384 -0.509733 4.91538 0.218527 7.98128C0.955171 11.0825 3.57256 14.2762 8.73822 16.2103L8.95738 16.2924L9.17653 16.2103C14.3422 14.2762 16.9596 11.0825 17.6962 7.98128C18.4244 4.91538 17.2864 1.9384 14.9676 0.694783C13.1076 -0.302795 11.4758 -0.0702416 10.321 0.425076C9.75195 0.669163 9.30218 0.974539 8.99431 1.21856C8.98177 1.2285 8.96946 1.23835 8.95737 1.24809ZM7.10094 1.57386C6.22986 1.20023 5.00423 1.00987 3.53783 1.79634C1.82767 2.71355 0.806537 5.04795 1.43469 7.6924C2.04508 10.2621 4.24115 13.129 8.95738 14.9562C13.6736 13.129 15.8696 10.2621 16.48 7.69241C17.1081 5.04797 16.087 2.71356 14.3768 1.79635C12.9104 1.00987 11.6848 1.20024 10.8138 1.57386C10.3699 1.76424 10.0147 2.00483 9.77077 2.19816C9.64942 2.29434 9.55743 2.3774 9.49767 2.43435C9.46784 2.46277 9.4462 2.48454 9.43311 2.49801L9.41998 2.51171C9.41944 2.51228 9.41903 2.51272 9.41876 2.51301C9.41861 2.51318 9.41849 2.5133 9.41843 2.51338C9.41842 2.51338 9.41841 2.51339 9.4184 2.5134L9.41835 2.51346L8.95739 3.02103L8.49642 2.51347C8.49642 2.51348 8.49628 2.51332 8.49599 2.51301C8.49572 2.51272 8.49532 2.51229 8.49478 2.51172L8.48165 2.49802C8.46856 2.48455 8.44692 2.46278 8.41709 2.43435C8.35733 2.37741 8.26533 2.29435 8.14397 2.19817C7.90005 2.00483 7.5448 1.76424 7.10094 1.57386Z"
                                            fill="#1E1F24" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="supplier_menu">
    <div class="container">
        <ul class="scroll-menu"> 
            <li class="current-menu-item"><a href="#gioi-thieu">Giới thiệu</a></li>
            <li><a href="#du-an">Dự án</a></li>
            <li><a href="#san-pham">Sản phẩm</a></li>
            <li><a href="#doi-tac">Đối tác</a></li>
            <li><a href="#hinh-anh">Hình ảnh</a></li>
            <li><a href="#video">Video</a></li>
            <li><a href="#tu-van">Tư vấn</a></li>
            <li><a href="#catalogue">Catalogue</a></li>
            <li><a href="#showroom">Showroom</a></li>
        </ul>
    </div>
</div>
</div>



<div id="gioi-thieu" class="desc-supp section-padding pb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 mb-4 mb-lg-0">
                <div class="sticky-top sticky-intro-label">
                    <span class="fs-18 fw-bold">Giới thiệu</span>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="project-content mb-5 border-bottom-0 pb-0 position-relative">

                    <div class="project_desc_wrapper collapsed">
                        <?php the_content(); ?>
                    </div>

                    <div class="desc_overlay"></div>

                    <div class="text-center mt-4 position-relative" style="z-index: 5;">
                        <a href="#" class="btn btn-black rounded-pill fs-14 px-4 btn_view_more btn-intro-view">
                            Xem thêm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
    // PHP Logic ban đầu
    $remote_fetcher = new Dezon_Remote_Project_Fetcher();
    $current_supplier_id = get_the_ID(); 

    // Lấy 6 dự án đầu tiên (Offset 0)
    $related_projects = $remote_fetcher->get_projects_by_supplier( $current_supplier_id, 6, 0 );

    // Chỉ hiển thị section nếu có dự án
    if ( ! empty( $related_projects ) ) : 
    ?>

<div id="du-an" class="project_tt section-padding pb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 mb-4 mb-lg-0">
                <div class="sticky-top sticky-intro-label">
                    <span class="fs-18 fw-bold">Dự án tham gia</span>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="row gy-4" id="supplier-project-list">
                    <?php 
                        foreach ( $related_projects as $project ) : 
                            get_template_part( 'template-parts/project', 'item', array( 'project' => $project ) );
                        endforeach; 
                        ?>
                </div>

                <?php if ( count($related_projects) === 6 ) : // Chỉ hiện nút nếu lấy đủ 6 bài (nghĩa là có thể còn bài nữa) ?>
                <div class="text-center mt-4 position-relative" style="z-index: 5;">
                    <button type="button" id="btn-load-more-projects"
                        class="btn btn-black rounded-pill fs-14 px-4 btn_view_more btn-intro-view"
                        data-supplier="<?php echo $current_supplier_id; ?>" data-page="2"> Xem thêm
                    </button>
                    <div id="loading-spinner" class="mt-2" style="display:none;">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang
                        tải...
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
    $('#btn-load-more-projects').on('click', function(e) {
        e.preventDefault();
        var button = $(this);
        var spinner = $('#loading-spinner');
        var supplier_id = button.data('supplier');
        var page = button.data('page');
        button.hide();
        spinner.show();

        $.ajax({
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            type: 'POST',
            data: {
                action: 'load_more_supplier_projects',
                supplier_id: supplier_id,
                page: page
            },
            success: function(response) {
                spinner.hide();
                if (response.success) {
                    $('#supplier-project-list').append(response.data.html);
                    button.data('page', page + 1);
                    if (response.data.has_more) {
                        button.show();
                    } else {
                        button.remove();
                    }
                } else {
                    button.remove();
                }
            },
            error: function() {
                spinner.hide();
                button.show();
                alert('Có lỗi xảy ra, vui lòng thử lại.');
            }
        });
    });
});
</script>
<?php endif; ?>


<?php


    $supplier_id = get_the_ID();
    $all_products_args = get_supplier_product_args( $supplier_id, 0, 1, -1 );
    $all_products_args['fields'] = 'ids'; 
    $all_product_ids = get_posts( $all_products_args );
    $related_cats = array();
    $terms_by_parent = array(); 
    $term_counts = array(); 

    if ( ! empty( $all_product_ids ) ) {
        $terms = wp_get_object_terms( $all_product_ids, 'product_cat', array( 'fields' => 'all' ) );
        
        if ( ! is_wp_error( $terms ) ) {
            foreach ( $all_product_ids as $pid ) {
                $cats = get_the_terms( $pid, 'product_cat' );
                if ( $cats ) {
                    foreach ( $cats as $c ) {
                        if ( ! isset( $term_counts[ $c->term_id ] ) ) $term_counts[ $c->term_id ] = 0;
                        $term_counts[ $c->term_id ]++;
                    }
                }
            }

            $term_map = array();
            foreach ($terms as $t) $term_map[$t->term_id] = $t;

            // Duyệt qua counts để cộng lên cha
            foreach ($term_counts as $tid => $count) {
                if(isset($term_map[$tid])) {
                    $parent = $term_map[$tid]->parent;
                    while($parent != 0 && isset($term_map[$parent])) {
                        if(!isset($term_counts[$parent])) $term_counts[$parent] = 0;
                        if(!isset($term_counts[$parent])) $term_counts[$parent] = 0; 
                        
                        $parent = $term_map[$parent]->parent;
                    }
                }
            }

            // C. Nhóm theo Parent
            foreach ( $terms as $term ) {
                if ( isset( $term_counts[ $term->term_id ] ) ) {
                    $term->custom_count = $term_counts[ $term->term_id ];
                    $terms_by_parent[ $term->parent ][] = $term;
                }
            }
        }
    }

    $initial_args = get_supplier_product_args( $supplier_id, 0, 1, 8 );
    $products_query = new WP_Query( $initial_args );
    ?>

<div id="san-pham" class="product_tt section-padding pb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 mb-4 mb-lg-0">
                <div class="sticky-top sticky-intro-label">
                    <span class="fs-18 fw-bold">Sản phẩm</span>

                    <div class="product-sidebar-filter mt-3">
                        <div class="filter-title">
                            <i class="fa fa-tag me-2" style="font-size: 14px;"></i> Products
                        </div>

                        <ul class="product-category-list" id="supplier-cat-filter">
                            <li class="active cat-item-all" data-id="0">
                                <a href="#">
                                    <span>All</span>
                                    <span class="count-badge"><?php echo count($all_product_ids); ?></span>
                                </a>
                            </li>

                            <?php 
                                if ( isset( $terms_by_parent[0] ) ) {
                                    render_supplier_cats_recursive( 0, $terms_by_parent ); 
                                }
                                ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="row gy-4 gx-3" id="supplier-product-list">
                    <?php
                        if ( $products_query->have_posts() ) :
                            while ( $products_query->have_posts() ) :
                                $products_query->the_post();
                                global $product;
                                get_template_part( 'template-parts/supplier-product', 'item' );
                            endwhile;
                            wp_reset_postdata();
                        else :
                            echo '<p class="text-center w-100 mt-4">Không có sản phẩm nào.</p>';
                        endif;
                        ?>
                </div>

                <?php if ( $products_query->max_num_pages > 1 ) : ?>
                <div class="text-center mt-4 position-relative" style="z-index: 5;">
                    <button type="button" id="btn-load-more-products"
                        class="btn btn-black rounded-pill fs-14 px-4 btn_view_more btn-intro-view"
                        data-supplier="<?php echo $supplier_id; ?>" data-page="1"
                        data-max="<?php echo $products_query->max_num_pages; ?>" data-cat="0"> Xem thêm
                    </button>
                    <div id="prod-loading" class="mt-2" style="display:none;">
                        <span class="spinner-border spinner-border-sm" role="status"></span> Đang tải...
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {

    var btnMore = $('#btn-load-more-products');
    var listContainer = $('#supplier-product-list');
    var loading = $('#prod-loading');
    $('#supplier-cat-filter').on('click', 'li > a', function(e) {
        e.preventDefault();
        e.stopPropagation(); // Ngăn sự kiện nổi lên cha

        var clickedLi = $(this).parent('li');

        // UI Active: Xóa active cũ, thêm active mới
        $('#supplier-cat-filter li').removeClass('active');
        clickedLi.addClass('active');

        // Lấy dữ liệu
        var cat_id = clickedLi.data('id');
        var supplier_id = btnMore.data('supplier') || <?php echo $supplier_id; ?>;

        // Reset nút Load More
        btnMore.data('page', 1);
        btnMore.data('cat', cat_id);

        // Loading effect
        listContainer.css('opacity', '0.5');

        // AJAX
        $.ajax({
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            type: 'POST',
            data: {
                action: 'filter_supplier_products',
                supplier_id: supplier_id,
                category_id: cat_id,
                page: 1
            },
            success: function(res) {
                listContainer.css('opacity', '1');
                if (res.success) {
                    listContainer.html(res.data.html);

                    // Update max page & show/hide button
                    btnMore.data('max', res.data.max_page);
                    if (res.data.max_page > 1) {
                        btnMore.show();
                    } else {
                        btnMore.hide();
                    }
                }
            }
        });
    });

    // 2. Xử lý Load More
    btnMore.on('click', function(e) {
        e.preventDefault();

        var current_page = $(this).data('page');
        var max_page = $(this).data('max');
        var cat_id = $(this).data('cat');
        var supplier_id = $(this).data('supplier');

        if (current_page >= max_page) return;

        var next_page = current_page + 1;

        btnMore.hide();
        loading.show();

        $.ajax({
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            type: 'POST',
            data: {
                action: 'filter_supplier_products',
                supplier_id: supplier_id,
                category_id: cat_id,
                page: next_page
            },
            success: function(res) {
                loading.hide();
                if (res.success) {
                    listContainer.append(res.data.html);
                    btnMore.data('page', next_page);

                    if (next_page < res.data.max_page) {
                        btnMore.show();
                    } else {
                        btnMore.hide();
                    }
                }
            }
        });
    });

});
</script>

<?php
    $current_supplier_id = get_the_ID();
    $initial_limit = 15; // Mặc định Desktop load 15 bài đầu tiên

    $fetcher = new Dezon_Remote_Project_Fetcher();
    // Load lần đầu (Offset 0)
    $partners = $fetcher->get_partners_via_supplier_projects( $current_supplier_id, $initial_limit, 0 );

    if ( !empty($partners) ) :
        $partner_chunks = array_chunk($partners, ceil(count($partners)/3));
        $partner_chunks = array_pad($partner_chunks, 3, []); 
    ?>

<div id="doi-tac" class="partner_tt section-padding pb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 mb-4 mb-lg-0">
                <div class="sticky-top sticky-intro-label">
                    <span class="fs-18 fw-bold">Được tin tưởng bởi các đối tác</span>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="row">
                    <?php 
                        foreach ($partner_chunks as $index => $column_partners): 
                            $col_id = 'partner-col-' . $index;
                            // QUAN TRỌNG: Không thêm d-none bằng PHP nữa để tránh lỗi ẩn dữ liệu
                            // Thay vào đó chỉ thêm class partner-col để JS xử lý
                            $col_class = 'col-xl-4 col-md-4 partner-col'; 
                            
                            // Chỉ ẩn trên mobile ở lần load ĐẦU TIÊN (CSS inline hoặc class riêng)
                            // Nếu bạn muốn mobile hiện cả 15 bài đầu tiên thì bỏ dòng if dưới đi
                            if ($index > 0) $col_class .= ' d-md-block d-none';
                        ?>
                    <div class="<?php echo esc_attr($col_class); ?>" id="<?php echo esc_attr($col_id); ?>">
                        <div class="list_partner mb-xl-0">
                            <?php 
                                    if (!empty($column_partners)) :
                                        foreach ($column_partners as $p) : 
                                            // ... (Render item giữ nguyên) ...
                                            ?>
                            <a href="<?php echo esc_url($p['link']); ?>" target="_blank" class="text-reset"
                                title="<?php echo esc_attr($p['title']); ?>">
                                <div class="d-flex mb-3 align-items-center">
                                    <img src="<?php echo esc_url($p['logo']); ?>"
                                        style="width: auto; height: 20px; object-fit: contain;"
                                        alt="<?php echo esc_attr($p['title']); ?>">
                                    <p class="mb-0 ms-2 text-truncate"><?php echo esc_html($p['title']); ?></p>
                                </div>
                            </a>
                            <?php
                                        endforeach; 
                                    endif;
                                    ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if (count($partners) >= $initial_limit) : ?>
                <div class="text-center mt-4 position-relative" style="z-index: 5;">
                    <button type="button" id="btn-load-more-partners"
                        class="btn btn-black rounded-pill fs-14 px-4 btn_view_more btn-intro-view"
                        data-supplier="<?php echo $current_supplier_id; ?>" data-offset="<?php echo $initial_limit; ?>">
                        Xem thêm
                    </button>

                    <div id="partner-loading" class="mt-2" style="display:none;">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang
                        tải...
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
    $('#btn-load-more-partners').on('click', function(e) {
        e.preventDefault();

        var button = $(this);
        var spinner = $('#partner-loading');
        var supplier_id = button.data('supplier');
        var current_offset = button.data('offset');

        // --- LOGIC PHÁT HIỆN MOBILE ---
        // Nếu màn hình < 768px thì limit = 5, ngược lại 15
        var isMobile = $(window).width() < 768;
        var dynamic_limit = isMobile ? 5 : 15;

        // In ra console để kiểm tra (F12)
        console.log('Đang chạy trên mobile?', isMobile);
        console.log('Số lượng sẽ load:', dynamic_limit);

        button.hide();
        spinner.show();

        $.ajax({
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            type: 'POST',
            data: {
                action: 'load_more_partners',
                supplier_id: supplier_id,
                offset: current_offset,
                limit: dynamic_limit // Gửi số 5 hoặc 15 lên Server
            },
            success: function(response) {
                spinner.hide();

                if (response.success) {
                    var items = response.data.items;
                    var loaded_count = response.data.loaded;

                    // Gỡ class ẩn để Mobile thấy dữ liệu ở cột 2, 3 (nếu có chia vào đó)
                    $('.partner-col').removeClass('d-none');

                    $.each(items, function(index, html) {
                        var colIndex = index % 3;
                        $('#partner-col-' + colIndex + ' .list_partner').append(
                            html);
                    });

                    // Cập nhật Offset mới
                    button.data('offset', current_offset + loaded_count);

                    if (response.data.has_more) {
                        button.show();
                    } else {
                        button.remove();
                    }
                } else {
                    button.remove();
                }
            },
            error: function() {
                spinner.hide();
                button.show();
                alert('Lỗi kết nối.');
            }
        });
    });
});
</script>
<?php endif; ?>



<?php
$current_supplier_id = get_the_ID();
$initial_limit = 8;

$all_images = get_supplier_gallery_flattened( $current_supplier_id );

if ( ! empty( $all_images ) ) : 
    $initial_images = array_slice( $all_images, 0, $initial_limit );
    $total_images   = count($all_images);
?>

<div id="hinh-anh" class="figure_tt section-padding pb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 mb-4 mb-lg-0">
                <div class="sticky-top sticky-intro-label">
                    <span class="fs-18 fw-bold">Hình ảnh</span>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="row g-3" id="supplier-gallery-grid">
                    <?php foreach ( $initial_images as $img ) : ?>
                    <div class="col-lg-3 col-6">
                        <figure class="mb-0 rounded-2 overflow-hidden position-relative hover-zoom"
                            style="padding-bottom: 66.66%;">
                            <a href="<?php echo esc_url($img['url']); ?>" data-fancybox="supplier-gallery"
                                class="d-block w-100 h-100 position-absolute top-0 start-0">
                                <img src="<?php echo esc_url($img['thumb']); ?>"
                                    class="w-100 h-100 object-fit-cover transition-transform"
                                    alt="<?php echo esc_attr($img['alt']); ?>">
                            </a>
                        </figure>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if ( $total_images > $initial_limit ) : ?>
                <div class="text-center mt-4 position-relative" style="z-index: 5;">
                    <button type="button" id="btn-load-more-gallery"
                        class="btn btn-black rounded-pill fs-14 px-4 btn_view_more btn-intro-view"
                        data-supplier="<?php echo $current_supplier_id; ?>" data-offset="<?php echo $initial_limit; ?>">
                        Xem thêm
                    </button>

                    <div id="gallery-loading" class="mt-2" style="display:none;">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang
                        tải...
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
    $('#btn-load-more-gallery').on('click', function(e) {
        e.preventDefault();

        var button = $(this);
        var spinner = $('#gallery-loading');
        var supplier_id = button.data('supplier');
        var current_offset = button.data('offset');

        button.hide();
        spinner.show();

        $.ajax({
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            type: 'POST',
            data: {
                action: 'load_more_supplier_gallery',
                supplier_id: supplier_id,
                offset: current_offset
            },
            success: function(response) {
                spinner.hide();

                if (response.success) {
                    var items = response.data.items;
                    var loaded_count = response.data.loaded;

                    // Append từng ảnh vào lưới
                    $.each(items, function(index, html) {
                        // Hiệu ứng Fade in nhẹ (nếu muốn CSS)
                        var $html = $(html).hide();
                        $('#supplier-gallery-grid').append($html);
                        $html.fadeIn(400);
                    });

                    // Cập nhật Offset
                    button.data('offset', current_offset + loaded_count);

                    // Kiểm tra còn ảnh không
                    if (response.data.has_more) {
                        button.show();
                    } else {
                        button.remove(); // Hết ảnh thì xóa nút
                    }
                } else {
                    button.remove();
                }
            },
            error: function() {
                spinner.hide();
                button.show();
                alert('Lỗi tải ảnh.');
            }
        });
    });
});
</script>

<?php endif; ?>

<?php
// 1. Lấy dữ liệu Repeater
$current_supplier_id = get_the_ID();
$all_videos = get_field('video');
$initial_limit = 4;

if ($all_videos) :
    // Cắt lấy 4 video đầu tiên
    $initial_videos = array_slice($all_videos, 0, $initial_limit);
    $total_videos = count($all_videos);
?>

<div id="video" class="video_tt section-padding pb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 mb-4 mb-lg-0">
                <div class="sticky-top sticky-intro-label">
                    <span class="fs-18 fw-bold">Video</span>
                </div>
            </div>

            <div class="col-lg-9">

                <div class="podcast_list">
                    <div class="row gy-4" id="video-list-ajax">

                        <?php 
                        foreach ($initial_videos as $row) : 
                            $item = isset($row['manual_content']) ? $row['manual_content'] : [];
                            if (empty($item)) continue;

                            $title  = isset($item['title']) ? $item['title'] : '';
                            $minute = isset($item['minute']) ? $item['minute'] : '';
                            $image  = isset($item['image']) ? $item['image'] : '';
                            
                            // Xử lý Link Video
                            $type = isset($item['manual_type']) ? $item['manual_type'] : '';
                            $video_url = '#';

                            if ($type === 'link') {
                                $video_url = isset($item['link_video']) ? $item['link_video'] : '#';
                            } elseif ($type === 'file') {
                                $file_data = isset($item['file_video']) ? $item['file_video'] : '';
                                if (is_array($file_data)) {
                                    $video_url = isset($file_data['url']) ? $file_data['url'] : '#';
                                } else {
                                    $video_url = $file_data;
                                }
                            }

                            // Xử lý Ảnh Thumbnail ($img_url)
                            $img_url = '';
                            if (is_array($image)) {
                                $img_url = isset($image['url']) ? $image['url'] : '';
                            } else {
                                $img_url = $image;
                            }
                            // Ảnh mặc định nếu không có ảnh
                            if (empty($img_url)) $img_url = get_template_directory_uri() . '/assets/images/gallery1.jpg'; 
                        ?>

                        <div class="col-lg-3">
                            <div class="item">
                                <figure class="mb-0 position-relative rounded-3 overflow-hidden">
                                    <a href="<?php echo esc_url($video_url); ?>" 
                                    class="d-block w-100 position-relative ratio-2-3"
                                    data-fancybox="supplier-video" 
                                    data-caption="<?php echo esc_attr($title); ?>">
                                    
                                        <img src="<?php echo esc_url($img_url); ?>"
                                            class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover img-zoom-hover"
                                            alt="<?php echo esc_attr($title); ?>">

                                        <div class="overlay-gradient-bottom">
                                            <h4 class="fs-24 fw-medium text-white mb-3 pe-auto" style="line-height: 1.4;">
                                                <span class="text-white text-decoration-none">
                                                    <?php echo esc_html($title); ?>
                                                </span>
                                            </h4>

                                            <div class="d-flex justify-content-between align-items-center pe-auto">
                                                <ul class="controls list-unstyled d-flex align-items-center gap-2 mb-0">
                                                    <li>
                                                        <span class="btn-play-video">
                                                            <i class="fa fa-play fs-12 ms-1"></i>
                                                        </span>
                                                    </li>
                                                    <?php if ($minute) : ?>
                                                    <li>
                                                        <span class="time-badge"><?php echo esc_html($minute); ?> phút</span>
                                                    </li>
                                                    <?php endif; ?>
                                                </ul>
                                                <div class="action">
                                                    <span class="btn-action-circle">
                                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/dots.svg" class="img-fluid"
                                                            style="height: 14px;" alt="More">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a> </figure>
                            </div>
                        </div>

                        <?php endforeach; ?>

                    </div>
                </div>

                <?php if ($total_videos > $initial_limit) : ?>
                <div class="text-center mt-4 position-relative" style="z-index: 5;">
                    <button type="button" id="btn-load-more-videos" 
                            class="btn btn-black rounded-pill fs-14 px-4 btn_view_more btn-intro-view"
                            data-supplier="<?php echo $current_supplier_id; ?>"
                            data-offset="<?php echo $initial_limit; ?>">
                        Xem thêm
                    </button>
                    
                    <div id="video-loading" class="mt-2" style="display:none;">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 
                        Đang tải...
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
    $('#btn-load-more-videos').on('click', function(e) {
        e.preventDefault();

        var button = $(this);
        var spinner = $('#video-loading');
        var supplier_id = button.data('supplier');
        var current_offset = button.data('offset');

        button.hide();
        spinner.show();

        $.ajax({
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            type: 'POST',
            data: {
                action: 'load_more_videos',
                supplier_id: supplier_id,
                offset: current_offset
            },
            success: function(response) {
                spinner.hide();

                if (response.success) {
                    var items = response.data.items;
                    var loaded_count = response.data.loaded;

                    // Append từng item
                    $.each(items, function(index, html) {
                        var $html = $(html).hide();
                        $('#video-list-ajax').append($html);
                        $html.fadeIn(400);
                    });

                    // Cập nhật offset
                    button.data('offset', current_offset + loaded_count);

                    // Kiểm tra còn video không
                    if (response.data.has_more) {
                        button.show();
                    } else {
                        button.remove();
                    }
                } else {
                    button.remove();
                }
            },
            error: function() {
                spinner.hide();
                button.show();
                alert('Lỗi tải video.');
            }
        });
    });
});
</script>

<?php endif; ?>

<div id="tu-van" class="news_tt section-padding pb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 mb-4 mb-lg-0">
                <div class="sticky-top sticky-intro-label">
                    <span class="fs-18 fw-bold">Tư vấn</span>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="space_post">

                    <div class="row list_post" data-id="<?php echo get_the_ID(); ?>">
                        <?php
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    $related_ids = get_field('advise', get_the_ID(), false);

                    if ($related_ids) :
                        $args = array(
                            'post_type'      => 'post',
                            'post__in'       => $related_ids,
                            'orderby'        => 'post__in',
                            'posts_per_page' => 6,
                            'paged'          => $paged
                        );
                        $the_query = new WP_Query($args);

                        if ($the_query->have_posts()) :
                            while ($the_query->have_posts()) : $the_query->the_post();
                                $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                                if (!$thumb_url) $thumb_url = get_template_directory_uri() . '/assets/images/cate1.jpg';
                                
                                $categories = get_the_category();
                                $cat_name = !empty($categories) ? $categories[0]->name : 'Giải pháp kiến trúc';
                                $cat_link = !empty($categories) ? get_category_link($categories[0]->term_id) : '#';
                                ?>
                        <div class="col-lg-4 mb-4">
                            <div class="post_item">
                                <figure>
                                    <a href="<?php the_permalink(); ?>">
                                        <img src="<?php echo esc_url($thumb_url); ?>" class="img-fluid"
                                            alt="<?php the_title(); ?>">
                                    </a>
                                </figure>
                                <div class="meta_info">
                                    <div class="row">
                                        <div class="col-auto">
                                            <div class="post_cate">
                                                <?php
                                                $cats = get_the_category();
                                                if ( ! empty($cats) ) {
                                                     $cat = $cats[0];
                                                     if ( $cat->parent ) {
                                                         $ancestors = get_ancestors($cat->term_id, 'category');
                                                         $root_id   = end($ancestors);
                                                         $cat       = get_category($root_id);
                                                    }
                                                    echo '<a href="' . esc_url(get_category_link($cat->term_id)) . '">' . esc_html($cat->name) . '</a>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="time_reading"><?php echo get_decox_reading_time(); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="like_post">
                                                <a href=""><i class="fa fa-heart-o" aria-hidden="true"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <h4 class="fs-20 fw-semibold post_title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h4>
                                    <div class="desc fs-14 cl-gray400">
                                        <p><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                            endwhile;
                        endif;
                    else:
                        echo '<div class="col-12"><p>Chưa có bài viết nào.</p></div>';
                    endif;
                    ?>
                    </div>

                    <div class="j_paging mt-3 mb-5 d-lg-block d-none">
                        <div class="wp-pagenavi">
                            <?php
                        if (isset($the_query) && $the_query->max_num_pages > 1) {
                            echo paginate_links(array(
                                'base'      => '%_%',
                                'format'    => '?paged=%#%',
                                'total'     => $the_query->max_num_pages,
                                'current'   => $paged,
                                'prev_text' => '<img src="'.get_template_directory_uri().'/assets/images/first.svg" class="img-fluid" alt="">',
                                'next_text' => '<img src="'.get_template_directory_uri().'/assets/images/last.svg" class="img-fluid" alt="">',
                                'type'      => 'plain',
                            ));
                        }
                        ?>
                        </div>
                    </div>
                    <?php if(isset($the_query)) wp_reset_postdata(); ?>

                </div>
            </div>
        </div>
    </div>
</div>


<?php
    // 1. Lấy dữ liệu
    $current_supplier_id = get_the_ID();
    $initial_limit = 12; // Desktop load 12

    $documents = get_supplier_documents_flattened( $current_supplier_id );

    if ( ! empty( $documents ) ) : 
        // Cắt 12 item đầu tiên
        $display_docs = array_slice( $documents, 0, $initial_limit );
        
        // Chia đều vào 3 cột
        $doc_chunks = array_chunk($display_docs, ceil(count($display_docs)/3));
        $doc_chunks = array_pad($doc_chunks, 3, []);
    ?>

<div id="catalogue" class="document_tt section-padding pb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 mb-4 mb-lg-0">
                <div class="sticky-top sticky-intro-label">
                    <span class="fs-18 fw-bold">Tài liệu và Catalogue</span>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="row">
                    <?php foreach ($doc_chunks as $index => $column_docs): 
                            $col_id = 'doc-col-' . $index;
                            $col_class = 'col-xl-4 col-md-4 doc-col';
                            if ($index > 0) $col_class .= ' d-md-block d-none';
                        ?>
                    <div class="<?php echo esc_attr($col_class); ?>" id="<?php echo esc_attr($col_id); ?>">
                        <div class="doc-list-wrapper">
                            <?php if (!empty($column_docs)) : foreach ($column_docs as $doc) : ?>
                            <div class="list_partner mb-xl-0 cl-blue mb-3">
                                <a href="<?php echo esc_url($doc['url']); ?>" target="_blank" class="text-reset"
                                    title="<?php echo esc_attr($doc['title']); ?>">
                                    <div class="d-flex mb-3 align-items-center">
                                        <img src="<?php echo esc_url($doc['icon']); ?>"
                                            style="width: auto; height: 20px; object-fit: contain;" alt="">
                                        <p class="mb-0 ms-2 text-truncate"><?php echo esc_html($doc['title']); ?></p>
                                    </div>
                                </a>
                            </div>
                            <?php endforeach; endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if ( count($documents) > $initial_limit ) : ?>
                <div class="text-center mt-4 position-relative" style="z-index: 5;">
                    <button type="button" id="btn-load-more-docs"
                        class="btn btn-black rounded-pill fs-14 px-4 btn_view_more btn-intro-view"
                        data-supplier="<?php echo $current_supplier_id; ?>" data-offset="<?php echo $initial_limit; ?>">
                        Xem thêm
                    </button>

                    <div id="doc-loading" class="mt-2" style="display:none;">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang
                        tải...
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
    $('#btn-load-more-docs').on('click', function(e) {
        e.preventDefault();

        var button = $(this);
        var spinner = $('#doc-loading');
        var supplier_id = button.data('supplier');
        var current_offset = button.data('offset');

        // --- LOGIC MOBILE ---
        var isMobile = $(window).width() < 768;
        var dynamic_limit = isMobile ? 4 : 12;

        button.hide();
        spinner.show();

        $.ajax({
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            type: 'POST',
            data: {
                action: 'load_more_documents',
                supplier_id: supplier_id,
                offset: current_offset,
                limit: dynamic_limit
            },
            success: function(response) {
                spinner.hide();
                if (response.success) {
                    var items = response.data.items;
                    var loaded_count = response.data.loaded;

                    // Gỡ class ẩn để Mobile thấy dữ liệu ở cột 2, 3
                    $('.doc-col').removeClass('d-none');

                    // Chia đều item vào 3 cột
                    $.each(items, function(index, html) {
                        var colIndex = index % 3;
                        $('#doc-col-' + colIndex + ' .doc-list-wrapper').append(
                            html);
                    });

                    button.data('offset', current_offset + loaded_count);

                    if (response.data.has_more) {
                        button.show();
                    } else {
                        button.remove();
                    }
                } else {
                    button.remove();
                }
            },
            error: function() {
                spinner.hide();
                button.show();
                alert('Lỗi kết nối.');
            }
        });
    });
});
</script>

<?php endif; ?>


<div id="showroom" class="map_site">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 mb-4 mb-lg-0">
                <div class="sticky-top sticky-intro-label">
                    <span class="fs-18 fw-bold">Địa chỉ và Showroom</span>
                </div>
            </div>
            <div class="col-lg-9">
            </div>
        </div>

        <?php
        // 1. Lấy dữ liệu từ ACF
        $map = get_field('space_map', get_the_ID());

        // 2. Kiểm tra xem có dữ liệu không
        if ($map && (!empty($map['address_top']['title']) || !empty($map['address_buttom_1']['title']))) :
            
            // Logic Active Tab: Nếu Tab 1 (Top) có dữ liệu thì nó Active, ngược lại check Tab 2
            $has_top = !empty($map['address_top']['title']);
            $active_top_nav = $has_top ? 'active' : '';
            $active_top_content = $has_top ? 'show active' : '';
            
            $active_bottom_nav = (!$has_top) ? 'active' : '';
            $active_bottom_content = (!$has_top) ? 'show active' : '';
        ?>
        <div class="space_map">
            <div class="map_wrapper">

                <div class="nav" id="v-pills-tab" role="tablist" aria-orientation="vertical">

                    <?php if (!empty($map['address_top']['title'])) : ?>
                    <a href="javascript:void(0);" class="nav-link <?php echo $active_top_nav; ?>" id="v-pills-home-tab"
                        data-bs-toggle="pill" data-bs-target="#v-pills-home" role="tab" aria-controls="v-pills-home"
                        aria-selected="<?php echo $has_top ? 'true' : 'false'; ?>">

                        <h4 class="fs-18 fw-semibold"><?php echo esc_html($map['address_top']['title']); ?></h4>

                        <?php if (!empty($map['address_top']['address'])) : ?>
                        <p class="fs-18">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/map.svg"
                                class="img-fluid" alt="">
                            <?php echo esc_html($map['address_top']['address']); ?>
                        </p>
                        <?php endif; ?>
                    </a>
                    <?php endif; ?>

                    <?php if (!empty($map['address_buttom_1']['title'])) : ?>
                    <a href="javascript:void(0);" class="nav-link <?php echo $active_bottom_nav; ?>"
                        id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" role="tab"
                        aria-controls="v-pills-profile" aria-selected="<?php echo (!$has_top) ? 'true' : 'false'; ?>">

                        <h4 class="fs-18 fw-semibold"><?php echo esc_html($map['address_buttom_1']['title']); ?></h4>

                        <?php if (!empty($map['address_buttom_1']['address'])) : ?>
                        <p class="fs-18">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/map.svg"
                                class="img-fluid" alt="">
                            <?php echo esc_html($map['address_buttom_1']['address']); ?>
                        </p>
                        <?php endif; ?>
                    </a>
                    <?php endif; ?>

                </div>

                <div class="tab-content" id="v-pills-tabContent">

                    <?php if (!empty($map['address_top']['link_map_embed'])) : ?>
                    <div class="tab-pane fade <?php echo $active_top_content; ?>" id="v-pills-home" role="tabpanel"
                        aria-labelledby="v-pills-home-tab" tabindex="0">
                        <div class="map_embed">
                            <iframe src="<?php echo esc_url($map['address_top']['link_map_embed']); ?>" width="600"
                                height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($map['address_buttom_1']['link_map_embed'])) : ?>
                    <div class="tab-pane fade <?php echo $active_bottom_content; ?>" id="v-pills-profile"
                        role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="0">
                        <div class="map_embed">
                            <iframe src="<?php echo esc_url($map['address_buttom_1']['link_map_embed']); ?>" width="600"
                                height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php else:
        // =====================================================
        // TỐI ƯU 1: Gom tất cả ACF fields về đầu (1 lần)
        // =====================================================
        $post_id = get_the_ID();
        $banner = get_field('banner', $post_id);
        $cspace_pro = get_field('product', $post_id);
        $abouts = get_field('about', $post_id);
        $contact = get_field('contact', $post_id);
        $map = get_field('space_map', $post_id);

        // =====================================================
        // TỐI ƯU 2: Lấy selected products 1 LẦN DUY NHẤT
        // =====================================================
        $selected_products = array();
        if (have_rows('item_product', $post_id)) {
            while (have_rows('item_product', $post_id)) {
                the_row();
                $products = get_sub_field('item');
                if ($products) {
                    foreach ($products as $product) {
                        $selected_products[] = is_object($product) ? $product->ID : $product;
                    }
                }
            }
        }
        $selected_products = array_unique($selected_products);

        // =====================================================
        // TỐI ƯU 3: Batch query categories từ products (thay vì N+1)
        // =====================================================
        // $category_ids = array();
        // if (!empty($selected_products)) {
        //     global $wpdb;
        //     $product_ids_string = implode(',', array_map('intval', $selected_products));
            
        //     // 1 query thay vì N queries
        //     $category_ids = $wpdb->get_col("
        //         SELECT DISTINCT tt.term_id 
        //         FROM {$wpdb->term_relationships} tr
        //         INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        //         WHERE tr.object_id IN ({$product_ids_string})
        //         AND tt.taxonomy = 'product_cat'
        //     ");
        // }

        // =====================================================
        // TỐI ƯU 4: Pre-fetch product data nếu có selected products
        // =====================================================
        $products_data = array();
        if (!empty($selected_products)) {
            // Prime post cache
            _prime_post_caches($selected_products, true, true);
            
            // Prime meta cache
            update_meta_cache('post', $selected_products);
            
            // Lấy gallery images cho tất cả products 1 lần
            foreach ($selected_products as $pid) {
                $gallery = get_post_meta($pid, '_product_image_gallery', true);
                $gallery_arr = $gallery ? explode(',', $gallery) : array();
                $thumb_id = !empty($gallery_arr[0]) ? (int)$gallery_arr[0] : 0;
                
                $products_data[$pid] = array(
                    'post' => get_post($pid),
                    'thumb_id' => $thumb_id,
                    'thumb_url' => $thumb_id ? wp_get_attachment_image_url($thumb_id, 'large') : wc_placeholder_img_src(),
                );
            }
        }
?>

<?php 
// ==================== BANNER SECTION ====================
if ($banner && !empty(array_filter($banner))) :
    $btn_data = $banner['button'] ?? null;
?>
<div class="space_banner">
    <div class="content_abs">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <?php if (!empty($banner['title'])) : ?>
                    <h1 class="fs-48 fw-medium title_group"><?php echo esc_html($banner['title']); ?></h1>
                    <?php endif; ?>

                    <?php if (!empty($banner['subtitle'])) : ?>
                    <div class="desc fs-20"><?php echo wp_kses_post($banner['subtitle']); ?></div>
                    <?php endif; ?>

                    <?php 
                    if ($btn_data && !empty($btn_data['title'])) :
                        $btn_link = '#';
                        $target = '_self';
                        if (isset($btn_data['link_or_phone']) && $btn_data['link_or_phone'] == 'phone') {
                            if (!empty($btn_data['phone'])) {
                                $btn_link = 'tel:' . $btn_data['phone'];
                            }
                        } else {
                            if (!empty($btn_data['link'])) {
                                $btn_link = $btn_data['link'];
                                $target = '_blank';
                            }
                        }
                    ?>
                    <a href="<?php echo esc_url($btn_link); ?>" target="<?php echo esc_attr($target); ?>"
                        rel="noopener noreferrer" class="btn btn-black fs-18">
                        <?php echo esc_html($btn_data['title']); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php if (!empty($banner['background'])) : ?>
    <img src="<?php echo esc_url($banner['background']); ?>" class="img-fluid"
        alt="<?php echo esc_attr($banner['title'] ?? 'Banner'); ?>" loading="eager" fetchpriority="high">
    <?php endif; ?>
</div>
<?php endif; ?>

<?php 
// ==================== MAIN CONTENT ====================
if ($cspace_pro || $abouts) :
?>
<div class="space_page">
    <?php if (($cspace_pro && !empty($cspace_pro['title'])) || ($abouts && !empty($abouts['title']))) : ?>

    <?php 
        // === XỬ LÝ SLUG ĐỘNG ===
        // 1. Lấy title từ CMS
        $title_1 = $cspace_pro['title'] ?? '';
        $title_2 = $abouts['title'] ?? '';

        // 2. Gọi hàm trong functions.php để tạo slug
        // Nếu title là "Không gian sản phẩm" -> slug là "khong-gian-san-pham"
        $slug_tab_1 = function_exists('get_slug_from_title') ? get_slug_from_title($title_1) : 'tab-1';
        $slug_tab_2 = function_exists('get_slug_from_title') ? get_slug_from_title($title_2) : 'tab-2';
    ?>

    <div class="tab-menu">
        <ul class="nav fs-18 justify-content-center nav-tabs" id="myTab" role="tablist">

            <?php if ($cspace_pro && !empty($cspace_pro['title'])) : ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane"
                    data-fragment="<?php echo esc_attr($slug_tab_1); ?>" type="button" role="tab"
                    aria-controls="home-tab-pane" aria-selected="true">
                    <?php echo esc_html($cspace_pro['title']); ?>
                </button>
            </li>
            <?php endif; ?>

            <?php if ($abouts && !empty($abouts['title'])) : ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo (!$cspace_pro || empty($cspace_pro['title'])) ? 'active' : ''; ?>"
                    id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane"
                    data-fragment="<?php echo esc_attr($slug_tab_2); ?>" type="button" role="tab"
                    aria-controls="profile-tab-pane" aria-selected="false">
                    <?php echo esc_html($abouts['title']); ?>
                </button>
            </li>
            <?php endif; ?>

        </ul>
    </div>
    <?php endif; ?>

    <div class="tab-content" id="myTabContent">
        <!-- TAB 1: Products -->
        <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab"
            tabindex="0">

            <?php if ($cspace_pro && !empty($cspace_pro['space_overview']['image'])) : ?>
            <div class="space_overview">
                <div class="container">
                    <figure>
                        <img src="<?php echo esc_url($cspace_pro['space_overview']['image']); ?>" class="img-fluid"
                            alt="" loading="lazy">
                    </figure>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($cspace_pro && !empty($cspace_pro['space_inspiration']['title'])) : ?>
            <div class="space_inspiration">
                <div class="container">
                    <h3 class="fs-64 fw-medium title_group">
                        <?php echo esc_html($cspace_pro['space_inspiration']['title']); ?>
                    </h3>
                    <div class="inspiration_list">
                        <div class="row row-gap-3">
                            <?php 
                            $inspirations = $cspace_pro['space_inspiration']['inspiration_list'] ?? [];
                            foreach ($inspirations as $item) :
                            ?>
                            <div class="col-lg-4">
                                <div class="inspiration_item">
                                    <figure>
                                        <a href="<?php echo $item['link']; ?>" target="_blank"
                                            rel="noopener noreferrer">
                                            <img src="<?php echo $item['image']; ?>" class="img-fluid" alt=""
                                                loading="lazy">
                                        </a>
                                    </figure>
                                    <div class="meta_info">
                                        <div class="row align-items-end">
                                            <div class="col">
                                                <h4 class="fs-24 fw-bold"><?php echo $item['title']; ?></h4>
                                            </div>
                                            <div class="col-auto">
                                                <a href="" target="_blank" rel="noopener noreferrer">
                                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/arroww.svg"
                                                        class="img-fluid" alt="">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($cspace_pro && !empty($cspace_pro['title_cspace']['title_space_cate'])) : ?>
            <div class="space_cate">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7">
                            <h3 class="fs-48 fw-medium">
                                <?php echo esc_html($cspace_pro['title_cspace']['title_space_cate']); ?></h3>
                        </div>
                        <div class="col-lg-5">
                            <?php if (!empty($cspace_pro['title_cspace']['subtitle_space_cate'])) : ?>
                            <div class="desc fs-18 fw-medium">
                                <?php echo wp_kses_post($cspace_pro['title_cspace']['subtitle_space_cate']); ?>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($cspace_pro['title_cspace']['button_view_cate']['link']) && !empty($cspace_pro['title_cspace']['button_view_cate']['title'])) : ?>
                            <a href="<?php echo esc_url($cspace_pro['title_cspace']['button_view_cate']['link']); ?>"
                                class="btn btn-bgblack fs-18" target="_blank" rel="noopener noreferrer">
                                <?php echo esc_html($cspace_pro['title_cspace']['button_view_cate']['title']); ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/arroww.svg"
                                    class="img-fluid" alt="">
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="all_cate">
                    <div class="row gx-0">
                        <?php
                        // =====================================================
                        // LOGIC: LẤY DANH MỤC CHA CỦA CÁC SẢN PHẨM ĐƯỢC CHỌN
                        // =====================================================
                        $parent_cat_ids = array();

                        if (!empty($selected_products)) {
                            foreach ($selected_products as $pid) {
                                // Lấy tất cả danh mục của sản phẩm
                                $terms = get_the_terms($pid, 'product_cat');
                                
                                if ($terms && !is_wp_error($terms)) {
                                    foreach ($terms as $t) {
                                        // 1. Bỏ qua danh mục "Uncategorized" (ID 15)
                                        if ($t->term_id == 15) continue;

                                        // 2. LOGIC TÌM CHA:
                                        if ($t->parent != 0) {
                                            // Nếu đây là cate CON (có parent), thì lấy ID của CHA nó
                                            $parent_cat_ids[] = $t->parent;
                                        } else {
                                            // Nếu đây đã là cate CHA (không có parent), thì lấy chính nó
                                            $parent_cat_ids[] = $t->term_id;
                                        }
                                    }
                                }
                            }
                        }
                        
                        // 3. KHỬ TRÙNG LẶP (QUAN TRỌNG)
                        // Ví dụ: 3 sản phẩm đều thuộc nhóm "Nội thất", thì chỉ giữ lại 1 ID "Nội thất"
                        $parent_cat_ids = array_unique($parent_cat_ids);

                        // =====================================================
                        // TRUY VẤN VÀ HIỂN THỊ
                        // =====================================================
                        if (!empty($parent_cat_ids)) :
                            $cat_args = array(
                                'taxonomy'   => 'product_cat',
                                'hide_empty' => false,
                                'include'    => $parent_cat_ids, // Chỉ lấy danh sách các cha đã tìm được
                                'orderby'    => 'include'
                            );

                            $product_categories = get_terms($cat_args);

                            if (!empty($product_categories) && !is_wp_error($product_categories)) :
                                // Cache dữ liệu để load nhanh hơn
                                $term_ids = wp_list_pluck($product_categories, 'term_id');
                                update_termmeta_cache($term_ids);
                                
                                foreach ($product_categories as $cat) :
                                    $thumbnail_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
                                    $image_url = $thumbnail_id 
                                        ? wp_get_attachment_image_url($thumbnail_id, 'large') 
                                        : wc_placeholder_img_src();
                                    $term_link = get_term_link($cat);
                        ?>
                        <div class="col-lg-4">
                            <div class="cate_item">
                                <figure>
                                    <a href="<?php echo esc_url($term_link); ?>" target="_blank"
                                        rel="noopener noreferrer">
                                        <img src="<?php echo esc_url($image_url); ?>" class="img-fluid"
                                            alt="<?php echo esc_attr($cat->name); ?>" loading="lazy">
                                    </a>
                                </figure>
                                <div class="meta_info">
                                    <ul class="fs-18 fw-medium">
                                        <li>
                                            <a href="<?php echo esc_url($term_link); ?>" target="_blank"
                                                rel="noopener noreferrer">
                                                <?php echo esc_html($cat->name); ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo esc_url($term_link); ?>" target="_blank"
                                                rel="noopener noreferrer">
                                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/arrow.svg"
                                                    class="img-fluid" alt="">
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php
                                endforeach;
                            endif;
                        else:
                            // Không tìm thấy danh mục nào
                        endif;
                        ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php 
            $com = $cspace_pro['space_comfor'] ?? '';
            if ($com && !empty($com['title'])) :
            ?>
            <div class="space_comfor">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <h3 class="fs-48 fw-medium text-center title_group"><?php echo esc_html($com['title']); ?>
                            </h3>
                        </div>
                    </div>
                    <div class="comfor_view">
                        <div class="row gx-0">
                            <?php for ($i = 1; $i <= 3; $i++) :
                                $item = $com["comfor_item_{$i}"] ?? [];
                                if (!empty($item['image'])) :
                            ?>
                            <div class="col-lg-4">
                                <div class="comfor_item">
                                    <figure>
                                        <a href="<?php echo esc_url($item['link'] ?? '#'); ?>" target="_blank"
                                            rel="noopener noreferrer">
                                            <img src="<?php echo esc_url($item['image']); ?>" class="img-fluid" alt=""
                                                loading="lazy">
                                        </a>
                                    </figure>
                                </div>
                            </div>
                            <?php 
                                endif;
                            endfor; 
                            ?>
                        </div>
                    </div>

                    <?php if (!empty($com['button']['link']) && !empty($com['button']['title'])) : ?>
                    <div class="text-center">
                        <a href="<?php echo esc_url($com['button']['link']); ?>" target="_blank"
                            rel="noopener noreferrer" class="btn btn-white">
                            <?php echo esc_html($com['button']['title']); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($cspace_pro && !empty($cspace_pro['title_cspace']['title_product'])) : ?>
            <div class="space_product">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4">
                            <h3 class="fs-48 fw-medium title_group">
                                <?php echo esc_html($cspace_pro['title_cspace']['title_product']); ?>
                            </h3>
                        </div>
                    </div>
                    <div class="product_wrapper">
                        <div class="product_slider">
                            <?php
                            // =====================================================
                            // SỬ DỤNG $products_data đã pre-fetch ở trên
                            // =====================================================
                            if (!empty($products_data)) :
                                foreach ($products_data as $pid => $data) :
                            ?>
                            <div class="item">
                                <figure>
                                    <a href="<?php echo get_permalink($pid); ?>" target="_blank"
                                        rel="noopener noreferrer">
                                        <img src="<?php echo esc_url($data['thumb_url']); ?>" class="img-fluid"
                                            alt="<?php echo esc_attr($data['post']->post_title); ?>" loading="lazy">
                                    </a>
                                </figure>
                                <div class="meta_info">
                                    <h4 class="fs-20 fw-semibold">
                                        <a href="<?php echo get_permalink($pid); ?>" target="_blank"
                                            rel="noopener noreferrer">
                                            <?php echo esc_html($data['post']->post_title); ?>
                                        </a>
                                    </h4>
                                </div>
                            </div>
                            <?php
                                endforeach;
                            else :
                                // Fallback: Query tất cả products nếu không có selected
                                $fallback_args = array(
                                    'post_type'              => 'product',
                                    'posts_per_page'         => -1,
                                    'post_status'            => 'publish',
                                    'orderby'                => 'date',
                                    'order'                  => 'DESC',
                                    'no_found_rows'          => true,
                                    'update_post_term_cache' => false,
                                    'fields'                 => 'ids',
                                );
                                
                                $fallback_ids = get_posts($fallback_args);
                                if (!empty($fallback_ids)) :
                                    update_meta_cache('post', $fallback_ids);
                                    
                                    foreach ($fallback_ids as $pid) :
                                        $gallery = get_post_meta($pid, '_product_image_gallery', true);
                                        $gallery_arr = $gallery ? explode(',', $gallery) : array();
                                        $thumb_id = !empty($gallery_arr[0]) ? (int)$gallery_arr[0] : 0;
                                        $thumb_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'large') : wc_placeholder_img_src();
                                        $post_obj = get_post($pid);
                            ?>
                            <div class="item">
                                <figure>
                                    <a href="<?php echo get_permalink($pid); ?>" target="_blank"
                                        rel="noopener noreferrer">
                                        <img src="<?php echo esc_url($thumb_url); ?>" class="img-fluid"
                                            alt="<?php echo esc_attr($post_obj->post_title); ?>" loading="lazy">
                                    </a>
                                </figure>
                                <div class="meta_info">
                                    <h4 class="fs-20 fw-semibold">
                                        <a href="<?php echo get_permalink($pid); ?>" target="_blank"
                                            rel="noopener noreferrer">
                                            <?php echo esc_html($post_obj->post_title); ?>
                                        </a>
                                    </h4>
                                </div>
                            </div>
                            <?php
                                    endforeach;
                                endif;
                            endif;
                            ?>
                        </div>
                    </div>
                    <div class="product_dots"></div>
                </div>
            </div>
            <?php endif; ?>

            <?php 
            $video = $cspace_pro['space_video'] ?? '';
            if ($video && (!empty($video['link_youtube']) || !empty($video['image']))) :
            ?>
            <div class="space_video">
                <div class="container">
                    <div class="video_wrapper">
                        <a href="<?php echo esc_url($video['link_youtube'] ?? '#'); ?>" data-fancybox="video-intro"
                            class="d-block position-relative group-video">
                            <?php if (!empty($video['image'])) : ?>
                            <figure class="m-0">
                                <img src="<?php echo esc_url($video['image']); ?>" class="img-fluid w-100"
                                    alt="Video cover" loading="lazy">
                            </figure>
                            <?php endif; ?>
                            <div class="playvideo">
                                <i class="fa fa-play"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- TAB 2: About -->
        <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">

            <?php 
            $about = $abouts['space_about'] ?? null;
            if ($about) :
            ?>
            <div class="space_about">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5">
                            <?php if (!empty($about['subtitle'])) : ?>
                            <div class="sub_title fs-18"><?php echo esc_html($about['subtitle']); ?></div>
                            <?php endif; ?>

                            <?php if (!empty($about['title'])) : ?>
                            <h3 class="fs-64 fw-medium title_group"><?php echo esc_html($about['title']); ?></h3>
                            <?php endif; ?>

                            <?php if (!empty($about['desc'])) : ?>
                            <div class="desc fs-20"><?php echo nl2br(esc_html($about['desc'])); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php 
                    $items = $about['about_item'] ?? [];
                    if (!empty($items)) :
                    ?>
                    <div class="about_wrapper">
                        <?php 
                        $count = 0;
                        foreach ($items as $row) :
                            $count++;
                            $item_group = $row['item'] ?? null;
                            if ($item_group && !empty($item_group['image'])) :
                        ?>
                        <div class="about_item <?php echo ($count == 1) ? 'active' : ''; ?>">
                            <div class="count"><?php echo str_pad($count, 2, '0', STR_PAD_LEFT); ?></div>
                            <figure>
                                <img src="<?php echo esc_url($item_group['image']); ?>" class="img-fluid" alt=""
                                    loading="lazy">
                            </figure>
                            <div class="meta_info">
                                <?php if (!empty($item_group['icon'])) : ?>
                                <div class="icon">
                                    <img src="<?php echo esc_url($item_group['icon']); ?>" class="img-fluid" alt="">
                                </div>
                                <?php endif; ?>

                                <?php if (!empty($item_group['title'])) : ?>
                                <h4 class="fs-32 fw-medium"><?php echo esc_html($item_group['title']); ?></h4>
                                <?php endif; ?>

                                <?php if (!empty($item_group['excerpt'])) : ?>
                                <div class="excerpt fs-20"><?php echo nl2br(esc_html($item_group['excerpt'])); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php 
            $slider = $abouts['space_brand'] ?? [];
            if (!empty($slider['item'])) :
            ?>
            <div class="space_brand">
                <div class="splide brand_slider">
                    <div class="splide__track">
                        <ul class="splide__list">
                            <?php foreach ($slider['item'] as $y) :
                                if (!empty($y['image'])) :
                            ?>
                            <li class="splide__slide">
                                <div class="item">
                                    <img src="<?php echo esc_url($y['image']); ?>" class="img-fluid" alt=""
                                        loading="lazy">
                                </div>
                            </li>
                            <?php
                                endif;
                            endforeach;
                            ?>
                        </ul>
                    </div>
                    <div class="splide__arrows">
                        <button class="splide__arrow splide__arrow--prev">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/dropdown.svg" alt="">
                        </button>
                        <button class="splide__arrow splide__arrow--next">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/dropdown.svg" alt="">
                        </button>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php 
            $digit = $abouts['space_digit'] ?? [];
            if (!empty($digit['image']) || !empty($digit['accordion'])) :
            ?>
            <div class="space_digit">
                <div class="value_section">
                    <div class="container">
                        <div class="row align-items-center">
                            <?php if (!empty($digit['image'])) : ?>
                            <div class="col-lg-5">
                                <figure>
                                    <img src="<?php echo esc_url($digit['image']); ?>" class="img-fluid" alt=""
                                        loading="lazy">
                                </figure>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($digit['accordion'])) : ?>
                            <div class="col-lg-5 offset-lg-1">
                                <div class="accordion" id="valueAccordition">
                                    <?php foreach ($digit['accordion'] as $key => $row) :
                                        $item = $row['item'] ?? [];
                                        if (empty($item['title'])) continue;
                                        $collapse_id = 'vcollapse_' . ($key + 1);
                                        $is_first = ($key == 0);
                                    ?>
                                    <div class="accordion-item">
                                        <div class="accordion-header">
                                            <a class="accordion-button fs-32 fw-medium <?php echo $is_first ? '' : 'collapsed'; ?>"
                                                href="javascript:;" data-bs-toggle="collapse"
                                                data-bs-target="#<?php echo $collapse_id; ?>"
                                                aria-expanded="<?php echo $is_first ? 'true' : 'false'; ?>">
                                                <?php if (!empty($item['icon'])) : ?>
                                                <img src="<?php echo esc_url($item['icon']); ?>" class="img-fluid me-2"
                                                    alt="">
                                                <?php endif; ?>
                                                <?php echo esc_html($item['title']); ?>
                                            </a>
                                        </div>
                                        <div id="<?php echo $collapse_id; ?>"
                                            class="accordion-collapse collapse <?php echo $is_first ? 'show' : ''; ?>"
                                            data-bs-parent="#valueAccordition">
                                            <div class="accordion-body">
                                                <div class="fs-20 fw-medium">
                                                    <?php echo nl2br(esc_html($item['excerpt'] ?? '')); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php 
                $number = $abouts['number_section'] ?? [];
                if (!empty($number['title'])) :
                ?>
                <div class="number_section">
                    <div class="container">
                        <div class="number_content">
                            <div class="row justify-content-between">
                                <div class="col-lg-5">
                                    <?php if (!empty($number['subtitle'])) : ?>
                                    <div class="sub_title fs-18"><?php echo esc_html($number['subtitle']); ?></div>
                                    <?php endif; ?>
                                    <h3 class="fs-64 fw-medium title_group"><?php echo esc_html($number['title']); ?>
                                    </h3>
                                    <?php if (!empty($number['desc'])) : ?>
                                    <div class="desc fs-20"><?php echo wp_kses_post($number['desc']); ?></div>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($number['digit_gap'])) : ?>
                                <div class="col-lg-6">
                                    <div class="row digit_gap">
                                        <?php foreach ($number['digit_gap'] as $x) :
                                            if (!empty($x['item']['title'])) :
                                        ?>
                                        <div class="col-lg-6">
                                            <div class="digit_item">
                                                <div class="fs-18 fw-medium digit_title">
                                                    <?php echo esc_html($x['item']['title']); ?></div>
                                                <?php if (!empty($x['item']['count'])) : ?>
                                                <div class="fs-48 fw-medium digit_count">
                                                    <span><?php echo esc_html($x['item']['count']); ?></span>+
                                                </div>
                                                <?php endif; ?>
                                                <?php if (!empty($x['item']['desc'])) : ?>
                                                <div class="digit_in"><?php echo wp_kses_post($x['item']['desc']); ?>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php
                                            endif;
                                        endforeach;
                                        ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php 
            $solution = $abouts['space_solution'] ?? [];
            if (!empty($solution['title'])) :
            ?>
            <div class="space_solution">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <?php if (!empty($solution['subtitle'])) : ?>
                            <div class="sub_title fs-18 text-center"><?php echo esc_html($solution['subtitle']); ?>
                            </div>
                            <?php endif; ?>
                            <h3 class="fs-64 fw-medium title_group text-center">
                                <?php echo esc_html($solution['title']); ?></h3>
                            <?php if (!empty($solution['desc'])) : ?>
                            <div class="desc fs-20 text-center"><?php echo nl2br(esc_html($solution['desc'])); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($solution['solution_list'])) : ?>
                    <div class="solution_list">
                        <div class="row gx-lg-0">
                            <?php foreach ($solution['solution_list'] as $x) :
                                if (!empty($x['image'])) :
                            ?>
                            <div class="col-lg-3">
                                <div class="solution_item">
                                    <figure>
                                        <img src="<?php echo esc_url($x['image']); ?>" class="img-fluid" alt=""
                                            loading="lazy">
                                    </figure>
                                    <?php if (!empty($x['meta_info'])) : ?>
                                    <div class="meta_info">
                                        <h4 class="fs-48 fw-medium text-center"><?php echo esc_html($x['meta_info']); ?>
                                        </h4>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php
                                endif;
                            endforeach;
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php 
            $team = $abouts['space_team'] ?? [];
            if (!empty($team['title'])) :
            ?>
            <div class="space_team">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8">
                            <?php if (!empty($team['subtitle'])) : ?>
                            <div class="sub_title fs-18"><?php echo esc_html($team['subtitle']); ?></div>
                            <?php endif; ?>
                            <h3 class="fs-64 fw-medium title_group"><?php echo nl2br(esc_html($team['title'])); ?></h3>
                        </div>
                    </div>

                    <?php if (!empty($team['list_team'])) : ?>
                    <div class="row">
                        <div class="col-lg-9 offset-lg-2">
                            <div class="row team_gap gx-lg-4 gx-2">
                                <?php foreach ($team['list_team'] as $x) :
                                    if (!empty($x['avatar'])) :
                                ?>
                                <div class="col-lg-4 col-6">
                                    <div class="team_item">
                                        <figure>
                                            <img src="<?php echo esc_url($x['avatar']); ?>" class="img-fluid" alt=""
                                                loading="lazy">
                                        </figure>
                                        <div class="meta_info">
                                            <?php if (!empty($x['name'])) : ?>
                                            <h4 class="fs-18 fw-medium"><?php echo esc_html($x['name']); ?></h4>
                                            <?php endif; ?>
                                            <?php if (!empty($x['position'])) : ?>
                                            <div class="position"><?php echo esc_html($x['position']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    endif;
                                endforeach;
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Events Section -->
            <div class="decox_events">
                <div class="container">
                    <h3 class="fs-48 fw-semibold text-center title_group">Event</h3>
                    <div class="slider_events">
                        <?php
                        // =====================================================
                        // TỐI ƯU 5: Sử dụng hàm có cache thay vì gọi API trực tiếp
                        // =====================================================
                        $events = function_exists('get_dezon_events_data') 
                            ? get_dezon_events_data(10) 
                            : array();

                        if (!empty($events)) :
                            foreach ($events as $event) :
                                $title = $event->title->rendered ?? '';
                                $link = $event->link ?? '#';

                                // Lấy ảnh
                                $img_url = '';
                                if (isset($event->_embedded->{'wp:featuredmedia'}[0]->source_url)) {
                                    $img_url = $event->_embedded->{'wp:featuredmedia'}[0]->source_url;
                                } elseif (isset($event->yoast_head_json->og_image[0]->url)) {
                                    $img_url = $event->yoast_head_json->og_image[0]->url;
                                } else {
                                    $img_url = 'https://via.placeholder.com/810x894';
                                }

                                $info_obj = $event->info ?? null;
                                $address = $info_obj->address ?? '';
                                $date_raw = $info_obj->date ?? '';
                                $time_raw = $info_obj->time ?? '';

                                $thu_hien_thi = function_exists('get_vietnamese_day_from_string') 
                                    ? get_vietnamese_day_from_string($date_raw) 
                                    : '';
                                $gio_hien_thi = substr($time_raw, 0, 5);

                                $ngay_thang_only = $date_raw;
                                if (!empty($date_raw)) {
                                    $parts = explode('/', $date_raw);
                                    if (count($parts) >= 2) {
                                        $ngay_thang_only = $parts[0] . '/' . $parts[1];
                                    }
                                }
                        ?>
                        <div class="item">
                            <figure>
                                <a href="<?php echo esc_url($link); ?>" target="_blank">
                                    <img src="<?php echo esc_url($img_url); ?>" class="img-fluid wp-post-image"
                                        alt="<?php echo esc_attr(strip_tags($title)); ?>" loading="lazy">
                                </a>
                            </figure>
                            <div class="meta_info">
                                <div class="row">
                                    <div class="col">
                                        <h4 class="fs-20 fw-semibold post_title">
                                            <a href="<?php echo esc_url($link); ?>"
                                                target="_blank"><?php echo wp_kses_post($title); ?></a>
                                        </h4>
                                        <?php if ($address) : ?>
                                        <div class="fs-18 fw-bold address"><?php echo esc_html($address); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($date_raw)) : ?>
                                    <div class="col-auto">
                                        <div class="post_date">
                                            <div class="fs-16">
                                                <?php echo esc_html($thu_hien_thi); ?><br>
                                                <?php echo esc_html($gio_hien_thi); ?>
                                            </div>
                                            <span class="fs-18"><?php echo esc_html($ngay_thang_only); ?></span>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                            endforeach;
                        else :
                        ?>
                        <p class="text-center">Hiện chưa có sự kiện nào.</p>
                        <?php endif; ?>
                    </div>
                    <div class="event__arrows">
                        <div class="prev_arrow">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/dropdown.svg" alt="">
                        </div>
                        <div class="next_arrow">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/dropdown.svg" alt="">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Posts Section (Lazy loaded via JS) -->
            <div class="space_post">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="fs-48 fw-semibold title_group">Các Bài viết từ Dezon</h3>
                        </div>
                        <div class="col-auto">
                            <div class="view_all">
                                <a href="https://dezon.vn/tin-tuc/" target="_blank" rel="noopener noreferrer"
                                    class="cl-blue fs-18">
                                    Xem tất cả
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/arrowb.svg"
                                        alt="">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="list_post">
                        <div class="row gy-4" id="dezon-post-list">
                            <div class="col-12 text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="j_paging mt-4">
                        <div class="wp-pagenavi" id="dezon-pagination" role="navigation"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php 
    // ==================== CONTACT SECTION ====================
    if ($contact && !empty($contact['title'])) :
    ?>
    <div class="space_form">
        <div class="container">
            <div class="row justify-content-center gap-lg-5">
                <div class="col-lg-6">
                    <div class="h-100 d-flex flex-column">
                        <h3 class="fs-48 fw-medium title_group"><?php echo esc_html($contact['title']); ?></h3>
                        <div class="contact_info fs-18">
                            <?php if (!empty($contact['email'])) : ?>
                            <small>Email:</small>
                            <p><?php echo esc_html($contact['email']); ?></p>
                            <?php endif; ?>

                            <?php if (!empty($contact['phone'])) : ?>
                            <small>Số điện thoại:</small>
                            <p><?php echo esc_html($contact['phone']); ?></p>
                            <?php endif; ?>

                            <?php if (!empty($contact['address_1']['title']) && !empty($contact['address_1']['address'])) : ?>
                            <small><?php echo esc_html($contact['address_1']['title']); ?>:</small>
                            <p><?php echo esc_html($contact['address_1']['address']); ?></p>
                            <?php endif; ?>

                            <?php if (!empty($contact['address_2']['title']) && !empty($contact['address_2']['address'])) : ?>
                            <small><?php echo esc_html($contact['address_2']['title']); ?>:</small>
                            <p><?php echo esc_html($contact['address_2']['address']); ?></p>
                            <?php endif; ?>

                            <?php if (!empty($contact['socials'])) : ?>
                            <small>Theo dõi trên:</small>
                            <ul>
                                <?php foreach ($contact['socials'] as $x) :
                                    if (!empty($x['icon'])) :
                                ?>
                                <li>
                                    <a href="<?php echo esc_url($x['link'] ?? '#'); ?>" target="_blank"
                                        rel="noopener noreferrer">
                                        <img src="<?php echo esc_url($x['icon']); ?>" alt="">
                                    </a>
                                </li>
                                <?php
                                    endif;
                                endforeach;
                                ?>
                            </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <?php echo do_shortcode('[contact-form-7 id="e125025" title="Contact form"]'); ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php 
    // ==================== MAP SECTION ====================
    if ($map && (!empty($map['address_top']) || !empty($map['address_buttom_1']))) :
    ?>
    <div class="space_map">
        <div class="map_wrapper">
            <div class="nav" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <?php if (!empty($map['address_top']['title'])) : ?>
                <a href="javascript:void(0);" class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill"
                    data-bs-target="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">
                    <h4 class="fs-18 fw-semibold"><?php echo esc_html($map['address_top']['title']); ?></h4>
                    <?php if (!empty($map['address_top']['address'])) : ?>
                    <p class="fs-18">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/map.svg" class="img-fluid"
                            alt="">
                        <?php echo esc_html($map['address_top']['address']); ?>
                    </p>
                    <?php endif; ?>
                </a>
                <?php endif; ?>

                <?php if (!empty($map['address_buttom_1']['title'])) : ?>
                <a href="javascript:void(0);"
                    class="nav-link <?php echo empty($map['address_top']['title']) ? 'active' : ''; ?>"
                    id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" role="tab"
                    aria-controls="v-pills-profile" aria-selected="false">
                    <h4 class="fs-18 fw-semibold"><?php echo esc_html($map['address_buttom_1']['title']); ?></h4>
                    <?php if (!empty($map['address_buttom_1']['address'])) : ?>
                    <p class="fs-18">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/map.svg" class="img-fluid"
                            alt="">
                        <?php echo esc_html($map['address_buttom_1']['address']); ?>
                    </p>
                    <?php endif; ?>
                </a>
                <?php endif; ?>
            </div>

            <div class="tab-content" id="v-pills-tabContent">
                <?php if (!empty($map['address_top']['link_map_embed'])) : ?>
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                    aria-labelledby="v-pills-home-tab" tabindex="0">
                    <div class="map_embed">
                        <iframe src="<?php echo esc_url($map['address_top']['link_map_embed']); ?>" width="600"
                            height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($map['address_buttom_1']['link_map_embed'])) : ?>
                <div class="tab-pane fade <?php echo empty($map['address_top']['link_map_embed']) ? 'show active' : ''; ?>"
                    id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="0">
                    <div class="map_embed">
                        <iframe src="<?php echo esc_url($map['address_buttom_1']['link_map_embed']); ?>" width="600"
                            height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>


<?php endif; ?>
<?php 
    endwhile;
endif;

get_footer();
?>