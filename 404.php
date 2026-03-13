<?php
get_header(); ?>
<?php 
$sea = get_field('search','option'); 
$projectpage = get_page_by_path('du-an');
$projectpage_url = $projectpage ? get_permalink($projectpage->ID) : home_url('/du-an/');
?>
<div class="explore_form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <h4 class="fs-20 text-center fw-medium mb-3">
                    <?php echo $sea['title'] ?? ''; ?>
                </h4>

                <form role="search" method="get" id="decox-search-form"
                    action="<?php echo esc_url($projectpage_url); ?>">

                    <button type="submit" class="btn-search-overlay" aria-label="Tìm kiếm"></button>

                    <div class="input-group">

                        <input type="search" class="form-control" placeholder="<?php echo $sea['placeholder'] ?? ''; ?>"
                            value="<?php echo get_search_query(); ?>" name="s" autocomplete="off">

                        <?php
                            // Lấy giá trị đã chọn để fill vào select box
                            $selected_cat      = isset($_GET['project_cat_filter']) ? sanitize_text_field($_GET['project_cat_filter']) : '';
                            $selected_designer = isset($_GET['project_designer_filter']) ? sanitize_text_field($_GET['project_designer_filter']) : '';
                            $selected_style    = isset($_GET['project_style_filter']) ? sanitize_text_field($_GET['project_style_filter']) : '';
                        ?>

                        <?php 
                            $cat_name = '';
                            if ($selected_cat) {
                                $term = get_term_by('slug', $selected_cat, 'project_category');
                                if ($term && !is_wp_error($term)) $cat_name = $term->name;
                            }
                        ?>
                        <select name="project_cat_filter" data-taxonomy="project_category"
                            class="form-select search-select2" data-placeholder="Dự án">
                            <option value="">Dự án</option>
                            <?php if ($selected_cat && $cat_name): ?>
                            <option value="<?php echo esc_attr($selected_cat); ?>" selected>
                                <?php echo esc_html($cat_name); ?>
                            </option>
                            <?php endif; ?>
                        </select>

                        <?php 
                            $designer_cpt_slug = 'partner'; 
                            $designer_name = '';
                            if ($selected_designer) {
                                $designer_post = get_page_by_path($selected_designer, OBJECT, $designer_cpt_slug);
                                if ($designer_post) $designer_name = $designer_post->post_title;
                            }
                        ?>
                        <select name="project_designer_filter"
                            data-post-type="<?php echo esc_attr($designer_cpt_slug); ?>"
                            class="form-select search-select2" data-placeholder="Thực hiện">
                            <option value="">Thực hiện</option>
                            <?php if ($selected_designer && $designer_name): ?>
                            <option value="<?php echo esc_attr($selected_designer); ?>" selected>
                                <?php echo esc_html($designer_name); ?>
                            </option>
                            <?php endif; ?>
                        </select>

                        <?php 
                            $style_name = '';
                            if ($selected_style) {
                                $term = get_term_by('slug', $selected_style, 'project_style');
                                if ($term && !is_wp_error($term)) $style_name = $term->name;
                            }
                        ?>
                        <select name="project_style_filter" data-taxonomy="project_style"
                            class="form-select search-select2" data-placeholder="Phong cách">
                            <option value="">Phong cách</option>
                            <?php if ($selected_style && $style_name): ?>
                            <option value="<?php echo esc_attr($selected_style); ?>" selected>
                                <?php echo esc_html($style_name); ?>
                            </option>
                            <?php endif; ?>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $te = get_field('404','option'); ?>
<div class="page_404">
        <div class="container">
            <div class="inner">
                <div class="img_background">
                    <img src="<?php echo $te['image_desktop'] ?? ''; ?>" class="img-fluid d-none d-lg-block" alt="">
                    <img src="<?php echo $te['image_mobile'] ?? '';?>" class="img-fluid d-block d-lg-none" alt="">
                </div>
                <div class="meta_info">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="text-center fs-20 fw-medium mb-3">
                                <?php echo $te['text'] ?? ''; ?>
                            </div>
                            <div class="d-flex flex-wrap gap-2 justify-content-center">
                                <a href="<?php echo home_url()?>" class="btn btn-black">
                                    <?php echo $te['button']['text_button_home'] ?? ''; ?>
                                </a>
                                <a href="https://dezon.vn/du-an/" class="btn btn-fblack">
                                    <?php echo $te['button']['text_button_project'] ?? ''; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
get_footer();