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
                <span></span><span></span><span></span><span></span><span></span><span></span>
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
                    'walker'         => new Dezon_Mobile_Walker(),             
                ));
            }
            ?>
        </ul>
        
        <?php if (function_exists('dezon_is_logged_in') && dezon_is_logged_in()): ?>
            <?php $user = dezon_get_user(); ?>
            <div class="btn-action my-3"> 
                <a class="btn btn-black" href="https://account.dezon.vn/ho-so/chinh-sua">Chào, <?php echo esc_html($user['full_name'] ?? $user['email']); ?></a>
            </div>
            <div class="btn-action"> 
                <a class="btn btn-black" href="<?php echo esc_url(home_url('?dezon_logout=1')); ?>">Đăng xuất</a>
            </div>
        <?php else: ?>
            <div class="btn-action my-3"> 
                <a class="btn btn-black" href="<?php echo esc_url(home_url('?trigger_sso=1')); ?>">Đăng nhập</a>
            </div>
            <div class="btn-action"> 
                <a class="btn btn-black" href="<?php echo esc_url(home_url('?trigger_sso_register=1')); ?>">Đăng ký</a>
            </div>
        <?php endif; ?>
        <div class="btn-action mt-3">
            <a href="https://dezon.vn/planning" class="btn btn-black">Design & Build Planning</a>
        </div>
    </div>
    
    <div class="overlay_menu"></div>
    
    <header id="header_site" class="sticky-top">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-auto">
                    <a href="<?php echo home_url('/') ?? ''; ?>" class="logo_site">
                        <img src="<?php echo $header['logo'] ?? ''; ?>" class="img-fluid" alt="">
                    </a>
                </div>
                <div class="col-auto d-block d-lg-none">
                    <div class="d-flex d-lg-none justify-content-end align-items-center">
                        <a href="" class="hamburger_btn d-lg-none">
                            <div class="hamburger-icon">
                                <span></span><span></span><span></span><span></span><span></span><span></span>
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
                                    'walker'         => new Dezon_Mega_Menu_Walker(), 
                                ));
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="col-auto d-none d-lg-block">
                    <ul class="user_action fs-18">
                        <?php if (function_exists('dezon_is_logged_in') && dezon_is_logged_in()): ?>
                            <?php $user = dezon_get_user(); ?>
                            <li class="user-profile-wrapper position-relative">
                                <a href="javascript:void(0);" class="user-trigger d-flex align-items-center gap-3 py-2" id="avatarTrigger">
                                    <div class="user-avatar-circle dark-theme">
                                        <img src="<?php echo get_template_directory_uri() . '/assets/images/logo-dezon-black.jpeg'; ?>" alt="User"> 
                                    </div>
                                    <div class="user-info-block text-start">
                                        <div class="fw-bold text-dark"><?php echo esc_html($user['full_name']) ?></div>
                                    </div>
                                </a>

                                <div class="custom-user-dropdown left-aligned" id="userDropdown">
                                    <div class="dropdown-header-info d-flex align-items-center gap-3">
                                        <div class="user-avatar-square dark-theme">
                                            <img src="<?php echo get_template_directory_uri() . '/assets/images/logo-dezon-black.jpeg'; ?>" alt="User">
                                        </div>
                                        <div class="user-text text-start">
                                            <div class="fw-bold text-dark fs-16"><?php echo esc_html($user['full_name']) ?></div>
                                            <div class="text-muted fs-14"><?php echo esc_html($user['email']) ?></div>
                                        </div>
                                    </div>

                                    <div class="dropdown-divider"></div>

                                    <ul class="dropdown-list list-unstyled mb-0">
                                        <li>
                                            <a href="#" class="d-flex">
                                                <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg></span>
                                                Chia sẻ dự án
                                            </a>
                                        </li>
                                        <div class="dropdown-divider"></div>
                                        <li>
                                            <a href="#" class="d-flex">
                                                <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg></span>
                                                Nâng cấp lên Pro
                                            </a>
                                        </li>
                                        <div class="dropdown-divider"></div>
                                        <li>
                                            <a href="https://account.dezon.vn/ho-so/chinh-sua" class="d-flex">
                                                <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></span>
                                                Quản trị tài khoản
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="d-flex">
                                                <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg></span>
                                                Thông báo
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="dropdown-divider"></div>

                                    <div class="dropdown-footer">
                                        <a href="<?php echo esc_url(home_url('?dezon_logout=1')); ?>" class="item-logout d-flex">
                                            <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></span> 
                                            Đăng xuất
                                        </a>
                                    </div>
                                </div>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="<?php echo esc_url(home_url('?trigger_sso=1')); ?>" class="fw-bold">Đăng nhập</a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url(home_url('?trigger_sso_register=1')); ?>" class="fw-bold">Đăng ký</a>
                            </li>
                        <?php endif; ?>
                        <li class="my-auto"><a href="https://dezon.vn/planning" class="fw-semibold">Design & Build Planning</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>