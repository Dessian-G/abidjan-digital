<?php
/**
 * Vue : retards de cotisation.
 *
 * @package FamilleWE
 * @var array    $s          Réglages.
 * @var array    $rows       Membres en retard (member, dues).
 * @var array    $reminders  member_id => date du dernier rappel.
 * @var string   $last_check Dernière vérification.
 * @var int|bool $next_check Prochaine vérification (timestamp).
 */

defined( 'ABSPATH' ) || exit;

$fwe_threshold = (int) $s['late_threshold'];
$fwe_critical  = array_filter(
	$rows,
	function ( $r ) {
		return 'critique' === $r['dues']['state'];
	}
);
?>
<header class="fwe-page-head">
	<div>
		<p class="fwe-label"><?php esc_html_e( 'Suivi des cotisations', 'famille-we' ); ?></p>
		<h1 class="fwe-h1"><?php esc_html_e( 'Retards', 'famille-we' ); ?></h1>
		<p class="fwe-sub">
			<?php
			/* translators: 1: nombre de membres en retard, 2: nombre au-delà du seuil, 3: seuil. */
			printf( esc_html__( '%1$d membres en retard, dont %2$d au-delà de %3$d mois.', 'famille-we' ), count( $rows ), count( $fwe_critical ), (int) $fwe_threshold );
			?>
		</p>
	</div>
</header>

<section class="fwe-card fwe-banner-card" data-testid="auto-check-card">
	<div class="fwe-banner-card__icon"><?php echo fwe_icon( 'bell', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	<div class="fwe-banner-card__body">
		<h2 class="fwe-h3">
			<?php
			/* translators: %d: seuil en mois. */
			printf( esc_html__( 'Alerte automatique au-delà de %d mois de retard', 'famille-we' ), (int) $fwe_threshold );
			?>
			<?php echo $s['auto_notify'] ? fwe_badge( __( 'Activée', 'famille-we' ), 'success', 'auto-notify-status' ) : fwe_badge( __( 'Désactivée', 'famille-we' ), 'neutral', 'auto-notify-status' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</h2>
		<p class="fwe-muted">
			<?php esc_html_e( 'Chaque jour, les membres dépassant le seuil reçoivent une notification dans leur espace, un e-mail et, si Twilio est configuré, un SMS et un message WhatsApp — une seule fois par mois.', 'famille-we' ); ?>
		</p>
		<p class="fwe-xs fwe-muted">
			<?php
			/* translators: 1: dernière vérification, 2: prochaine vérification. */
			printf( esc_html__( 'Dernière vérification : %1$s · Prochaine : %2$s', 'famille-we' ), esc_html( $last_check ? fwe_format_date( $last_check, true ) : __( 'jamais', 'famille-we' ) ), esc_html( $next_check ? fwe_format_date( wp_date( 'Y-m-d H:i:s', $next_check ), true ) : __( 'non planifiée', 'famille-we' ) ) );
			?>
		</p>
	</div>
	<form method="post" action="<?php echo esc_url( FWE_Admin::post_url() ); ?>">
		<?php FWE_Admin::form_fields( 'run_check' ); ?>
		<button type="submit" class="fwe-btn fwe-btn--secondary" data-testid="run-late-check"><?php esc_html_e( 'Vérifier maintenant', 'famille-we' ); ?></button>
	</form>
</section>

<div class="fwe-card fwe-card--flush">
	<?php if ( ! $rows ) : ?>
		<div class="fwe-empty fwe-empty--pad" data-testid="no-late">
			<?php echo fwe_icon( 'check', 32 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<p><?php esc_html_e( 'Aucun retard : tous les membres actifs sont à jour.', 'famille-we' ); ?></p>
		</div>
	<?php else : ?>
		<div class="fwe-table-wrap">
			<table class="fwe-table" data-testid="late-table">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Membre', 'famille-we' ); ?></th>
						<th><?php esc_html_e( 'Mois impayés', 'famille-we' ); ?></th>
						<th><?php esc_html_e( 'Montant en retard', 'famille-we' ); ?></th>
						<th><?php esc_html_e( 'Situation', 'famille-we' ); ?></th>
						<th><?php esc_html_e( 'Dernier rappel', 'famille-we' ); ?></th>
						<th class="fwe-right"><?php esc_html_e( 'Actions', 'famille-we' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $rows as $fwe_row ) : ?>
						<?php
						$fwe_member = $fwe_row['member'];
						$fwe_dues   = $fwe_row['dues'];
						?>
						<tr data-testid="late-row-<?php echo esc_attr( $fwe_member->id ); ?>">
							<td>
								<div class="fwe-person">
									<span class="fwe-avatar"><?php echo esc_html( fwe_initials( $fwe_member ) ); ?></span>
									<div>
										<a class="fwe-strong-link" href="<?php echo esc_url( FWE_Admin::url( 'fwe-membres', array( 'view' => 'edit', 'id' => $fwe_member->id ) ) ); ?>"><?php echo esc_html( fwe_member_name( $fwe_member ) ); ?></a>
										<span class="fwe-muted fwe-xs fwe-block"><?php echo esc_html( $fwe_member->phone ? $fwe_member->phone : $fwe_member->email ); ?></span>
									</div>
								</div>
							</td>
							<td><?php echo esc_html( FWE_Dues::periods_text( $fwe_dues['late'] ) ); ?></td>
							<td><strong><?php echo esc_html( fwe_money( $fwe_dues['amount_late'] ) ); ?></strong></td>
							<td><?php echo fwe_dues_badge( $fwe_dues, 'late-badge-' . $fwe_member->id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td><?php echo esc_html( isset( $reminders[ (int) $fwe_member->id ] ) ? fwe_format_date( $reminders[ (int) $fwe_member->id ] ) : '—' ); ?></td>
							<td class="fwe-right">
								<div class="fwe-row-actions">
									<a class="fwe-btn fwe-btn--outline fwe-btn--sm" href="<?php echo esc_url( FWE_Admin::url( 'fwe-cotisations', array( 'member_id' => $fwe_member->id ) ) . '#fwe-record' ); ?>" data-testid="late-pay-<?php echo esc_attr( $fwe_member->id ); ?>"><?php esc_html_e( 'Paiement', 'famille-we' ); ?></a>
									<form method="post" action="<?php echo esc_url( FWE_Admin::post_url() ); ?>">
										<?php FWE_Admin::form_fields( 'send_reminder', 'fwe_send_reminder_' . $fwe_member->id ); ?>
										<input type="hidden" name="id" value="<?php echo esc_attr( $fwe_member->id ); ?>">
										<button type="submit" class="fwe-btn fwe-btn--primary fwe-btn--sm" data-testid="send-reminder-<?php echo esc_attr( $fwe_member->id ); ?>"><?php echo fwe_icon( 'send', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Rappel', 'famille-we' ); ?></button>
									</form>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>
