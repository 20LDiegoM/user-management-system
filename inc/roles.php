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
function ck_register_custom_roles_and_capabilities() {
	// Add "Cool Kid" role.
	add_role(
		'cool_kid',
		__( 'Cool Kid', 'cool-kids-network' ),
		array(
			'read' => true, // Allows reading content.
		)
	);

	// Add "Cooler Kid" role.
	add_role(
		'cooler_kid',
		__( 'Cooler Kid', 'cool-kids-network' ),
		array(
			'read'                  => true,
			'view_other_characters' => true, // Custom capability.
		)
	);

	// Add "Coolest Kid" role.
	add_role(
		'coolest_kid',
		__( 'Coolest Kid', 'cool-kids-network' ),
		array(
			'read'                    => true,
			'view_other_characters'   => true, // Custom capability.
			'view_other_emails_roles' => true, // Custom capability.
		)
	);

	// Add custom capabilities to the "administrator" role.
	$administrator = get_role( 'administrator' );
	if ( $administrator ) {
		$administrator->add_cap( 'view_other_characters' );
		$administrator->add_cap( 'view_other_emails_roles' );
	}
}
add_action( 'init', 'ck_register_custom_roles_and_capabilities' );

/**
 * Remove custom user roles and capabilities.
 *
 * This function removes the custom roles and capabilities when the theme
 * or plugin is deactivated.
 *
 * @since 1.0.0
 */
function ck_remove_custom_roles_and_capabilities() {
	// Remove "Cool Kid" role.
	remove_role( 'cool_kid' );

	// Remove "Cooler Kid" role.
	remove_role( 'cooler_kid' );

	// Remove "Coolest Kid" role.
	remove_role( 'coolest_kid' );

	// Remove custom capabilities from "administrator" role.
	$administrator = get_role( 'administrator' );
	if ( $administrator ) {
		$administrator->remove_cap( 'view_other_characters' );
		$administrator->remove_cap( 'view_other_emails_roles' );
	}
}
if ( function_exists( 'register_deactivation_hook' ) ) {
	register_deactivation_hook( __FILE__, 'ck_remove_custom_roles_and_capabilities' );
}
