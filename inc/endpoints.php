<?php
/**
 * Registers a REST API endpoint to change user roles in WordPress.
 * 
 * @package UserManagementSystem
 * @since 1.0.0
 */

/**
 * Registers the REST API endpoint `ck/v1/change-role`.
 *
 * This endpoint allows changing the role of a specific user by providing their
 * email or full name and the desired role. It validates the input and checks
 * permissions before making any changes.
 *
 * @return void
 */
function ck_register_change_role_endpoint()
{
    register_rest_route('ck/v1', '/change-role', [
        'methods'             => 'POST',
        'callback'            => 'ck_change_user_role',
        'permission_callback' => 'ck_change_role_permissions',
        'args'                => [
            'email' => [
                'required'    => false,
                'type'        => 'string',
                'description' => 'The email of the user whose role will be changed.',
            ],
            'first_name' => [
                'required'    => false,
                'type'        => 'string',
                'description' => 'The first name of the user.',
            ],
            'last_name' => [
                'required'    => false,
                'type'        => 'string',
                'description' => 'The last name of the user.',
            ],
            'role' => [
                'required'    => true,
                'type'        => 'string',
                'description' => 'The new role to assign to the user.',
                'validate_callback' => function ($param) {
                    // Validates that the role is one of the allowed options
                    return in_array($param, ['cool_kid', 'cooler_kid', 'coolest_kid'], true);
                },
            ],
        ],
    ]);
}
add_action('rest_api_init', 'ck_register_change_role_endpoint');

/**
 * Callback function to change a user's role.
 *
 * Handles the logic for finding the user (by email or full name) and updating
 * their role in WordPress. Returns appropriate error messages if the user is
 * not found or if required parameters are missing.
 *
 * @param WP_REST_Request $request The REST API request object.
 * @return WP_REST_Response|WP_Error A response or error object.
 */
function ck_change_user_role(WP_REST_Request $request)
{
    // Retrieve parameters from the request
    $email      = $request->get_param('email');
    $first_name = $request->get_param('first_name');
    $last_name  = $request->get_param('last_name');
    $new_role   = $request->get_param('role');

    // Validate that at least email or full name is provided
    if (empty($email) && (empty($first_name) || empty($last_name))) {
        return new WP_Error(
            'missing_parameters',
            'Email or full name is required to identify the user.',
            ['status' => 400]
        );
    }

    // Search for the user by email or full name
    if (!empty($email)) {
        $user = get_user_by('email', $email);
    } else {
        $users = get_users([
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key'     => 'first_name',
                    'value'   => $first_name,
                    'compare' => '='
                ],
                [
                    'key'     => 'last_name',
                    'value'   => $last_name,
                    'compare' => '='
                ],
            ],
        ]);
        $user = $users[0] ?? null; // Take the first user found
    }

    // Return error if the user is not found
    if (!$user) {
        return new WP_Error(
            'user_not_found',
            'The specified user was not found.',
            ['status' => 404]
        );
    }

    // Update the user's role
    $user_id = $user->ID;
    $user->set_role($new_role);

    // Return a success response
    return rest_ensure_response([
        'message'  => 'User role updated successfully.',
        'user_id'  => $user_id,
        'new_role' => $new_role,
    ]);
}

/**
 * Permission callback for the `ck/v1/change-role` endpoint.
 *
 * Ensures that the current user has the capability to edit other users.
 *
 * @return bool True if the user has permission, false otherwise.
 */
function ck_change_role_permissions()
{
    return current_user_can('edit_users');
}
