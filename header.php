<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="KPhBzkfIgFBG3RzhU1gr12_FEOxEe4AcatgWAjRMUpg" />
    <title><?php wp_title(''); ?></title>
    <link rel="apple-touch-icon" sizes="180x180" href="https://dezon.vn/wp-content/uploads/2025/11/cropped-logo-1.png">
    <link rel="apple-touch-icon" sizes="167x167" href="https://dezon.vn/wp-content/uploads/2025/11/cropped-logo-1.png">
    <link rel="apple-touch-icon" sizes="152x152" href="https://dezon.vn/wp-content/uploads/2025/11/cropped-logo-1.png">
    <link rel="apple-touch-icon" href="https://dezon.vn/wp-content/uploads/2025/11/cropped-logo-1.png">
    <?php wp_head(); ?>
</head>

<body <?php body_class() ?>>
    <?php $header = get_field('header', 'option'); ?>
    <?php //$special_menu_class = 'cspace';
    // if ( is_page_template('page-templates/cspace-page.php') || is_page_template('page-templates/product-detail.php') ) {
    //     $special_menu_class .= ' active';
    //} ?>
    <div id="menu_mobile">
        <a href="" class="hamburger_btn d-lg-none">
            <div class="hamburger-icon">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </a>
        <ul class="menu_site">
            <?php
                   
                        if (has_nav_menu('primary_menu')) {
                            wp_nav_menu(array(
                                'theme_location' => 'primary_menu', 
                                'container'      => false,        
                                'items_wrap'     => '%3$s',       
                                'fallback_cb'    => false,
                                'depth'          => 3,             
                            ));
                        }
                        ?>
        </ul>
        <!-- <div class="special_menu">
            <a href="https://dezon.vn/" class="cspace active">
                <img src="<?php //echo get_template_directory_uri(); ?>/assets/images/cspace.png" class="img-fluid"
                    alt="">
            </a>
        </div> -->
        <ul class="user_action fs-18">
                <?php if (function_exists('dezon_is_logged_in') && dezon_is_logged_in()): ?>
                    <?php $user = dezon_get_user(); ?>
                    <li>
                        <a href="#" class="fw-bold">
                            Chào, <?php echo esc_html($user['full_name'] ?? $user['email']); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(home_url('?dezon_logout=1')); ?>" class="fw-bold">
                            Đăng xuất
                        </a>
                    </li>
                <?php else: ?>
                <li>
                    <a href="<?php echo esc_url(home_url('?trigger_sso=1')); ?>" class="fw-bold">Đăng nhập</a>
                </li>
                <li>
                    <a href="<?php echo esc_url(home_url('?trigger_sso=1')); ?>" class="fw-bold">Đăng ký</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
    <div class="overlay_menu"></div>
    <header id="header_site" class="sticky-top">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-auto">
                    <a href="https://dezon.vn/" class="logo_site">
                        <img src="<?php echo $header['logo'] ?? ''; ?>" class="img-fluid" alt="">
                    </a>
                </div>
                <div class="col-auto d-block d-lg-none">
                    <div class="d-flex d-lg-none justify-content-end align-items-center">
                        <a href="" class="hamburger_btn d-lg-none">
                            <div class="hamburger-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg d-none d-lg-block">
                    <div class="d-flex align-items-center">
                    <ul class="menu_site fs-18">
                        <?php
                        if (has_nav_menu('primary_menu')) {
                            wp_nav_menu(array(
                                'theme_location' => 'primary_menu',
                                'container'      => false,         
                                'items_wrap'     => '%3$s',         
                                'fallback_cb'    => false,
                                'depth'          => 3,              
                            ));
                        }
                        ?>
                    </ul>
                    <!-- <div class="special_menu">
                        <a href="<?php //echo home_url('/'); ?>" class="cspace active">
                            <img src="<?php //echo get_template_directory_uri(); ?>/assets/images/cspace.png"
                                class="img-fluid" alt="">
                        </a>
                    </div> -->
                    </div>
                </div>
                <div class="col-auto d-none d-lg-block">
                    <ul class="user_action fs-18">
                        <?php if (function_exists('dezon_is_logged_in') && dezon_is_logged_in()): ?>
                            <?php $user = dezon_get_user(); ?>
                            <li>
                                <a href="#" class="fw-bold">
                                    Chào, <?php echo esc_html($user['full_name'] ?? $user['email']); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url(home_url('?dezon_logout=1')); ?>" class="fw-bold">
                                    Đăng xuất
                                </a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="<?php echo esc_url(home_url('?trigger_sso=1')); ?>" class="fw-bold">Đăng nhập</a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url(home_url('?trigger_sso=1')); ?>" class="fw-bold">Đăng ký</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </header>