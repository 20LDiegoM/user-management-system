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

// Redirect non-logged-in users to the login page.
if ( ! is_user_logged_in() ) {
	wp_safe_redirect( home_url( '' ) );
	exit;
}

// Get current user information.
$current_user_ums = wp_get_current_user();

/**
 * Retrieve character data for the current user.
 *
 * User metadata is stored and fetched using WordPress's built-in functions.
 */
$character_data = array(
	'First Name' => get_user_meta( $current_user_ums->ID, 'first_name', true ),
	'Last Name'  => get_user_meta( $current_user_ums->ID, 'last_name', true ),
	'Country'    => get_user_meta( $current_user_ums->ID, 'country', true ),
	'Email'      => $current_user_ums->user_email,
	'Role'       => ucfirst( implode( ', ', $current_user_ums->roles ) ),
);

get_header();
?>

<main id="main-content">
	<div class="wrap-content">
		<!-- Profile Information Section -->
		<section class="profile-information">
			<h1 class="main-title"><?php esc_html_e( 'Profile Information', 'cool-kids-network' ); ?></h1>
			<ul class="profile-info">
				<?php foreach ( $character_data as $key => $value ) : ?>
					<li><strong><?php echo esc_html( $key ); ?>:</strong> <?php echo esc_html( $value ); ?></li>
				<?php endforeach; ?>
			</ul>
		</section>

		<!-- Role-Based Information Section -->
		<section class="role-based-information">
			<h2 class="main-title"><?php esc_html_e( 'Users Accessible by Your Role', 'cool-kids-network' ); ?></h2>

			<?php
			// Fetch users with specified roles.
			$args                = array(
				'role__in' => array( 'cool_kid', 'cooler_kid', 'coolest_kid' ),
				'fields'   => 'all',
			);
			$users               = get_users( $args );
			$current_user_ums_id = get_current_user_id();
			?>

			<?php // phpcs:ignore WordPress.WP.Capabilities.Unknown ?>
			<?php if ( current_user_can( 'view_other_characters' ) ) : ?>
				<div class="user-cards">
					<?php foreach ( $users as $user ) : ?>
						<div class="user-card <?php echo ( $user->ID === $current_user_ums_id ) ? 'current-user-card' : ''; ?>">
							<h3><?php echo esc_html( get_user_meta( $user->ID, 'first_name', true ) ) . ' ' . esc_html( get_user_meta( $user->ID, 'last_name', true ) ); ?></h3>

							<p><strong><?php esc_html_e( 'Country:', 'cool-kids-network' ); ?></strong>
								<?php echo esc_html( get_user_meta( $user->ID, 'country', true ) ); ?>
							</p>
							<p><strong><?php esc_html_e( 'Role:', 'cool-kids-network' ); ?></strong>
								<?php echo esc_html( implode( ', ', $user->roles ) ); ?>
							</p>
							<?php // phpcs:ignore WordPress.WP.Capabilities.Unknown ?>
							<?php if ( current_user_can( 'view_other_emails_roles' ) ) : ?>
								<p><strong><?php esc_html_e( 'Email:', 'cool-kids-network' ); ?></strong>
									<?php echo esc_html( $user->user_email ); ?>
								</p>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<p><?php esc_html_e( 'You do not have permission to access other users\' information.', 'cool-kids-network' ); ?></p>
			<?php endif; ?>
		</section>
	</div>
</main>

<?php get_footer(); ?>