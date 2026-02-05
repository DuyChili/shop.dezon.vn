jQuery(document).ready(function ($) {
    $('#btn-load-more-projects').on('click', function (e) {
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
            success: function (response) {
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
            error: function () {
                spinner.hide();
                button.show();
                alert('Có lỗi xảy ra, vui lòng thử lại.');
            }
        });
    });
});

jQuery(document).ready(function ($) {

    var btnMore = $('#btn-load-more-products');
    var listContainer = $('#supplier-product-list');
    var loading = $('#prod-loading');

    // Lấy ID từ biến cục bộ (nếu btnMore không có data-supplier thì dùng cái này)
    // supplier_ajax_params được truyền từ wp_localize_script
    var globalSupplierId = (typeof supplier_ajax_params !== 'undefined') ? supplier_ajax_params.supplier_id : 0;
    var ajaxUrl = (typeof supplier_ajax_params !== 'undefined') ? supplier_ajax_params.ajax_url : '/wp-admin/admin-ajax.php';

    // 1. Xử lý khi bấm vào Category (Filter)
    $('#supplier-cat-filter').on('click', 'li > a', function (e) {
        e.preventDefault();
        e.stopPropagation(); 

        var clickedLi = $(this).parent('li');

        // UI Active
        $('#supplier-cat-filter li').removeClass('active');
        clickedLi.addClass('active');

        // Lấy dữ liệu
        var cat_id = clickedLi.data('id');
        // Ưu tiên data-supplier trên nút, nếu không có thì lấy từ global
        var supplier_id = btnMore.data('supplier') || globalSupplierId;

        // Reset nút Load More
        btnMore.data('page', 1);
        btnMore.data('cat', cat_id);

        // Loading effect
        listContainer.css('opacity', '0.5');

        // AJAX
        $.ajax({
            url: ajaxUrl, // Dùng biến ajaxUrl
            type: 'POST',
            data: {
                action: 'filter_supplier_products',
                supplier_id: supplier_id,
                category_id: cat_id,
                page: 1
            },
            success: function (res) {
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
    btnMore.on('click', function (e) {
        e.preventDefault();

        var current_page = $(this).data('page');
        var max_page = $(this).data('max');
        var cat_id = $(this).data('cat');
        var supplier_id = $(this).data('supplier') || globalSupplierId;

        if (current_page >= max_page) return;

        var next_page = current_page + 1;

        btnMore.hide();
        loading.show();

        $.ajax({
            url: ajaxUrl, // Dùng biến ajaxUrl
            type: 'POST',
            data: {
                action: 'filter_supplier_products',
                supplier_id: supplier_id,
                category_id: cat_id,
                page: next_page
            },
            success: function (res) {
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



jQuery(document).ready(function($) {
    $(document).on('click', '.j_paging .wp-pagenavi a', function(e) {
        e.preventDefault();

        var link = $(this).attr('href');
        var page = 1;
        if (link.indexOf('paged=') !== -1) {
            page = link.split('paged=')[1];
        } else if (link.indexOf('/page/') !== -1) {
            var parts = link.split('/page/');
            page = parseInt(parts[1]);
        }
        
        // --- QUAN TRỌNG: LẤY ID CỦA BÀI VIẾT HIỆN TẠI ---
        // Cách lấy ID: Ta sẽ đặt ID vào một thẻ HTML ở bước 3, ví dụ thẻ bao quanh list
        var currentPostId = $('.list_post').data('id'); 

        $('.list_post').css('opacity', '0.5');

        $.ajax({
            url: my_ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'load_posts',
                nonce: my_ajax_object.nonce,
                page: page,
                post_id: currentPostId // Gửi ID về server
            },
            success: function(response) {
                if (response.success) {
                    $('.list_post').html(response.data.html);
                    $('.j_paging').html(response.data.pagination);
                    $('html, body').animate({ scrollTop: $(".list_post").offset().top - 100 }, 500);
                }
                $('.list_post').css('opacity', '1');
            }
        });
    });
});

jQuery(document).ready(function($) {
    const slider = $('.scroll-menu');
    let isDown = false;
    let startX;
    let scrollLeft;

    slider.on('mousedown', function(e) {
        isDown = true;
        $(this).addClass('active');
        // Ghi lại vị trí bắt đầu
        startX = e.pageX - $(this).offset().left;
        scrollLeft = $(this).scrollLeft();
    });

    slider.on('mouseleave', function() {
        isDown = false;
        $(this).removeClass('active');
    });

    slider.on('mouseup', function() {
        isDown = false;
        $(this).removeClass('active');
    });

    slider.on('mousemove', function(e) {
        // [QUAN TRỌNG] Kiểm tra kỹ: 
        // 1. Nếu biến isDown là false -> Dừng.
        // 2. Nếu phần cứng chuột không nhấn nút trái (e.buttons !== 1) -> Dừng ngay lập tức.
        if (!isDown || e.buttons !== 1) { 
            isDown = false;
            $(this).removeClass('active');
            return; 
        }

        e.preventDefault(); // Ngăn chọn văn bản
        
        const x = e.pageX - $(this).offset().left;
        
        // Tốc độ kéo (scroll-speed). 
        // Số 1: Kéo 1px đi 1px (tự nhiên nhất).
        // Số 2 hoặc 3: Kéo nhẹ đi xa (nhanh hơn).
        const walk = (x - startX) * 1; 
        
        $(this).scrollLeft(scrollLeft - walk);
    });
});

jQuery(document).ready(function($) {
    $('#btn-load-more-videos').on('click', function(e) {
        e.preventDefault();

        var button = $(this);
        var spinner = $('#video-loading');
        var supplier_id = button.data('supplier');
        var current_offset = button.data('offset');

        // Ẩn nút, hiện loading
        button.hide();
        spinner.show();

        $.ajax({
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            type: 'POST',
            data: {
                action: 'load_more_videos', // Khớp với tên action trong functions.php
                supplier_id: supplier_id,
                offset: current_offset
            },
            success: function(response) {
                spinner.hide();

                if (response.success) {
                    var items = response.data.items;
                    var loaded_count = response.data.loaded;

                    // Append từng item vào list với hiệu ứng fade-in
                    $.each(items, function(index, html) {
                        var $html = $(html).hide();
                        $('#video-list-container').append($html);
                        $html.fadeIn(400);
                    });

                    // Cập nhật Offset mới
                    var new_offset = current_offset + loaded_count;
                    button.data('offset', new_offset);

                    // Kiểm tra còn video không
                    if (response.data.has_more) {
                        button.show();
                    } else {
                        button.remove(); // Xóa nút nếu hết video
                    }
                } else {
                    // Xử lý lỗi hoặc hết dữ liệu
                    button.remove();
                }
            },
            error: function() {
                spinner.hide();
                button.show();
                alert('Có lỗi xảy ra khi tải video.');
            }
        });
    });
});

jQuery(document).ready(function($) {
    // Cấu hình chiều cao Header (nếu có sticky header thì tăng số này lên, ví dụ 80 hoặc 100)
    var headerOffset = 100; 

    // 1. Xử lý sự kiện CLICK vào menu
    $('.scroll-menu a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        
        var targetId = $(this).attr('href');
        var $target = $(targetId);

        if ($target.length) {
            // Xóa active cũ, thêm active mới ngay lập tức
            $('.scroll-menu li').removeClass('current-menu-item');
            $(this).parent('li').addClass('current-menu-item');

            // Cuộn mượt
            $('html, body').animate({
                scrollTop: $target.offset().top - headerOffset
            }, 800); // 800ms tốc độ cuộn
        }
    });

    // 2. Xử lý SCROLL SPY (Tự động Active khi cuộn)
    $(window).on('scroll', function() {
        var scrollPos = $(window).scrollTop();
        
        $('.scroll-menu a[href^="#"]').each(function() {
            var currLink = $(this);
            var refElement = $(currLink.attr("href"));
            
            // Kiểm tra nếu section tồn tại trong DOM
            if (refElement.length) {
                // Logic: Vị trí scroll nằm trong khoảng bắt đầu và kết thúc của section
                if (refElement.offset().top - headerOffset - 10 <= scrollPos && 
                    refElement.offset().top + refElement.outerHeight() > scrollPos) {
                    
                    $('.scroll-menu li').removeClass("current-menu-item");
                    currLink.parent('li').addClass("current-menu-item");
                }
            }
        });
    });
});