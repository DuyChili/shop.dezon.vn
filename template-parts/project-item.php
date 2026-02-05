<?php
/**
 * Template part: Project Item (Remote Data)
 */

$project = isset($args['project']) ? $args['project'] : null;

if ( ! $project ) return;

$slider_images = isset($project['thumbnail']) ? $project['thumbnail'] : array();
?>

<div class="col-lg-4 col-6">
    <div class="item_post">
        <figure class="project-slide-wrapper">
            
            <?php if ( !empty($project['studio']) ): ?>
                <a href="<?php echo esc_url($project['studio']['link']); ?>" class="post_studio" target="_blank">
                    By <?php echo esc_html($project['studio']['name']); ?>
                </a>
            <?php endif; ?>

            <a href="<?php echo esc_url($project['link']); ?>" class="slider-link" target="_blank">
                <div class="slider-track">
                    <?php 
                    if ( !empty($slider_images) && is_array($slider_images) ) :
                        foreach( $slider_images as $index => $src ) : 
                            $active_class = ($index === 0) ? 'active' : ''; 
                            ?>
                            <div class="slide-item <?php echo $active_class; ?>">
                                <img src="<?php echo esc_url($src); ?>" class="img-fluid" alt="<?php echo esc_attr($project['title']); ?>">
                            </div>
                        <?php endforeach; 
                    else: ?>
                        <div class="slide-item active">
                            <?php 
                            $img_src = (is_string($slider_images) && !empty($slider_images)) ? $slider_images : get_template_directory_uri() . '/assets/images/du-an-default.png';
                            ?>
                            <img src="<?php echo esc_url($img_src); ?>" class="img-fluid" alt="Project Image">
                        </div>
                    <?php endif; ?>
                </div>
            </a>

            <?php if ( is_array($slider_images) && count($slider_images) > 1 ) : ?>
            <div class="slider-nav">
                <span class="nav-arrow prev-slide">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </span>
                <span class="nav-arrow next-slide">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </span>
            </div>
            <?php endif; ?>

            <div class="meta-bottom-left">
                <div class="post_view">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/eye.svg" class="img-fluid" alt="View icon">
                    <?php echo esc_html($project['views']); ?>
                </div>

                <?php if ( $project['has_ai'] ): ?>
                    <div class="post_ask_ai">Ask AI</div>
                <?php endif; ?>
            </div>

            <?php if ( !empty($project['categories']) ): ?>
            <div class="post_cate d-none d-lg-flex">
                <?php foreach( $project['categories'] as $cat ): ?>
                    <a href="<?php echo esc_url($cat->link); ?>" target="_blank">
                        <?php echo esc_html($cat->name); ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

        </figure>

        <div class="meta_info">
            <div class="row single-line-force">
                <div class="col">
                    <?php if ( !empty($project['categories']) ): ?>
                    <div class="project-cat-mobile d-block d-lg-none">
                        <a href="<?php echo esc_url($project['categories'][0]->link); ?>" target="_blank">
                            <?php echo esc_html($project['categories'][0]->name); ?>
                        </a>
                    </div>
                    <?php endif; ?>

                    <h4 class="fs-14 fw-semibold post_title single-line-title">
                        <a href="<?php echo esc_url($project['link']); ?>" target="_blank">
                            <?php 
                                echo esc_html($project['title']); 
                            ?>
                        </a>
                    </h4>
                </div>
            </div>
        </div>
    </div>
</div>