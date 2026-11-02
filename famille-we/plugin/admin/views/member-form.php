<?php
/**
 * Vue : création / modification d'un membre.
 *
 * @package FamilleWE
 * @var array        $s      Réglages.
 * @var object|null  $member Membre (null = création).
 * @var array|null   $dues   Situation de cotisation.
 * @var WP_User|null $user   Compte WordPress rattaché.
 */

defined( 'ABSPATH' ) || exit;

$fwe_m = $member ? $member : (object) array(
	'id'         => 0,
	'first_name' => '',
	'last_name'  => '',
	'email'      => '',
	'phone'      => '',
	'city'       => '',
	'state'      => 'TX',
	'join_date'  => current_time( 'Y-m-d' ),
	'status'     => 'actif',
	'notes'      => '',
	'user_id'    => 0,
);
?>
<header class="fwe-page-head">
	<div>
		<a class="fwe-back" href="<?php echo esc_url( FWE_Admin::url( 'fwe-membres' ) ); ?>"><?php echo fwe_icon( 'left', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Tous les membres', 'famille-we' ); ?></a>
		<h1 class="fwe-h1"><?php echo $member ? esc_html( fwe_member_name( $member ) ) : esc_html__( 'Nouveau membre', 'famille-we' ); ?></h1>
		<?php if ( $member ) : ?>
			<p class="fwe-sub">
				<?php
				/* translators: %s: date. */
				printf( esc_html__( 'Membre depuis le %s', 'famille-we' ), esc_html( fwe_format_date( $member->join_date ) ) );
				?>
			</p>
		<?php endif; ?>
	</div>
	<?php if ( $member && $dues ) : ?>
		<div class="fwe-head-actions">
			<?php echo fwe_dues_badge( $dues, 'member-form-dues' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<a class="fwe-btn fwe-btn--outline" href="<?php echo esc_url( FWE_Admin::url( 'fwe-cotisations', array( 'member_id' => $member->id ) ) . '#fwe-record' ); ?>" data-testid="member-record-payment"><?php echo fwe_icon( 'wallet', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Enregistrer un paiement', 'famille-we' ); ?></a>
		</div>
	<?php endif; ?>
</header>

<div class="fwe-grid fwe-grid--3">
	<form class="fwe-card fwe-span-2 fwe-form" method="post" action="<?php echo esc_url( FWE_Admin::post_url() ); ?>" data-testid="member-form">
		<?php FWE_Admin::form_fields( 'save_member' ); ?>
		<input type="hidden" name="id" value="<?php echo esc_attr( $fwe_m->id ); ?>">

		<h2 class="fwe-h3"><?php esc_html_e( 'Identité & contact', 'famille-we' ); ?></h2>
		<div class="fwe-form-grid">
			<label class="fwe-field">
				<span class="fwe-label"><?php esc_html_e( 'Prénom', 'famille-we' ); ?> *</span>
				<input class="fwe-input" type="text" name="first_name" value="<?php echo esc_attr( $fwe_m->first_name ); ?>" required data-testid="member-first-name">
			</label>
			<label class="fwe-field">
				<span class="fwe-label"><?php esc_html_e( 'Nom', 'famille-we' ); ?> *</span>
				<input class="fwe-input" type="text" name="last_name" value="<?php echo esc_attr( $fwe_m->last_name ); ?>" required data-testid="member-last-name">
			</label>
			<label class="fwe-field">
				<span class="fwe-label"><?php esc_html_e( 'E-mail', 'famille-we' ); ?></span>
				<input class="fwe-input" type="email" name="email" value="<?php echo esc_attr( $fwe_m->email ); ?>" data-testid="member-email">
			</label>
			<label class="fwe-field">
				<span class="fwe-label"><?php esc_html_e( 'Téléphone (SMS / WhatsApp)', 'famille-we' ); ?></span>
				<input class="fwe-input" type="tel" name="phone" value="<?php echo esc_attr( $fwe_m->phone ); ?>" placeholder="(214) 555-0123" data-testid="member-phone">
			</label>
			<label class="fwe-field">
				<span class="fwe-label"><?php esc_html_e( 'Ville', 'famille-we' ); ?></span>
				<input class="fwe-input" type="text" name="city" value="<?php echo esc_attr( $fwe_m->city ); ?>" placeholder="Dallas" data-testid="member-city">
			</label>
			<label class="fwe-field">
				<span class="fwe-label"><?php esc_html_e( 'État', 'famille-we' ); ?></span>
				<select class="fwe-input" name="state" data-testid="member-state">
					<?php foreach ( fwe_labels( 'state' ) as $fwe_key => $fwe_label ) : ?>
						<option value="<?php echo esc_attr( $fwe_key ); ?>" <?php selected( $fwe_m->state, $fwe_key ); ?>><?php echo esc_html( $fwe_label ); ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</div>

		<h2 class="fwe-h3"><?php esc_html_e( 'Adhésion', 'famille-we' ); ?></h2>
		<div class="fwe-form-grid">
			<label class="fwe-field">
				<span class="fwe-label"><?php esc_html_e( 'Date d’adhésion', 'famille-we' ); ?></span>
				<input class="fwe-input" type="date" name="join_date" value="<?php echo esc_attr( $fwe_m->join_date ); ?>" data-testid="member-join-date">
				<span class="fwe-hint">
					<?php
					/* translators: %s: montant mensuel. */
					printf( esc_html__( 'La cotisation de %s/mois est due à partir de ce mois.', 'famille-we' ), esc_html( fwe_money( $s['monthly_amount'] ) ) );
					?>
				</span>
			</label>
			<label class="fwe-field">
				<span class="fwe-label"><?php esc_html_e( 'Statut', 'famille-we' ); ?></span>
				<select class="fwe-input" name="status" data-testid="member-status">
					<?php foreach ( fwe_labels( 'member_status' ) as $fwe_key => $fwe_label ) : ?>
						<option value="<?php echo esc_attr( $fwe_key ); ?>" <?php selected( $fwe_m->status, $fwe_key ); ?>><?php echo esc_html( $fwe_label ); ?></option>
					<?php endforeach; ?>
				</select>
				<span class="fwe-hint"><?php esc_html_e( 'Seuls les membres actifs doivent des cotisations.', 'famille-we' ); ?></span>
			</label>
		</div>
		<label class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Notes du bureau', 'famille-we' ); ?></span>
			<textarea class="fwe-input" name="notes" rows="3" data-testid="member-notes"><?php echo esc_textarea( $fwe_m->notes ); ?></textarea>
		</label>

		<?php if ( ! $fwe_m->user_id ) : ?>
			<label class="fwe-check">
				<input type="checkbox" name="create_account" value="1" data-testid="member-create-account">
				<span><?php esc_html_e( 'Créer son accès en ligne et lui envoyer l’invitation par e-mail', 'famille-we' ); ?></span>
			</label>
		<?php endif; ?>

		<div class="fwe-form-actions">
			<button type="submit" class="fwe-btn fwe-btn--primary" data-testid="member-save"><?php echo $member ? esc_html__( 'Enregistrer les modifications', 'famille-we' ) : esc_html__( 'Ajouter le membre', 'famille-we' ); ?></button>
			<a class="fwe-btn fwe-btn--ghost" href="<?php echo esc_url( FWE_Admin::url( 'fwe-membres' ) ); ?>"><?php esc_html_e( 'Annuler', 'famille-we' ); ?></a>
		</div>
	</form>

	<?php if ( $member ) : ?>
		<aside class="fwe-stack">
			<div class="fwe-card" data-testid="member-dues-card">
				<h2 class="fwe-h3"><?php esc_html_e( 'Situation', 'famille-we' ); ?></h2>
				<dl class="fwe-dl">
					<dt><?php esc_html_e( 'Mois en retard', 'famille-we' ); ?></dt>
					<dd><?php echo esc_html( $dues['months_late'] ); ?></dd>
					<dt><?php esc_html_e( 'Montant dû (mois courant inclus)', 'famille-we' ); ?></dt>
					<dd><?php echo esc_html( fwe_money( $dues['amount_due'] ) ); ?></dd>
					<?php if ( $dues['late'] ) : ?>
						<dt><?php esc_html_e( 'Mois impayés', 'famille-we' ); ?></dt>
						<dd><?php echo esc_html( FWE_Dues::periods_text( $dues['late'] ) ); ?></dd>
					<?php endif; ?>
				</dl>
			</div>
			<div class="fwe-card" data-testid="member-account-card">
				<h2 class="fwe-h3"><?php esc_html_e( 'Accès en ligne', 'famille-we' ); ?></h2>
				<?php if ( $user ) : ?>
					<p><?php echo fwe_badge( __( 'Actif', 'famille-we' ), 'success' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
					<p class="fwe-muted">
						<?php
						/* translators: %s: identifiant. */
						printf( esc_html__( 'Identifiant : %s', 'famille-we' ), esc_html( $user->user_login ) );
						?>
					</p>
				<?php else : ?>
					<p class="fwe-muted"><?php esc_html_e( 'Ce membre n’a pas encore d’accès à son espace en ligne.', 'famille-we' ); ?></p>
					<form method="post" action="<?php echo esc_url( FWE_Admin::post_url() ); ?>">
						<?php FWE_Admin::form_fields( 'invite_member', 'fwe_invite_member_' . $member->id ); ?>
						<input type="hidden" name="id" value="<?php echo esc_attr( $member->id ); ?>">
						<button type="submit" class="fwe-btn fwe-btn--secondary fwe-btn--sm" data-testid="invite-member"><?php echo fwe_icon( 'send', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Envoyer une invitation', 'famille-we' ); ?></button>
					</form>
				<?php endif; ?>
			</div>
		</aside>
	<?php endif; ?>
</div>
