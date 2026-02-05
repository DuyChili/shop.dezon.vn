<?php
function my_theme_enqueue_assets() {
    // --- Enqueue CSS Files ---
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css');
    wp_enqueue_style('splide', get_template_directory_uri() . '/assets/css/splide.min.css');
    wp_enqueue_style('slick', get_template_directory_uri() . '/assets/css/slick.css');
    wp_enqueue_style('slick-theme', get_template_directory_uri() . '/assets/css/slick-theme.css');
    wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css');
    wp_enqueue_style('fancybox', get_template_directory_uri() . '/assets/css/fancybox.css');
    wp_enqueue_style('main-style', get_template_directory_uri() . '/assets/css/main.css', array(), '5.0');
     wp_enqueue_style('main-2-style', get_template_directory_uri() . '/assets/css/main-2.css', array(), '1.0');


    // --- Enqueue JavaScript Files ---
    wp_enqueue_script('bootstrap-bundle', get_template_directory_uri() . '/assets/js/bootstrap.bundle.min.js', array('jquery'), null, true);
    wp_enqueue_script('imagesloaded', get_template_directory_uri() . '/assets/js/imagesloaded.pkgd.min.js', array('jquery'), null, true);
    wp_enqueue_script('splide', get_template_directory_uri() . '/assets/js/splide.min.js', array(), null, true);
    wp_enqueue_script('splide-autoscroll', get_template_directory_uri() . '/assets/js/splide-extension-auto-scroll.min.js', array('splide'), null, true);
    wp_enqueue_script('slick', get_template_directory_uri() . '/assets/js/slick.min.js', array('jquery'), null, true);
    wp_enqueue_script('fancybox', get_template_directory_uri() . '/assets/js/fancybox.umd.js', array(), null, true);
    wp_enqueue_script('main-script', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '2.2', true);
    wp_enqueue_script('api-render-script', get_template_directory_uri() . '/assets/js/api-render.js', array('jquery'), '2.3', true);
    wp_enqueue_script('load-more-script', get_template_directory_uri() . '/assets/js/load-more.js', array('jquery'), '1.0', true);
    // wp_enqueue_script('main-2-script', get_template_directory_uri() . '/assets/js/main-2.js', array('jquery'), '2.2', true);
    
    // =====================================================
    // QUAN TRỌNG: Truyền biến PHP xuống JavaScript
    // =====================================================
    wp_localize_script('api-render-script', 'myLocalThemeParams', array(
        'defaultImage' => get_template_directory_uri() . '/assets/images/cate1.jpg',
        'iconPrev'     => get_template_directory_uri() . '/assets/images/first.svg',
        'iconNext'     => get_template_directory_uri() . '/assets/images/last.svg',
        'ajaxUrl'      => admin_url('admin-ajax.php'),
        'restUrl'      => rest_url('theme/v1/'),
    ));

    wp_localize_script('load-more-script', 'my_ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('ajax-nonce')
    ));

    
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_assets');


// =====================================================
// REST API: Proxy endpoint cho Dezon Posts (có cache)
// =====================================================
add_action('rest_api_init', function() {
    register_rest_route('theme/v1', '/dezon-posts', array(
        'methods'             => 'GET',
        'callback'            => 'get_cached_dezon_posts',
        'permission_callback' => '__return_true',
        'args'                => array(
            'page' => array(
                'default'           => 1,
                'sanitize_callback' => 'absint',
            ),
            'per_page' => array(
                'default'           => 6,
                'sanitize_callback' => 'absint',
            ),
        ),
    ));
});

function get_cached_dezon_posts($request) {
    $page = $request->get_param('page') ?: 1;
    $per_page = $request->get_param('per_page') ?: 6;
    $cache_key = "dezon_posts_page_{$page}_per_{$per_page}";
    
    // Kiểm tra cache
    $cached = get_transient($cache_key);
    if ($cached !== false) {
        $response = new WP_REST_Response($cached['data'], 200);
        $response->header('X-WP-TotalPages', $cached['total_pages']);
        $response->header('X-Cache', 'HIT');
        return $response;
    }
    
    // Gọi API từ Dezon
    $api_url = add_query_arg(array(
        '_embed'   => 'true',
        'per_page' => $per_page,
        'page'     => $page,
    ), 'https://dezon.vn/wp-json/wp/v2/posts');
    
    $remote_response = wp_remote_get($api_url, array(
        'timeout' => 15,
        'headers' => array(
            'Accept' => 'application/json',
        ),
    ));
    
    // Xử lý lỗi
    if (is_wp_error($remote_response)) {
        return new WP_Error(
            'api_error', 
            'Không thể kết nối đến Dezon API: ' . $remote_response->get_error_message(), 
            array('status' => 500)
        );
    }
    
    $status_code = wp_remote_retrieve_response_code($remote_response);
    if ($status_code !== 200) {
        return new WP_Error(
            'api_error', 
            'Dezon API trả về lỗi: ' . $status_code, 
            array('status' => $status_code)
        );
    }
    
    // Parse response
    $total_pages = wp_remote_retrieve_header($remote_response, 'X-WP-TotalPages') ?: 1;
    $body = wp_remote_retrieve_body($remote_response);
    $data = json_decode($body, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        return new WP_Error(
            'json_error', 
            'Lỗi parse JSON từ Dezon API', 
            array('status' => 500)
        );
    }
    
    // Lưu cache 30 phút
    set_transient($cache_key, array(
        'data'        => $data,
        'total_pages' => $total_pages,
    ), 30 * MINUTE_IN_SECONDS);
    
    // Trả về response
    $response = new WP_REST_Response($data, 200);
    $response->header('X-WP-TotalPages', $total_pages);
    $response->header('X-Cache', 'MISS');
    
    return $response;
}

// =====================================================
// Xóa cache khi cần (optional - gọi từ admin)
// =====================================================
function clear_dezon_posts_cache() {
    global $wpdb;

    // 1. Xóa cache bài viết Dezon (News)
    $wpdb->query(
        "DELETE FROM {$wpdb->options} 
         WHERE option_name LIKE '_transient_dezon_posts_page_%' 
         OR option_name LIKE '_transient_timeout_dezon_posts_page_%'"
    );

    // 2. Xóa cache Dự án (List & Count)
    // remote_proj_% sẽ xóa cả 'remote_proj_123' và 'remote_proj_count_123'
    $wpdb->query(
        "DELETE FROM {$wpdb->options} 
         WHERE option_name LIKE '_transient_remote_proj_%' 
         OR option_name LIKE '_transient_timeout_remote_proj_%'"
    );

    // 3. Xóa cache Đối tác (CẬP NHẬT MỚI)
    // Thêm dòng 'partners_ids_of_sup' vì đây là key chứa danh sách ID đối tác ta vừa tạo
    $wpdb->query(
        "DELETE FROM {$wpdb->options} 
         WHERE option_name LIKE '_transient_partners_of_sup_%' 
         OR option_name LIKE '_transient_timeout_partners_of_sup_%'
         OR option_name LIKE '_transient_partners_ids_of_sup_%' 
         OR option_name LIKE '_transient_timeout_partners_ids_of_sup_%'
         OR option_name LIKE '_transient_remote_partners_%'
         OR option_name LIKE '_transient_timeout_remote_partners_%'"
    );

    // 4. Xóa cache Gallery & Danh sách dự án liên kết (Admin)
    $wpdb->query(
        "DELETE FROM {$wpdb->options} 
         WHERE option_name LIKE '_transient_supplier_full_gallery_%' 
         OR option_name LIKE '_transient_timeout_supplier_full_gallery_%'
         OR option_name LIKE '_transient_remote_all_project_list'
         OR option_name LIKE '_transient_timeout_remote_all_project_list'"
    );

    // 5. Xóa cache Tài liệu (Documents)
    $wpdb->query(
        "DELETE FROM {$wpdb->options} 
         WHERE option_name LIKE '_transient_supplier_documents_list_v2_%' 
         OR option_name LIKE '_transient_timeout_supplier_documents_list_v2_%'"
    );
}

// Thêm nút clear cache trong admin (optional)
add_action('admin_bar_menu', function($wp_admin_bar) {
    if (!current_user_can('manage_options')) return;
    
    $wp_admin_bar->add_node(array(
        'id'    => 'clear-dezon-cache',
        'title' => '🗑️ Clear Dezon Cache',
        'href'  => admin_url('admin-post.php?action=clear_dezon_cache'),
    ));
}, 100);

add_action('admin_post_clear_dezon_cache', function() {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }
    clear_dezon_posts_cache();
    wp_redirect(wp_get_referer() ?: home_url());
    exit;
});


add_action('acf/init', 'my_acf_op_init');
function my_acf_op_init() {
    // Kiểm tra hàm có tồn tại không
    if( function_exists('acf_add_options_page') ) {
        acf_add_options_page(array(
            'page_title'    => 'Themes Settings',
            'menu_title'    => 'Themes Settings',
            'menu_slug'     => 'themes-settings', 
            'capability'    => 'edit_posts',
            'redirect'      => false
        ));
    }
}

// Trong file functions.php
function my_theme_register_nav_menus() {
    register_nav_menus(
        array(
            'primary_menu' => __( 'Primary Menu', 'uat.decox.vn' ),
            'footer_menu_col1' => __( 'Footer Menu Column 1', 'uat.decox.vn' ),
            'footer_menu_col2' => __( 'Footer Menu Column 2', 'uat.decox.vn' ),
            'footer_menu_col3' => __( 'Footer Menu Column 3', 'uat.decox.vn' ),
        )
    );
}
add_action( 'after_setup_theme', 'my_theme_register_nav_menus' );

// Hàm trợ giúp để hiển thị cột menu footer
if ( ! function_exists( 'render_footer_menu_column' ) ) {
    function render_footer_menu_column( $location ) {
        if ( has_nav_menu( $location ) ) {
            $locations = get_nav_menu_locations();
            $menu_obj = isset( $locations[ $location ] ) ? wp_get_nav_menu_object( $locations[ $location ] ) : false;
            $title = $menu_obj && ! empty( $menu_obj->name ) ? esc_html( $menu_obj->name ) : ucwords( str_replace('_', ' ', str_replace('footer_menu_', '', $location)));
            echo '<div class="col-lg-4 col-6">';
            echo '<h4 class="fs-16 fw-medium">' . $title . '</h4>';
            // Hiển thị menu
            wp_nav_menu(
                array(
                    'theme_location' => $location,
                    'container'      => false,
                    'menu_class'     => 'fs-18', 
                    'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>', 
                    'depth'          => 1, 
                    'fallback_cb'    => false,
                )
            );
            echo '</div>';
        }
    }
}

// Cho phép tải lên file SVG
function add_file_types_to_uploads($file_types){
    $file_types['svg'] = 'image/svg+xml';
    $file_types['svgz'] = 'image/svg+xml';
    return $file_types;
}
add_action('upload_mimes', 'add_file_types_to_uploads');


function decox_ajax_load_product_related_posts() {
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;

    $args = array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => 6,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $query = new WP_Query( $args );

    $response = array(
        'html'       => '',
        'pagination' => '',
        'success'    => false
    );

    if ( $query->have_posts() ) {
        ob_start();
        while ( $query->have_posts() ) {
            $query->the_post();
            
            // Lấy dữ liệu y hệt phần template
            $categories = get_the_category();
            $cat_name   = ! empty( $categories ) ? $categories[0]->name : '';
            $cat_link   = ! empty( $categories ) ? get_category_link( $categories[0]->term_id ) : '#';
            $reading_time = function_exists('get_decox_reading_time') ? get_decox_reading_time(get_the_ID()) : '3 phút đọc';
            $author_id = get_the_author_meta('ID');
            $author_avatar = get_avatar_url($author_id, ['size' => 50]);
            
            $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
            if(empty($thumb_url)) {
                $thumb_url = get_template_directory_uri() . '/assets/images/cate1.jpg';
            }
            ?>
            <div class="col-lg-4">
                <div class="post_item">
                    <figure>
                        <a href="<?php the_permalink(); ?>">
                            <img src="<?php echo esc_url($thumb_url); ?>" class="img-fluid" alt="<?php the_title_attribute(); ?>">
                        </a>
                    </figure>
                    <div class="meta_info">
                        <div class="row">
                            <div class="col-auto">
                                <div class="post_cate fw-semibold">
                                    <a href="<?php echo esc_url($cat_link); ?>"><?php echo esc_html($cat_name); ?></a>
                                </div>
                            </div>
                            <div class="col">
                                <div class="time_reading">
                                    <?php echo esc_html($reading_time); ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="like_post">
                                    <a href="#" class="add-to-favorite" data-post-id="<?php echo get_the_ID(); ?>">
                                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <h4 class="fs-20 fw-semibold post_title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h4>
                        <div class="desc fs-14 cl-gray400">
                            <?php echo wp_trim_words( get_the_excerpt(), 25, '...' ); ?>
                        </div>
                        <div class="post_meta">
                            <ul>
                                <li>
                                    <img src="<?php echo esc_url($author_avatar); ?>" class="img-fluid" alt="" style="width:24px; height:24px; border-radius:50%; object-fit:cover; margin-right:5px;">
                                    <?php the_author(); ?>
                                </li>
                                <li><?php echo get_the_date('d/m/Y'); ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        $response['html'] = ob_get_clean();

        // Tạo lại Pagination
        $big = 999999999;
        $response['pagination'] = paginate_links( array(
            'base'      => '#',
            'format'    => '?paged=%#%',
            'current'   => max( 1, $paged ),
            'total'     => $query->max_num_pages,
            'prev_text' => '<img src="' . get_template_directory_uri() . '/assets/images/first.svg" class="img-fluid" alt="Previous">',
            'next_text' => '<img src="' . get_template_directory_uri() . '/assets/images/last.svg" class="img-fluid" alt="Next">',
            'type'      => 'plain',
        ) );
        $response['success'] = true;
    }
    
    wp_reset_postdata();
    wp_send_json($response);
}
add_action('wp_ajax_decox_ajax_load_product_related_posts', 'decox_ajax_load_product_related_posts');
add_action('wp_ajax_nopriv_decox_ajax_load_product_related_posts', 'decox_ajax_load_product_related_posts');

/* * Thay đổi ký hiệu tiền tệ từ ₫ sang VND 
 */
add_filter( 'woocommerce_currency_symbol', 'tk_change_vnd_currency_symbol', 10, 2 );
function tk_change_vnd_currency_symbol( $currency_symbol, $currency ) {
    switch( $currency ) {
        case 'VND': 
            $currency_symbol = 'VND'; 
            break;
    }
    return $currency_symbol;
}


add_filter( 'wpseo_breadcrumb_links', 'tk_custom_breadcrumbs_remove_category' );

function tk_custom_breadcrumbs_remove_category( $links ) {

    if ( is_singular( 'product' ) ) {
        
        // 1. Tạo Link Home (Dezon.vn)
        $dezon_home = array(
            'url'  => 'https://dezon.vn',
            'text' => 'Trang chủ',
        );

        $cspace_home = isset($links[0]) ? $links[0] : array(
            'url'  => home_url('/'),
            'text' => 'Cspace'
        );
        $current_product = end( $links );
        $links = array( $dezon_home, $cspace_home, $current_product );
    }

    return $links;
}

add_filter( 'wpseo_breadcrumb_separator', 'tk_custom_breadcrumb_separator' );

function tk_custom_breadcrumb_separator( $separator ) {
    return '<i class="fa fa-angle-right"></i>';
}

add_filter( 'product_type_selector', 'remove_unwanted_product_types' );

function remove_unwanted_product_types( $types ) {
    unset( $types['grouped'] );
    unset( $types['external'] );

    return $types;
}

// Ẩn metabox "Product Image" trong trang chỉnh sửa sản phẩm
function hide_product_image_metabox() {
    remove_meta_box( 'postimagediv', 'product', 'side' );
}
add_action( 'do_meta_boxes', 'hide_product_image_metabox' );

// ghi chú vào metabox Product Gallery trong trang Admin
function add_note_to_product_gallery_metabox() {
    global $post;
    if ( ! $post || 'product' !== get_post_type( $post ) ) {
        return;
    }
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var noteHtml = '<p style="color: blue; font-style: italic; padding: 8px;">' +
                           '<b>Lưu ý:</b> Ảnh đầu tiên trong bộ sưu tập này sẽ được dùng làm <b>Ảnh đại diện (Thumbnail)</b>' +
                           '</p>';
            
            $('#woocommerce-product-images .inside').prepend(noteHtml);
        });
    </script>
    <?php
}
add_action( 'admin_footer', 'add_note_to_product_gallery_metabox' );

/**
 * Tự động lấy ảnh đầu tiên trong Gallery làm Ảnh đại diện (Thumbnail)
 */
add_filter( 'woocommerce_product_get_image_id', 'force_gallery_first_image_as_thumbnail', 10, 2 );

function force_gallery_first_image_as_thumbnail( $image_id, $product ) {
    if ( $image_id ) {
        return $image_id;
    }

    $gallery_ids = $product->get_gallery_image_ids();

    if ( ! empty( $gallery_ids ) ) {
        return $gallery_ids[0];
    }
    return $image_id;
}

/*
 * Register Custom Post Type: Supplier
 */
function create_supplier_cpt() {
    $labels = array(
        'name'                  => 'Suppliers', 
        'singular_name'         => 'Supplier',
        'menu_name'             => 'Supplier',
        'name_admin_bar'        => 'Supplier',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New Supplier',
        'new_item'              => 'New Supplier',
        'edit_item'             => 'Edit Supplier',
        'view_item'             => 'View Supplier',
        'all_items'             => 'All Suppliers',
        'search_items'          => 'Search Suppliers',
        'not_found'             => 'No suppliers found',
        'not_found_in_trash'    => 'No suppliers found in Trash',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'nha-cung-cap' ), 
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-store', 
        'supports'           => array( 'title', 'thumbnail','editor', 'thumbnail', 'excerpt' ),//, 
    );

    register_post_type( 'supplier', $args );
    
}
add_action( 'init', 'create_supplier_cpt' );

function create_supplier_taxonomies() {
    
    //  Region (category-like)
    register_taxonomy( 'supplier_location', 'supplier', array(
        'hierarchical'      => true,
        'labels'            => array(
            'name'          => 'Location',
            'singular_name' => 'Khu vực',
            'add_new_item'  => 'Add New Location',
            'menu_name'     => 'Locations'
        ),
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array(
            'slug'       => 'khu-vuc',
            'with_front' => false
        ),
    ));
    register_taxonomy( 'supplier_expertise', 'supplier', array(
        'hierarchical'      => true,
        'labels'            => array(
            'name'          => 'Expertise',
            'singular_name' => 'Chuyên môn',
            'add_new_item'  => 'Add New Expertise',
            'menu_name'     => 'Expertise'
        ),
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array(
            'slug'       => 'chuyen-mon',
            'with_front' => false
        ),
    ));
}
add_action( 'init', 'create_supplier_taxonomies', 0 );


add_action('admin_footer', 'custom_clean_acf_relationship_filters');

function custom_clean_acf_relationship_filters() {
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        
        function cleanACFFilters() {
            $('.acf-field-relationship .filters select').each(function() {
                var $select = $(this);
                $select.find('optgroup[label="Product visibility"]').remove();
                $select.find('optgroup[label="Product type"] option').each(function() {
                    var text = $(this).text().trim().toLowerCase();
                    if( text === 'external' || text === 'grouped' ) {
                        $(this).remove();
                    }
                });
            });
        }
        cleanACFFilters();
        if( typeof acf !== 'undefined' ) {
            acf.addAction('ready', cleanACFFilters);
            acf.addAction('append', cleanACFFilters);
        }
    });
    </script>
    <?php
}


add_filter( 'wpcf7_mail_components', 'dezon_final_dynamic_email', 10, 3 );

function dezon_final_dynamic_email( $components, $form, $obj ) {
    if ( (int) $form->id() !== 236 ) {
        return $components;
    }
    $submission = WPCF7_Submission::get_instance();
    $post_id = $submission ? (int) $submission->get_meta( 'container_post_id' ) : 0;
    if ( !$post_id && isset($_POST['_wpcf7_container_post']) ) {
        $post_id = (int) $_POST['_wpcf7_container_post'];
    }

    if ( $post_id > 0 ) {
        $contact = get_field('contact', $post_id);
        if ( !empty($contact) && !empty($contact['email']) ) {
            $components['recipient'] = trim( $contact['email'] );
        }
    }
    
    return $components;
}

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

add_filter( 'loop_shop_per_page', 'dezon_custom_products_per_page', 20 );

function dezon_custom_products_per_page( $cols ) {
    return 6; 
}

/* -------------------------------------------------------------------------- */
/* Hàm lấy thứ tiếng Việt
/* -------------------------------------------------------------------------- */
if (!function_exists('get_vietnamese_day_from_string')) {
    function get_vietnamese_day_from_string($date_string) {
        $date = DateTime::createFromFormat('d/m/Y', $date_string);
        if ($date) {
            $days = ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'];
            return $days[$date->format('w')];
        }
        return '';
    }
}

/* -------------------------------------------------------------------------- */
/* Hàm gọi API Dezon có Cache
/* -------------------------------------------------------------------------- */
function get_dezon_events_data($limit = 10) {
    $cache_key = 'dezon_events_data_cache_' . $limit;
    $events = get_transient($cache_key);
    if (false === $events) {
        $api_url = "https://dezon.vn/wp-json/wp/v2/event?per_page={$limit}&_embed";
        $response = wp_remote_get($api_url, array('timeout' => 5));

        if (is_wp_error($response)) {
            return []; // Trả về mảng rỗng nếu lỗi
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body);

        if (!empty($data) && is_array($data)) {
            $events = $data;
            // Lưu cache trong 12 tiếng (43200 giây)
            set_transient($cache_key, $events, 12 * HOUR_IN_SECONDS);
        } else {
            return [];
        }
    }

    return $events;
}

/**
 * Hàm tạo slug từ chuỗi text (Hỗ trợ tiếng Việt)
 */
if (!function_exists('get_slug_from_title')) {
    function get_slug_from_title($string) {
        if (empty($string)) {
            return '';
        }
        return sanitize_title($string);
    }
}

add_action('acf/save_post', 'auto_sync_supplier_to_woo_brand_with_image', 20);

function auto_sync_supplier_to_woo_brand_with_image($post_id) {
    
    // 1. Chỉ chạy khi post type là 'supplier'
    if (get_post_type($post_id) !== 'supplier') {
        return;
    }

    // ==========================================================
    // CẤU HÌNH: ĐIỀN SLUG BRAND CỦA BẠN (Lấy từ URL như bước trước)
    // ==========================================================
    $taxonomy_slug = 'product_brand'; // <-- Ví dụ: 'pwb-brand', 'product_brand', 'brand'

    // 2. Lấy tên Supplier
    $supplier_name = get_the_title($post_id);
    if (empty($supplier_name)) return;

    // 3. Xử lý Brand (Tìm hoặc Tạo mới)
    $term_id = 0;
    $term = term_exists($supplier_name, $taxonomy_slug);

    if ($term !== 0 && $term !== null) {
        $term_id = (int)$term['term_id'];
    } else {
        $new_term = wp_insert_term($supplier_name, $taxonomy_slug);
        if (!is_wp_error($new_term)) {
            $term_id = (int)$new_term['term_id'];
        }
    }

    // Nếu không có term_id thì dừng
    if (!$term_id) return;

    // ==========================================================
    // 4. MỚI: ĐỒNG BỘ ẢNH ĐẠI DIỆN (THUMBNAIL)
    // ==========================================================
    
    // Lấy ID ảnh đại diện của bài Supplier
    $image_id = get_post_thumbnail_id($post_id);

    if ($image_id) {
        update_term_meta($term_id, 'thumbnail_id', $image_id);
        update_term_meta($term_id, 'pwb_brand_image', $image_id);
    }

    // 5. Đồng bộ Product (Gán Brand vào Product)
    $products_to_update = array();

    if (have_rows('item_product', $post_id)) {
        while (have_rows('item_product', $post_id)) {
            the_row();
            $product_obj = get_sub_field('item'); 
            
            if ($product_obj) {
                if (is_array($product_obj)) {
                     foreach ($product_obj as $p) {
                        $products_to_update[] = is_object($p) ? $p->ID : $p;
                     }
                } elseif (is_object($product_obj)) {
                    $products_to_update[] = $product_obj->ID;
                } else {
                    $products_to_update[] = $product_obj;
                }
            }
        }
    }

    if (!empty($products_to_update)) {
        $products_to_update = array_unique($products_to_update);
        foreach ($products_to_update as $pid) {
            // Gán Brand vào Product (false = xóa brand cũ, thay bằng brand này)
            wp_set_object_terms($pid, $term_id, $taxonomy_slug, false);
        }
    }
}
// add_action('save_post_product', 'auto_sync_product_to_supplier_acf_post_object', 20, 3);

// function auto_sync_product_to_supplier_acf_post_object($post_id, $post, $update) {
    
//     // 1. Kiểm tra điều kiện cơ bản
//     if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
//     if ($post->post_status !== 'publish') return;

//     // ==========================================================
//     // CẤU HÌNH SLUG BRAND (Kiểm tra kỹ slug này trên web bạn)
//     // ==========================================================
//     $taxonomy_slug = 'product_brand'; 
    
//     $brands = wp_get_post_terms($post_id, $taxonomy_slug);

//     if (empty($brands) || is_wp_error($brands)) return;

//     foreach ($brands as $brand) {
//         $supplier_name = $brand->name;

//         // Tìm Supplier có tên trùng với Brand
//         $args = array(
//             'post_type'              => 'supplier',
//             'title'                  => $supplier_name,
//             'post_status'            => 'publish',
//             'posts_per_page'         => 1,
//             'fields'                 => 'ids',
//             'no_found_rows'          => true,
//             'update_post_term_cache' => false,
//             'update_post_meta_cache' => false,
//         );

//         $query = new WP_Query($args);

//         if ($query->have_posts()) {
//             $supplier_id = $query->posts[0];

//             // ==========================================================
//             // QUAN TRỌNG: LẤY FIELD 'item' (Theo hình bạn gửi)
//             // Tham số false ở cuối để LẤY RAW ID (kể cả khi setup là Return Object)
//             // ==========================================================
//             $current_product_ids = get_field('item', $supplier_id, false);
            
//             // Đảm bảo dữ liệu là mảng
//             if (empty($current_product_ids)) {
//                 $current_product_ids = array();
//             } elseif (!is_array($current_product_ids)) {
//                 // Trường hợp data cũ bị lỗi format
//                 $current_product_ids = array($current_product_ids);
//             }

//             // Ép kiểu về số nguyên để so sánh cho chuẩn
//             $current_product_ids = array_map('intval', $current_product_ids);

//             // Kiểm tra: Nếu sản phẩm chưa có trong list thì thêm vào
//             if (!in_array($post_id, $current_product_ids)) {
//                 $current_product_ids[] = $post_id;

//                 // Cập nhật lại field 'item' cho Supplier
//                 update_field('item', $current_product_ids, $supplier_id);
//             }
//         }
//     }
// }

/**
 * Cho phép hiển thị biến thể ngay cả khi không nhập giá (Empty Price)
 */
add_filter( 'woocommerce_variation_is_visible', 'force_variation_visible_without_price', 10, 4 );

function force_variation_visible_without_price( $is_visible, $variation_id, $parent_id, $variation ) {
    // Luôn trả về true để bắt buộc hiển thị
    return true;
}

/**
 * Thêm Avatar mặc định mới 
 */
function decox_custom_default_avatar( $avatar_defaults ) {
    $my_new_avatar_url = get_template_directory_uri() . '/assets/images/logo-dezon-black.jpeg'; 
    $avatar_defaults[$my_new_avatar_url] = 'Decox Official Avatar'; 
    
    return $avatar_defaults;
}
add_filter( 'avatar_defaults', 'decox_custom_default_avatar' );


class Dezon_Remote_Project_Fetcher {

    // Cấu hình Database của Web B (Project Site - Dezon.vn)
    private $db_user = 'decoxvn_user';
    private $db_pass = '&Decox@2025!';
    private $db_name = 'decoxvn_db';
    private $db_host = 'localhost';
    private $table_prefix = 'wp_'; // Prefix của web kia (thường là wp_)
    
    const REMOTE_SITE_URL = 'https://dezon.vn'; 

    private $db_connection = null;

    private function get_connection() {
        if ( $this->db_connection === null ) {
            $this->db_connection = new wpdb( $this->db_user, $this->db_pass, $this->db_name, $this->db_host );
        }
        return $this->db_connection;
    }

    /**
     * CẬP NHẬT: Thêm $offset để phân trang và Cache để tăng tốc độ
     */
    public function get_projects_by_supplier( $current_supplier_id, $limit = 6, $offset = 0 ) {
        
        // 1. TỐI ƯU: Kiểm tra Cache trước
        // Key cache định danh theo Supplier ID, Limit và Offset
        $cache_key = 'remote_proj_' . $current_supplier_id . '_' . $limit . '_' . $offset;
        $cached_data = get_transient( $cache_key );

        if ( false !== $cached_data ) {
            return $cached_data; // Trả về ngay nếu đã có cache (Siêu nhanh)
        }

        // 2. Nếu không có cache, mới kết nối Remote DB
        $remote_db = $this->get_connection();
        if ( ! empty( $remote_db->error ) ) return [];

        $like_query = '%' . $remote_db->esc_like( '"' . $current_supplier_id . '"' ) . '%';
        
        // Query có thêm OFFSET
        $query = $remote_db->prepare(
            "SELECT p.ID, p.post_title, p.post_name 
             FROM {$this->table_prefix}posts p
             INNER JOIN {$this->table_prefix}postmeta pm ON p.ID = pm.post_id
             WHERE p.post_type = 'project' 
             AND p.post_status = 'publish'
             AND (pm.meta_key = 'selected_suppliers' OR pm.meta_key = 'supplier_selected_suppliers')
             AND pm.meta_value LIKE %s
             GROUP BY p.ID
             ORDER BY p.post_date DESC
             LIMIT %d, %d", // Limit Offset, RowCount
            $like_query,
            $offset, // Vị trí bắt đầu
            $limit   // Số lượng lấy
        );

        $posts = $remote_db->get_results( $query );
        $projects_data = [];

        if ( $posts ) {
            foreach ( $posts as $post ) {
                $projects_data[] = [
                    'id'        => $post->ID,
                    'title'     => $post->post_title,
                    'link'      => self::REMOTE_SITE_URL . '/du-an/' . $post->post_name . '/',
                    'thumbnail' => $this->get_remote_slider_images( $remote_db, $post->ID ), // Hàm này nặng, cache sẽ giúp chỗ này
                    'studio'    => $this->get_remote_studio_data( $remote_db, $post->ID ),
                    'categories'=> $this->get_remote_terms( $remote_db, $post->ID, 'project_category' ),
                    'views'     => $this->get_remote_views_from_table( $remote_db, $post->ID ),
                    'has_ai'    => $this->check_remote_ai_content( $remote_db, $post->ID )
                ];
            }
        }

        // 3. Lưu vào Cache trong 12 tiếng (43200 giây)
        set_transient( $cache_key, $projects_data, 1 * HOUR_IN_SECONDS );

        return $projects_data;
    }

    private function get_remote_slider_images( $db, $post_id ) {
        $slider_images = [];

        // Dùng SQL LIKE để quét trực tiếp các key: media_slider_gallery_0_image, media_slider_gallery_1_image...
        // Cách này bỏ qua việc check count (thường bị lỗi null hoặc 0)
        // Tìm tất cả các meta_value là ID ảnh (số nguyên dương) của các field trong repeater slider_gallery
        $query = $db->prepare(
            "SELECT meta_value FROM {$this->table_prefix}postmeta 
             WHERE post_id = %d 
             AND meta_key LIKE %s
             ORDER BY meta_id ASC 
             LIMIT 5", // Giới hạn 5 ảnh để load cho nhanh
            $post_id,
            '%slider_gallery%_image' // Tìm tất cả key chứa cụm từ này (ví dụ: media_slider_gallery_0_image)
        );

        $image_ids = $db->get_col( $query );

        if ( ! empty( $image_ids ) ) {
            foreach ( $image_ids as $img_id ) {
                // Kiểm tra nếu ID hợp lệ (là số và > 0)
                if ( is_numeric($img_id) && $img_id > 0 ) {
                    $url = $this->get_image_url_by_id( $db, $img_id );
                    if ( $url ) {
                        $slider_images[] = $url;
                    }
                }
            }
        }

        // FALLBACK: Nếu vẫn chưa có ảnh -> Lấy Featured Image
        if ( empty( $slider_images ) ) {
            $thumb_id = $db->get_var( $db->prepare(
                "SELECT meta_value FROM {$this->table_prefix}postmeta 
                 WHERE post_id = %d AND meta_key = '_thumbnail_id'", 
                $post_id 
            ));
            
            if ( $thumb_id ) {
                $url = $this->get_image_url_by_id( $db, $thumb_id );
                if ( $url ) $slider_images[] = $url;
            }
        }

        // MẶC ĐỊNH: Nếu không có bất kỳ ảnh nào -> Lấy ảnh placeholder
        if ( empty( $slider_images ) ) {
            $slider_images[] = get_template_directory_uri() . '/assets/images/du-an-default.png';
        }

        return $slider_images;
    }

    // Helper: Lấy URL ảnh từ ID attachment (cần có trong class)
    private function get_image_url_by_id( $db, $attachment_id ) {
        // Kiểm tra nếu ID thực ra là URL (trường hợp hiếm nhưng có thể xảy ra)
        if ( !is_numeric($attachment_id) && filter_var($attachment_id, FILTER_VALIDATE_URL) ) {
            return $attachment_id;
        }

        $file_path = $db->get_var( $db->prepare(
            "SELECT meta_value FROM {$this->table_prefix}postmeta 
             WHERE post_id = %d AND meta_key = '_wp_attached_file'", 
            $attachment_id
        ));
        if ( $file_path ) {
            // Nối với đường dẫn upload của site remote
            return self::REMOTE_SITE_URL . '/wp-content/uploads/' . $file_path;
        }
        return null;
    }

    // 2. Lấy thông tin Studio (Tên và Link)
    private function get_remote_studio_data( $db, $post_id ) {
        $studio_id_serialized = $db->get_var( "SELECT meta_value FROM {$this->table_prefix}postmeta WHERE post_id = $post_id AND meta_key = 'project_studio'" );
        $studio_id = maybe_unserialize( $studio_id_serialized );
        
        if ( is_array( $studio_id ) ) $studio_id = !empty($studio_id) ? $studio_id[0] : 0;

        if ( $studio_id ) {
            $studio = $db->get_row( "SELECT post_title, post_name FROM {$this->table_prefix}posts WHERE ID = $studio_id" );
            if($studio){
                return [
                    'name' => $studio->post_title,
                    'link' => self::REMOTE_SITE_URL . '/studio/' . $studio->post_name . '/' 
                ];
            }
        }
        return null;
    }

    // 3. Lấy Categories
    private function get_remote_terms( $db, $post_id, $taxonomy ) {
        // Lấy cả tên (name) và đường dẫn tĩnh (slug)
        $sql = "SELECT t.name, t.slug 
                FROM {$this->table_prefix}terms t 
                INNER JOIN {$this->table_prefix}term_taxonomy tt ON t.term_id = tt.term_id 
                INNER JOIN {$this->table_prefix}term_relationships tr ON tt.term_taxonomy_id = tr.term_taxonomy_id 
                WHERE tt.taxonomy = '$taxonomy' AND tr.object_id = $post_id 
                LIMIT 2";
        
        $terms = $db->get_results( $sql );
        
        // Xử lý thêm link trực tiếp sang site kia
        if ( !empty($terms) ) {
            foreach ( $terms as $term ) {
                // Tạo link: https://dezon.vn/the-loai-du-an/{slug}/
                // Lưu ý: Cần check rewrite rule bên kia. Thường là /category/ hoặc custom slug.
                // Dựa trên code functions.php bên kia: 'rewrite' => array( 'slug' => 'the-loai-du-an' )
                $term->link = self::REMOTE_SITE_URL . '/the-loai-du-an/' . $term->slug . '/';
            }
        }
        
        return $terms;
    }

    // 4. Lấy Views từ bảng riêng `wp_project_views` (Theo code functions.php bạn gửi)
    private function get_remote_views_from_table( $db, $post_id ) {
        $table_views = $this->table_prefix . 'project_views';
        // Kiểm tra xem bảng có tồn tại không để tránh lỗi
        $check_table = $db->get_var("SHOW TABLES LIKE '$table_views'");
        
        if($check_table) {
            $views = $db->get_var( $db->prepare( "SELECT views FROM $table_views WHERE project_id = %d", $post_id ) );
            $views = (int)$views;
        } else {
            $views = 0;
        }

        // Format số (k, m, b) giống hàm decox_get_formatted_views
        if ( $views >= 1000000000 ) return round( $views / 1000000000, 1 ) . 'b';
        if ( $views >= 1000000 ) return round( $views / 1000000, 1 ) . 'm';
        if ( $views >= 1000 ) return round( $views / 1000, 1 ) . 'k';
        
        return (string)$views;
    }


    public function get_partners_via_supplier_projects( $current_supplier_id, $limit = 15, $offset = 0 ) {
        error_log("DEBUG PARTNER: Supplier ID: $current_supplier_id - Limit nhận được: $limit - Offset: $offset");
        // 1. Key cache lưu toàn bộ ID (để dùng chung cho mọi trang)
        $cache_key = 'partners_ids_of_sup_' . $current_supplier_id;
        $studio_ids = get_transient( $cache_key );

        if ( false === $studio_ids ) {
            $remote_db = $this->get_connection();
            if ( ! empty( $remote_db->error ) ) return [];

            // Query lấy TOÀN BỘ ID Studio
            $supplier_search = '%"' . $remote_db->esc_like( $current_supplier_id ) . '"%';
            $query = $remote_db->prepare(
                "SELECT DISTINCT m2.meta_value 
                FROM {$this->table_prefix}postmeta m1
                INNER JOIN {$this->table_prefix}postmeta m2 ON m1.post_id = m2.post_id
                WHERE m1.meta_key = 'supplier_selected_suppliers' 
                AND m1.meta_value LIKE %s
                AND m2.meta_key = 'project_studio'
                AND m2.meta_value != ''", 
                $supplier_search
            );
            
            $studio_meta_values = $remote_db->get_col( $query );

            // Xử lý ID và lọc trùng
            $temp_ids = [];
            if ( $studio_meta_values ) {
                foreach ( $studio_meta_values as $meta_val ) {
                    $data = maybe_unserialize( $meta_val );
                    if ( is_array($data) ) {
                        $temp_ids = array_merge($temp_ids, $data);
                    } elseif ( is_numeric($data) ) {
                        $temp_ids[] = $data;
                    }
                }
            }
            $studio_ids = array_unique( array_filter( array_map('intval', $temp_ids) ) );
            
            set_transient( $cache_key, $studio_ids, 1 * HOUR_IN_SECONDS );
        }

        if ( empty($studio_ids) ) return [];

        // 2. Cắt mảng ID theo limit/offset (Logic phân trang)
        $sliced_ids = ($limit > 0) ? array_slice($studio_ids, $offset, $limit) : $studio_ids;

        if ( empty($sliced_ids) ) return [];

        // 3. Query lấy thông tin chi tiết
        $remote_db = $this->get_connection(); 
        $ids_placeholder = implode(',', $sliced_ids);
        
        $partners_query = "SELECT ID, post_title, post_name 
                        FROM {$this->table_prefix}posts 
                        WHERE ID IN ($ids_placeholder) 
                        AND post_status = 'publish'";
        
        $posts = $remote_db->get_results( $partners_query );
        $partners_data = [];

        if ( $posts ) {
            foreach ( $posts as $post ) {
                $partners_data[] = [
                    'id'    => $post->ID,
                    'title' => $post->post_title,
                    'link'  => self::REMOTE_SITE_URL . '/studio/' . $post->post_name . '/',
                    'logo'  => $this->get_remote_featured_image_url($remote_db, $post->ID),
                ];
            }
        }

        return $partners_data;
    }
    private function get_remote_featured_image_url( $db, $post_id ) {
        $thumb_id = $db->get_var( $db->prepare( "SELECT meta_value FROM {$this->table_prefix}postmeta WHERE post_id = %d AND meta_key = '_thumbnail_id'", $post_id ));
        if ( $thumb_id ) {
            $file_path = $db->get_var( $db->prepare( "SELECT meta_value FROM {$this->table_prefix}postmeta WHERE post_id = %d AND meta_key = '_wp_attached_file'", $thumb_id ));
            if ( $file_path ) return self::REMOTE_SITE_URL . '/wp-content/uploads/' . $file_path;
        }
        return get_template_directory_uri() . '/assets/images/partner-default.png'; // Fallback image
    }

    // 5. Check AI Content
    private function check_remote_ai_content( $db, $post_id ) {
        $ai_content = $db->get_var( "SELECT meta_value FROM {$this->table_prefix}postmeta WHERE post_id = $post_id AND meta_key = 'content_for_ai_content'" );
        return !empty($ai_content);
    }

    public function get_remote_projects_linked_to_supplier( $local_supplier_id ) {
    
        $remote_db = $this->get_connection();
        if ( ! empty( $remote_db->error ) ) return [];
        
        $like_query = '%"' . $remote_db->esc_like( $local_supplier_id ) . '"%';

        // Query tối ưu: Join bảng Posts và Postmeta
        $query = $remote_db->prepare(
            "SELECT p.ID, p.post_title
            FROM {$this->table_prefix}posts p
            INNER JOIN {$this->table_prefix}postmeta pm ON p.ID = pm.post_id
            WHERE p.post_type = 'project'
            AND p.post_status = 'publish'
            AND pm.meta_key = 'supplier_selected_suppliers' 
            AND pm.meta_value LIKE %s
            ORDER BY p.post_date DESC",
            $like_query
        );

        $results = $remote_db->get_results( $query );

        $choices = [];
        if ( $results ) {
            foreach ( $results as $p ) {
                $choices[ $p->ID ] = $p->post_title;
            }
        }
        
        return $choices;
    }

    public function count_total_projects( $current_supplier_id ) {
        $cache_key = 'remote_proj_count_' . $current_supplier_id;
        $count = get_transient( $cache_key );

        if ( false !== $count ) {
            return $count;
        }

        $remote_db = $this->get_connection();
        if ( ! empty( $remote_db->error ) ) return 0;

        $like_query = '%' . $remote_db->esc_like( '"' . $current_supplier_id . '"' ) . '%';

        // Chỉ đếm số lượng ID
        $query = $remote_db->prepare(
            "SELECT COUNT(DISTINCT p.ID) 
             FROM {$this->table_prefix}posts p
             INNER JOIN {$this->table_prefix}postmeta pm ON p.ID = pm.post_id
             WHERE p.post_type = 'project' 
             AND p.post_status = 'publish'
             AND (pm.meta_key = 'selected_suppliers' OR pm.meta_key = 'supplier_selected_suppliers')
             AND pm.meta_value LIKE %s",
            $like_query
        );

        $count = $remote_db->get_var( $query );
        
        // Cache 12 tiếng
        set_transient( $cache_key, $count, 12 * HOUR_IN_SECONDS );

        return (int)$count;
    }

    /**
     * Hàm đếm tổng số đối tác (Remote)
     */
    public function count_total_partners( $current_supplier_id ) {
        // Tận dụng lại cache ID của hàm get_partners_via_supplier_projects nếu có
        $cache_key_ids = 'partners_ids_of_sup_' . $current_supplier_id;
        $cached_ids = get_transient( $cache_key_ids );

        if ( false !== $cached_ids && is_array($cached_ids) ) {
            return count($cached_ids);
        }

        // Nếu chưa có cache ID, query đếm trực tiếp
        $remote_db = $this->get_connection();
        if ( ! empty( $remote_db->error ) ) return 0;

        $supplier_search = '%"' . $remote_db->esc_like( $current_supplier_id ) . '"%';
        
        // Query đếm số lượng Studio Unique
        $query = $remote_db->prepare(
            "SELECT COUNT(DISTINCT m2.meta_value) 
            FROM {$this->table_prefix}postmeta m1
            INNER JOIN {$this->table_prefix}postmeta m2 ON m1.post_id = m2.post_id
            WHERE m1.meta_key = 'supplier_selected_suppliers' 
            AND m1.meta_value LIKE %s
            AND m2.meta_key = 'project_studio'
            AND m2.meta_value != ''", 
            $supplier_search
        );

        $count = $remote_db->get_var( $query );
        return (int)$count;
    }


}

/**
 * AJAX Handler: Load More Remote Projects
 */
add_action('wp_ajax_load_more_supplier_projects', 'handle_load_more_supplier_projects');
add_action('wp_ajax_nopriv_load_more_supplier_projects', 'handle_load_more_supplier_projects');

function handle_load_more_supplier_projects() {
    // 1. Kiểm tra request
    $supplier_id = isset($_POST['supplier_id']) ? intval($_POST['supplier_id']) : 0;
    $page        = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $limit       = 6;
    $offset      = ($page - 1) * $limit;

    if ( $supplier_id === 0 ) wp_send_json_error('Invalid Supplier ID');

    // 2. Lấy dữ liệu (Đã có cache ở bước 1 nên rất nhanh)
    $fetcher = new Dezon_Remote_Project_Fetcher();
    $projects = $fetcher->get_projects_by_supplier( $supplier_id, $limit, $offset );

    // 3. Render HTML
    if ( ! empty( $projects ) ) {
        ob_start();
        foreach ( $projects as $project ) {
            // Sử dụng lại template part cũ
            get_template_part( 'template-parts/project', 'item', array( 'project' => $project ) );
        }
        $html = ob_get_clean();
        $has_more = (count($projects) === $limit);

        wp_send_json_success([
            'html' => $html,
            'has_more' => $has_more
        ]);
    } else {
        wp_send_json_error('No more posts');
    }
    
    wp_die();
}

/**
 * 1. HELPER: Tạo Query Arguments cho sản phẩm của Supplier
 * Giúp đồng bộ logic giữa load thường và load AJAX
 */
function get_supplier_product_args( $supplier_id, $category_id = 0, $paged = 1, $posts_per_page = 8 ) {
    $supplier_name = get_the_title( $supplier_id );
    
    // Logic 1: Lấy từ ACF
    $selected_products = array();
    if ( have_rows( 'item_product', $supplier_id ) ) {
        while ( have_rows( 'item_product', $supplier_id ) ) {
            the_row();
            $items = get_sub_field('item');
            if ( $items ) {
                foreach ( $items as $p ) {
                    $selected_products[] = is_object($p) ? $p->ID : $p;
                }
            }
        }
    }

    // Query cơ bản
    $args = array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => $posts_per_page,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'tax_query'      => array( 'relation' => 'AND' ),
    );

    // Logic 2: Áp dụng nguồn sản phẩm (ACF hoặc Brand)
    if ( ! empty( $selected_products ) ) {
        $args['post__in'] = $selected_products;
        $args['orderby']  = 'post__in';
    } else {
        // Fallback: Theo Brand
        $args['tax_query'][] = array(
            'taxonomy' => 'product_brand', // Check lại slug brand của bạn
            'field'    => 'name',
            'terms'    => $supplier_name,
        );
    }

    // Logic 3: Lọc theo Category (Nếu user click sidebar)
    if ( $category_id > 0 ) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat', // Taxonomy mặc định của Woo
            'field'    => 'term_id',
            'terms'    => $category_id,
        );
    }

    return $args;
}

/**
 * 2. AJAX HANDLER: Xử lý Filter & Load More
 */
add_action( 'wp_ajax_filter_supplier_products', 'handle_filter_supplier_products' );
add_action( 'wp_ajax_nopriv_filter_supplier_products', 'handle_filter_supplier_products' );

function handle_filter_supplier_products() {
    // Check nonce nếu cần bảo mật kỹ hơn
    
    $supplier_id = isset($_POST['supplier_id']) ? intval($_POST['supplier_id']) : 0;
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
    $paged       = isset($_POST['page']) ? intval($_POST['page']) : 1;

    // Lấy args từ hàm helper
    $args = get_supplier_product_args( $supplier_id, $category_id, $paged, 8 );
    
    $query = new WP_Query( $args );

    $html = '';
    if ( $query->have_posts() ) {
        ob_start();
        while ( $query->have_posts() ) {
            $query->the_post();
            global $product;
            // Gọi lại template part item bạn đã tạo trước đó
            get_template_part( 'template-parts/supplier-product', 'item' );
        }
        $html = ob_get_clean();
    }

    wp_send_json_success( array(
        'html'      => $html,
        'max_page'  => $query->max_num_pages,
        'found'     => $query->found_posts // Trả về tổng số bài để update số lượng nếu cần
    ) );
    
    wp_die();
}

/**
 * Hàm đệ quy render HTML danh mục Cha/Con cho Supplier
 */
if ( ! function_exists( 'render_supplier_cats_recursive' ) ) {
    function render_supplier_cats_recursive( $parent_id, $terms_by_parent, $level = 0 ) {
        if ( ! isset( $terms_by_parent[ $parent_id ] ) ) return;

        // Xử lý style thụt đầu dòng cho cấp con
        $style = ($level > 0) ? 'padding-left: 20px; display: block;' : '';
        $ul_class = ($level > 0) ? 'product-category-list sub-menu' : 'product-category-list';

        if ( $level > 0 ) echo '<ul class="' . $ul_class . '" style="' . $style . '">';

        foreach ( $terms_by_parent[ $parent_id ] as $cat ) {
            ?>
            <li class="cat-item" data-id="<?php echo esc_attr( $cat->term_id ); ?>">
                <a href="#">
                    <span><?php echo esc_html( $cat->name ); ?></span>
                    <span class="count-badge"><?php echo esc_html( $cat->custom_count ); ?></span>
                </a>
                
                <?php render_supplier_cats_recursive( $cat->term_id, $terms_by_parent, $level + 1 ); ?>
            </li>
            <?php
        }

        if ( $level > 0 ) echo '</ul>';
    }
}
// Trong functions.php

add_action( 'wp_ajax_load_more_partners', 'ajax_load_more_partners' );
add_action( 'wp_ajax_nopriv_load_more_partners', 'ajax_load_more_partners' );

function ajax_load_more_partners() {
    // 1. Lấy Supplier ID
    $supplier_id = isset($_POST['supplier_id']) ? intval($_POST['supplier_id']) : 0;
    
    // 2. Lấy Offset
    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
    
    // 3. QUAN TRỌNG: Kiểm tra kỹ biến limit
    // Nếu JS gửi '5' -> lấy 5. Nếu không gửi -> lấy 15.
    if ( isset($_POST['limit']) ) {
        $limit = intval($_POST['limit']);
    } else {
        $limit = 15; // Mặc định nếu không nhận được gì
    }

    if ( ! $supplier_id ) wp_send_json_error('Missing Supplier ID');

    $fetcher = new Dezon_Remote_Project_Fetcher();
    
    // 4. Gọi hàm fetch (Lúc này $limit bắt buộc phải đúng)
    $partners = $fetcher->get_partners_via_supplier_projects( $supplier_id, $limit, $offset );

    // Logic kiểm tra còn data
    $has_more = (count($partners) >= $limit);

    $html_items = [];
    if ( ! empty( $partners ) ) {
        foreach ( $partners as $p ) {
            ob_start();
            ?>
            <a href="<?php echo esc_url($p['link']); ?>" target="_blank" class="text-reset partner-item-anim" title="<?php echo esc_attr($p['title']); ?>">
                <div class="d-flex mb-3 align-items-center">
                    <img src="<?php echo esc_url($p['logo']); ?>" 
                         style="width: auto; height: 20px; object-fit: contain;" 
                         alt="<?php echo esc_attr($p['title']); ?>">
                    <p class="mb-0 ms-2 text-truncate"><?php echo esc_html($p['title']); ?></p>
                </div>
            </a>
            <?php
            $html_items[] = ob_get_clean();
        }
        
        wp_send_json_success([
            'items'    => $html_items,
            'has_more' => $has_more,
            'loaded'   => count($partners),
            'debug_limit' => $limit // Trả về limit để bạn check trong Network tab
        ]);
    } else {
        wp_send_json_error('No partners found');
    }
    
    wp_die();
}

/**
 * Đổ dữ liệu vào Select Field: related_remote_project_id
 * Logic: Chỉ lấy các Project có liên kết với Supplier đang sửa
 */
add_filter('acf/load_field/name=related_remote_project_id', function( $field ) {
    
    // 1. Chỉ chạy trong trang Admin và phải có ID bài viết đang sửa
    if ( ! is_admin() || ! isset($_GET['post']) ) {
        return $field;
    }

    $current_supplier_id = intval($_GET['post']);

    // 2. Gọi class Fetcher để lấy dữ liệu
    if ( class_exists('Dezon_Remote_Project_Fetcher') ) {
        $fetcher = new Dezon_Remote_Project_Fetcher();
        
        // Gọi hàm vừa viết ở Bước 2
        $projects = $fetcher->get_remote_projects_linked_to_supplier( $current_supplier_id );
        
        // 3. Đổ dữ liệu vào field
        $field['choices'] = array();
        
        if ( ! empty( $projects ) ) {
            $field['choices'] = $projects;
        } else {
            $field['choices'][''] = 'Chưa có dự án nào bên Dezon liên kết với Supplier này';
        }
    }

    return $field;
});

// Trong functions.php

function get_supplier_gallery_flattened( $supplier_id ) {
    $cache_key = 'supplier_full_gallery_' . $supplier_id;
    $all_images = get_transient( $cache_key );

    if ( false === $all_images ) {
        // Lấy Repeater
        $gallery_repeater = get_field('supplier_project_gallery', $supplier_id);
        $all_images = [];

        if ( $gallery_repeater ) {
            foreach ( $gallery_repeater as $row ) {
                // Lấy sub field là Gallery
                $images = $row['project_images']; 
                if ( $images ) {
                    foreach ( $images as $img ) {
                        $all_images[] = [
                            'url'     => $img['url'],
                            'thumb'   => $img['sizes']['medium_large'],
                            'alt'     => $img['alt'],
                            'caption' => $img['caption']
                        ];
                    }
                }
            }
        }
        set_transient( $cache_key, $all_images, 1 * HOUR_IN_SECONDS );
    }
    return $all_images;
}

// Hook xóa cache khi cập nhật Supplier
add_action('save_post_supplier', function($post_id) {
    delete_transient( 'supplier_full_gallery_' . $post_id );
});

/**
 * 2. Ajax Handler: Xử lý Load More Gallery
 */
add_action( 'wp_ajax_load_more_supplier_gallery', 'ajax_load_more_supplier_gallery' );
add_action( 'wp_ajax_nopriv_load_more_supplier_gallery', 'ajax_load_more_supplier_gallery' );

function ajax_load_more_supplier_gallery() {
    $supplier_id = isset($_POST['supplier_id']) ? intval($_POST['supplier_id']) : 0;
    $offset      = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
    $limit       = 8; // CỐ ĐỊNH: Load thêm 8 ảnh

    if ( ! $supplier_id ) wp_send_json_error();

    // Lấy toàn bộ ảnh (từ cache)
    $all_images = get_supplier_gallery_flattened( $supplier_id );
    
    // Cắt mảng theo offset và limit
    $sliced_images = array_slice( $all_images, $offset, $limit );
    $total_images  = count($all_images);
    
    // Kiểm tra còn ảnh nữa không
    $has_more = ($offset + $limit) < $total_images;

    $html_items = [];
    if ( ! empty( $sliced_images ) ) {
        foreach ( $sliced_images as $img ) {
            ob_start();
            ?>
            <div class="col-lg-3 col-6 gallery-item-anim">
                <figure class="mb-0 rounded-2 overflow-hidden position-relative hover-zoom" style="padding-bottom: 66.66%;">
                    <a href="<?php echo esc_url($img['url']); ?>" 
                       data-fancybox="supplier-gallery"
                       class="d-block w-100 h-100 position-absolute top-0 start-0">
                        <img src="<?php echo esc_url($img['thumb']); ?>"
                             class="w-100 h-100 object-fit-cover transition-transform" 
                             alt="<?php echo esc_attr($img['alt']); ?>">
                    </a>
                </figure>
            </div>
            <?php
            $html_items[] = ob_get_clean();
        }
        
        wp_send_json_success([
            'items'    => $html_items,
            'has_more' => $has_more,
            'loaded'   => count($sliced_images)
        ]);
    } else {
        wp_send_json_error();
    }
    
    wp_die();
}

// Hook xóa cache khi lưu bài để cập nhật ảnh mới ngay lập tức
add_action('save_post', function($post_id) {
    delete_transient( 'supplier_full_gallery_' . $post_id );
});

/**
 * 1. Helper: Lấy danh sách Tài liệu từ Repeater (Có hỗ trợ Icon động)
 */
function get_supplier_documents_flattened( $supplier_id ) {
    // Thêm hậu tố _v2 để làm mới cache tránh xung đột cấu trúc cũ
    $cache_key = 'supplier_documents_list_v2_' . $supplier_id;
    $documents = get_transient( $cache_key );

    if ( false === $documents ) {
        $repeater = get_field('catalogue', $supplier_id);
        $documents = [];

        if ( $repeater ) {
            foreach ( $repeater as $row ) {
                // 1. Xử lý File URL
                $file_url = '';
                $file_title = '';
                
                if ( is_array($row['file']) ) {
                    $file_url   = $row['file']['url'];
                    $file_title = $row['file']['title']; // Lấy tiêu đề file gốc
                } else {
                    $file_url   = $row['file'];
                    $file_title = basename($file_url);
                }

                // 2. Xử lý Icon (Ảnh đại diện)
                $icon_url = '';
                if ( ! empty($row['icon']) ) {
                    // Nếu return là Array
                    if ( is_array($row['icon']) ) {
                        $icon_url = $row['icon']['url']; // Hoặc ['sizes']['thumbnail'] nếu muốn nhẹ
                    } else {
                        $icon_url = $row['icon']; // Nếu return là URL
                    }
                } 
                // Fallback: Nếu không chọn icon thì dùng icon PDF mặc định của theme
                if ( empty($icon_url) ) {
                    $icon_url = get_template_directory_uri() . '/assets/images/img-pdf.jpg';
                }

                if ( $file_url ) {
                    $documents[] = [
                        'title' => $file_title,
                        'url'   => $file_url,
                        'icon'  => $icon_url, // URL icon đã xử lý
                        'cate'  => $row['cate']
                    ];
                }
            }
        }
        set_transient( $cache_key, $documents, 12 * HOUR_IN_SECONDS );
    }
    return $documents;
}

/**
 * 2. Ajax Handler: Load More Documents (Cập nhật HTML trả về)
 */
add_action( 'wp_ajax_load_more_documents', 'ajax_load_more_documents' );
add_action( 'wp_ajax_nopriv_load_more_documents', 'ajax_load_more_documents' );

function ajax_load_more_documents() {
    $supplier_id = isset($_POST['supplier_id']) ? intval($_POST['supplier_id']) : 0;
    $offset      = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
    $limit       = isset($_POST['limit']) ? intval($_POST['limit']) : 12;

    if ( ! $supplier_id ) wp_send_json_error();

    $all_docs = get_supplier_documents_flattened( $supplier_id );
    $sliced_docs = array_slice( $all_docs, $offset, $limit );
    $has_more    = ($offset + $limit) < count($all_docs);

    $html_items = [];
    if ( ! empty( $sliced_docs ) ) {
        foreach ( $sliced_docs as $doc ) {
            ob_start();
            ?>
            <div class="list_partner mb-xl-0 cl-blue doc-item-anim mb-3">
                <a href="<?php echo esc_url($doc['url']); ?>" target="_blank" class="text-reset" title="Tải xuống: <?php echo esc_attr($doc['title']); ?>">
                    <div class="d-flex mb-3 align-items-center">
                        <img src="<?php echo esc_url($doc['icon']); ?>" 
                             style="width: auto; height: 20px; object-fit: contain;" 
                             alt="Icon">
                        <p class="mb-0 ms-2 text-truncate"><?php echo esc_html($doc['title']); ?></p>
                    </div>
                </a>
            </div>
            <?php
            $html_items[] = ob_get_clean();
        }
        
        wp_send_json_success([
            'items'    => $html_items,
            'has_more' => $has_more,
            'loaded'   => count($sliced_docs)
        ]);
    } else {
        wp_send_json_error();
    }
    
    wp_die();
}



/**
 * Tạo Mục lục 
 */
function decox_process_toc_and_content( $content ) {
    if ( ! is_singular( array('post', 'project') ) ) {
        return array( 'toc' => '', 'content' => $content );
    }

    $toc_html = '';
    $headers = array();
    $id_counter = 0;

    $pattern = '/<h([2-4])(.*?)>(.*?)<\/h\1>/is';

    $content = preg_replace_callback(
        $pattern,
        function( $matches ) use ( &$headers, &$id_counter ) {
            $level = intval( $matches[1] );
            $attrs = $matches[2]; 
            $title_html = $matches[3]; 
            $title_text = strip_tags( $title_html ); 

            if ( empty( trim( $title_text ) ) ) {
                return $matches[0]; 
            }

            if ( preg_match( '/id=["\']([^"\']+)["\']/is', $attrs, $id_match ) ) {
                $final_slug = $id_match[1];
            } else {
                $base_slug = sanitize_title( $title_text );
                if ( ! $base_slug ) { $base_slug = 'section'; }
                
                $final_slug = 'toc-' . $base_slug . '-' . ++$id_counter;
                
                $attrs .= ' id="' . $final_slug . '"';
            }

            $headers[] = array(
                'level' => $level,
                'title' => $title_text,
                'slug'  => $final_slug
            );

            return '<h' . $level . $attrs . '>' . $title_html . '</h' . $level . '>';
        },
        $content
    );

    if ( ! empty( $headers ) ) {
        $toc_html .= '<ul class="toc-list">';
        $current_level = $headers[0]['level']; 

        foreach ( $headers as $index => $header ) {
            $level = $header['level'];

            if ( $index > 0 ) {
                if ( $level > $current_level ) {
                    $toc_html .= "\n<ul class=\"sub-menu mt-3\" style=\"list-style: none; padding-left: 0;\">\n";
                } elseif ( $level < $current_level ) {
                    $diff = $current_level - $level;
                    for ( $i = 0; $i < $diff; $i++ ) {
                        $toc_html .= "</li>\n</ul>\n";
                    }
                    $toc_html .= "</li>\n"; 
                } else {
                    // Cùng cấp
                    $toc_html .= "</li>\n";
                }
            }

            $toc_html .= '<li class="toc-item toc-h' . $level . '">';
            $toc_html .= '<a href="#' . $header['slug'] . '">' . $header['title'] . '</a>';

            $current_level = $level;
        }

        while ( $current_level > $headers[0]['level'] ) {
            $toc_html .= "</li>\n</ul>\n";
            $current_level--;
        }
        $toc_html .= "</li>\n</ul>"; 
    }

    // Wrap HTML
    if ( ! empty( $toc_html ) ) {
        $final_toc = '
        <div id="decox_toc" class="decox-toc-wrapper toc-open toc-closed">
            <div class="toc-header">
                <p class="toc-title">Mục lục</p>
                <span class="toc-toggle" id="toc-toggle-btn">
                    <svg width="14" height="9" viewBox="0 0 14 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 1L7 7L13 1" stroke="#0A0A0A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </div>
            ' . $toc_html . '
        </div>';
    } else {
        $final_toc = '';
    }

    return array(
        'toc'     => $final_toc,
        'content' => $content
    );
}

function load_posts_by_ajax_callback() {
    check_ajax_referer('ajax-nonce', 'nonce');

    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    $related_ids = get_field('advise', $post_id, false);

    if (empty($related_ids)) {
        wp_send_json_error('Chưa chọn bài viết nào trong mục Advise.');
        die();
    }

    $args = array(
        'post_type'      => 'post',
        'post__in'       => $related_ids,
        'orderby'        => 'post__in',
        'posts_per_page' => 6,
        'paged'          => $paged,
        'post_status'    => 'publish'
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        $html_content = '';

        while ($query->have_posts()) : $query->the_post();
            $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
            if(!$thumb_url) $thumb_url = get_template_directory_uri() . '/assets/images/cate1.jpg';

            $categories = get_the_category();
            $cat_name = !empty($categories) ? $categories[0]->name : 'Giải pháp kiến trúc';
            $cat_link = !empty($categories) ? get_category_link($categories[0]->term_id) : '#';

            ob_start();
            ?>
            <div class="col-lg-4 mb-4">
                <div class="post_item">
                    <figure>
                        <a href="<?php the_permalink(); ?>">
                            <img src="<?php echo esc_url($thumb_url); ?>" class="img-fluid" alt="<?php the_title(); ?>">
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
            $html_content .= ob_get_clean();
        endwhile;

        $pagination = paginate_links(array(
            'base'      => '%_%',
            'format'    => '?paged=%#%',
            'current'   => $paged,
            'total'     => $query->max_num_pages,
            'prev_text' => '<img src="'.get_template_directory_uri().'/assets/images/first.svg" class="img-fluid" alt="">',
            'next_text' => '<img src="'.get_template_directory_uri().'/assets/images/last.svg" class="img-fluid" alt="">',
            'type'      => 'plain',
        ));

        wp_send_json_success(array(
            'html'       => $html_content,
            'pagination' => '<div class="wp-pagenavi">' . $pagination . '</div>'
        ));

    else :
        wp_send_json_error('Hết bài viết.');
    endif;

    wp_reset_postdata();
    die();
}

add_action('wp_ajax_load_posts', 'load_posts_by_ajax_callback');
add_action('wp_ajax_nopriv_load_posts', 'load_posts_by_ajax_callback');


// Tính thời gian đọc
function get_decox_reading_time( $post_id = null ) {
    if ( empty( $post_id ) ) {
        $post_id = get_the_ID();
    }
    $content = get_post_field( 'post_content', $post_id );
    if ( empty( $content ) ) {
        return '1 phút đọc';
    }

    $word_count = str_word_count( strip_tags( $content ) );
    $words_per_minute = 200;

    $minutes = ceil( $word_count / $words_per_minute );
    if ( $minutes < 1 ) {
        $minutes = 1;
    }

    return $minutes . ' phút đọc';
}


add_action('wp_ajax_load_more_videos', 'load_more_videos_ajax_handler');
add_action('wp_ajax_nopriv_load_more_videos', 'load_more_videos_ajax_handler');

function load_more_videos_ajax_handler() {
    $supplier_id = isset($_POST['supplier_id']) ? intval($_POST['supplier_id']) : 0;
    $offset      = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
    $limit       = 4; // Số lượng load thêm

    // Lấy dữ liệu Repeater
    $all_videos = get_field('video', $supplier_id);

    if (empty($all_videos) || !is_array($all_videos)) {
        wp_send_json_error('Không có dữ liệu.');
    }

    // Cắt mảng
    $videos_to_show = array_slice($all_videos, $offset, $limit);
    $total_videos   = count($all_videos);
    $has_more       = ($offset + $limit) < $total_videos;

    $html_response = [];

    if (!empty($videos_to_show)) {
        foreach ($videos_to_show as $row) {
            // --- LOGIC PHP XỬ LÝ DỮ LIỆU ---
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

            // Xử lý Ảnh Thumbnail
            $img_url = '';
            if (is_array($image)) {
                $img_url = isset($image['url']) ? $image['url'] : '';
            } else {
                $img_url = $image;
            }
            // Ảnh mặc định
            if (empty($img_url)) $img_url = get_template_directory_uri() . '/assets/images/gallery1.jpg'; 
            
            // --- BẮT ĐẦU HTML ITEM (ĐÃ CẬP NHẬT CẤU TRÚC MỚI) ---
            ob_start();
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
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/dots.svg" 
                                                 class="img-fluid" style="height: 14px;" alt="More">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a> </figure>
                </div>
            </div>
            <?php
            $html_response[] = ob_get_clean();
            // --- KẾT THÚC HTML ITEM ---
        }
    }

    wp_send_json_success([
        'items'    => $html_response,
        'loaded'   => count($videos_to_show),
        'has_more' => $has_more
    ]);
}