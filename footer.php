
<?php $footer = get_field('footer','option'); ?>
<footer id="footer_site">
        <div class="foot_content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4">
                        <a href="" class="logo_ft">
                            <img src="<?php echo $footer['logo'] ?? ''; ?>" class="img-fluid" alt="">
                        </a>
                        <div class="slogan fs-16">
                           <?php echo $footer['slogan'] ?? ''; ?>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="row">
                            <?php 
                            render_footer_menu_column( 'footer_menu_col1' );
                            render_footer_menu_column( 'footer_menu_col2' );
                            render_footer_menu_column( 'footer_menu_col3' );
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="foot_end">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-auto">
                        <div class="copyright fs-16">
                           <?php echo "© " . date('Y') ." " . $footer['end']['copyright'] ?? ''; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <ul class="social">
                            <?php foreach($footer['end']['social'] ?? '' as $z): ?>
                            <li><a href="<?php echo $z['link'] ?? ''; ?>"><img src="<?php echo $z['icon'] ?? ''; ?>" alt=""></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <?php //$zalo = get_field('zalo','option'); ?>
    <!-- <a href="https://zalo.me/<?php// echo $zalo['phone'] ?? ''; ?>" target="_blank" rel="nofollow" class="btn-zalo-noi">
    <img src="https://upload.wikimedia.org/wikipedia/commons/9/91/Icon_of_Zalo.svg" alt="Chat Zalo">
    </a> -->

    <?php wp_footer(); ?>
    <script type="text/javascript">
    var myLocalThemeParams = {
        defaultImage: "<?php echo get_template_directory_uri(); ?>/assets/images/cate1.jpg",
        iconPrev: "<?php echo get_template_directory_uri(); ?>/assets/images/first.svg",
        iconNext: "<?php echo get_template_directory_uri(); ?>/assets/images/last.svg"
    };
</script>
</body>

</html>