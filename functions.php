<?php

/**
 * Theme Functions
 *
 * This file initializes the theme by including necessary files 
 * for user role management and API endpoints.
 *
 * @package UserManagementSystem
 * @since 1.0.0
 */

// Prevent direct file access for security reasons
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Include user roles configuration.
 *
 * This file defines custom user roles and their capabilities.
 * It ensures the correct role hierarchy and permissions within the system.
 */
require_once get_template_directory() . '/inc/roles.php';

/**
 * Include custom API endpoints.
 *
 * This file registers custom REST API endpoints for managing users.
 * It provides functionalities such as role assignment and authentication.
 */
require_once get_template_directory() . '/inc/endpoints.php';

/**
 * Theme setup function.
 *
 * This function initializes theme features such as support for title tags, 
 * post thumbnails, and menu locations.
 *
 * @since 1.0.0
 */
function ums_theme_setup()
{
    // Enable support for site title in the header
    add_theme_support('title-tag');

    // Enable support for featured images
    add_theme_support('post-thumbnails');

    // Register navigation menus
    register_nav_menus([
        'primary' => __('Primary Menu', 'user-management-system'),
    ]);
}
add_action('after_setup_theme', 'ums_theme_setup');

/**
 * Enqueue theme styles and scripts.
 *
 * This function loads the theme's styles and scripts into the frontend.
 *
 * @since 1.0.0
 */
function ums_enqueue_assets()
{
    // Load the main stylesheet
    wp_enqueue_style('ums-style', get_stylesheet_uri(), [], '1.0.0', 'all');
}
add_action('wp_enqueue_scripts', 'ums_enqueue_assets');
