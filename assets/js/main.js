var App = {
  scrollHeader: function () {
    function toggleHeaderScrolled() {
      if (jQuery(window).scrollTop() > 100) {
        jQuery('#header_site').addClass('scrolled');
      } else {
        jQuery('#header_site').removeClass('scrolled');
      }
    }
    // Gọi khi vừa load trang
    toggleHeaderScrolled();
    // Gọi mỗi khi scroll
    jQuery(window).on('scroll', function () {
      toggleHeaderScrolled();
    });
  },
  sliderInit: function () {
    if (jQuery('.featured_cate').length) {
      var startSlideIndex = 0;
      var $slides = jQuery('.featured_cate .splide__list .splide__slide:not(.splide__slide--clone)');

      $slides.each(function (index) {
        if (jQuery(this).hasClass('active')) {
          startSlideIndex = index;
          return false;
        }
      });


      var splide = new Splide('.featured_cate', {
        type: 'loop',
        perPage: 1,
        autoWidth: true,
        focus: 'center',
        start: startSlideIndex,
        autoScroll: {
          speed: 0.001,
        },
        arrows: true,
        pagination: false,
        breakpoints: {
          991: {
            perPage: 1,
          },
        }
      });

      splide.mount(window.splide.Extensions);
      jQuery('.featured_cate').data('splide', splide);

      splide.Components.AutoScroll.pause();
    }
    jQuery('.slider_podcast').slick({
      dots: false,
      infinite: false,
      speed: 500,
      arrows: true,
      slidesToShow: 1,
      slidesToScroll: 1,
      autoplay: false,
      variableWidth: true,
      autoplaySpeed: 5000,
      pauseOnFocus: false,
      pauseOnHover: false,
      prevArrow: '.podcast__arrows .prev_arrow',
      nextArrow: '.podcast__arrows .next_arrow',
      swipeToSlide: true,
    });
    jQuery('.slider_videos').slick({
      dots: false,
      infinite: false,
      speed: 1500,
      arrows: true,
      slidesToShow: 1,
      slidesToScroll: 1,
      autoplay: false,
      variableWidth: true,
      autoplaySpeed: 5000,
      pauseOnFocus: false,
      pauseOnHover: false,
      prevArrow: '.videos__arrows .prev_arrow',
      nextArrow: '.videos__arrows .next_arrow',
    });
    jQuery('.slider_design').slick({
      dots: false,
      infinite: true,
      speed: 500,
      arrows: true,
      slidesToShow: 1,
      slidesToScroll: 1,
      autoplay: false,
      variableWidth: true,
      autoplaySpeed: 5000,
      pauseOnFocus: false,
      pauseOnHover: false,
      prevArrow: '.design__arrows .prev_arrow',
      nextArrow: '.design__arrows .next_arrow',
      swipeToSlide: true,
    });
    jQuery('.slider_events').slick({
      dots: false,
      infinite: true,
      speed: 500,
      cssEase: 'ease-out',
      arrows: true,
      slidesToShow: 3,
      slidesToScroll: 1,
      autoplay: false,
      autoplaySpeed: 5000,
      pauseOnFocus: false,
      pauseOnHover: false,
      prevArrow: '.event__arrows .prev_arrow',
      nextArrow: '.event__arrows .next_arrow',
      swipeToSlide: true,
      touchThreshold: 15,
      waitForAnimate: false,
      useTransform: true,
      responsive: [
        {
          breakpoint: 991,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
          }
        },
      ]
    });
    jQuery('.slider_explore').slick({
      dots: false,
      infinite: true,
      speed: 500,
      arrows: true,
      rows: 2,
      slidesPerRow: 5,
      autoplay: false,
      autoplaySpeed: 5000,
      pauseOnFocus: false,
      pauseOnHover: false,
      prevArrow: '.explore__arrows .prev_arrow',
      nextArrow: '.explore__arrows .next_arrow',
      swipeToSlide: true,
      responsive: [
        {
          breakpoint: 991,
          settings: {
            rows: 2,
            slidesPerRow: 2,
          }
        },
      ]
    });
    jQuery('.slider_partner').slick({
      dots: false,
      infinite: true,
      speed: 500,
      arrows: true,
      rows: 2,
      slidesPerRow: 6,
      autoplay: false,
      autoplaySpeed: 5000,
      pauseOnFocus: false,
      pauseOnHover: false,
      prevArrow: '.partner__arrows .prev_arrow',
      nextArrow: '.partner__arrows .next_arrow',
      swipeToSlide: true,
      responsive: [
        {
          breakpoint: 991,
          settings: {
            rows: 2,
            slidesPerRow: 2,
          }
        },
      ]
    });
    jQuery('.project_slider').slick({
      dots: true,
      infinite: false,
      speed: 500,
      arrows: true,
      slidesToShow: 1,
      slidesToScroll: 1,
      autoplay: false,
      autoplaySpeed: 5000,
      pauseOnFocus: false,
      pauseOnHover: false,
      prevArrow: '.project__arrows .prev_arrow',
      nextArrow: '.project__arrows .next_arrow',
      swipeToSlide: true,
    });
    // Add cspaceItem to phase 2
    jQuery('.product_slider').slick({
     dots: true,
    infinite: false,
    speed: 400,       
    arrows: false,
    slidesToShow: 1,
    slidesToScroll: 1,   
    swipeToSlide: true,   
    touchThreshold: 100, 
    waitForAnimate: false, 
    useTransform: true, 
    cssEase: 'ease-out',  
    autoplay: false,
    variableWidth: true,
    pauseOnFocus: false,
    pauseOnHover: false,
    appendDots: jQuery('.product_dots')
    });
    jQuery('a[data-bs-toggle="tab"], a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      jQuery('.product_slider').slick('setPosition');
      jQuery('.product_slider').slick('refresh');
    });
    jQuery('.product_gallery').slick({
      dots: false,
      infinite: true, 
      speed: 1500,
      arrows: true,
      slidesToShow: 1,
      slidesToScroll: 1,
      autoplay: false,
      pauseOnFocus: false,
      focusOnSelect: true,
      prevArrow: '.gallery__arrows .prev_arrow',
      nextArrow: '.gallery__arrows .next_arrow',
      asNavFor: '.slider_thumb'
    });
    var $thumbSlider = jQuery('.slider_thumb');
    var allowScroll = !$thumbSlider.hasClass('is-static-gallery');

    $thumbSlider.slick({
      dots: false,
      infinite: allowScroll,       
      verticalSwiping: allowScroll, 
      swipe: allowScroll,           
      draggable: allowScroll,       
      accessibility: allowScroll,   
      centerMode: false, 
      centerPadding: '0px',
      slidesToShow: 5, 
      slidesToScroll: 1,
      speed: 1500, 
      arrows: false,
      vertical: true,
      pauseOnFocus: false,
      focusOnSelect: true, // Vẫn cho phép click để chọn dù không scroll
      asNavFor: '.product_gallery',
      responsive: [
        {
          breakpoint: 991,
          settings: {
            vertical: false,
            verticalSwiping: true,
            slidesToShow: 5,
          }
        },
      ]
    });
    if (jQuery('.brand_slider').length) {
      var splide = new Splide('.brand_slider', {
        type: 'loop',
        perPage: 1,
        autoWidth: true,
        autoScroll: {
          speed: 0.5,
        },
        arrows: false,
        pagination: false,
        breakpoints: {
          991: {
            perPage: 1,
          },
        }
      });
      splide.mount(window.splide.Extensions);
    }
  },
  menuMobile: function () {
    jQuery('.hamburger_btn').click(function (e) {
      e.preventDefault();
      jQuery('.hamburger-icon').toggleClass('open');
      jQuery('#menu_mobile').toggleClass('open');
      jQuery('.overlay_menu').toggleClass('is-active');
    });
    jQuery('.overlay_menu').click(function () {
      jQuery('.hamburger-icon').removeClass('open');
      jQuery('#menu_mobile').removeClass('open');
      jQuery('.overlay_menu').removeClass('is-active');
    });
    jQuery('#menu_mobile .menu_site li.menu-item-has-children').click(function (e) {
      e.stopPropagation();
      jQuery(this).toggleClass('show_submenu');
      jQuery(this).find('>.sub-menu').stop().slideToggle('fast');
    });
  },
  // Add cspaceItem to phase 2
  cspaceItem: function () {
    jQuery('.about_item').click(function (e) {
      e.preventDefault();
      jQuery('.about_item').removeClass('active');
      jQuery(this).addClass('active');
    })
  }
};
jQuery(document).ready(function () {
  App.scrollHeader();
  App.sliderInit();
  App.cspaceItem();
  App.menuMobile();
  Fancybox.unbind("[data-fancybox]");
  Fancybox.close();
  Fancybox.bind('[data-fancybox="supplier-gallery"]', {
    groupAll : true,
    Thumbs : {
        autoStart : true
    }
});
    Fancybox.bind('[data-fancybox="supplier-video"]', {
        groupAll : true,
        Html: {
            video: {
                autoplay: true,
            },
        },
    });
  jQuery(document).on('click', '.product_gallery a[data-fancybox="gallery"]', function(e) {
      e.preventDefault(); 
      var $uniqueLinks = jQuery('.product_gallery .slick-slide:not(.slick-cloned) a[data-fancybox="gallery"]');
      var galleryItems = [];
      var clickedHref = jQuery(this).attr('href'); 
      var startShowIndex = 0;

      $uniqueLinks.each(function(index) {
          var href = jQuery(this).attr('href');
          galleryItems.push({
              src: href,
              type: 'image',
              caption: jQuery(this).find('img').attr('alt')
          });
          if (href === clickedHref) {
              startShowIndex = index;
          }
      });
      Fancybox.show(galleryItems, {
          startIndex: startShowIndex, 
      });
  });
  Fancybox.bind('[data-fancybox="video-intro"]', {
      // Tùy chọn settings nếu cần
      Html: {
        video: {
          autoplay: true,
        },
      },
  });

  Fancybox.bind('.single_post .post_content figure a[data-fancybox="post-gallery"]', {
      groupAll : true,
  });
  jQuery('.single_post .post_content figure img').each(function () {
    const src = jQuery(this).attr('src');

    jQuery(this).wrap(
      `<a href="${src}" data-fancybox="post-gallery"></a>`
    );
  });


});

// jQuery(document).ready(function($) {
//     var allSelects = '.explore_form form .form-select ';
//     function updateSelectWidth(selectElement) {
//         var $select = $(selectElement);
//         var selectedText = $select.find('option:selected').text();

//         var $tester = $('<span>')
//             .css({
//                 'font-size': $select.css('font-size'),
//                 'font-family': $select.css('font-family'),
//                 'font-weight': $select.css('font-weight'),
//                 'visibility': 'hidden', 
//                 'position': 'absolute',
//                 'display': 'inline-block',
//                 'white-space': 'nowrap' 
//             })
//             .text(selectedText);

//         $('body').append($tester);

//         var padding = 55; 
//         var newWidth = $tester.width() + padding;

//         $tester.remove(); 
//         $select.css('width', newWidth + 'px');
//     }
//     $(allSelects).each(function() {
//         updateSelectWidth(this);
//     });

//     $(allSelects).on('change', function() {
//         updateSelectWidth(this);
//     });

//     // $(allSelects).on('focus mousedown', function() {
//     //     $(this).css('width', 'auto');
//     // });

//     // $(allSelects).on('blur', function() {
//     //     updateSelectWidth(this);
//     // });

// });
// jQuery(document).ready(function ($) {
//     var allSelects = '.project_filter .project-filter-select';
//     var selectsToUpdate = $(allSelects);
//     var updateCount = 0;
//     var totalSelects = selectsToUpdate.length;

//     function updateSelectWidth(selectElement) {
//         var $select = $(selectElement);
//         var selectedText = $select.find('option:selected').text();

//         var $tester = $('<span>')
//             .css({
//                 'font-size': $select.css('font-size'),
//                 'font-family': $select.css('font-family'),
//                 'font-weight': $select.css('font-weight'),
//                 'visibility': 'hidden', 
//                 'position': 'absolute',
//                 'display': 'inline-block',
//                 'white-space': 'nowrap' 
//             })
//             .text(selectedText);

//         $('body').append($tester);

//         var padding = 60; 
//         var newWidth = $tester.width() + padding;

//         $tester.remove(); 
//         $select.css('width', newWidth + 'px');

//         // Đánh dấu đã hoàn thành và kiểm tra xem tất cả đã xong chưa
//         updateCount++;
//         if (updateCount === totalSelects) {
//             // Khi TẤT CẢ đã được tính toán, loại bỏ class ẩn
//             selectsToUpdate.removeClass('hide-until-ready');
//             // Đảm bảo opacity được set để hiển thị
//             selectsToUpdate.css('opacity', '1');
//         }
//     }

//     // Gán opacity: 0 ngay khi JS chạy lần đầu (để đảm bảo ẩn)
//     selectsToUpdate.addClass('hide-until-ready').css('opacity', '0');

//     // Lặp qua để tính toán và cập nhật
//     selectsToUpdate.each(function() {
//         updateSelectWidth(this);
//     });

//     $(allSelects).on('change', function() {
//         // Reset count và chạy lại logic nếu cần, nhưng cho sự kiện change thì chỉ cần update riêng là đủ.
//         updateSelectWidth(this);
//     });
// });


jQuery(document).ready(function ($) {
  function getEmbedUrl(url) {
    let videoId = '';
    const patterns = [
      /(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?v=([^&]+)/,
      /(?:https?:\/\/)?(?:www\.)?youtu\.be\/([^?]+)/,
      /(?:https?:\/\/)?(?:www\.)?youtube\.com\/embed\/([^?]+)/,
    ];

    for (const pattern of patterns) {
      const match = url.match(pattern);
      if (match && match[1]) {
        videoId = match[1];
        break;
      }
    }

    if (videoId) {
      return 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0';
    }
    return null;
  }
  $('.play-video-btn').on('click', function (e) {
    e.preventDefault();

    const videoUrl = $(this).data('video-url');
    const embedUrl = getEmbedUrl(videoUrl);

    if (embedUrl) {
      $('#youtube-player-iframe').attr('src', embedUrl);
      $('#video-popup-overlay').css('display', 'flex').fadeIn(300);
    } else {
      alert('Định dạng URL YouTube không hợp lệ.');
    }
  });
  $('.video-popup-close').on('click', function () {
    $('#video-popup-overlay').fadeOut(300, function () {
      $('#youtube-player-iframe').attr('src', '');
    });
  });
  $('#video-popup-overlay').on('click', function (e) {
    if ($(e.target).is('#video-popup-overlay')) {
      $(this).fadeOut(300, function () {
        $('#youtube-player-iframe').attr('src', '');
      });
    }
  });
});


jQuery(document).ready(function($) {

    function checkContentHeight() {
        $('.project-content').each(function() {
            var $wrapper = $(this).find('.project_desc_wrapper');
            var $content = $(this).find('.project_editor_content');
            var $overlay = $(this).find('.desc_overlay');
            var $btnDiv  = $(this).find('.btn_view_more').parent(); 
  
            var collapsedHeight = 600; 
            if ($(window).width() < 992) {
                collapsedHeight = 300;
            }

            if ($content.outerHeight() <= collapsedHeight) {
                $wrapper.removeClass('collapsed'); 
                $overlay.addClass('hidden');       
                $btnDiv.hide();               
            } else {
                $btnDiv.show();              
            }
        });
    }

    checkContentHeight();

    $('.btn_view_more').on('click', function(e) {
        e.preventDefault();

        var $container = $(this).closest('.project-content');
        var $wrapper   = $container.find('.project_desc_wrapper');
        var $overlay   = $container.find('.desc_overlay');
        var $btn       = $(this);

        if ($wrapper.hasClass('collapsed')) {
            // MỞ RỘNG
            $wrapper.removeClass('collapsed').addClass('expanded');
            $overlay.addClass('hidden'); 
            $btn.text('Thu gọn');
        } else {
            // THU GỌN
            $wrapper.removeClass('expanded').addClass('collapsed');
            $overlay.removeClass('hidden');
            $btn.text('Xem thêm');
            
            $('html, body').animate({
                scrollTop: $container.offset().top - 100 
            }, 500);
        }
    });

    $(window).on('resize', function() {
        if (!$('.project_desc_wrapper').hasClass('expanded')) {
             checkContentHeight();
        }
    });

});
document.addEventListener('DOMContentLoaded', function() {
    const avatarTrigger = document.getElementById('avatarTrigger');
    const userDropdown = document.getElementById('userDropdown');

    if (avatarTrigger && userDropdown) {
        avatarTrigger.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            userDropdown.classList.toggle('show');
        });

        document.addEventListener('click', function(e) {
            if (!userDropdown.contains(e.target) && !avatarTrigger.contains(e.target)) {
                userDropdown.classList.remove('show');
            }
        });
    }
});


jQuery(document).ready(function ($) {

    var isAnimating = false;
    function changeSlide($wrapper, direction) {
        if (isAnimating) return;
        isAnimating = true;

        var $items = $wrapper.find('.slide-item');
        var $current = $wrapper.find('.slide-item.active');

        if ($current.length === 0) {
            $items.first().addClass('active');
            $current = $items.first();
        }

        var currentIndex = $items.index($current);
        var total = $items.length;
        var nextIndex;
        if (direction === 'next') {
            nextIndex = (currentIndex + 1) >= total ? 0 : currentIndex + 1;
        } else {
            nextIndex = (currentIndex - 1) < 0 ? total - 1 : currentIndex - 1;
        }
        if (currentIndex === nextIndex) {
            isAnimating = false;
            return;
        }

        var $next = $items.eq(nextIndex);
        $next.addClass('animating');

        if (direction === 'next') {
            $next.css('transform', 'translateX(100%)');
        } else {
            $next.css('transform', 'translateX(-100%)');
        }
        void $next[0].offsetWidth;
        if (direction === 'next') {
            $current.css('transform', 'translateX(-100%)');
            $next.css('transform', 'translateX(0)');
        } else {
            $current.css('transform', 'translateX(100%)');
            $next.css('transform', 'translateX(0)');
        }
        setTimeout(function () {
            $current.removeClass('active').css('transform', '');
            $next.removeClass('animating').addClass('active').css('transform', '');

            isAnimating = false;
        }, 400);
    }
    $(document).on('click', '.project-slide-wrapper .nav-arrow', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var $wrapper = $(this).closest('.project-slide-wrapper');
        var direction = $(this).hasClass('next-slide') ? 'next' : 'prev';

        changeSlide($wrapper, direction);
    });
    var startX = 0;
    var endX = 0;
    var isDragging = false;
    var hasMoved = false;
    var $currentWrapper = null;
    var threshold = 50;

    $(document).on('touchstart mousedown', '.project-slide-wrapper', function (e) {
        if (isAnimating) return;

        if (e.type === 'touchstart') {
            startX = e.changedTouches[0].screenX;
        } else {
            startX = e.pageX;
            e.preventDefault();
        }

        isDragging = true;
        hasMoved = false;
        $currentWrapper = $(this);
    });

    $(document).on('touchend mouseup mouseleave', '.project-slide-wrapper', function (e) {
        if (!isDragging || !$currentWrapper) return;

        if (e.type === 'touchend') {
            endX = e.changedTouches[0].screenX;
        } else {
            endX = e.pageX;
        }

        var distance = endX - startX;

        if (Math.abs(distance) > threshold) {
            hasMoved = true;
            if (distance < 0) {
                changeSlide($currentWrapper, 'next');
            } else {
                changeSlide($currentWrapper, 'prev');
            }
        }

        isDragging = false;
        $currentWrapper = null;

        setTimeout(function () {
            hasMoved = false;
        }, 100);
    });

    $(document).on('click', '.slider-link', function (e) {
        if (hasMoved) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    });

});
document.addEventListener("DOMContentLoaded", function () {
    var tocHeader = document.querySelector('.decox-toc-wrapper .toc-header');
    var tocWrapper = document.getElementById('decox_toc');
    if (tocHeader && tocWrapper) {
        tocHeader.addEventListener('click', function () {
            tocWrapper.classList.toggle('toc-closed');
        });
    }
});