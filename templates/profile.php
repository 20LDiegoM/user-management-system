<?php

/**
 * Template Name: Profile
 * Description: User profile page for the Cool Kids Network.
 *
 * This template displays the profile information of the logged-in user.
 * It also provides role-based access to other users' data based on the user's role.
 *
 * @package UserManagementSystem
 * @since 1.0.0
 */

get_header();

// Redirect non-logged-in users to the login page
if (!is_user_logged_in()) {
    wp_redirect(home_url(''));
    exit;
}

// Get current user information
$current_user = wp_get_current_user();

/**
 * Retrieve character data for the current user.
 *
 * User metadata is stored and fetched using WordPress's built-in functions.
 */
$character_data = [
    'First Name' => get_user_meta($current_user->ID, 'first_name', true),
    'Last Name'  => get_user_meta($current_user->ID, 'last_name', true),
    'Country'    => get_user_meta($current_user->ID, 'country', true),
    'Email'      => $current_user->user_email,
    'Role'       => ucfirst(implode(', ', $current_user->roles)),
];
?>

<main id="main-content">
    <!-- Profile Information Section -->
    <section class="profile-information">
        <h1><?php esc_html_e('Profile Information', 'cool-kids-network'); ?></h1>
        <ul>
            <?php foreach ($character_data as $key => $value) : ?>
                <li><strong><?php echo esc_html($key); ?>:</strong> <?php echo esc_html($value); ?></li>
            <?php endforeach; ?>
        </ul>
        <a href="<?php echo esc_url(wp_logout_url(home_url(''))); ?>" class="btn btn-secondary">
            <?php esc_html_e('Log Out', 'cool-kids-network'); ?>
        </a>
    </section>

    <!-- Role-Based Information Section -->
    <section class="role-based-information">
        <h2><?php esc_html_e('Users Accessible by Your Role', 'cool-kids-network'); ?></h2>

        <?php
        // Fetch users with specified roles
        $args = [
            'role__in' => ['cool_kid', 'cooler_kid', 'coolest_kid'],
            'fields'   => 'all',
        ];
        $users = get_users($args);
        ?>

        <?php if (current_user_can('view_other_characters')) : ?>
            <table>
                <thead>
                    <tr>
                        <th><?php esc_html_e('Name', 'cool-kids-network'); ?></th>
                        <th><?php esc_html_e('Country', 'cool-kids-network'); ?></th>
                        <?php if (current_user_can('view_other_emails_roles')) : ?>
                            <th><?php esc_html_e('Email', 'cool-kids-network'); ?></th>
                            <th><?php esc_html_e('Role', 'cool-kids-network'); ?></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <td>
                                <?php
                                echo esc_html(get_user_meta($user->ID, 'first_name', true)) . ' ' .
                                    esc_html(get_user_meta($user->ID, 'last_name', true));
                                ?>
                            </td>
                            <td>
                                <?php echo esc_html(get_user_meta($user->ID, 'country', true)); ?>
                            </td>
                            <?php if (current_user_can('view_other_emails_roles')) : ?>
                                <td><?php echo esc_html($user->user_email); ?></td>
                                <td><?php echo esc_html(implode(', ', $user->roles)); ?></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p><?php esc_html_e('You do not have permission to access other users\' information.', 'cool-kids-network'); ?></p>
        <?php endif; ?>
    </section>
</main>

<?php get_footer(); ?>