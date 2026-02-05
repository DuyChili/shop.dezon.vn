<?php
/**
 * Template Name: Custom Product Layout
 */

get_header(); 
?>

<?php
while ( have_posts() ) :
    the_post();
    global $product;
    $a = get_field('info');
    $acf_chi_tiet   = $a['mo_ta_chi_tiet'];
    $acf_van_chuyen = $a['van_chuyen'];
    $acf_vong_doi   = $a['vong_doi'];
    $acf_vat_lieu   = $a['vat_lieu'];
    $time_min = $a['lead_time']['min_lead_time'] ?? ''; 
    $time_max = $a['lead_time']['max_lead_time'] ?? '';

    $ph_group = get_field('product_help');
    
    $ph_repeater = ( $ph_group && isset($ph_group['item']) && is_array($ph_group['item']) ) ? $ph_group['item'] : array();

    $attachment_ids = $product->get_gallery_image_ids();
    if ( $product->get_image_id() ) {
        array_unshift( $attachment_ids, $product->get_image_id() );
    }
    $attachment_ids = array_unique( $attachment_ids );

    $contact_group = get_field('contact_product','option');
    $contact_title = $contact_group['title'] ?? '';
    
    $contact_href = 'tel:0935489789'; 

    if ( $contact_group ) {
        $type = isset($contact_group['link_or_phone']) ? $contact_group['link_or_phone'] : '';

        if ( $type === 'phone' && !empty($contact_group['phone_number']) ) {
            $contact_href = 'tel:' . $contact_group['phone_number'];
        } 
        elseif ( $type === 'link' && !empty($contact_group['link_contact']) ) {
            $contact_href = $contact_group['link_contact'];
        }
    }
?>


<div class="space_page">
    <div class="product_detail">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-11">
                    <div id="jbreadcrumb" class="jbreadcrumb fs-18">
                        <?php yoast_breadcrumb( '<div class="breadcrumb-container">', '</div>' ); ?>
                    </div>
                    <div class="product_info">
                        <div class="row">
                            <div class="col-lg-5 order-lg-1">
                                <div class="gallery_wrapper">
                                    <div class="product_gallery">
                                        <?php if ( $attachment_ids ) : foreach ( $attachment_ids as $attachment_id ) : 
                                            $full_src = wp_get_attachment_image_url( $attachment_id, 'full' );
                                        ?>
                                        <div class="item">
                                            <figure>
                                                <a href="<?php echo esc_url($full_src); ?>" data-fancybox="gallery">
                                                    <img src="<?php echo esc_url($full_src); ?>" class="img-fluid"
                                                        alt="<?php echo get_post_meta($attachment_id, '_wp_attachment_image_alt', true); ?>">
                                                </a>
                                            </figure>
                                        </div>
                                        <?php endforeach; endif; ?>
                                    </div>
                                    <div class="gallery__arrows">
                                        <div class="prev_arrow">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/dropdown.svg"
                                                alt="">
                                        </div>
                                        <div class="next_arrow">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/dropdown.svg"
                                                alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-1 order-lg-0">
                                <?php 
    // 1. Kiểm tra số lượng ảnh
    $count_imgs = $attachment_ids ? count($attachment_ids) : 0;
    $thumb_class = ($count_imgs <= 5) ? 'is-static-gallery' : ''; 
    ?>

                                <div class="slider_thumb <?php echo $thumb_class; ?>">
                                    <?php if ( $attachment_ids ) : foreach ( $attachment_ids as $attachment_id ) : 
            $thumb_src = wp_get_attachment_image_url( $attachment_id, 'woocommerce_gallery_thumbnail' );
        ?>
                                    <div class="item">
                                        <figure>
                                            <img src="<?php echo esc_url($thumb_src); ?>" class="img-fluid" alt="">
                                        </figure>
                                    </div>
                                    <?php endforeach; endif; ?>
                                </div>
                            </div>

                            <div class="col-lg-6 order-lg-2">
                                <div class="ps-lg-5">
                                    <h1 class="fs-48 fw-medium product_title"><?php the_title(); ?></h1>
                                    <div class="fs-18 fw-medium product_by">
                                        <?php $short_description = $product->get_short_description();

    // Kiểm tra nếu có dữ liệu thì mới hiển thị
    if ( ! empty( $short_description ) ) {
        echo apply_filters( 'woocommerce_short_description', $short_description );
    } ?>
                                    </div>
                                    <div class="highlight fw-medium">
                                        Giao hàng có thời gian chờ
                                    </div>
                                    <div class="price fs-32 fw-medium">
                                        <?php echo $product->get_price_html(); ?>
                                    </div>
                                    <?php if ( $time_min !== '' && $time_max !== '' ): ?>
                                    <div class="more_info">
                                        Thời gian chờ trung bình: <?php echo $time_min; ?>-<?php echo $time_max; ?> tuần
                                        <span data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Sản phẩm được đặt làm riêng, thời gian sản xuất và vận chuyển dự kiến từ <?php echo $time_min; ?> đến <?php echo $time_max; ?> tuần.">
                                            i
                                        </span>
                                    </div>
                                    <?php endif; ?>
                                    <div class="product_option">
                                <?php 
                                // TRƯỜNG HỢP 1: SẢN PHẨM CÓ BIẾN THỂ
                                if ( $product->is_type( 'variable' ) ) :
                                    $variation_attributes = $product->get_variation_attributes();
                                    
                                    // Lấy dữ liệu biến thể an toàn
                                    $available_variations = $product->get_available_variations();
                                    
                                    // In dữ liệu ra biến JS global để tránh lỗi parse HTML
                                    echo '<div id="data_variations" style="display:none;" data-variations=\'' . wp_json_encode( $available_variations ) . '\'></div>';

                                    foreach ( $variation_attributes as $attribute_name => $options ) : 
                                        // Logic lấy tên key chuẩn của Woo
                                        $attribute_key = 'attribute_' . sanitize_title( $attribute_name );
                                        ?>
                                        <div class="mb-3">
                                            <label class="mb-1 fw-medium"><?php echo wc_attribute_label( $attribute_name ); ?></label>
                                            
                                            <select name="<?php echo esc_attr( $attribute_key ); ?>"
                                                    class="form-select fs-16 js-variation-select"
                                                    data-attribute_name="<?php echo esc_attr( $attribute_key ); ?>">
                                                
                                                <option value="">Chọn <?php echo wc_attribute_label( $attribute_name ); ?></option>
                                                
                                                <?php 
                                                if ( ! empty( $options ) ) : 
                                                    if ( $product && taxonomy_exists( $attribute_name ) ) {
                                                        $terms = wc_get_product_terms( $product->get_id(), $attribute_name, array( 'fields' => 'all' ) );
                                                        foreach ( $terms as $term ) {
                                                            if ( in_array( $term->slug, $options ) ) {
                                                                echo '<option value="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</option>';
                                                            }
                                                        }
                                                    } else {
                                                        foreach ( $options as $option ) {
                                                            echo '<option value="' . esc_attr( $option ) . '">' . esc_html( $option ) . '</option>';
                                                        }
                                                    }
                                                endif;
                                                ?>
                                            </select>
                                        </div>
                                    <?php endforeach; ?>

                                <?php 
                                // TRƯỜNG HỢP 2: SẢN PHẨM ĐƠN GIẢN
                                elseif ( $product->is_type( 'simple' ) ) : 
                                ?>
                                    <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" />
                                    <div class="mb-3 stock-status fs-16">
                                        <?php echo wc_get_stock_html( $product ); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                                    <div class="row align-items-center">
                                        <div class="col-auto d-none">
                                            <div class="quantity">
                                                <input type="number" value="1" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <a href="<?php echo esc_url($contact_href); ?>"
                                                class="btn btn-bgblack fs-18 fw-medium buy_btn"
                                                <?php echo ($contact_group && $contact_group['link_or_phone'] === 'link') ? 'target="_blank"' : ''; ?>>
                                                <?php echo $contact_title; ?>
                                            </a>
                                        </div>
                                        <div class="col">
                                            <div class="like_post">
                                                <a href="">
                                                    <i class="fa fa-heart-o" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sku_product fw-medium">
                                        SKU <?php echo ( $sku = $product->get_sku() ) ? $sku : 'N/A'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="product_description">
                        <div class="accordion" id="productDescription">
                            <div class="accordion-item">
                                <div class="accordion-header fs-20 fw-medium">
                                    <a class="accordion-button" href="javascript:;" data-bs-toggle="collapse"
                                        data-bs-target="#pdesc_1" aria-expanded="true" aria-controls="pdesc_1">
                                        Mô tả
                                    </a>
                                </div>
                                <div id="pdesc_1" class="accordion-collapse collapse show"
                                    data-bs-parent="#productDescription">
                                    <div class="accordion-body fs-16">
                                        <?php echo do_shortcode( wpautop( $product->get_description() ) ); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <div class="accordion-header fs-20 fw-medium">
                                    <a class="accordion-button collapsed" href="javascript:;" data-bs-toggle="collapse"
                                        data-bs-target="#pdesc_2" aria-expanded="false" aria-controls="pdesc_2">
                                        Chi tiết
                                    </a>
                                </div>
                                <div id="pdesc_2" class="accordion-collapse collapse"
                                    data-bs-parent="#productDescription">
                                    <div class="accordion-body fs-16">
                                        <?php 
                                        if ( $acf_chi_tiet ) {
                                            echo $acf_chi_tiet; 
                                        } else {
                                            echo 'Nội dung chi tiết đang cập nhật...';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <div class="accordion-header fs-20 fw-medium">
                                    <a class="accordion-button collapsed" href="javascript:;" data-bs-toggle="collapse"
                                        data-bs-target="#pdesc_3" aria-expanded="false" aria-controls="pdesc_3">
                                        Vận chuyển
                                    </a>
                                </div>
                                <div id="pdesc_3" class="accordion-collapse collapse"
                                    data-bs-parent="#productDescription">
                                    <div class="accordion-body fs-16">
                                        <?php 
                                        if ( $acf_van_chuyen ) {
                                            echo $acf_van_chuyen; 
                                        } else {
                                            echo 'Nội dung vận chuyển đang cập nhật...';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <div class="accordion-header fs-20 fw-medium">
                                    <a class="accordion-button collapsed" href="javascript:;" data-bs-toggle="collapse"
                                        data-bs-target="#pdesc_4" aria-expanded="false" aria-controls="pdesc_4">
                                        Kích thước
                                    </a>
                                </div>
                                <div id="pdesc_4" class="accordion-collapse collapse"
                                    data-bs-parent="#productDescription">
                                    <div class="accordion-body fs-16">
                                        <div class="product_dimensions">
                                            <?php 
        // Lấy kích thước và cân nặng
        $dims = $product->get_dimensions(false); 
        $weight = $product->get_weight();
        
        // Kiểm tra nếu có bất kỳ thông tin nào
        if ( ! empty($dims) || ! empty($weight) ) {
            
            echo '<ul class="dimension-list style-none" style="padding: 0; margin: 0;">';
            
            // 1. Hiển thị Kích thước (Dài - Rộng - Cao)
            if ( ! empty($dims['length']) ) {
                echo '<li>Chiều dài: ' . esc_html($dims['length']) . ' cm</li>';
            }
            if ( ! empty($dims['width']) ) {
                echo '<li>Chiều rộng: ' . esc_html($dims['width']) . ' cm</li>';
            }
            if ( ! empty($dims['height']) ) {
                echo '<li>Chiều cao: ' . esc_html($dims['height']) . ' cm</li>';
            }

            // 2. Hiển thị Cân nặng (NẾU CÓ)
            if ( ! empty($weight) ) {
                echo '<li>Cân nặng: ' . esc_html($weight) . ' kg</li>';
            }
            
            echo '</ul>';
        } else {
            echo '<span>Đang cập nhật thông tin...</span>';
        }
        ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <div class="accordion-header fs-20 fw-medium">
                                    <a class="accordion-button collapsed" href="javascript:;" data-bs-toggle="collapse"
                                        data-bs-target="#pdesc_5" aria-expanded="false" aria-controls="pdesc_5">
                                        Vòng đời
                                    </a>
                                </div>
                                <div id="pdesc_5" class="accordion-collapse collapse"
                                    data-bs-parent="#productDescription">
                                    <div class="accordion-body fs-16">
                                        <?php 
                                        if ( $acf_vong_doi ) {
                                            echo $acf_vong_doi; 
                                        } else {
                                            echo 'Nội dung vòng đời đang cập nhật...';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <div class="accordion-header fs-20 fw-medium">
                                    <a class="accordion-button collapsed" href="javascript:;" data-bs-toggle="collapse"
                                        data-bs-target="#pdesc_6" aria-expanded="false" aria-controls="pdesc_6">
                                        Vật liệu
                                    </a>
                                </div>
                                <div id="pdesc_6" class="accordion-collapse collapse"
                                    data-bs-parent="#productDescription">
                                    <div class="accordion-body fs-16">
                                        <?php 
                                        if ( $acf_vat_lieu ) {
                                            echo $acf_vat_lieu; 
                                        } else {
                                            echo 'Nội dung vật liệu đang cập nhật...';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                    <?php if( ! empty($ph_repeater) ): ?>
                    <div class="product_help mt-5">
                        <div class="row gx-lg-0">
                            <div class="col-lg-8">
                                <div class="help_question">
                                    <h3 class="fs-32 fw-medium mb-lg-5 mb-4">Cần giúp đỡ?</h3>
                                    <div class="accordion" id="accordionHelp">
                                        <?php 
                                        foreach( $ph_repeater as $key => $row ): 
                                            $h_id = $key + 1; 
                                            
                                            // Lấy Group 'q&a' (3. Lấy Group con trong repeater)
                                            $qa_group = isset($row['q&a']) ? $row['q&a'] : false;
                                            
                                            if ( $qa_group ) {
                                                // 4. Lấy fields title & desc
                                                $qa_title = isset($qa_group['title']) ? $qa_group['title'] : '';
                                                $qa_desc  = isset($qa_group['desc']) ? $qa_group['desc'] : '';
                                                
                                                // Nếu không có title thì bỏ qua row này
                                                if ( ! $qa_title ) continue;
                                        ?>
                                        <div class="accordion-item">
                                            <div class="accordion-header">
                                                <a class="accordion-button <?php echo ($h_id != 1) ? 'collapsed' : ''; ?>"
                                                    href="javascript:;" data-bs-toggle="collapse"
                                                    data-bs-target="#helpcollase_<?php echo $h_id; ?>"
                                                    aria-expanded="<?php echo ($h_id == 1) ? 'true' : 'false'; ?>"
                                                    aria-controls="helpcollase_<?php echo $h_id; ?>">
                                                    <?php echo esc_html($qa_title); ?>
                                                </a>
                                            </div>
                                            <div id="helpcollase_<?php echo $h_id; ?>"
                                                class="accordion-collapse collapse <?php echo ($h_id == 1) ? 'show' : ''; ?>"
                                                data-bs-parent="#accordionHelp">
                                                <div class="accordion-body">
                                                    <?php echo $qa_desc; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php 
                                            } // End if qa_group
                                        endforeach; 
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form_help">
                                    <h3 class="fs-32 fw-medium form_title">Không tìm thấy câu trả lời của bạn?</h3>
                                    <div class="fs-16 form_desc">
                                        Liên hệ với chúng tôi tại đây:
                                    </div>
                                    <?php echo do_shortcode('[contact-form-7 id="e74fb74" title="Contact Form Product"]'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>


            </div>
        </div>
    </div>
</div>
<?php endwhile; ?>

<div class="space_product">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <h3 class="fs-48 fw-medium title_group">Mọi thứ mà ngôi nhà của bạn xứng đáng</h3>
            </div>
        </div>
        <div class="product_wrapper">
            <div class="product_slider">
                <?php
                $current_id = get_the_ID();

                $args = array(
                    'post_type'      => 'product',      
                    'posts_per_page' => 8,              
                    'post_status'    => 'publish',    
                    'orderby'        => 'date',        
                    'order'          => 'DESC',
                    'post__not_in'   => array( $current_id ), 
                );

                $products_query = new WP_Query( $args );

                if ( $products_query->have_posts() ) :
                    while ( $products_query->have_posts() ) : $products_query->the_post();
                        global $product; 

                        // --- BẮT ĐẦU XỬ LÝ ẢNH THUMBNAIL TỰ ĐỘNG ---

                        // 1. Thử lấy ID của ảnh đại diện (Featured Image)
                        $thumb_id = '';// $product->get_image_id();

                        // 2. Nếu KHÔNG có ảnh đại diện -> Lấy ảnh đầu tiên trong Gallery
                        if ( ! $thumb_id ) {
                            $gallery_ids = $product->get_gallery_image_ids(); // Lấy danh sách ID ảnh gallery
                            if ( ! empty( $gallery_ids ) ) {
                                $thumb_id = $gallery_ids[0]; // Lấy ID ảnh đầu tiên
                            }
                        }

                        // 3. Lấy URL từ ID đã tìm được (Featured hoặc Gallery)
                        $thumb_url = '';
                        if ( $thumb_id ) {
                            $thumb_url = wp_get_attachment_image_url( $thumb_id, 'large' );
                        } 

                        if ( empty($thumb_url) ) {
                            $thumb_url = wc_placeholder_img_src();
                        }
                        ?>

                <div class="item">
                    <figure>
                        <a href="<?php the_permalink(); ?>" target="_blank" rel="noopener noreferrer">
                            <img src="<?php echo esc_url($thumb_url); ?>" class="img-fluid"
                                alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                        </a>
                    </figure>
                    <div class="meta_info">
                        <h4 class="fs-20 fw-semibold">
                            <a href="<?php the_permalink(); ?>" target="_blank"
                                rel="noopener noreferrer"><?php the_title(); ?></a>
                        </h4>
                    </div>
                </div>

                <?php endwhile;
                    wp_reset_postdata();
                else :
                    // Code xử lý khi không có sản phẩm (để trống hoặc echo thông báo)
                endif;
                ?>
            </div>
        </div>
        <div class="product_dots">
        </div>
    </div>
</div>
<?php 
// Lấy Group field chính
$video_data = get_field('video_product'); 
?>

<?php if ($video_data['image']): ?>
    <div class="space_video">
        <div class="container">
            <div class="video_wrapper">
                <?php 
                    $image = $video_data['image'];
                    $imageSrc = is_array($image) ? $image['url'] : $image;
                    $choice = $video_data['file_or_link_youtube'];
                    $finalLink = ($choice == 'file') ? $video_data['file'] : $video_data['link_youtube'];
                    if (is_array($finalLink)) {
                        $finalLink = $finalLink['link'];
                    }
                ?>

                <a href="<?php echo esc_url($finalLink); ?>" 
                   data-fancybox="video-intro"
                   class="d-block position-relative group-video">

                    <figure class="m-0">
                        <?php if ($imageSrc): ?>
                            <img src="<?php echo esc_url($imageSrc); ?>" class="img-fluid w-100" alt="Video cover">
                        <?php else: ?>
                            <img src="path/to/default-image.jpg" class="img-fluid w-100" alt="Default cover">
                        <?php endif; ?>
                    </figure>

                    <div class="playvideo">
                        <i class="fa fa-play"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $map = get_field('space_map','option'); ?>
<div class="space_map">
    <div class="map_wrapper">
        <div class="nav" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <a href="javascript;;" class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill"
                data-bs-target="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">
                <h4 class="fs-18 fw-semibold"><?php echo $map['address_top']['title'] ?? ''; ?></h4>
                <p class="fs-18"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/map.svg"
                        class="img-fluid" alt=""><?php echo $map['address_top']['address'] ?? ''; ?></p>
            </a>
            <a href="javascript;;" class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill"
                data-bs-target="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                <h4 class="fs-18 fw-semibold"><?php echo $map['address_buttom']['title'] ?? ''; ?></h4>
                <p class="fs-18"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/map.svg"
                        class="img-fluid" alt=""><?php echo $map['address_buttom']['address'] ?? ''; ?></p>
            </a>
        </div>
        <div class="tab-content" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab"
                tabindex="0">
                <div class="map_embed">
                    <iframe src="<?php echo $map['address_top']['link_map_embed'] ?? ''; ?>" width="600" height="450"
                        style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
            <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab"
                tabindex="0">
                <div class="map_embed">
                    <iframe src="<?php echo $map['address_buttom']['link_map_embed'] ?? ''; ?>" width="600" height="450"
                        style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="space_post" id="decox-related-blog-wrapper">
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="fs-48 fw-semibold title_group">Các Bài viết khác</h3>
            </div>
            <div class="col-auto">
                <div class="view_all">
                    <a href="https://dezon.vn/tin-tuc/" target="_blank" rel="noopener noreferrer" class="cl-blue fs-18">
                        Xem tất cả
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/arrowb.svg" alt="">
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
            <div class="wp-pagenavi" id="dezon-pagination" role="navigation">
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    var $gallery = $('.product_gallery');
    var $thumbSlider = $('.slider_thumb');

    var $firstSlideImg = $gallery.find('.slick-slide[data-slick-index="0"] img');
    if ($firstSlideImg.length === 0) {
        $firstSlideImg = $('.product_gallery .item:first-child img');
    }
    var originalImgSrc = $firstSlideImg.attr('src');
    var originalImgSrcset = $firstSlideImg.attr('srcset');
    var originalLinkHref = $firstSlideImg.parent('a').attr('href');

    var $firstThumbImg = $thumbSlider.find('.slick-slide[data-slick-index="0"] img');
    if ($firstThumbImg.length === 0) {
        $firstThumbImg = $('.slider_thumb .item:first-child img');
    }
    var originalThumbSrc = $firstThumbImg.attr('src');
    var originalThumbSrcset = $firstThumbImg.attr('srcset');

    // Các biến thông tin sản phẩm
    var $priceContainer = $('.product_detail .price');
    var originalPriceHtml = $priceContainer.html();
    var $skuContainer = $('.sku_product');
    var originalSkuText = $skuContainer.text();
    var $dimsContainer = $('.product_dimensions');
    var originalDimsHtml = $dimsContainer.html(); 

    var variationsData = $('#data_variations').data('variations');
      console.log('Variations Data:', variationsData);
    function updateSliderImage(src, srcset, full_src) {
        // A. Xử lý Slider Lớn
        $gallery.slick('slickGoTo', 0);
        var $currentActiveImg = $gallery.find('.slick-current img');
        var $realSlide0 = $gallery.find('.slick-slide[data-slick-index="0"] img');
        var $currentLink = $currentActiveImg.parent('a');

        $currentActiveImg.attr('src', src);
        $realSlide0.attr('src', src);

        if (srcset) {
            $currentActiveImg.attr('srcset', srcset);
            $realSlide0.attr('srcset', srcset);
        } else {
            $currentActiveImg.removeAttr('srcset');
            $realSlide0.removeAttr('srcset');
        }

        $currentLink.attr('href', full_src);
        $realSlide0.parent('a').attr('href', full_src);

        // B. Xử lý Slider Thumbnail
        if ($thumbSlider.length > 0) {
            $thumbSlider.slick('slickGoTo', 0);
            var $thumbImg0 = $thumbSlider.find('.slick-slide[data-slick-index="0"] img');
            var $thumbActive = $thumbSlider.find('.slick-current img');

            $thumbImg0.attr('src', src);
            $thumbActive.attr('src', src);

            if (srcset) {
                $thumbImg0.attr('srcset', srcset);
                $thumbActive.attr('srcset', srcset);
            } else {
                $thumbImg0.removeAttr('srcset');
                $thumbActive.removeAttr('srcset');
            }
        }
    }

    // --- SỰ KIỆN CHANGE ---
    $('.js-variation-select').on('change', function() {
        var allSelected = true;
        var currentSelection = {};

        $('.js-variation-select').each(function() {
            var attributeName = $(this).data('attribute_name');
            var val = $(this).val();
            if (!val) {
                allSelected = false;
            }
            currentSelection[attributeName] = val;
        });

        // TRƯỜNG HỢP 1: Reset -> Về gốc (GIỮ NGUYÊN)
        if (!allSelected) {
            $priceContainer.html(originalPriceHtml);
            $skuContainer.text(originalSkuText);

            // Reset nội dung kích thước/cân nặng về ban đầu
            $dimsContainer.html(originalDimsHtml);

            updateSliderImage(originalImgSrc, originalImgSrcset, originalLinkHref);

            var $thumbImg0 = $thumbSlider.find('.slick-slide[data-slick-index="0"] img');
            var $thumbActive = $thumbSlider.find('.slick-current img');

            $thumbImg0.attr('src', originalThumbSrc);
            $thumbActive.attr('src', originalThumbSrc);

            if (originalThumbSrcset) {
                $thumbImg0.attr('srcset', originalThumbSrcset);
                $thumbActive.attr('srcset', originalThumbSrcset);
            }
            return;
        }

        // TRƯỜNG HỢP 2: Đã chọn hết -> Tìm biến thể
        var match = variationsData.find(function(variation) {
            var isMatch = true;
            for (var key in currentSelection) {
                if (variation.attributes[key] !== "" && variation.attributes[key] !==
                    currentSelection[key]) {
                    isMatch = false;
                    break;
                }
            }
            return isMatch;
        });

        if (match) {
            if (match.price_html) {
                var newPrice = match.price_html.replace(/&#8363;/g, 'VND').replace(/₫/g, 'VND');
                $priceContainer.html(newPrice);
            }
            if (match.sku) $skuContainer.text('SKU ' + match.sku);

            var hasDim = (match.dimensions && (match.dimensions.length || match.dimensions.width ||
                match.dimensions.height));
            var hasWeight = (match.weight && match.weight !== "");

            if (hasDim || hasWeight) {
                var listHtml = '<ul class="dimension-list style-none" style=" padding: 0; margin: 0;">';

                // Thêm các dòng kích thước (nếu có)
                if (match.dimensions) {
                    if (match.dimensions.length) listHtml += '<li>Chiều dài: ' + match.dimensions
                        .length + ' cm</li>';
                    if (match.dimensions.width) listHtml += '<li>Chiều rộng: ' + match.dimensions
                        .width + ' cm</li>';
                    if (match.dimensions.height) listHtml += '<li>Chiều cao: ' + match.dimensions
                        .height + ' cm</li>';
                }

                // Thêm dòng cân nặng (CHỈ THÊM MỚI CHỖ NÀY)
                if (hasWeight) {
                    listHtml += '<li>Cân nặng: ' + match.weight + ' kg</li>';
                }

                listHtml += '</ul>';
                $dimsContainer.html(listHtml);
            } else if (match.dimensions_html && match.dimensions_html !== "") {
                // Fallback về mặc định của Woo nếu không bóc tách được dữ liệu
                $dimsContainer.html(match.dimensions_html);
            } else {
                $dimsContainer.html('<span>Thông tin đang cập nhật</span>');
            }
            // -----------------------------------------------------------

            if (match.image && match.image.src) {
                updateSliderImage(match.image.src, match.image.srcset, match.image.full_src);
            }
        }
    });
});
</script>
<?php
get_footer(); 
?>