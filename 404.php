<?php
/**
 * 404 Error Page Template
 *
 * This template is displayed when a requested page is not found.
 *
 * @package UserManagementSystem
 * @since 1.0.0
 */

get_header(); ?>

<main id="main-content" class="error-404">
	<div class="container">
		<h1><?php esc_html_e( 'Oops! Page Not Found', 'cool-kids-network' ); ?></h1>
		<p><?php esc_html_e( 'It looks like nothing was found at this location.', 'cool-kids-network' ); ?></p>

		<div class="error-image">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/404.png' ); ?>" alt="404 Not Found">
		</div>

		<div class="error-actions">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
				<?php esc_html_e( 'Go to Homepage', 'cool-kids-network' ); ?>
			</a>
		</div>
	</div>
</main>

<?php get_footer(); ?>
