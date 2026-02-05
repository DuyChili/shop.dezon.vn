jQuery(document).ready(function ($) {
    // ==================== CONFIG ====================
    const CONFIG = {
        postsPerPage: 6,
        // Sử dụng proxy endpoint nếu có, fallback về API gốc
        apiUrl: '/wp-json/theme/v1/dezon-posts',
        apiUrlFallback: 'https://dezon.vn/wp-json/wp/v2/posts',
        scrollOffset: 200,
        cacheExpiry: 5 * 60 * 1000, // 5 phút
    };

    // ==================== CACHE MANAGER ====================
    const CacheManager = {
        cache: {},

        set(page, data) {
            this.cache[page] = {
                data: data,
                timestamp: Date.now()
            };
        },

        get(page) {
            const cached = this.cache[page];
            if (!cached) return null;

            // Kiểm tra cache còn hạn không
            if (Date.now() - cached.timestamp > CONFIG.cacheExpiry) {
                delete this.cache[page];
                return null;
            }

            return cached.data;
        },

        clear() {
            this.cache = {};
        }
    };

    // ==================== VALIDATION ====================
    if (typeof myLocalThemeParams === 'undefined') {
        console.error('LỖI: Chưa khai báo biến myLocalThemeParams trong footer.php');
        return;
    }

    const { defaultImage, iconPrev, iconNext } = myLocalThemeParams;

    // ==================== DOM ELEMENTS ====================
    const $postSection = $('.space_post');
    const $listContainer = $('#dezon-post-list');
    const $pagingContainer = $('#dezon-pagination');

    // ==================== STATE ====================
    let apiLoaded = false;
    let isLoading = false;
    let useProxyApi = true; // Thử proxy trước

    // ==================== LAZY LOAD INITIALIZATION ====================
    function initLazyLoad() {
        if (!$postSection.length) {
            loadDezonPosts(1);
            return;
        }

        // Sử dụng Intersection Observer nếu browser hỗ trợ
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !apiLoaded) {
                        loadDezonPosts(1);
                        apiLoaded = true;
                        observer.disconnect();
                    }
                });
            }, {
                rootMargin: `${CONFIG.scrollOffset}px`
            });

            observer.observe($postSection[0]);
        } else {
            // Fallback cho browser cũ
            $(window).on('scroll.dezonPosts', throttle(function () {
                if (apiLoaded) return;

                const sectionTop = $postSection.offset().top;
                const sectionHeight = $postSection.outerHeight();
                const windowHeight = $(window).height();
                const scrollTop = $(window).scrollTop();

                if (scrollTop > (sectionTop + sectionHeight - windowHeight - CONFIG.scrollOffset)) {
                    loadDezonPosts(1);
                    apiLoaded = true;
                    $(window).off('scroll.dezonPosts');
                }
            }, 100));

            // Check ngay khi load nếu đã ở vị trí section
            if ($(window).scrollTop() + $(window).height() > $postSection.offset().top) {
                loadDezonPosts(1);
                apiLoaded = true;
            }
        }
    }

    // ==================== THROTTLE UTILITY ====================
    function throttle(func, limit) {
        let inThrottle;
        return function (...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    // ==================== LOADING STATE ====================
    function showLoading() {
        $listContainer.css('opacity', '0.5');
    }

    function hideLoading() {
        $listContainer.css('opacity', '1');
    }

    function showSpinner() {
        $listContainer.html(`
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `);
    }

    // ==================== DATA HELPERS ====================
    function extractThumbnail(post) {
        // Ưu tiên 1: Featured media từ _embedded
        if (post._embedded &&
            post._embedded['wp:featuredmedia'] &&
            post._embedded['wp:featuredmedia'][0] &&
            post._embedded['wp:featuredmedia'][0].source_url) {
            return post._embedded['wp:featuredmedia'][0].source_url;
        }

        // Ưu tiên 2: Yoast SEO og_image
        if (post.yoast_head_json &&
            post.yoast_head_json.og_image &&
            post.yoast_head_json.og_image[0] &&
            post.yoast_head_json.og_image[0].url) {
            return post.yoast_head_json.og_image[0].url;
        }

        // Ưu tiên 3: Jetpack featured_media
        if (post.jetpack_featured_media_url) {
            return post.jetpack_featured_media_url;
        }

        // Fallback: Default image
        return defaultImage;
    }

    function extractAuthor(post) {
        if (post._embedded && post._embedded['author'] && post._embedded['author'][0]) {
            const author = post._embedded['author'][0];
            return {
                name: author.name || 'Dezon',
                avatar: (author.avatar_urls && author.avatar_urls['48'])
                    ? author.avatar_urls['48']
                    : 'https://secure.gravatar.com/avatar/?s=50&d=mm&r=g'
            };
        }
        return {
            name: 'Dezon',
            avatar: 'https://secure.gravatar.com/avatar/?s=50&d=mm&r=g'
        };
    }

    function extractCategory(post) {
        if (post._embedded &&
            post._embedded['wp:term'] &&
            post._embedded['wp:term'][0] &&
            post._embedded['wp:term'][0].length > 0) {
            const cat = post._embedded['wp:term'][0][0];
            return {
                name: cat.name || 'Tin tức',
                link: cat.link || '#'
            };
        }
        return { name: 'Tin tức', link: '#' };
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    function calculateReadingTime(post) {
        // Ưu tiên reading_time từ API nếu có
        if (post.reading_time) {
            return post.reading_time;
        }

        // Tính từ content
        if (post.content && post.content.rendered) {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = post.content.rendered;
            const plainText = tempDiv.textContent || tempDiv.innerText || '';
            const wordCount = plainText.trim().split(/\s+/).length;
            let minutes = Math.ceil(wordCount / 200);
            minutes = Math.ceil(minutes * 1.4);
            return `${Math.max(1, minutes)} phút đọc`;
        }

        return '3 phút đọc';
    }

    function stripHtml(html) {
        if (!html) return '';
        return html.replace(/(<([^>]+)>)/gi, '');
    }

    function decodeHtmlEntities(text) {
        const textArea = document.createElement('textarea');
        textArea.innerHTML = text;
        return textArea.value;
    }

    // ==================== RENDER FUNCTIONS ====================
    function renderPost(post) {
        const thumbUrl = extractThumbnail(post);
        const author = extractAuthor(post);
        const category = extractCategory(post);
        const title = post.title && post.title.rendered
            ? decodeHtmlEntities(post.title.rendered)
            : 'Không có tiêu đề';
        const link = post.link || '#';
        const excerptRaw = post.excerpt && post.excerpt.rendered
            ? stripHtml(post.excerpt.rendered)
            : '';
        const excerpt = excerptRaw.substring(0, 100) + (excerptRaw.length > 100 ? '...' : '');
        const dateStr = formatDate(post.date);
        const readingTime = calculateReadingTime(post);

        return `
            <div class="col-lg-4">
                <div class="post_item">
                    <figure>
                        <a href="${link}" target="_blank" rel="noopener noreferrer">
                            <img src="${thumbUrl}" 
                                 class="img-fluid" 
                                 alt="${title}" 
                                 style="aspect-ratio: 4/3; object-fit: cover; width: 100%;" 
                                 loading="lazy"
                                 onerror="this.onerror=null;this.src='${defaultImage}';">
                        </a>
                    </figure>
                    <div class="meta_info">
                        <div class="row">
                            <div class="col-auto">
                                <div class="post_cate fw-semibold">
                                    <a href="${category.link}" target="_blank" rel="noopener noreferrer">${category.name}</a>
                                </div>
                            </div>
                            <div class="col">
                                <div class="time_reading">${readingTime}</div>
                            </div>
                            <div class="col-auto">
                                <div class="like_post">
                                    <a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                        <h4 class="fs-20 fw-semibold post_title">
                            <a href="${link}" target="_blank" rel="noopener noreferrer">${title}</a>
                        </h4>
                        <div class="desc fs-14 cl-gray400">
                            <p>${excerpt}</p>
                        </div>
                        <div class="post_meta">
                            <ul>
                                <li>
                                    <img src="${author.avatar}" 
                                         class="img-fluid" 
                                         alt="${author.name}" 
                                         style="width:24px; height:24px; border-radius:50%; object-fit:cover; margin-right:5px;"
                                         loading="lazy">
                                    ${author.name}
                                </li>
                                <li>${dateStr}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>`;
    }

    function renderPosts(posts, totalPages, currentPage) {
        if (!posts || posts.length === 0) {
            $listContainer.html('<div class="col-12 text-center">Không tìm thấy bài viết nào.</div>');
            $pagingContainer.empty();
            hideLoading();
            return;
        }

        const html = posts.map(renderPost).join('');
        $listContainer.html(html);
        hideLoading();

        renderPagination(totalPages, currentPage);

        // Scroll to section nếu không phải trang đầu
        if (currentPage > 1) {
            $('html, body').animate({
                scrollTop: $postSection.offset().top - 100
            }, 500);
        }
    }

    function renderPagination(totalPages, currentPage) {
        if (totalPages <= 1) {
            $pagingContainer.empty();
            return;
        }

        let html = '';

        // Previous button
        if (currentPage > 1) {
            html += `<a class="prevpostslink" href="#" data-page="${currentPage - 1}">
                        <img src="${iconPrev}" class="img-fluid" alt="Previous">
                     </a>`;
        }

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            const showPage = i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1);
            const showEllipsis = i === currentPage - 2 || i === currentPage + 2;

            if (showPage) {
                if (i === currentPage) {
                    html += `<span aria-current="page" class="current">${i}</span>`;
                } else {
                    html += `<a class="page larger" href="#" data-page="${i}">${i}</a>`;
                }
            } else if (showEllipsis) {
                html += `<span class="extend">...</span>`;
            }
        }

        // Next button
        if (currentPage < totalPages) {
            html += `<a class="nextpostslink" href="#" data-page="${currentPage + 1}">
                        <img src="${iconNext}" class="img-fluid" alt="Next">
                     </a>`;
        }

        $pagingContainer.html(html);
    }

    // ==================== API CALL ====================
    function loadDezonPosts(page) {
        // Prevent duplicate requests
        if (isLoading) return;

        // Check cache first
        const cached = CacheManager.get(page);
        if (cached) {
            console.log('📦 Loaded from cache, page:', page);
            renderPosts(cached.posts, cached.totalPages, page);
            return;
        }

        isLoading = true;
        showLoading();

        // Thử proxy API trước, nếu lỗi thì fallback về API gốc
        const apiUrl = useProxyApi ? CONFIG.apiUrl : CONFIG.apiUrlFallback;
        const requestData = useProxyApi
            ? { page: page }
            : { per_page: CONFIG.postsPerPage, page: page, _embed: true };

        console.log('🌐 Fetching from:', apiUrl, 'page:', page);

        $.ajax({
            url: apiUrl,
            method: 'GET',
            data: requestData,
            timeout: 10000,
            success: function (posts, textStatus, request) {
                const totalPages = parseInt(request.getResponseHeader('X-WP-TotalPages')) || 1;

                console.log('✅ API success, total pages:', totalPages, 'posts:', posts.length);

                // Save to cache
                CacheManager.set(page, { posts, totalPages });

                renderPosts(posts, totalPages, page);
            },
            error: function (xhr, status, error) {
                console.error('❌ API Error:', status, error, 'URL:', apiUrl);

                // Nếu proxy lỗi, thử fallback về API gốc
                if (useProxyApi) {
                    console.log('🔄 Proxy failed, trying direct API...');
                    useProxyApi = false;
                    isLoading = false;
                    loadDezonPosts(page);
                    return;
                }

                let errorMessage = 'Lỗi kết nối server.';
                if (status === 'timeout') {
                    errorMessage = 'Kết nối quá thời gian. Vui lòng thử lại.';
                } else if (xhr.status === 404) {
                    errorMessage = 'Không tìm thấy dữ liệu.';
                }

                $listContainer.html(`
                    <div class="col-12 text-center">
                        <p>${errorMessage}</p>
                        <button class="btn btn-primary btn-sm mt-2" id="retry-load">Thử lại</button>
                    </div>
                `);
                hideLoading();
            },
            complete: function () {
                isLoading = false;
            }
        });
    }

    // ==================== EVENT HANDLERS ====================
    // Pagination click
    $(document).on('click', '#dezon-pagination a', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page && !isLoading) {
            loadDezonPosts(page);
        }
    });

    // Retry button
    $(document).on('click', '#retry-load', function () {
        showSpinner();
        useProxyApi = true; // Reset để thử lại từ proxy
        loadDezonPosts(1);
    });

    // ==================== INIT ====================
    initLazyLoad();
});


document.addEventListener("DOMContentLoaded", function () {
    // 1. Xử lý khi người dùng click vào Tab -> Đổi URL
    var triggerTabList = [].slice.call(document.querySelectorAll('#myTab button[data-bs-toggle="tab"]'));
    triggerTabList.forEach(function (triggerEl) {
        triggerEl.addEventListener('shown.bs.tab', function (event) {
            var fragment = event.target.getAttribute('data-fragment');
            if (fragment) {
                history.pushState(null, null, '#' + fragment);
            }
        });
    });

    var hash = window.location.hash.substring(1); 
    if (hash) {
        var targetTab = document.querySelector('#myTab button[data-fragment="' + hash + '"]');
        if (targetTab) {
            var tab = new bootstrap.Tab(targetTab);
            tab.show();
            setTimeout(() => {
                targetTab.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 300);
        }
    }
});