<?php
/**
 * Template Name: Homepage
 * Description: Main page template for the "Cool Kids Network" website.
 *
 * This template handles both user registration and login functionalities.
 * New users can sign up with an email and are automatically assigned the "Cool Kid" role.
 * User credentials are generated automatically for demonstration purposes.
 *
 * @package CoolKidsNetwork
 * @since 1.0.0
 */

// Initialize messages for user feedback.
$success_message = '';
$error_message   = '';

/**
 * Handles user registration.
 *
 * When the form is submitted with a valid email, a new WordPress user is created.
 * The user receives a default password, and a character profile is generated using the Random User API.
 */
if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['action'] ) && 'signup' === $_POST['action'] ) {
	if ( isset( $_POST['sign_up_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sign_up_nonce'] ) ), 'sign_up_action' ) ) {
		if ( isset( $_POST['email'] ) ) {
			$email = sanitize_email( wp_unslash( $_POST['email'] ) );
		}

		if ( is_email( $email ) ) {
			if ( ! email_exists( $email ) ) {
				// TODO: Replace generic password with a randomly generated one.
				$generic_password = '1q2w3e';
				$user_id          = wp_create_user( $email, $generic_password, $email );

				if ( ! is_wp_error( $user_id ) ) {
					// Assign "Cool Kid" role to the new user.
					$user = get_user_by( 'id', $user_id );
					$user->set_role( 'cool_kid' );

					// Fetch character details from Random User API.
					$response = wp_remote_get( 'https://randomuser.me/api/?results=1' );
					if ( ! is_wp_error( $response ) ) {
						$data = json_decode( wp_remote_retrieve_body( $response ), true );
						if ( isset( $data['results'][0] ) ) {
							$character = $data['results'][0];
							update_user_meta( $user_id, 'first_name', sanitize_text_field( $character['name']['first'] ) );
							update_user_meta( $user_id, 'last_name', sanitize_text_field( $character['name']['last'] ) );
							update_user_meta( $user_id, 'country', sanitize_text_field( $character['location']['country'] ) );
						}
					}

					$success_message = esc_html__( 'Account created successfully. Your password is: ', 'cool-kids-network' ) . $generic_password;
				} else {
					$error_message = esc_html__( 'An error occurred while creating your account. Please try again.', 'cool-kids-network' );
				}
			} else {
				$error_message = esc_html__( 'This email is already registered. Please log in instead.', 'cool-kids-network' );
			}
		} else {
			$error_message = esc_html__( 'Please provide a valid email address.', 'cool-kids-network' );
		}
	}
}

/**
 * Handles user login.
 *
 * If the provided email and password match a registered user, the user is logged in
 * and redirected to their profile page.
 */
if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['action'] ) && 'login' === $_POST['action'] ) {
	if ( isset( $_POST['email'] ) ) {
		$email = sanitize_email( wp_unslash( $_POST['email'] ) );
	}

	if ( isset( $_POST['password'] ) ) {
		$password = sanitize_text_field( wp_unslash( $_POST['password'] ) );
	}

	$user = wp_authenticate( $email, $password );
	if ( ! is_wp_error( $user ) ) {
		wp_set_current_user( $user->ID );
		wp_set_auth_cookie( $user->ID );
		wp_safe_redirect( home_url( '/profile/' ) );
		exit;
	} else {
		$error_message = esc_html__( 'Login failed. Please check your email and password.', 'cool-kids-network' );
	}
}
get_header();
?>

<main id="main-content">
	<section class="homepage-content">
		<h1 class="main-title"><?php esc_html_e( 'Welcome to the Cool Kids Network', 'cool-kids-network' ); ?></h1>

		<?php if ( is_user_logged_in() ) : ?>
			<p>
				<?php
				printf(
					/* translators: %s is the name of the user */
					esc_html__( 'Hello, %s! Explore your character\'s details in your profile.', 'cool-kids-network' ),
					esc_html( wp_get_current_user()->first_name )
				);
				?>
			</p>
		<?php else : ?>

			<!-- Display success or error messages -->
			<?php if ( ! empty( $success_message ) ) : ?>
				<p class="alert success"><?php echo esc_html( $success_message ); ?></p>
			<?php elseif ( ! empty( $error_message ) ) : ?>
				<p class="alert error"><?php echo esc_html( $error_message ); ?></p>
			<?php endif; ?>

			<!-- Buttons to toggle between forms -->
			<div class="auth-buttons">
				<button id="show-signup" class="btn btn-primary"><?php esc_html_e( 'Sign Up', 'cool-kids-network' ); ?></button>
				<button id="show-login" class="btn btn-secondary"><?php esc_html_e( 'Log In', 'cool-kids-network' ); ?></button>
			</div>

			<!-- Forms Container -->
			<div class="auth-forms">
				<!-- Sign-Up Form -->
				<div id="signup-form" class="form-container">
					<h2><?php esc_html_e( 'Sign Up', 'cool-kids-network' ); ?></h2>
					<form method="POST">
						<?php wp_nonce_field( 'sign_up_action', 'sign_up_nonce' ); ?>
						<input type="hidden" name="action" value="signup">
						<label for="signup-email"><?php esc_html_e( 'Email Address', 'cool-kids-network' ); ?></label>
						<input type="email" id="signup-email" name="email" required>
						<button type="submit" class="btn btn-primary"><?php esc_html_e( 'Sign Up', 'cool-kids-network' ); ?></button>
					</form>
				</div>

				<!-- Login Form -->
				<div id="login-form" class="form-container">
					<h2><?php esc_html_e( 'Login', 'cool-kids-network' ); ?></h2>
					<form method="POST">
						<?php wp_nonce_field( 'login_action', 'login_nonce' ); ?>
						<input type="hidden" name="action" value="login">
						<label for="login-email"><?php esc_html_e( 'Email Address', 'cool-kids-network' ); ?></label>
						<input type="email" id="login-email" name="email" required>
						<label for="login-password"><?php esc_html_e( 'Password', 'cool-kids-network' ); ?></label>
						<input type="password" id="login-password" name="password" placeholder="Default: 1q2w3e" required>
						<button type="submit" class="btn btn-secondary"><?php esc_html_e( 'Log In', 'cool-kids-network' ); ?></button>
					</form>
				</div>
			</div>


		<?php endif; ?>
	</section>
</main>

<?php get_footer(); ?>