<?php
/**
 * Vue : centre de notifications (annonces + journal des envois).
 *
 * @package FamilleWE
 * @var array    $s             Réglages.
 * @var array    $filters       Filtres actifs.
 * @var object[] $notifications Journal.
 */

defined( 'ABSPATH' ) || exit;

$fwe_status_tone = array(
	'envoye'        => 'success',
	'echec'         => 'danger',
	'non_configure' => 'neutral',
	'ignore'        => 'warning',
);
?>
<header class="fwe-page-head">
	<div>
		<p class="fwe-label"><?php esc_html_e( 'Communication', 'famille-we' ); ?></p>
		<h1 class="fwe-h1"><?php esc_html_e( 'Notifications', 'famille-we' ); ?></h1>
		<p class="fwe-sub"><?php esc_html_e( 'Annonces à la famille et suivi de chaque envoi : espace membre, e-mail, SMS et WhatsApp.', 'famille-we' ); ?></p>
	</div>
</header>

<div class="fwe-grid fwe-grid--3">
	<form class="fwe-card fwe-form" method="post" action="<?php echo esc_url( FWE_Admin::post_url() ); ?>" data-testid="announcement-form">
		<?php FWE_Admin::form_fields( 'send_announcement' ); ?>
		<h2 class="fwe-h3"><?php echo fwe_icon( 'megaphone', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php esc_html_e( 'Envoyer une annonce', 'famille-we' ); ?></h2>
		<label class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Titre', 'famille-we' ); ?> *</span>
			<input class="fwe-input" type="text" name="title" required data-testid="announcement-title">
		</label>
		<label class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Message', 'famille-we' ); ?> *</span>
			<textarea class="fwe-input" name="message" rows="5" required data-testid="announcement-message"></textarea>
		</label>
		<label class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Destinataires', 'famille-we' ); ?></span>
			<select class="fwe-input" name="target" data-testid="announcement-target">
				<option value=""><?php esc_html_e( 'Tous les membres actifs', 'famille-we' ); ?></option>
				<?php foreach ( fwe_labels( 'state' ) as $fwe_key => $fwe_label ) : ?>
					<option value="<?php echo esc_attr( $fwe_key ); ?>">
						<?php
						/* translators: %s: état. */
						printf( esc_html__( 'Membres du %s', 'famille-we' ), esc_html( $fwe_label ) );
						?>
					</option>
				<?php endforeach; ?>
			</select>
		</label>
		<fieldset class="fwe-field">
			<legend class="fwe-label"><?php esc_html_e( 'Canaux', 'famille-we' ); ?></legend>
			<label class="fwe-check"><input type="checkbox" checked disabled><span><?php esc_html_e( 'Espace membre (toujours)', 'famille-we' ); ?></span></label>
			<label class="fwe-check"><input type="checkbox" name="channels[]" value="email" <?php checked( (bool) $s['notify_email'] ); ?> data-testid="announcement-email"><span><?php esc_html_e( 'E-mail', 'famille-we' ); ?></span></label>
			<label class="fwe-check"><input type="checkbox" name="channels[]" value="sms" <?php checked( (bool) $s['enable_sms'] ); ?> data-testid="announcement-sms"><span><?php esc_html_e( 'SMS', 'famille-we' ); ?><?php echo $s['enable_sms'] ? '' : ' <em class="fwe-muted">(' . esc_html__( 'non configuré', 'famille-we' ) . ')</em>'; ?></span></label>
			<label class="fwe-check"><input type="checkbox" name="channels[]" value="whatsapp" <?php checked( (bool) $s['enable_whatsapp'] ); ?> data-testid="announcement-whatsapp"><span><?php esc_html_e( 'WhatsApp', 'famille-we' ); ?><?php echo $s['enable_whatsapp'] ? '' : ' <em class="fwe-muted">(' . esc_html__( 'non configuré', 'famille-we' ) . ')</em>'; ?></span></label>
		</fieldset>
		<div class="fwe-form-actions">
			<button type="submit" class="fwe-btn fwe-btn--primary" data-testid="announcement-send"><?php echo fwe_icon( 'send', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Envoyer', 'famille-we' ); ?></button>
		</div>
	</form>

	<section class="fwe-card fwe-card--flush fwe-span-2">
		<div class="fwe-card__head fwe-card__head--pad">
			<h2 class="fwe-h3"><?php esc_html_e( 'Journal des envois', 'famille-we' ); ?></h2>
			<form class="fwe-inline" method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>">
				<input type="hidden" name="page" value="fwe-notifications">
				<select class="fwe-input fwe-input--sm" name="channel" data-testid="log-channel-filter">
					<option value=""><?php esc_html_e( 'Tous les canaux', 'famille-we' ); ?></option>
					<?php foreach ( fwe_labels( 'channel' ) as $fwe_key => $fwe_label ) : ?>
						<option value="<?php echo esc_attr( $fwe_key ); ?>" <?php selected( $filters['channel'], $fwe_key ); ?>><?php echo esc_html( $fwe_label ); ?></option>
					<?php endforeach; ?>
				</select>
				<select class="fwe-input fwe-input--sm" name="nstatus" data-testid="log-status-filter">
					<option value=""><?php esc_html_e( 'Tous les statuts', 'famille-we' ); ?></option>
					<?php foreach ( fwe_labels( 'notif_status' ) as $fwe_key => $fwe_label ) : ?>
						<option value="<?php echo esc_attr( $fwe_key ); ?>" <?php selected( $filters['status'], $fwe_key ); ?>><?php echo esc_html( $fwe_label ); ?></option>
					<?php endforeach; ?>
				</select>
				<button type="submit" class="fwe-btn fwe-btn--outline fwe-btn--sm"><?php esc_html_e( 'Filtrer', 'famille-we' ); ?></button>
			</form>
		</div>
		<?php if ( ! $notifications ) : ?>
			<div class="fwe-empty fwe-empty--pad"><?php echo fwe_icon( 'bell', 32 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><p><?php esc_html_e( 'Aucune notification envoyée pour le moment.', 'famille-we' ); ?></p></div>
		<?php else : ?>
			<div class="fwe-table-wrap">
				<table class="fwe-table" data-testid="notifications-log">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Date', 'famille-we' ); ?></th>
							<th><?php esc_html_e( 'Membre', 'famille-we' ); ?></th>
							<th><?php esc_html_e( 'Notification', 'famille-we' ); ?></th>
							<th><?php esc_html_e( 'Canal', 'famille-we' ); ?></th>
							<th><?php esc_html_e( 'Statut', 'famille-we' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $notifications as $fwe_n ) : ?>
							<tr data-testid="log-row-<?php echo esc_attr( $fwe_n->id ); ?>">
								<td class="fwe-nowrap fwe-xs"><?php echo esc_html( fwe_format_date( $fwe_n->created_at, true ) ); ?></td>
								<td><?php echo esc_html( $fwe_n->member_id ? trim( $fwe_n->first_name . ' ' . $fwe_n->last_name ) : '—' ); ?></td>
								<td>
									<?php echo fwe_badge( fwe_label( 'notif_type', $fwe_n->type ), 'retard' === $fwe_n->type || 'rappel' === $fwe_n->type ? 'danger' : 'secondary' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<strong class="fwe-block"><?php echo esc_html( $fwe_n->title ); ?></strong>
								</td>
								<td><?php echo esc_html( fwe_label( 'channel', $fwe_n->channel ) ); ?></td>
								<td>
									<?php echo fwe_badge( fwe_label( 'notif_status', $fwe_n->status ), $fwe_status_tone[ $fwe_n->status ] ?? 'neutral', 'log-status-' . $fwe_n->id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<?php if ( $fwe_n->detail ) : ?>
										<span class="fwe-muted fwe-xs fwe-block"><?php echo esc_html( $fwe_n->detail ); ?></span>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	</section>
</div>
