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