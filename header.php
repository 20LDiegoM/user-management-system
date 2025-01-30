<?php
/**
 * Header Template
 *
 * This file is included in all pages using `get_header();`
 *
 * @package UserManagementSystem
 * @since 1.0.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <header class="site-header">
        <div class="container">
            <a href="<?php echo esc_url(home_url('/')); ?>"><img class="site-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/group.png'); ?>" alt="Site Logo"></a>
        
            <?php if(is_user_logged_in()):?>
                <div class="logged-in-btns">
                    <a href="<?php echo esc_url(home_url('/profile/')); ?>" class="btn btn-primary">
                        <?php esc_html_e('Profile', 'cool-kids-network'); ?>
                    </a>
                    <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="btn btn-secondary">
                        <?php esc_html_e('Log Out', 'cool-kids-network'); ?>
                    </a>
                </div>
            <?php endif;?>
        </div>
    </header>