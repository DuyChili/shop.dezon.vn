jQuery(document).ready(function(){

    jQuery('.slider_arrival-main').slick({
        dots: true,
        infinite: true,
        speed: 1500,
        arrows: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 5000,
        pauseOnFocus: false,
        pauseOnHover: false,
        prevArrow: '.slider_arrival .left-arrival',
        nextArrow: '.slider_arrival .right-arrival',
    });
    jQuery('.slider_arrival-sub').slick({
        dots: false,
        infinite: true,
        speed: 1500,
        arrows: false,
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 5000,
        pauseOnFocus: false,
        pauseOnHover: false,
        responsive: [
            {
            breakpoint: 600,
            settings: {
                slidesToShow: 2,
            }
            },
            {
            breakpoint: 480,
            settings: {
                slidesToShow: 1.5,
                infinite: false,
            }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });
    function initArrivalSubSecond() {
        var $slider = jQuery('.slider_arrival-sub-second');
        var w = jQuery(window).width();

        if ($slider.hasClass('slick-initialized')) {
            $slider.slick('unslick');
        }

        if (w < 768) {
            // 👉 MOBILE: 1 HÀNG – 1.5 ITEM
            $slider.slick({
            dots: false,
            infinite: false,
            speed: 1500,
            arrows: false,
            slidesToShow: 1.5,   // 👈 1.5 ITEM
            slidesToScroll: 1,
            autoplay: false,
            swipeToSlide: true
            });
        } else {
            // 👉 DESKTOP / TABLET
            $slider.slick({
            dots: false,
            infinite: true,
            speed: 1500,
            arrows: false,
            slidesToShow: 1,
            rows: 2,
            slidesPerRow: 2,
            slidesToScroll: 1,
            autoplay: false
            });
        }
    }

    initArrivalSubSecond();
    jQuery(window).on('resize', function () {
    clearTimeout(window._arrivalResizeTimer);
    window._arrivalResizeTimer = setTimeout(initArrivalSubSecond, 200);
    });


    jQuery('.slider_collection').slick({
        dots: false,
        infinite: true,
        speed: 1500,
        arrows: false,
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 5000,
        pauseOnFocus: false,
        pauseOnHover: false,
        responsive: [
            {
            breakpoint: 1025,
            settings: {
                slidesToShow: 3,
            }
            },
            {
            breakpoint: 600,
            settings: {
                slidesToShow: 2,
            }
            },
            {
            breakpoint: 480,
            settings: {
                slidesToShow: 1.5,
                infinite: false
            }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });  
    jQuery('.slider_collecting').slick({
        dots: false,
        infinite: true,
        speed: 1500,
        arrows: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 5000,
        pauseOnFocus: false,
        pauseOnHover: false,
        prevArrow: '.left-collecting',
        nextArrow: '.right-collecting',
        responsive: [
            {
            breakpoint: 1025,
            settings: {
                slidesToShow: 3,
            }
            },
            {
            breakpoint: 600,
            settings: {
                slidesToShow: 2,
            }
            },
            {
            breakpoint: 480,
            settings: {
                slidesToShow: 1.5,
                infinite: false
            }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });  
    jQuery('.slider_interior_main').slick({
        dots: false,
        infinite: true,
        speed: 1500,
        arrows: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 5000,
        pauseOnFocus: false,
        pauseOnHover: false,
        asNavFor: ".slider_interior_sub-1, .slider_interior_sub-2",
    });
    jQuery('.slider_interior_sub-1').slick({
        dots: false,
        infinite: true,
        speed: 1500,
        arrows: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 5000,
        pauseOnFocus: false,
        pauseOnHover: false,
        prevArrow: '.slider_interior_sub-wrapper .left-interior',
        nextArrow: '.slider_interior_sub-wrapper .right-interior',
        asNavFor: ".slider_interior_main, .slider_interior_sub-2",
    });  
    jQuery('.slider_interior_sub-2').slick({
        dots: false,
        infinite: true,
        speed: 1500,
        arrows: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 5000,
        pauseOnFocus: false,
        pauseOnHover: false,
        asNavFor: ".slider_interior_main, .slider_interior_sub-1",
    });  
    jQuery('.slider_feedback').slick({
        dots: true,
        infinite: true,
        speed: 1500,
        arrows: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 5000,
        pauseOnFocus: false,
        pauseOnHover: false,
    });   
    jQuery('.project_feature .cate_sidebar .title-cate').on('click', function () {
        const $this = jQuery(this);

        $this.toggleClass('active');
        $this.next('.list-cate').stop(true, true).slideToggle();
    });

    jQuery('.slider_feedback-owner').slick({
        dots: false,
        infinite: true,
        speed: 1500,
        arrows: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 5000,
        pauseOnFocus: false,
        pauseOnHover: false,
        prevArrow: '.slider_houseowner-wrapper .left-owner-btn',
        nextArrow: '.slider_houseowner-wrapper .right-owner-btn',
          responsive: [
            {
            breakpoint: 1025,
            settings: {
                slidesToShow: 2,
            }
            },
            {
            breakpoint: 600,
            settings: {
                slidesToShow: 2,
            }
            },
            {
            breakpoint: 480,
            settings: {
                slidesToShow: 1.2,
                infinite: false
            }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    }); 
    jQuery('.slider_solution').slick({
        dots: false,
        infinite: false,
        speed: 1500,
        arrows: false,
        slidesToShow: 2.5,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 5000,
        pauseOnFocus: false,
        pauseOnHover: false,
          responsive: [
            {
            breakpoint: 1025,
            settings: {
                slidesToShow: 3,
            }
            },
            {
            breakpoint: 600,
            settings: {
                slidesToShow: 2.5,
            }
            },
            {
            breakpoint: 480,
            settings: {
                slidesToShow: 2.5,
                infinite: false
            }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });   

    jQuery('.slider_video').on('setPosition', function () {
    const h = jQuery(this).outerHeight();
    jQuery(this).css('min-height', h);
    });
    jQuery('.slider_video').slick({
        dots: false,
        infinite: true,
        speed: 1500,
        arrows: false,
        centerMode:true,
        centerPadding: '200px',
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 5000,
        pauseOnFocus: false,
        pauseOnHover: false,
        focusOnSelect: true,
        responsive: [
            {
            breakpoint: 1025,
            settings: {
                centerPadding: '90px',
            }
            },
            {
            breakpoint: 600,
            settings: {
                centerPadding: '200px',
            }
            },
            {
            breakpoint: 480,
            settings: {
                centerPadding: '0px',
                centerMode: false,
                dots: true,
            }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });   
    Fancybox.bind(
        '.slider_detail-product .slick-slide:not(.slick-cloned) a[data-fancybox="photos"]',
        {
            Thumbs: false,
            on: {
                destroy: () => {
                jQuery('.slider_detail-product ').slick('refresh');
                }
            },
        },
        
    );

    jQuery('.slider_breakcrumb').slick({
        dots: false,
        infinite: false,
        speed: 1500,
        arrows: false,
        slidesToShow: 2.5,
        variableWidth: true,
        slidesToScroll: 1,
        autoplay: false,
        focusOnSelect: true,
    });

    //slider product with row
    function initGalleryProductSlider() {
        var $slider = jQuery('.slider_gallery_product');
        var winWidth = jQuery(window).width();

        if ($slider.hasClass('slick-initialized')) {
            $slider.slick('unslick');
        }

        if (winWidth < 480) {
            // 👉 MOBILE: 1 HÀNG – 2.5 ITEM
            $slider.slick({
            dots: false,
            infinite: false,
            speed: 1500,
            arrows: false,
            slidesToShow: 2.5,   // 👈 2.5 ITEM
            slidesToScroll: 1,
            autoplay: false,
            swipeToSlide: true
            });
        } else if (winWidth < 1025) {
            // 👉 TABLET
            $slider.slick({
            dots: false,
            infinite: true,
            rows: 2,
            slidesPerRow: 4,
            speed: 1500,
            arrows: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: false
            });
        } else {
            // 👉 DESKTOP
            $slider.slick({
            dots: false,
            infinite: true,
            rows: 2,
            slidesPerRow: 6,
            speed: 1500,
            arrows: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: false
            });
        }
    }
    // init
    initGalleryProductSlider();
    // re-init khi resize
    jQuery(window).on('resize', function () {
    clearTimeout(window._galleryResizeTimer);
    window._galleryResizeTimer = setTimeout(initGalleryProductSlider, 200);
    });

    //slider feedback version 2
    jQuery('.slider-fb-second').slick({
        dots: false,
        infinite: true,
        speed: 1500,
        arrows: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: false,
        focusOnSelect: true,
        prevArrow: '.slide-control-feedback .left-feedback',
        nextArrow: '.slide-control-feedback .right-feedback',
        responsive: [
            {
            breakpoint: 480,
            settings: {
                dots: true,
            }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    }); 

    jQuery('.slider-rooms').slick({
        dots: false,
        infinite: true,
        speed: 500,
        arrows: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: false,
        focusOnSelect: false,
    }); 
    jQuery('.list_room-hover .room-bar').each(function (index) {
        jQuery(this).on('mouseenter', function () {
            jQuery('.slider-rooms').slick('slickGoTo', index);
        });
    });

    var $slider = jQuery('.slider_feature-construct');
    var $prev = jQuery('.slider_feature-construct-wrapper .prev_slide');
    var $next = jQuery('.slider_feature-construct-wrapper .next_slide');

    $slider.on('init reInit afterChange', function (event, slick, currentSlide) {
        var i = currentSlide ? currentSlide : 0;

        // Ẩn/hiện nút trái
        if (i === 0) {
            $prev.hide();
        } else {
            $prev.show();
        }

        // Ẩn/hiện nút phải
        if (i >= slick.slideCount - slick.options.slidesToShow) {
            $next.hide();
        } else {
            $next.show();
        }
    });
    jQuery('.slider_feature-construct').slick({
        dots: false,
        infinite: false,
        speed: 500,
        arrows: true,
        slidesToShow: 2.5,
        slidesToScroll: 1,
        autoplay: false,
        focusOnSelect: false,
        nextArrow: '.slider_feature-construct-wrapper .next_slide',
        prevArrow: '.slider_feature-construct-wrapper .prev_slide',
        responsive: [
            {
            breakpoint: 600,
            settings: {
                slidesToShow: 2,
            }
            },
            {
            breakpoint: 480,
            settings: {
                slidesToShow: 1.2,
                arrows: false,
                dots: true, 
            }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    }); 

    jQuery('.slider_about_wrapper').slick({
        dots: true,
        infinite: false,
        speed: 500,
        arrows: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: false,
        focusOnSelect: false,
    });
    jQuery('.slider_feature-post').slick({
        dots: false,
        infinite: false,
        speed: 500,
        arrows: false,
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: false,
        focusOnSelect: false,
        responsive: [
             {
            breakpoint: 1024,
            settings: {
                slidesToShow: 2.5,
            }
            },
            {
            breakpoint: 600,
            settings: {
                slidesToShow: 2,
            }
            },
            {
            breakpoint: 480,
            settings: {
                slidesToShow: 1.2,
                arrows: false,
            }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });
    if (document.querySelector('.mySwiper')) {
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 4.5,
            spaceBetween: 30,
            freeMode: true,
            pagination: {
                el: ".product_dots",
                type: "progressbar",
                clickable: true,
            },
            breakpoints: {
                0: {
                    slidesPerView: 1.3,
                    spaceBetween: 16,
                },
                768: {
                    slidesPerView: 2.5,
                    spaceBetween: 24,
                },
                1024: {
                    slidesPerView: 4.5,
                    spaceBetween: 30,
                },
            },
        });
    }
    jQuery('.color-section .list-color .color-item').on('click', function () {
        const $this = jQuery(this);

        // bỏ active tất cả màu trong cùng group
        $this.closest('.list-color').find('.color-item').removeClass('active');

        // thêm active cho màu được click
        $this.addClass('active');
    });

    // mở filter
    jQuery('.filter_site .btn-side-filter').on('click', function () {
        jQuery('.filter_side_wrap').css('transform', 'translateX(0%)');
        jQuery('.filter_overlay').css({
            opacity: 1,
            visibility: 'visible'
        });
    });

    // click overlay thì đóng
    jQuery('.filter_overlay').on('click', function () {
        jQuery('.filter_side_wrap').css('transform', 'translateX(-100%)');
        jQuery(this).css({
            opacity: 0,
            visibility: 'hidden'
        });
    });
     jQuery('.btn-cancel-filter').on('click', function () {
        jQuery('.filter_side_wrap').css('transform', 'translateX(-100%)');
        jQuery('.filter_overlay').css({
            opacity: 0,
            visibility: 'hidden'
        });
    });

})
