<?php
/**
 * Vue publique : connexion / inscription.
 *
 * @package FamilleWE
 * @var array  $s   Réglages.
 * @var string $tab login|register.
 */

defined( 'ABSPATH' ) || exit;

$fwe_post = admin_url( 'admin-post.php' );
?>
<section class="fwe-auth" data-testid="fwe-auth">
	<div class="fwe-auth__visual" style="background-image:url('<?php echo esc_url( FWE_Assets::image( 'login' ) ); ?>')">
		<div class="fwe-auth__overlay"></div>
		<div class="fwe-auth__copy">
			<p class="fwe-label fwe-label--light"><?php echo esc_html( $s['region'] ); ?></p>
			<h1 class="fwe-display"><?php echo esc_html( $s['association_name'] ); ?></h1>
			<p class="fwe-auth__lead"><?php esc_html_e( 'Loin du pays, mais jamais seuls. Votre espace pour cotiser, suivre la vie de la famille et se soutenir dans les grands moments.', 'famille-we' ); ?></p>
			<ul class="fwe-auth__points">
				<li><?php echo fwe_icon( 'wallet', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Vos cotisations et paiements Zelle', 'famille-we' ); ?></li>
				<li><?php echo fwe_icon( 'calendar', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'AG, festivals et rencontres', 'famille-we' ); ?></li>
				<li><?php echo fwe_icon( 'heart', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Solidarité : naissances, mariages, décès', 'famille-we' ); ?></li>
			</ul>
		</div>
	</div>

	<div class="fwe-auth__panel">
		<div class="fwe-tabs" role="tablist" data-fwe-tabs>
			<a role="tab" class="fwe-tabs__tab<?php echo 'login' === $tab ? ' is-active' : ''; ?>" href="<?php echo esc_url( remove_query_arg( array( 'tab', 'fwe_err', 'fwe_msg' ) ) ); ?>" data-fwe-tab="login" aria-selected="<?php echo 'login' === $tab ? 'true' : 'false'; ?>" data-testid="tab-login"><?php esc_html_e( 'Connexion', 'famille-we' ); ?></a>
			<a role="tab" class="fwe-tabs__tab<?php echo 'register' === $tab ? ' is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'tab', 'inscription', remove_query_arg( array( 'fwe_err', 'fwe_msg' ) ) ) ); ?>" data-fwe-tab="register" aria-selected="<?php echo 'register' === $tab ? 'true' : 'false'; ?>" data-testid="tab-register"><?php esc_html_e( 'Rejoindre la famille', 'famille-we' ); ?></a>
		</div>

		<div data-fwe-panel="login" role="tabpanel"<?php echo 'login' === $tab ? '' : ' hidden'; ?>>
			<h2 class="fwe-h2"><?php esc_html_e( 'Bon retour parmi nous', 'famille-we' ); ?></h2>
			<p class="fwe-muted"><?php esc_html_e( 'Connectez-vous avec votre e-mail et votre mot de passe.', 'famille-we' ); ?></p>
			<form class="fwe-form" method="post" action="<?php echo esc_url( $fwe_post ); ?>" data-testid="login-form">
				<input type="hidden" name="action" value="fwe_login">
				<?php wp_nonce_field( 'fwe_login' ); ?>
				<label class="fwe-field">
					<span class="fwe-label"><?php esc_html_e( 'E-mail', 'famille-we' ); ?></span>
					<input class="fwe-input" type="text" name="log" autocomplete="username" required data-testid="login-email">
				</label>
				<label class="fwe-field">
					<span class="fwe-label"><?php esc_html_e( 'Mot de passe', 'famille-we' ); ?></span>
					<input class="fwe-input" type="password" name="pwd" autocomplete="current-password" required data-testid="login-password">
				</label>
				<div class="fwe-split">
					<label class="fwe-check"><input type="checkbox" name="rememberme" value="1" data-testid="login-remember"><span><?php esc_html_e( 'Rester connecté', 'famille-we' ); ?></span></label>
					<a class="fwe-link" href="<?php echo esc_url( wp_lostpassword_url( fwe_espace_url() ) ); ?>" data-testid="login-lost-password"><?php esc_html_e( 'Mot de passe oublié ?', 'famille-we' ); ?></a>
				</div>
				<button type="submit" class="fwe-btn fwe-btn--primary fwe-btn--block" data-testid="login-submit-button"><?php esc_html_e( 'Se connecter', 'famille-we' ); ?></button>
			</form>
		</div>

		<div data-fwe-panel="register" role="tabpanel"<?php echo 'register' === $tab ? '' : ' hidden'; ?>>
			<h2 class="fwe-h2"><?php esc_html_e( 'Rejoindre la Famille WÊ', 'famille-we' ); ?></h2>
			<?php if ( ! $s['allow_registration'] ) : ?>
				<p class="fwe-muted"><?php esc_html_e( 'Les inscriptions en ligne sont fermées. Rapprochez-vous d’un membre du bureau pour adhérer.', 'famille-we' ); ?></p>
			<?php else : ?>
				<p class="fwe-muted"><?php esc_html_e( 'Le bureau valide chaque nouvelle adhésion. Si le bureau vous a déjà enregistré, utilisez la même adresse e-mail : votre compte sera actif immédiatement.', 'famille-we' ); ?></p>
				<form class="fwe-form" method="post" action="<?php echo esc_url( $fwe_post ); ?>" data-testid="register-form">
					<input type="hidden" name="action" value="fwe_register">
					<?php wp_nonce_field( 'fwe_register' ); ?>
					<div class="fwe-hp" aria-hidden="true"><label><?php esc_html_e( 'Site web', 'famille-we' ); ?><input type="text" name="fwe_website" tabindex="-1" autocomplete="off"></label></div>
					<div class="fwe-form-grid">
						<label class="fwe-field">
							<span class="fwe-label"><?php esc_html_e( 'Prénom', 'famille-we' ); ?> *</span>
							<input class="fwe-input" type="text" name="first_name" autocomplete="given-name" required data-testid="register-first-name">
						</label>
						<label class="fwe-field">
							<span class="fwe-label"><?php esc_html_e( 'Nom', 'famille-we' ); ?> *</span>
							<input class="fwe-input" type="text" name="last_name" autocomplete="family-name" required data-testid="register-last-name">
						</label>
					</div>
					<label class="fwe-field">
						<span class="fwe-label"><?php esc_html_e( 'E-mail', 'famille-we' ); ?> *</span>
						<input class="fwe-input" type="email" name="email" autocomplete="email" required data-testid="register-email">
					</label>
					<div class="fwe-form-grid">
						<label class="fwe-field">
							<span class="fwe-label"><?php esc_html_e( 'Téléphone', 'famille-we' ); ?></span>
							<input class="fwe-input" type="tel" name="phone" autocomplete="tel" placeholder="(214) 555-0123" data-testid="register-phone">
						</label>
						<label class="fwe-field">
							<span class="fwe-label"><?php esc_html_e( 'Ville', 'famille-we' ); ?></span>
							<input class="fwe-input" type="text" name="city" autocomplete="address-level2" placeholder="Dallas" data-testid="register-city">
						</label>
					</div>
					<fieldset class="fwe-field">
						<legend class="fwe-label"><?php esc_html_e( 'État', 'famille-we' ); ?></legend>
						<div class="fwe-segmented">
							<?php foreach ( fwe_labels( 'state' ) as $fwe_key => $fwe_label ) : ?>
								<label class="fwe-segmented__item"><input type="radio" name="state" value="<?php echo esc_attr( $fwe_key ); ?>" <?php checked( 'TX', $fwe_key ); ?> data-testid="register-state-<?php echo esc_attr( strtolower( $fwe_key ) ); ?>"><span><?php echo esc_html( $fwe_label ); ?></span></label>
							<?php endforeach; ?>
						</div>
					</fieldset>
					<div class="fwe-form-grid">
						<label class="fwe-field">
							<span class="fwe-label"><?php esc_html_e( 'Mot de passe', 'famille-we' ); ?> *</span>
							<input class="fwe-input" type="password" name="pass1" minlength="8" autocomplete="new-password" required data-testid="register-password">
						</label>
						<label class="fwe-field">
							<span class="fwe-label"><?php esc_html_e( 'Confirmation', 'famille-we' ); ?> *</span>
							<input class="fwe-input" type="password" name="pass2" minlength="8" autocomplete="new-password" required data-testid="register-password-confirm">
						</label>
					</div>
					<button type="submit" class="fwe-btn fwe-btn--primary fwe-btn--block" data-testid="register-submit-button"><?php esc_html_e( 'Créer mon compte', 'famille-we' ); ?></button>
				</form>
			<?php endif; ?>
		</div>
	</div>
</section>
