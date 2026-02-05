<?php
/*
Template Name: Cspacepage
*/

// =====================================================
// TỐI ƯU 1: Cache toàn bộ ACF fields trong 1 biến
// =====================================================
$page_id = get_the_ID();

// Lấy tất cả ACF fields của page 1 lần 
$banner = get_field('banner', $page_id);
$cspace_pro = get_field('product', $page_id);
$abouts = get_field('about', $page_id);

// Cache ACF Options 
$contact = get_field('contact', 'option');
$map = get_field('space_map', 'option');

get_header();
?>

<?php $bg_url = $banner['background'] ?? ''; ?>
<div class="space_banner">
    <div class="content_abs">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <h1 class="fs-48 fw-medium title_group"><?php echo $banner['title'] ?? ''; ?></h1>
                    <div class="desc fs-20">
                        <?php echo $banner['subtitle'] ?? ''; ?>
                    </div>
                    <a href="<?php echo $banner['button']['link'] ?? ''; ?>" target="_blank" rel="noopener noreferrer" class="btn btn-black fs-18">
                        <?php echo $banner['button']['title'] ?? ''; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <img src="<?php echo esc_url($bg_url); ?>" class="img-fluid" alt="<?php echo esc_attr($banner['title'] ?? 'Banner'); ?>" loading="eager" fetchpriority="high">
</div>

<div class="space_page">
    <div class="tab-menu">
        <ul class="nav fs-18 justify-content-center nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane"
                    type="button" role="tab" aria-controls="home-tab-pane"
                    aria-selected="true"><?php echo $cspace_pro['title'] ?? ''; ?></button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane"
                    type="button" role="tab" aria-controls="profile-tab-pane"
                    aria-selected="false"><?php echo $abouts['title'] ?? ''; ?></button>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
            
            <div class="space_overview">
                <div class="container">
                    <figure>
                        <img src="<?php echo $cspace_pro['space_overview']['image'] ?? ''; ?>" class="img-fluid" alt="" loading="lazy">
                    </figure>
                </div>
            </div>

            <div class="space_inspiration">
                <div class="container">
                    <h3 class="fs-64 fw-medium title_group">
                        <?php echo $cspace_pro['title_cspace']['title_space_inspiration'] ?? ''; ?>
                    </h3>
                    <div class="inspiration_list">
                        <div class="row row-gap-3">
                            <div class="col-lg-4">
                                <div class="inspiration_item">
                                    <figure>
                                        <a href="" target="_blank" rel="noopener noreferrer">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/inspiration1.jpg" class="img-fluid" alt="" loading="lazy">
                                        </a>
                                    </figure>
                                    <div class="meta_info">
                                        <div class="row align-items-end">
                                            <div class="col">
                                                <h4 class="fs-24 fw-bold">Triết lý</h4>
                                            </div>
                                            <div class="col-auto">
                                                <a href="" target="_blank" rel="noopener noreferrer">
                                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/arroww.svg" class="img-fluid" alt="">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="inspiration_item">
                                    <figure>
                                        <a href="" target="_blank" rel="noopener noreferrer">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/inspiration2.jpg" class="img-fluid" alt="" loading="lazy">
                                        </a>
                                    </figure>
                                    <div class="meta_info">
                                        <div class="row align-items-end">
                                            <div class="col">
                                                <h4 class="fs-24 fw-bold">Dự án</h4>
                                            </div>
                                            <div class="col-auto">
                                                <a href="" target="_blank" rel="noopener noreferrer">
                                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/arroww.svg" class="img-fluid" alt="">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="inspiration_item">
                                    <figure>
                                        <a href="" target="_blank" rel="noopener noreferrer">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/inspiration3.jpg" class="img-fluid" alt="" loading="lazy">
                                        </a>
                                    </figure>
                                    <div class="meta_info">
                                        <div class="row align-items-end">
                                            <div class="col">
                                                <h4 class="fs-24 fw-bold">Chức năng</h4>
                                            </div>
                                            <div class="col-auto">
                                                <a href="" target="_blank" rel="noopener noreferrer">
                                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/arroww.svg" class="img-fluid" alt="">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space_cate">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7">
                            <h3 class="fs-48 fw-medium">
                                <?php echo $cspace_pro['title_cspace']['title_space_cate'] ?? ''; ?>
                            </h3>
                        </div>
                        <div class="col-lg-5">
                            <div class="desc fs-18 fw-medium">
                                <?php echo $cspace_pro['title_cspace']['subtitle_space_cate'] ?? ''; ?>
                            </div>
                            <a href="<?php echo $cspace_pro['title_cspace']['button_view_cate']['link'] ?? ''; ?>" class="btn btn-bgblack fs-18" target="_blank" rel="noopener noreferrer">
                                <?php echo $cspace_pro['title_cspace']['button_view_cate']['title'] ?? ''; ?> 
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/arroww.svg" class="img-fluid" alt="">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="all_cate">
                    <div class="row gx-0">
                        <?php
                        // =====================================================
                        // TỐI ƯU 2: Product Categories 
                        // =====================================================
                        $cat_args = array(
                            'taxonomy'   => 'product_cat',
                            'hide_empty' => false,
                            'number'     => 6,
                            'orderby'    => 'count',
                            'order'      => 'DESC',
                            'parent'     => 0,
                            'exclude'    => 15
                        );

                        $product_categories = get_terms($cat_args);

                        if (!empty($product_categories) && !is_wp_error($product_categories)) :
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
                                    <a href="<?php echo esc_url($term_link); ?>" target="_blank" rel="noopener noreferrer">
                                        <img src="<?php echo esc_url($image_url); ?>" class="img-fluid" alt="<?php echo esc_attr($cat->name); ?>" loading="lazy">
                                    </a>
                                </figure>
                                <div class="meta_info">
                                    <ul class="fs-18 fw-medium">
                                        <li>
                                            <a href="<?php echo esc_url($term_link); ?>" target="_blank" rel="noopener noreferrer">
                                                <?php echo esc_html($cat->name); ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo esc_url($term_link); ?>" target="_blank" rel="noopener noreferrer">
                                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/arrow.svg" class="img-fluid" alt="">
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php
                            endforeach;
                        else :
                            echo '<p class="text-center">Chưa có danh mục nào.</p>';
                        endif;
                        ?>
                    </div>
                </div>
            </div>

            <?php $com = $cspace_pro['space_comfor'] ?? ''; ?>
            <div class="space_comfor">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <h3 class="fs-48 fw-medium text-center title_group">
                                <?php echo $com['title'] ?? ''; ?>
                            </h3>
                        </div>
                    </div>
                    <div class="comfor_view">
                        <div class="row gx-0">
                            <?php for ($i = 1; $i <= 3; $i++) : 
                                $item_key = "comfor_item_{$i}";
                                $item = $com[$item_key] ?? [];
                            ?>
                            <div class="col-lg-4">
                                <div class="comfor_item">
                                    <figure>
                                        <a href="<?php echo $item['link'] ?? ''; ?>" target="_blank" rel="noopener noreferrer">
                                            <img src="<?php echo $item['image'] ?? ''; ?>" class="img-fluid" alt="" loading="lazy">
                                        </a>
                                    </figure>
                                </div>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="text-center">
                        <a href="<?php echo $com['button']['link'] ?? ''; ?>" target="_blank" rel="noopener noreferrer" class="btn btn-white">
                            <?php echo $com['button']['title'] ?? ''; ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="space_product">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4">
                            <h3 class="fs-48 fw-medium title_group">
                                <?php echo $cspace_pro['title_cspace']['title_product'] ?? ''; ?>
                            </h3>
                        </div>
                    </div>
                    <div class="product_wrapper">
                        <div class="product_slider">
                            <?php
                            // =====================================================
                            // TỐI ƯU 3: Products Query - Cải thiện đáng kể
                            // =====================================================
                            $products_args = array(
                                'post_type'              => 'product',
                                'posts_per_page'         => 8,
                                'post_status'            => 'publish',
                                'orderby'                => 'date',
                                'order'                  => 'DESC',
                                'no_found_rows'          => true,  // Không cần pagination
                                'ignore_sticky_posts'    => true,
                                'update_post_term_cache' => false, // Không cần terms
                                'update_post_meta_cache' => true,  // Cần meta cho gallery
                                'fields'                 => 'ids', // Chỉ lấy IDs trước
                            );

                            // Bước 1: Lấy IDs
                            $product_ids = get_posts($products_args);

                            if (!empty($product_ids)) :
                                // Bước 2: Prime meta cache cho tất cả products 1 lần
                                update_meta_cache('post', $product_ids);
                                
                                // Bước 3: Lấy gallery image IDs cho tất cả products
                                $gallery_images = array();
                                foreach ($product_ids as $pid) {
                                    $gallery = get_post_meta($pid, '_product_image_gallery', true);
                                    if ($gallery) {
                                        $gallery_arr = explode(',', $gallery);
                                        $gallery_images[$pid] = !empty($gallery_arr[0]) ? (int)$gallery_arr[0] : 0;
                                    } else {
                                        $gallery_images[$pid] = 0;
                                    }
                                }
                                
                                // Bước 4: Prime attachment cache
                                $attachment_ids = array_filter(array_values($gallery_images));
                                if (!empty($attachment_ids)) {
                                    // Lấy tất cả attachment URLs 1 lần
                                    $attachment_urls = array();
                                    foreach ($attachment_ids as $att_id) {
                                        $attachment_urls[$att_id] = wp_get_attachment_image_url($att_id, 'large');
                                    }
                                }
                                
                                // Bước 5: Render
                                foreach ($product_ids as $pid) :
                                    $post = get_post($pid);
                                    $thumb_id = $gallery_images[$pid];
                                    $thumb_url = '';
                                    
                                    if ($thumb_id && isset($attachment_urls[$thumb_id])) {
                                        $thumb_url = $attachment_urls[$thumb_id];
                                    }
                                    
                                    if (empty($thumb_url)) {
                                        $thumb_url = wc_placeholder_img_src();
                                    }
                            ?>
                            <div class="item">
                                <figure>
                                    <a href="<?php echo get_permalink($pid); ?>" target="_blank" rel="noopener noreferrer">
                                        <img src="<?php echo esc_url($thumb_url); ?>" class="img-fluid" alt="<?php echo esc_attr($post->post_title); ?>" loading="lazy">
                                    </a>
                                </figure>
                                <div class="meta_info">
                                    <h4 class="fs-20 fw-semibold">
                                        <a href="<?php echo get_permalink($pid); ?>" target="_blank" rel="noopener noreferrer">
                                            <?php echo esc_html($post->post_title); ?>
                                        </a>
                                    </h4>
                                </div>
                            </div>
                            <?php 
                                endforeach;
                            endif;
                            ?>
                        </div>
                    </div>
                    <div class="product_dots"></div>
                </div>
            </div>

            <?php $video = $cspace_pro['space_video'] ?? []; ?>
            <div class="space_video">
                <div class="container">
                    <div class="video_wrapper">
                        <?php 
                        $videoLink = !empty($video['link_youtube']) ? esc_url($video['link_youtube']) : '';
                        $imageSrc = !empty($video['image']) ? $video['image'] : '';
                        ?>
                        <a href="<?php echo $videoLink; ?>" data-fancybox="video-intro" class="d-block position-relative group-video">
                            <figure class="m-0">
                                <img src="<?php echo $imageSrc; ?>" class="img-fluid w-100" alt="Video cover" loading="lazy">
                            </figure>
                            <div class="playvideo">
                                <i class="fa fa-play"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

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

                    <div class="about_wrapper">
                        <?php 
                        $items = $about['about_item'] ?? [];
                        if (!empty($items)) :
                            $count = 0;
                            foreach ($items as $row) :
                                $count++;
                                $item_group = $row['item'] ?? null;
                                if ($item_group) :
                                    $image   = $item_group['image'] ?? '';
                                    $icon    = $item_group['icon'] ?? '';
                                    $title   = $item_group['title'] ?? '';
                                    $excerpt = $item_group['excerpt'] ?? '';
                        ?>
                        <div class="about_item <?php echo ($count == 1) ? 'active' : ''; ?>">
                            <div class="count"><?php echo str_pad($count, 2, '0', STR_PAD_LEFT); ?></div>
                            <figure>
                                <img src="<?php echo $image; ?>" class="img-fluid" alt="" loading="lazy">
                            </figure>
                            <div class="meta_info">
                                <div class="icon">
                                    <img src="<?php echo $icon; ?>" class="img-fluid" alt="">
                                </div>
                                <h4 class="fs-32 fw-medium"><?php echo esc_html($title); ?></h4>
                                <div class="excerpt fs-20"><?php echo nl2br(esc_html($excerpt)); ?></div>
                            </div>
                        </div>
                        <?php 
                                endif;
                            endforeach;
                        endif;
                        ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php $slider = $abouts['space_brand'] ?? []; ?>
            <?php if (!empty($slider['item'])) : ?>
            <div class="space_brand">
                <div class="splide brand_slider">
                    <div class="splide__track">
                        <ul class="splide__list">
                            <?php foreach ($slider['item'] as $y) : ?>
                            <li class="splide__slide">
                                <div class="item">
                                    <img src="<?php echo $y['image'] ?? ''; ?>" class="img-fluid" alt="" loading="lazy">
                                </div>
                            </li>
                            <?php endforeach; ?>
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

            <?php $digit = $abouts['space_digit'] ?? []; ?>
            <div class="space_digit">
                <?php
                $main_image = $digit['image'] ?? '';
                $accordion_list = $digit['accordion'] ?? [];
                ?>
                <div class="value_section">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-5">
                                <figure>
                                    <?php if ($main_image) : ?>
                                    <img src="<?php echo $main_image; ?>" class="img-fluid" alt="" loading="lazy">
                                    <?php endif; ?>
                                </figure>
                            </div>
                            <div class="col-lg-5 offset-lg-1">
                                <?php if (!empty($accordion_list)) : ?>
                                <div class="accordion" id="valueAccordition">
                                    <?php foreach ($accordion_list as $key => $row) :
                                        $item = $row['item'] ?? [];
                                        $icon = $item['icon'] ?? '';
                                        $title = $item['title'] ?? '';
                                        $excerpt = $item['excerpt'] ?? '';
                                        $collapse_id = 'vcollapse_' . ($key + 1);
                                        $is_first = ($key == 0);
                                    ?>
                                    <div class="accordion-item">
                                        <div class="accordion-header">
                                            <a class="accordion-button fs-32 fw-medium <?php echo $is_first ? '' : 'collapsed'; ?>"
                                               href="javascript:;" 
                                               data-bs-toggle="collapse"
                                               data-bs-target="#<?php echo $collapse_id; ?>"
                                               aria-expanded="<?php echo $is_first ? 'true' : 'false'; ?>"
                                               aria-controls="<?php echo $collapse_id; ?>">
                                                <?php if ($icon) : ?>
                                                <img src="<?php echo $icon; ?>" class="img-fluid me-2" alt="">
                                                <?php endif; ?>
                                                <?php echo esc_html($title); ?>
                                            </a>
                                        </div>
                                        <div id="<?php echo $collapse_id; ?>" 
                                             class="accordion-collapse collapse <?php echo $is_first ? 'show' : ''; ?>"
                                             data-bs-parent="#valueAccordition">
                                            <div class="accordion-body">
                                                <div class="fs-20 fw-medium"><?php echo nl2br(esc_html($excerpt)); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php $number = $abouts['number_section'] ?? []; ?>
                <div class="number_section">
                    <div class="container">
                        <div class="number_content">
                            <div class="row justify-content-between">
                                <div class="col-lg-5">
                                    <div class="sub_title fs-18"><?php echo $number['subtitle'] ?? ''; ?></div>
                                    <h3 class="fs-64 fw-medium title_group"><?php echo $number['title'] ?? ''; ?></h3>
                                    <div class="desc fs-20"><?php echo $number['desc'] ?? ''; ?></div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row digit_gap">
                                        <?php foreach ($number['digit_gap'] ?? [] as $x) : ?>
                                        <div class="col-lg-6">
                                            <div class="digit_item">
                                                <div class="fs-18 fw-medium digit_title"><?php echo $x['item']['title'] ?? ''; ?></div>
                                                <div class="fs-48 fw-medium digit_count">
                                                    <span><?php echo $x['item']['count'] ?? ''; ?></span>+
                                                </div>
                                                <div class="digit_in"><?php echo $x['item']['desc'] ?? ''; ?></div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php $solution = $abouts['space_solution'] ?? []; ?>
            <div class="space_solution">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="sub_title fs-18 text-center"><?php echo $solution['subtitle'] ?? ''; ?></div>
                            <h3 class="fs-64 fw-medium title_group text-center"><?php echo $solution['title'] ?? ''; ?></h3>
                            <div class="desc fs-20 text-center"><?php echo nl2br($solution['desc'] ?? ''); ?></div>
                        </div>
                    </div>
                    <div class="solution_list">
                        <div class="row gx-lg-0">
                            <?php foreach ($solution['solution_list'] ?? [] as $x) : ?>
                            <div class="col-lg-3">
                                <div class="solution_item">
                                    <figure>
                                        <img src="<?php echo $x['image'] ?? ''; ?>" class="img-fluid" alt="" loading="lazy">
                                    </figure>
                                    <div class="meta_info">
                                        <h4 class="fs-48 fw-medium text-center"><?php echo $x['meta_info'] ?? ''; ?></h4>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php $team = $abouts['space_team'] ?? []; ?>
            <div class="space_team">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="sub_title fs-18"><?php echo $team['subtitle'] ?? ''; ?></div>
                            <h3 class="fs-64 fw-medium title_group"><?php echo nl2br($team['title'] ?? ''); ?></h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-9 offset-lg-2">
                            <div class="row team_gap gx-lg-4 gx-2">
                                <?php foreach ($team['list_team'] ?? [] as $x) : ?>
                                <div class="col-lg-4 col-6">
                                    <div class="team_item">
                                        <figure>
                                            <img src="<?php echo $x['avatar'] ?? ''; ?>" class="img-fluid" alt="" loading="lazy">
                                        </figure>
                                        <div class="meta_info">
                                            <h4 class="fs-18 fw-medium"><?php echo $x['name'] ?? ''; ?></h4>
                                            <div class="position"><?php echo $x['position'] ?? ''; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="decox_events">
                <div class="container">
                    <h3 class="fs-48 fw-semibold text-center title_group">Event</h3>
                    <div class="slider_events">
                        <?php
                        // =====================================================
                        // TỐI ƯU 4: Events API - Đã có cache 12h
                        // =====================================================
                        $events = get_dezon_events_data(10);

                        if (!empty($events)) :
                            foreach ($events as $event) :
                                $title = $event->title->rendered;
                                $link  = $event->link;

                                // Lấy ảnh
                                $img_url = '';
                                if (isset($event->_embedded->{'wp:featuredmedia'}[0]->source_url)) {
                                    $img_url = $event->_embedded->{'wp:featuredmedia'}[0]->source_url;
                                } elseif (isset($event->yoast_head_json->og_image[0]->url)) {
                                    $img_url = $event->yoast_head_json->og_image[0]->url;
                                } else {
                                    $img_url = 'https://via.placeholder.com/810x894';
                                }

                                // Lấy thông tin ngày giờ
                                $info_obj = $event->info ?? null;
                                $address  = $info_obj->address ?? '';
                                $date_raw = $info_obj->date ?? '';
                                $time_raw = $info_obj->time ?? '';

                                $thu_hien_thi = function_exists('get_vietnamese_day_from_string') ? get_vietnamese_day_from_string($date_raw) : '';
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
                                    <img src="<?php echo esc_url($img_url); ?>" class="img-fluid wp-post-image" alt="<?php echo esc_attr(strip_tags($title)); ?>" loading="lazy">
                                </a>
                            </figure>
                            <div class="meta_info">
                                <div class="row">
                                    <div class="col">
                                        <h4 class="fs-20 fw-semibold post_title">
                                            <a href="<?php echo esc_url($link); ?>" target="_blank"><?php echo wp_kses_post($title); ?></a>
                                        </h4>
                                        <div class="fs-18 fw-bold address"><?php echo esc_html($address); ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <?php if (!empty($date_raw)) : ?>
                                        <div class="post_date">
                                            <div class="fs-16">
                                                <?php echo esc_html($thu_hien_thi); ?><br>
                                                <?php echo esc_html($gio_hien_thi); ?>
                                            </div>
                                            <span class="fs-18"><?php echo esc_html($ngay_thang_only); ?></span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
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

            <div class="space_post">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="fs-48 fw-semibold title_group">Các Bài viết từ Dezon</h3>
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
                        <div class="wp-pagenavi" id="dezon-pagination" role="navigation"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="space_form">
        <div class="container">
            <div class="row justify-content-center gap-lg-5">
                <div class="col-lg-6">
                    <div class="h-100 d-flex flex-column">
                        <h3 class="fs-48 fw-medium title_group"><?php echo $contact['title'] ?? ''; ?></h3>
                        <div class="contact_info fs-18">
                            <small>Email:</small>
                            <p><?php echo $contact['email'] ?? ''; ?></p>
                            <small>Số điện thoại:</small>
                            <p><?php echo $contact['phone'] ?? ''; ?></p>
                            <small><?php echo $contact['address_1']['title'] ?? ''; ?>:</small>
                            <p><?php echo $contact['address_1']['address'] ?? ''; ?></p>
                            <small><?php echo $contact['address_2']['title'] ?? ''; ?>:</small>
                            <p><?php echo $contact['address_2']['address'] ?? ''; ?></p>
                            <small>Theo dõi trên:</small>
                            <ul>
                                <?php foreach ($contact['socials'] ?? [] as $x) : ?>
                                <li>
                                    <a href="<?php echo $x['link'] ?? ''; ?>" target="_blank" rel="noopener noreferrer">
                                        <img src="<?php echo $x['icon'] ?? ''; ?>" alt="">
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <?php echo do_shortcode('[contact-form-7 id="e125025" title="Contact form"]'); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="space_map">
        <div class="map_wrapper">
            <div class="nav" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a href="javascript:;" class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill"
                   data-bs-target="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">
                    <h4 class="fs-18 fw-semibold"><?php echo $map['address_top']['title'] ?? ''; ?></h4>
                    <p class="fs-18">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/map.svg" class="img-fluid" alt="">
                        <?php echo $map['address_top']['address'] ?? ''; ?>
                    </p>
                </a>
                <a href="javascript:;" class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill"
                   data-bs-target="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                    <h4 class="fs-18 fw-semibold"><?php echo $map['address_buttom']['title'] ?? ''; ?></h4>
                    <p class="fs-18">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/map.svg" class="img-fluid" alt="">
                        <?php echo $map['address_buttom']['address'] ?? ''; ?>
                    </p>
                </a>
            </div>
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab" tabindex="0">
                    <div class="map_embed">
                        <iframe src="<?php echo $map['address_top']['link_map_embed'] ?? ''; ?>" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="0">
                    <div class="map_embed">
                        <iframe src="<?php echo $map['address_buttom']['link_map_embed'] ?? ''; ?>" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>