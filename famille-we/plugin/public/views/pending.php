<?php
/**
 * Vue publique : adhésion en attente ou membre inactif.
 *
 * @package FamilleWE
 * @var array  $s      Réglages.
 * @var object $member Membre.
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="fwe-card fwe-centered" data-testid="fwe-pending">
	<span class="fwe-stat__icon fwe-stat__icon--secondary fwe-center-icon"><?php echo fwe_icon( 'clock', 26 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
	<?php if ( 'en_attente' === $member->status ) : ?>
		<h2 class="fwe-h2">
			<?php
			/* translators: %s: prénom. */
			printf( esc_html__( 'Merci %s, votre demande est bien reçue', 'famille-we' ), esc_html( $member->first_name ) );
			?>
		</h2>
		<p class="fwe-muted"><?php esc_html_e( 'Le bureau va valider votre adhésion. Vous aurez ensuite accès à vos cotisations, aux événements et aux actions de solidarité.', 'famille-we' ); ?></p>
	<?php else : ?>
		<h2 class="fwe-h2"><?php esc_html_e( 'Votre adhésion est inactive', 'famille-we' ); ?></h2>
		<p class="fwe-muted"><?php esc_html_e( 'Contactez le bureau pour réactiver votre adhésion.', 'famille-we' ); ?></p>
	<?php endif; ?>
	<a class="fwe-btn fwe-btn--ghost" href="<?php echo esc_url( wp_logout_url( fwe_espace_url() ) ); ?>" data-testid="logout-link"><?php echo fwe_icon( 'logout', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Se déconnecter', 'famille-we' ); ?></a>
</section>
