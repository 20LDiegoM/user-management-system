<?php

/**
 * Custom User Roles
 *
 * This file defines custom roles for the Cool Kids Network.
 * It adds three user roles with different levels of access:
 * - "Cool Kid": Basic access with read permissions.
 * - "Cooler Kid": Can view other users' character details (excluding email and role).
 * - "Coolest Kid": Full access to other users' character details, including email and role.
 *
 * @package UserManagementSystem
 * @since 1.0.0
 */

/**
 * Register custom user roles.
 *
 * This function adds three roles: "Cool Kid", "Cooler Kid", and "Coolest Kid".
 * Each role has specific capabilities, allowing different levels of access.
 *
 * @since 1.0.0
 */
function ck_register_custom_roles()
{
    // "Cool Kid" role (Basic user with read access)
    add_role(
        'cool_kid',
        __('Cool Kid', 'cool-kids-network'),
        [
            'read' => true, // Allows reading content
        ]
    );

    // "Cooler Kid" role (Can view other users' character names and countries)
    add_role(
        'cooler_kid',
        __('Cooler Kid', 'cool-kids-network'),
        [
            'read' => true,
            'view_other_characters' => true, // Custom capability
        ]
    );

    // "Coolest Kid" role (Can view all user details, including email and role)
    add_role(
        'coolest_kid',
        __('Coolest Kid', 'cool-kids-network'),
        [
            'read' => true,
            'view_other_characters' => true, // Custom capability
            'view_other_emails_roles' => true, // Custom capability
        ]
    );
}
add_action('init', 'ck_register_custom_roles');

/**
 * Remove custom user roles.
 *
 * This function removes the custom roles when the theme or plugin is deactivated.
 * It ensures that no orphaned roles remain in the system.
 *
 * @since 1.0.0
 */
function ck_remove_custom_roles()
{
    remove_role('cool_kid');
    remove_role('cooler_kid');
    remove_role('coolest_kid');
}

// Ensure the function runs when the theme/plugin is deactivated
if (function_exists('register_deactivation_hook')) {
    register_deactivation_hook(__FILE__, 'ck_remove_custom_roles');
}
