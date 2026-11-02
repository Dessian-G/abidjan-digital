<?php
/**
 * Vue publique : utilisateur WordPress connecté sans fiche membre.
 *
 * @package FamilleWE
 * @var array   $s    Réglages.
 * @var WP_User $user Utilisateur.
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="fwe-card fwe-centered" data-testid="fwe-join">
	<span class="fwe-stat__icon fwe-stat__icon--primary fwe-center-icon"><?php echo fwe_icon( 'users', 26 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
	<h2 class="fwe-h2">
		<?php
		/* translators: %s: nom de l'association. */
		printf( esc_html__( 'Rejoindre la %s', 'famille-we' ), esc_html( $s['association_name'] ) );
		?>
	</h2>
	<p class="fwe-muted">
		<?php
		/* translators: %s: e-mail. */
		printf( esc_html__( 'Votre compte (%s) n’est pas encore rattaché à une fiche membre. Envoyez votre demande au bureau :', 'famille-we' ), esc_html( $user->user_email ) );
		?>
	</p>
	<form class="fwe-form fwe-form--narrow" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-testid="join-form">
		<input type="hidden" name="action" value="fwe_join">
		<?php wp_nonce_field( 'fwe_join' ); ?>
		<label class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Téléphone', 'famille-we' ); ?></span>
			<input class="fwe-input" type="tel" name="phone" data-testid="join-phone">
		</label>
		<div class="fwe-form-grid">
			<label class="fwe-field">
				<span class="fwe-label"><?php esc_html_e( 'Ville', 'famille-we' ); ?></span>
				<input class="fwe-input" type="text" name="city" data-testid="join-city">
			</label>
			<label class="fwe-field">
				<span class="fwe-label"><?php esc_html_e( 'État', 'famille-we' ); ?></span>
				<select class="fwe-input" name="state" data-testid="join-state">
					<?php foreach ( fwe_labels( 'state' ) as $fwe_key => $fwe_label ) : ?>
						<option value="<?php echo esc_attr( $fwe_key ); ?>"><?php echo esc_html( $fwe_label ); ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</div>
		<button type="submit" class="fwe-btn fwe-btn--primary fwe-btn--block" data-testid="join-submit"><?php esc_html_e( 'Envoyer ma demande', 'famille-we' ); ?></button>
	</form>
</section>
