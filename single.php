<?php
/**
 * The template for displaying all single posts
 */

get_header(); ?>

<?php 
if ( have_posts() ) : while ( have_posts() ) : the_post(); 

    $post_description = get_field('post_description');
    $main_content = get_field('main_content');

    $raw_content = get_the_content();
    
    // Gọi hàm xử lý để lấy TOC HTML và Nội dung đã gắn ID
    $processed_data = decox_process_toc_and_content( $raw_content );
    
    $toc_html      = $processed_data['toc'];
    $final_content = apply_filters( 'the_content', $processed_data['content'] );
?>

<div id="jbreadcrumb" class="fs-18">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <?php yoast_breadcrumb( '<div class="breadcrumb-container">', '</div>' ); ?>
            </div>
        </div>
    </div>
</div>

<div class="single_post">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <h1 class="fs-64 fw-medium mb-4"><?php the_title(); ?></h1>

                <!-- === PHẦN MÔ TẢ NGẮN (LẤY TỪ ACF FIELD 'post_description') === -->
                <?php if ( $post_description ) :?>
                <div class="desc fw-medium fs-20 mb-3">
                    <?php echo $post_description; ?>
                </div>
                <?php endif; ?>

                <div class="project_meta">
                    <div class="row align-items-end">
                        <div class="col-auto">
                            <div class="author">
                                <figure>
                                    <?php echo get_avatar( get_the_author_meta('ID'), 40, '', 'Author Avatar', array('class' => 'img-fluid') ); ?>
                                </figure>
                                <div>
                                    <div class="cl-gray300 fw-medium">Tác giả</div>
                                    <div class="name fw-medium fs-16"><?php echo get_the_author(); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="line"></div>
                        </div>
                        <div class="col-auto">
                            <div>
                                <div class="cl-gray300 fw-medium">CẬP NHẬT</div>
                                <div class="post_date fw-medium fs-16">
                                    <?php echo date_i18n( 'j \t\h\á\n\g n Y', strtotime( get_the_date( 'Y-m-d' ) ) ); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="cl-gray300 fw-medium">Lượt xem</div>
                            <div class="post_date fw-medium fs-16">
                                <span
                                    id="project-view-count"
                                    data-project-id="<?php echo get_the_ID(); ?>"
                                >
                                </span>
                            </div>
                        </div>
                        <div class="col-lg">
                            <ul class="social fs-18">
                                <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>"
                                        target="_blank"><img
                                            src="<?php echo get_template_directory_uri(); ?>/assets/images/facebook2.svg"
                                            class="img-fluid" alt="Facebook Share"></a></li>
                                <li><a
                                        href="https://mail.google.com/mail/?view=cm&fs=1&to=<?php echo rawurlencode( get_the_title() ); ?>&body=<?php echo urlencode( get_permalink() ); ?>"><img
                                            src="<?php echo get_template_directory_uri(); ?>/assets/images/mail.svg"
                                            class="img-fluid" alt="Email Share"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="post_content fs-18">
                    <?php if($main_content): ?>
                        <div class="fs-24 cl-gray500"><p><?php echo $main_content; ?></p></div>
                        <hr>
                    <?php endif; ?>
                    
                    <?php echo $toc_html; ?>

                    <?php echo $final_content; ?>

                    <?php if ( has_excerpt() ) : ?>
                    <div class="summary">
                        <h3>Tóm tắt bài viết</h3>
                        <?php echo '<p>' . get_the_excerpt() . '</p>'; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <ul class="share_post fs-18 fw-medium">
                    <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>"
                            target="_blank"><img
                                src="<?php echo get_template_directory_uri(); ?>/assets/images/share.svg"
                                class="img-fluid" alt=""> Chia sẻ bài viết này</a></li>
                    <li><a href="#" id="copy-link-button"><img
                                src="<?php echo get_template_directory_uri(); ?>/assets/images/copy.svg"
                                class="img-fluid" alt=""><span class="copy-text">Sao chép liên kết bài viết</span></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php endwhile; endif; ?>

<!-- <?php //$fo = get_field('form', 'option'); ?>

<div id="decox_planning" class="decox_planning">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-11">
				<div class="content_center">
					<div class="row justify-content-center">
						<div class="col-xxl-6 col-lg-8">
							<div class="text-center">
								<div class="mb-3">
									<span class="btn btn-black"><?php //echo $fo['title'] ?? ''; ?></span>
								</div>

								<h3 class="fs-36 fw-semibold mb-3 cl-gray900">
									<?php //echo $fo['subtitle'] ?? ''; ?>
								</h3>

								<form id="planning-form">
									<div class="input-group">
										<input
											type="text"
											class="form-control"
											name="contact_info"
											placeholder="<?php //echo $fo['placeholder'] ?? ''; ?>"
											required
										>

										<input
											type="submit"
											class="btn btn-fullblack fs-18 fw-medium"
											value="<?php //echo $fo['submit'] ?? ''; ?>"
										>
									</div>
								</form>

								<div id="form-message" style="margin-top: 15px; font-weight: 500;"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> -->

<!-- <div class="decox_podcast">
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="fs-48 fw-semibold title_group">Podcast</h3>
            </div>
            <div class="col-auto">
                <div class="podcast__arrows">
                    <div class="prev_arrow">
                        <img src="<?php //echo get_template_directory_uri(); ?>/assets/images/dropdown.svg"
                            alt="Previous">
                    </div>
                    <div class="next_arrow">
                        <img src="<?php// echo get_template_directory_uri(); ?>/assets/images/dropdown.svg" alt="Next">
                    </div>
                </div>
            </div>
        </div>
        <div class="slider_podcast">
            <?php
            // $args = array(
            //     'post_type'      => 'podcast',
            //     'posts_per_page' => 8,
            //     'orderby'        => 'date',
            //     'order'          => 'DESC',
            // );

            // $podcast_slider_query = new WP_Query( $args );

            // if ( $podcast_slider_query->have_posts() ) :
            //     while ( $podcast_slider_query->have_posts() ) : $podcast_slider_query->the_post();
            ?>

            <div class="item">
                <figure>
                    <a href="<?php //the_permalink(); ?>">
                        <?php //the_post_thumbnail('full', ['class' => 'img-fluid']);?>
                    </a>
                </figure>
                <div class="meta_info">
                    <h4 class="fs-24 fw-medium">
                        <a href="<?php //the_permalink(); ?>"><?php //the_title(); ?></a>
                    </h4>
                    <div class="row justify-content-between">
                        <div class="col-auto">
                            <ul class="controls">
                                <li>
                                    <?php//  $youtube_link = get_field('youtube_link'); ?>
                                    <a href="#" class="play-video-btn"
                                        data-video-url="<?php //echo esc_url($youtube_link); ?>"><i
                                            class="fa fa-play"></i></a>
                                </li>
                                <li><span><?php //echo get_field('minute_time') ?? '' ?> phút</span></li>
                            </ul>
                        </div>
                        <div class="col-auto">
                            <div class="action">
                                <a href="#" class=""><img
                                        src="<?php //echo get_template_directory_uri(); ?>/assets/images/dots.svg"
                                        class="img-fluid"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            //     endwhile;
            // endif;
            // wp_reset_postdata();
            ?>
        </div>
    </div>
</div> -->

<div class="decox_news">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="fs-48 fw-semibold title_group">Tiêu điểm</h3>
                    </div>
                    <div class="col-auto">
                        <div class="view_all">
                            <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'tin-tuc' ) ) ); ?>"
                                class="cl-blue fs-18">Xem tất cả <img
                                    src="<?php echo get_template_directory_uri(); ?>/assets/images/arrowb.svg"
                                    alt=""></a>
                        </div>
                    </div>
                </div>

                <?php
                // ===== ONE QUERY ONLY (5 posts: 1 featured + 1 left + 3 right) =====
                $exclude_slug   = 'studio-va-nha-thiet-ke';
                $exclude_cat    = get_category_by_slug($exclude_slug);
                $exclude_cat_id = $exclude_cat ? (int) $exclude_cat->term_id : 0;

                $news_query = new WP_Query(array(
                    'post_type'           => 'post',
                    'post_status'         => 'publish',
                    'posts_per_page'      => 8,
                    'ignore_sticky_posts' => 1,
                    'category__not_in'    => $exclude_cat_id ? array($exclude_cat_id) : array(),
                ));

                $news_posts = !empty($news_query->posts) ? $news_query->posts : array();

                $news_map = array(
                    'featured'        => $news_posts[0] ?? null,
                    'highlight_left'  => $news_posts[1] ?? null,
                    'highlight_right' => array_slice($news_posts, 2, 3),
                );
                ?>

                <?php if ( $news_map['featured'] ) : ?>
                    <?php
                    $post = $news_map['featured'];
                    setup_postdata($post);
                    ?>
                    <div class="post_featured">
                        <figure>
                            <a href="<?php the_permalink(); ?>">
                               <?php if ( has_post_thumbnail() ) {
                                    the_post_thumbnail('full');
                                } else {
                                    echo '<img src="' . get_template_directory_uri() . '/assets/images/tin-tuc-default.png" alt="' . get_the_title() . '">';
                                } ?>
                            </a>
                        </figure>
                        <div class="meta_info">
                            <h4 class="fs-48 fw-medium"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            <!-- <div class="post_meta">
                                <ul>
                                    <li class="fw-medium">
                                        <?php// echo get_avatar(get_the_author_meta('ID'), 24, '', 'Author Avatar', array('class' => 'img-fluid')); ?>
                                        <?php //the_author(); ?></li>
                                    <li><?php// echo get_the_date( 'd/m/Y' ); ?></li>
                                </ul>
                            </div> -->
                        </div>
                    </div>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>

                <div class="highlight">
                    <div class="row">
                        <div class="col-lg-5">
                            <?php if ( $news_map['highlight_left'] ) : ?>
                                <?php
                                $post = $news_map['highlight_left'];
                                setup_postdata($post);
                                ?>
                                <div class="post_highlight">
                                    <figure>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php if ( has_post_thumbnail() ) {
                                                the_post_thumbnail('medium_large', array('class' => 'img-fluid'));
                                            } else {
                                                echo '<img src="' . get_template_directory_uri() . '/assets/images/tin-tuc-default.png" alt="' . get_the_title() . '">';
                                            } ?>
                                        </a>
                                        <!-- <div class="post_view">
                                            <img src="<?php //echo get_template_directory_uri(); ?>/assets/images/eye.svg"
                                                class="img-fluid" alt=""> <?php
                                                //$views = decox_get_formatted_views( get_the_ID() );
                                                //echo $views;
                                            ?>
                                        </div> -->
                                    </figure>
                                    <div class="meta_info">
                                        <div class="row">
                                            <div class="col-auto">
                                                <div class="post_cate">
                                                    <?php
                                                    $categories = get_the_category();
                                                    if ( ! empty( $categories ) ) {
                                                        $cat = $categories[0];
                                                        if ( $cat->parent ) {
                                                            $ancestors = get_ancestors( $cat->term_id, 'category' );
                                                            $root_id = end( $ancestors );
                                                            $cat = get_category( $root_id );
                                                        }
                                                        echo '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '">' . esc_html( $cat->name ) . '</a>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="time_reading"><?php echo get_decox_reading_time(); ?></div>
                                            </div>
                                        </div>
                                        <h4 class="fs-24 fw-medium post_title"><a
                                                href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                        <div class="desc fs-16 cl-gray400">
                                            <p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
                                        </div>
                                        <!-- <div class="post_meta">
                                            <ul>
                                                <li><?php //echo get_avatar(get_the_author_meta('ID'), 24, '', 'Author Avatar', array('class' => 'img-fluid')); ?>
                                                    <?php //the_author(); ?></li>
                                                <li><?php //echo get_the_date( 'd/m/Y' ); ?></li>
                                            </ul>
                                        </div> -->
                                    </div>
                                </div>
                                <?php wp_reset_postdata(); ?>
                            <?php endif; ?>
                        </div>

                        <div class="col-lg-7">
                            <?php if ( ! empty($news_map['highlight_right']) ) : ?>
                                <?php foreach ( $news_map['highlight_right'] as $right_post ) : ?>
                                    <?php
                                    if ( ! $right_post ) continue;
                                    $post = $right_post;
                                    setup_postdata($post);
                                    ?>
                                    <div class="second_highlight">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <figure>
                                                    <a href="<?php the_permalink(); ?>">
                                                        <?php if ( has_post_thumbnail() ) {
                                                            the_post_thumbnail('medium', array('class' => 'img-fluid'));
                                                        } else {
                                                            echo '<img src="' . get_template_directory_uri() . '/assets/images/tin-tuc-default.png" alt="' . get_the_title() . '">';
                                                        } ?>
                                                    </a>
                                                    <!-- <div class="post_view">
                                                        <img src="<?php //echo get_template_directory_uri(); ?>/assets/images/eye.svg"
                                                            class="img-fluid" alt=""> <?php
                                                        //$views = decox_get_formatted_views( get_the_ID() );
                                                        //echo $views;
                                                    ?> -->
                                                    <!-- </div> -->
                                                </figure>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="meta_info">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="post_cate">
                                                                <?php
                                                                $categories = get_the_category();
                                                                if ( ! empty( $categories ) ) {
                                                                    $cat = $categories[0];
                                                                    if ( $cat->parent ) {
                                                                        $ancestors = get_ancestors( $cat->term_id, 'category' );
                                                                        $root_id = end( $ancestors );
                                                                        $cat = get_category( $root_id );
                                                                    }
                                                                    echo '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '">' . esc_html( $cat->name ) . '</a>';
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="time_reading"><?php echo get_decox_reading_time(); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h4 class="fs-18 fw-semibold post_title"><a
                                                            href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                                    <!-- <div class="post_meta">
                                                        <ul>
                                                            <li><?php //echo get_avatar(get_the_author_meta('ID'), 24, '', 'Author Avatar', array('class' => 'img-fluid')); ?>
                                                                <?php //the_author(); ?></li>
                                                            <li><?php //echo get_the_date( 'd/m/Y' ); ?></li>
                                                        </ul>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php wp_reset_postdata(); ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="space_post py-0">
                    <div class="list_post mt-0">
                        <div class="row gy-lg-4">
                            <?php
                            // Lấy đúng 3 bài: index 5,6,7
                            for ( $i = 5; $i <= 7; $i++ ) :
                                if ( empty($news_posts[$i]) ) continue;

                                $post = $news_posts[$i];
                                setup_postdata($post);
                            ?>
                                <div class="col-lg-4">
                                    <div class="post_item">
                                        <figure>
                                            <a href="<?php the_permalink(); ?>">
                                                <?php
                                                if ( has_post_thumbnail() ) {
                                                    the_post_thumbnail('medium_large', array('class' => 'img-fluid'));
                                                } else {
                                                    echo '<img src="' . get_template_directory_uri() . '/assets/images/product1.jpg" class="img-fluid" alt="Placeholder">';
                                                }
                                                ?>
                                            </a>
                                        </figure>

                                        <div class="meta_info">
                                            <div class="row">
                                                <div class="col-auto">
                                                    <div class="post_cate fw-semibold">
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
                                                        <a href="">
                                                            <i class="fa fa-heart-o" aria-hidden="true"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <h4 class="fs-20 fw-semibold post_title">
                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                            </h4>

                                            <div class="desc fs-14 cl-gray400">
                                                <?php the_excerpt(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                            <?php wp_reset_postdata(); ?>
                        </div>
                    </div>
                </div>


                <?php wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const copyButton = document.getElementById('copy-link-button');
    if (copyButton) {
        const copyTextSpan = copyButton.querySelector('.copy-text');
        const originalText = copyTextSpan.textContent;
        copyButton.addEventListener('click', function(e) {
            e.preventDefault();
            const urlToCopy = window.location.href;
            navigator.clipboard.writeText(urlToCopy).then(function() {
                copyTextSpan.textContent = 'Đã sao chép!';
                setTimeout(function() {
                    copyTextSpan.textContent = originalText
                }, 2e3)
            }).catch(function(err) {
                copyTextSpan.textContent = 'Lỗi khi sao chép';
                console.error('Could not copy text: ', err)
            })
        })
    }
    if (typeof jQuery !== 'undefined') {
        setTimeout(function() {
            jQuery('.single_post .post_content img').each(function() {
                const $img = jQuery(this);
                
                if ($img.closest('a[data-fancybox]').length > 0) {
                    return;
                }
                
                let fullSrc = $img.attr('data-src') || $img.attr('data-lazy-src') || $img.attr('data-original') || $img.attr('src');
                
                const srcset = $img.attr('srcset');
                if (srcset) {
                    const srcsetArray = srcset.split(',');
                    let maxWidth = 0;
                    let maxSrc = fullSrc;
                    
                    srcsetArray.forEach(function(src) {
                        const parts = src.trim().split(' ');
                        if (parts.length >= 2) {
                            const width = parseInt(parts[1]);
                            if (width > maxWidth) {
                                maxWidth = width;
                                maxSrc = parts[0];
                            }
                        }
                    });
                    
                    if (maxWidth > 0) {
                        fullSrc = maxSrc;
                    }
                }
                
                if (fullSrc && fullSrc.length > 0) {
                    $img.wrap('<a href="' + fullSrc + '" data-fancybox="post-gallery"></a>');
                }
            });
            
            if (typeof Fancybox !== 'undefined') {
                Fancybox.destroy();
                Fancybox.bind("[data-fancybox]", {
                    Toolbar: {
                        display: {
                            left: ["infobar"],
                            middle: [],
                            right: ["slideshow", "zoom", "fullscreen", "close"],
                        },
                    },
                    Thumbs: {
                        autoStart: true,
                    },
                    preload: 2,
                    infinite: false,
                    parentEl: document.body,
                    on: {
                        // === BẮT ĐẦU ĐOẠN SỬA ===
                        init: function () {
                            // Khi mở ảnh: Bỏ sticky header
                            jQuery('#header_site').css({
                                'position': 'relative',
                                'z-index': '1'
                            });
                            jQuery('#header_site').removeClass('sticky-top');
                        },
                        destroy: function () {
                            // Khi tắt ảnh: Khôi phục sticky header
                            jQuery('#header_site').css({
                                'position': '',
                                'z-index': ''
                            });
                            jQuery('#header_site').addClass('sticky-top');
                        },
                        // === KẾT THÚC ĐOẠN SỬA ===
                        
                        error: function(fancybox, slide) {
                            console.error('Fancybox load error:', slide.src);
                        }
                    }
                });
            }
        }, 200);
    }
});
</script>

<?php get_footer(); ?>