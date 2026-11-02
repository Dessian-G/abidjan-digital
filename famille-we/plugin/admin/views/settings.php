<?php
/**
 * Vue : réglages.
 *
 * @package FamilleWE
 * @var array     $s     Réglages.
 * @var WP_Post[] $pages Pages publiées.
 */

defined( 'ABSPATH' ) || exit;
?>
<header class="fwe-page-head">
	<div>
		<p class="fwe-label"><?php esc_html_e( 'Configuration', 'famille-we' ); ?></p>
		<h1 class="fwe-h1"><?php esc_html_e( 'Réglages', 'famille-we' ); ?></h1>
		<p class="fwe-sub">
			<?php esc_html_e( 'Espace membre :', 'famille-we' ); ?>
			<a href="<?php echo esc_url( fwe_espace_url() ); ?>" target="_blank" rel="noopener" data-testid="settings-espace-link"><?php echo esc_html( fwe_espace_url() ); ?></a>
		</p>
	</div>
</header>

<form class="fwe-form" method="post" action="<?php echo esc_url( FWE_Admin::post_url() ); ?>" data-testid="settings-form">
	<?php FWE_Admin::form_fields( 'save_settings' ); ?>
	<div class="fwe-grid fwe-grid--2">
		<section class="fwe-card fwe-form">
			<h2 class="fwe-h3"><?php esc_html_e( 'Association', 'famille-we' ); ?></h2>
			<label class="fwe-field">
				<span class="fwe-label"><?php esc_html_e( 'Nom', 'famille-we' ); ?></span>
				<input class="fwe-input" type="text" name="association_name" value="<?php echo esc_attr( $s['association_name'] ); ?>" data-testid="setting-name">
			</label>
			<label class="fwe-field">
				<span class="fwe-label"><?php esc_html_e( 'Région', 'famille-we' ); ?></span>
				<input class="fwe-input" type="text" name="region" value="<?php echo esc_attr( $s['region'] ); ?>" data-testid="setting-region">
			</label>
			<label class="fwe-field">
				<span class="fwe-label"><?php esc_html_e( 'Page de l’espace membre', 'famille-we' ); ?></span>
				<select class="fwe-input" name="page_id" data-testid="setting-page">
					<?php foreach ( $pages as $fwe_page ) : ?>
						<option value="<?php echo esc_attr( $fwe_page->ID ); ?>" <?php selected( (int) $s['page_id'], (int) $fwe_page->ID ); ?>><?php echo esc_html( $fwe_page->post_title ); ?></option>
					<?php endforeach; ?>
				</select>
				<span class="fwe-hint"><?php esc_html_e( 'Cette page doit contenir le shortcode [famille_we_espace].', 'famille-we' ); ?></span>
			</label>
			<label class="fwe-check">
				<input type="checkbox" name="allow_registration" value="1" <?php checked( (bool) $s['allow_registration'] ); ?> data-testid="setting-registration">
				<span><?php esc_html_e( 'Autoriser l’inscription en ligne (validation par le bureau, sauf e-mail déjà enregistré par le bureau)', 'famille-we' ); ?></span>
			</label>
		</section>

		<section class="fwe-card fwe-form">
			<h2 class="fwe-h3"><?php esc_html_e( 'Cotisations', 'famille-we' ); ?></h2>
			<div class="fwe-form-grid">
				<label class="fwe-field">
					<span class="fwe-label"><?php esc_html_e( 'Montant mensuel ($)', 'famille-we' ); ?></span>
					<input class="fwe-input" type="text" inputmode="decimal" name="monthly_amount" value="<?php echo esc_attr( $s['monthly_amount'] ); ?>" data-testid="setting-amount">
				</label>
				<label class="fwe-field">
					<span class="fwe-label"><?php esc_html_e( 'Alerte au-delà de (mois)', 'famille-we' ); ?></span>
					<input class="fwe-input" type="number" min="1" max="24" name="late_threshold" value="<?php echo esc_attr( $s['late_threshold'] ); ?>" data-testid="setting-threshold">
				</label>
			</div>
			<label class="fwe-field">
				<span class="fwe-label"><?php esc_html_e( 'Début du suivi des cotisations', 'famille-we' ); ?></span>
				<input class="fwe-input" type="month" name="start_period" value="<?php echo esc_attr( $s['start_period'] ); ?>" data-testid="setting-start">
				<span class="fwe-hint"><?php esc_html_e( 'Aucun mois antérieur n’est réclamé, même aux membres plus anciens.', 'famille-we' ); ?></span>
			</label>
			<label class="fwe-check">
				<input type="checkbox" name="auto_notify" value="1" <?php checked( (bool) $s['auto_notify'] ); ?> data-testid="setting-auto-notify">
				<span><?php esc_html_e( 'Alerter automatiquement les membres qui dépassent le seuil (vérification quotidienne)', 'famille-we' ); ?></span>
			</label>
		</section>

		<section class="fwe-card fwe-form">
			<h2 class="fwe-h3"><?php esc_html_e( 'Paiement Zelle', 'famille-we' ); ?></h2>
			<label class="fwe-field">
				<span class="fwe-label"><?php esc_html_e( 'Trésorier / bénéficiaire Zelle', 'famille-we' ); ?></span>
				<input class="fwe-input" type="text" name="treasurer_name" value="<?php echo esc_attr( $s['treasurer_name'] ); ?>" data-testid="setting-treasurer">
			</label>
			<label class="fwe-field">
				<span class="fwe-label"><?php esc_html_e( 'E-mail ou téléphone Zelle', 'famille-we' ); ?></span>
				<input class="fwe-input" type="text" name="zelle_contact" value="<?php echo esc_attr( $s['zelle_contact'] ); ?>" data-testid="setting-zelle">
			</label>
			<label class="fwe-field">
				<span class="fwe-label"><?php esc_html_e( 'Instructions affichées aux membres', 'famille-we' ); ?></span>
				<textarea class="fwe-input" name="zelle_note" rows="3" placeholder="<?php esc_attr_e( 'Indiquez votre nom et le mois dans le mémo Zelle.', 'famille-we' ); ?>" data-testid="setting-zelle-note"><?php echo esc_textarea( $s['zelle_note'] ); ?></textarea>
			</label>
		</section>

		<section class="fwe-card fwe-form">
			<h2 class="fwe-h3"><?php esc_html_e( 'E-mail, SMS & WhatsApp', 'famille-we' ); ?></h2>
			<label class="fwe-check">
				<input type="checkbox" name="notify_email" value="1" <?php checked( (bool) $s['notify_email'] ); ?> data-testid="setting-email">
				<span><?php esc_html_e( 'Envoyer aussi les notifications par e-mail', 'famille-we' ); ?></span>
			</label>
			<p class="fwe-hint"><?php esc_html_e( 'SMS et WhatsApp passent par Twilio (compte payant). Sans configuration, ces envois sont journalisés « Non configuré ».', 'famille-we' ); ?></p>
			<div class="fwe-form-grid">
				<label class="fwe-field">
					<span class="fwe-label">Twilio Account SID</span>
					<input class="fwe-input" type="text" name="twilio_sid" value="<?php echo esc_attr( $s['twilio_sid'] ); ?>" autocomplete="off" data-testid="setting-twilio-sid">
				</label>
				<label class="fwe-field">
					<span class="fwe-label">Twilio Auth Token</span>
					<input class="fwe-input" type="password" name="twilio_token" value="" autocomplete="new-password" placeholder="<?php echo $s['twilio_token'] ? esc_attr__( '•••••• (enregistré)', 'famille-we' ) : ''; ?>" data-testid="setting-twilio-token">
				</label>
				<label class="fwe-field">
					<span class="fwe-label"><?php esc_html_e( 'Numéro SMS Twilio', 'famille-we' ); ?></span>
					<input class="fwe-input" type="text" name="twilio_sms_from" value="<?php echo esc_attr( $s['twilio_sms_from'] ); ?>" placeholder="+12145550100" data-testid="setting-twilio-sms">
				</label>
				<label class="fwe-field">
					<span class="fwe-label"><?php esc_html_e( 'Numéro WhatsApp Twilio', 'famille-we' ); ?></span>
					<input class="fwe-input" type="text" name="twilio_whatsapp_from" value="<?php echo esc_attr( $s['twilio_whatsapp_from'] ); ?>" placeholder="+14155238886" data-testid="setting-twilio-whatsapp">
				</label>
			</div>
			<label class="fwe-check">
				<input type="checkbox" name="enable_sms" value="1" <?php checked( (bool) $s['enable_sms'] ); ?> data-testid="setting-enable-sms">
				<span><?php esc_html_e( 'Activer les SMS', 'famille-we' ); ?></span>
			</label>
			<label class="fwe-check">
				<input type="checkbox" name="enable_whatsapp" value="1" <?php checked( (bool) $s['enable_whatsapp'] ); ?> data-testid="setting-enable-whatsapp">
				<span><?php esc_html_e( 'Activer WhatsApp', 'famille-we' ); ?></span>
			</label>
		</section>
	</div>

	<section class="fwe-card fwe-form">
		<label class="fwe-check">
			<input type="checkbox" name="delete_on_uninstall" value="1" <?php checked( (bool) $s['delete_on_uninstall'] ); ?> data-testid="setting-delete">
			<span><?php esc_html_e( 'Supprimer toutes les données (membres, cotisations…) si le plugin est désinstallé', 'famille-we' ); ?></span>
		</label>
		<div class="fwe-form-actions">
			<button type="submit" class="fwe-btn fwe-btn--primary" data-testid="settings-save"><?php esc_html_e( 'Enregistrer les réglages', 'famille-we' ); ?></button>
		</div>
	</section>
</form>
