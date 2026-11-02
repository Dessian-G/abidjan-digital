<?php
/**
 * Vue : cotisations (saisie + grille annuelle).
 *
 * @package FamilleWE
 * @var array    $s        Réglages.
 * @var int      $year     Année affichée.
 * @var string   $current  Mois courant.
 * @var object[] $members  Membres (hors en attente).
 * @var array    $summary  Situations.
 * @var array    $payments member_id => period => paiement.
 * @var array    $totals   period => total.
 * @var int      $preset   Membre présélectionné.
 */

defined( 'ABSPATH' ) || exit;

$fwe_months    = fwe_periods_between( sprintf( '%04d-01', $year ), sprintf( '%04d-12', $year ) );
$fwe_short     = fwe_month_names( true );
$fwe_active    = array_filter(
	$members,
	function ( $m ) {
		return 'actif' === $m->status;
	}
);
$fwe_year_paid = array_sum( $totals );
$fwe_expected  = 0;
foreach ( $fwe_active as $fwe_member ) {
	$fwe_start     = FWE_Dues::start_period_for( $fwe_member );
	$fwe_expected += count(
		array_filter(
			$fwe_months,
			function ( $p ) use ( $fwe_start, $current ) {
				return $p >= $fwe_start && $p <= $current;
			}
		)
	) * (float) $s['monthly_amount'];
}
?>
<header class="fwe-page-head">
	<div>
		<p class="fwe-label">
			<?php
			/* translators: %s: montant mensuel. */
			printf( esc_html__( '%s par mois et par membre', 'famille-we' ), esc_html( fwe_money( $s['monthly_amount'] ) ) );
			?>
		</p>
		<h1 class="fwe-h1"><?php esc_html_e( 'Cotisations', 'famille-we' ); ?></h1>
		<p class="fwe-sub">
			<?php
			/* translators: 1: encaissé, 2: attendu à date. */
			printf( esc_html__( '%1$s encaissés cette année · %2$s attendus à date', 'famille-we' ), '<strong data-testid="cotisations-year-paid">' . esc_html( fwe_money( $fwe_year_paid ) ) . '</strong>', esc_html( fwe_money( $fwe_expected ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</p>
	</div>
	<nav class="fwe-year-nav" aria-label="<?php esc_attr_e( 'Année', 'famille-we' ); ?>">
		<a class="fwe-icon-btn" href="<?php echo esc_url( FWE_Admin::url( 'fwe-cotisations', array( 'year' => $year - 1 ) ) ); ?>" data-testid="year-prev"><?php echo fwe_icon( 'left', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="screen-reader-text"><?php esc_html_e( 'Année précédente', 'famille-we' ); ?></span></a>
		<strong class="fwe-year" data-testid="year-current"><?php echo esc_html( $year ); ?></strong>
		<a class="fwe-icon-btn" href="<?php echo esc_url( FWE_Admin::url( 'fwe-cotisations', array( 'year' => $year + 1 ) ) ); ?>" data-testid="year-next"><?php echo fwe_icon( 'right', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="screen-reader-text"><?php esc_html_e( 'Année suivante', 'famille-we' ); ?></span></a>
	</nav>
</header>

<form id="fwe-record" class="fwe-card fwe-form" method="post" action="<?php echo esc_url( FWE_Admin::post_url() ); ?>" data-testid="record-payment-form">
	<?php FWE_Admin::form_fields( 'record_payment' ); ?>
	<div class="fwe-card__head">
		<div>
			<h2 class="fwe-h3"><?php esc_html_e( 'Enregistrer un paiement', 'famille-we' ); ?></h2>
			<p class="fwe-muted"><?php esc_html_e( 'Un virement Zelle peut couvrir plusieurs mois : indiquez le premier mois et le nombre de mois réglés.', 'famille-we' ); ?></p>
		</div>
	</div>
	<div class="fwe-form-grid fwe-form-grid--4">
		<label class="fwe-field fwe-span-2">
			<span class="fwe-label"><?php esc_html_e( 'Membre', 'famille-we' ); ?> *</span>
			<select class="fwe-input" name="member_id" required data-testid="payment-member">
				<option value=""><?php esc_html_e( '— Choisir —', 'famille-we' ); ?></option>
				<?php foreach ( $members as $fwe_member ) : ?>
					<option value="<?php echo esc_attr( $fwe_member->id ); ?>" <?php selected( $preset, (int) $fwe_member->id ); ?>><?php echo esc_html( fwe_member_name( $fwe_member ) . ( 'inactif' === $fwe_member->status ? ' (' . __( 'inactif', 'famille-we' ) . ')' : '' ) ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>
		<label class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Premier mois', 'famille-we' ); ?> *</span>
			<input class="fwe-input" type="month" name="from_period" value="<?php echo esc_attr( $preset && isset( $summary[ $preset ] ) && $summary[ $preset ]['unpaid'] ? $summary[ $preset ]['unpaid'][0] : $current ); ?>" required data-testid="payment-from">
		</label>
		<label class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Nombre de mois', 'famille-we' ); ?></span>
			<input class="fwe-input" type="number" name="months" min="1" max="24" value="1" data-testid="payment-months">
		</label>
		<label class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Montant par mois ($)', 'famille-we' ); ?></span>
			<input class="fwe-input" type="text" inputmode="decimal" name="amount" value="<?php echo esc_attr( $s['monthly_amount'] ); ?>" data-testid="payment-amount">
		</label>
		<label class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Date du paiement', 'famille-we' ); ?></span>
			<input class="fwe-input" type="date" name="paid_at" value="<?php echo esc_attr( current_time( 'Y-m-d' ) ); ?>" data-testid="payment-date">
		</label>
		<label class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Moyen', 'famille-we' ); ?></span>
			<select class="fwe-input" name="method" data-testid="payment-method">
				<?php foreach ( fwe_labels( 'method' ) as $fwe_key => $fwe_label ) : ?>
					<option value="<?php echo esc_attr( $fwe_key ); ?>"><?php echo esc_html( $fwe_label ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>
		<label class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Référence / note', 'famille-we' ); ?></span>
			<input class="fwe-input" type="text" name="note" placeholder="<?php esc_attr_e( 'N° de transaction Zelle…', 'famille-we' ); ?>" data-testid="payment-note">
		</label>
	</div>
	<div class="fwe-form-actions">
		<button type="submit" class="fwe-btn fwe-btn--primary" data-testid="pay-cotisation-action"><?php echo fwe_icon( 'check', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Enregistrer', 'famille-we' ); ?></button>
	</div>
</form>

<div class="fwe-card fwe-card--flush">
	<div class="fwe-card__head fwe-card__head--pad">
		<div>
			<h2 class="fwe-h3">
				<?php
				/* translators: %d: année. */
				printf( esc_html__( 'Grille %d', 'famille-we' ), (int) $year );
				?>
			</h2>
			<p class="fwe-muted"><?php esc_html_e( 'Cliquez sur une case pour marquer le mois comme payé (montant standard, Zelle, date du jour) ou pour annuler un paiement.', 'famille-we' ); ?></p>
		</div>
		<ul class="fwe-legend">
			<li><span class="fwe-cell fwe-cell--paid fwe-cell--legend"></span><?php esc_html_e( 'Payé', 'famille-we' ); ?></li>
			<li><span class="fwe-cell fwe-cell--late fwe-cell--legend"></span><?php esc_html_e( 'En retard', 'famille-we' ); ?></li>
			<li><span class="fwe-cell fwe-cell--due fwe-cell--legend"></span><?php esc_html_e( 'À payer ce mois', 'famille-we' ); ?></li>
			<li><span class="fwe-cell fwe-cell--none fwe-cell--legend"></span><?php esc_html_e( 'Non dû', 'famille-we' ); ?></li>
		</ul>
	</div>

	<form id="fwe-cell-form" method="post" action="<?php echo esc_url( FWE_Admin::post_url() ); ?>">
		<?php FWE_Admin::form_fields( 'toggle_payment' ); ?>
		<input type="hidden" name="year" value="<?php echo esc_attr( $year ); ?>">
	</form>

	<?php if ( ! $members ) : ?>
		<div class="fwe-empty fwe-empty--pad"><p><?php esc_html_e( 'Ajoutez des membres pour suivre leurs cotisations.', 'famille-we' ); ?></p></div>
	<?php else : ?>
		<div class="fwe-table-wrap">
			<table class="fwe-table fwe-grid-table" data-testid="cotisations-grid">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Membre', 'famille-we' ); ?></th>
						<?php foreach ( $fwe_months as $fwe_period ) : ?>
							<th class="fwe-center<?php echo $fwe_period === $current ? ' is-current' : ''; ?>"><?php echo esc_html( $fwe_short[ (int) substr( $fwe_period, 5, 2 ) ] ); ?></th>
						<?php endforeach; ?>
						<th class="fwe-right"><?php esc_html_e( 'Situation', 'famille-we' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $members as $fwe_member ) : ?>
						<?php
						$fwe_dues  = $summary[ (int) $fwe_member->id ];
						$fwe_start = FWE_Dues::start_period_for( $fwe_member );
						?>
						<tr>
							<td>
								<a class="fwe-strong-link" href="<?php echo esc_url( FWE_Admin::url( 'fwe-membres', array( 'view' => 'edit', 'id' => $fwe_member->id ) ) ); ?>"><?php echo esc_html( fwe_member_name( $fwe_member ) ); ?></a>
								<span class="fwe-muted fwe-xs fwe-block"><?php echo esc_html( fwe_label( 'state', $fwe_member->state ) ); ?></span>
							</td>
							<?php foreach ( $fwe_months as $fwe_period ) : ?>
								<?php
								$fwe_paid = isset( $payments[ (int) $fwe_member->id ][ $fwe_period ] ) ? $payments[ (int) $fwe_member->id ][ $fwe_period ] : null;
								$fwe_due  = 'actif' === $fwe_member->status && $fwe_period >= $fwe_start && $fwe_period <= $current;
								if ( $fwe_paid ) {
									$fwe_class = 'paid';
									$fwe_title = sprintf( '%s · %s · %s', fwe_money( $fwe_paid->amount ), fwe_label( 'method', $fwe_paid->method ), fwe_format_date( $fwe_paid->paid_at ) );
								} elseif ( $fwe_due && $fwe_period < $current ) {
									$fwe_class = 'late';
									$fwe_title = __( 'En retard — cliquer pour marquer payé', 'famille-we' );
								} elseif ( $fwe_due ) {
									$fwe_class = 'due';
									$fwe_title = __( 'À payer — cliquer pour marquer payé', 'famille-we' );
								} else {
									$fwe_class = 'none';
									$fwe_title = __( 'Non dû — cliquer pour enregistrer un paiement anticipé', 'famille-we' );
								}
								?>
								<td class="fwe-center">
									<button type="submit" form="fwe-cell-form" name="cell" value="<?php echo esc_attr( $fwe_member->id . '|' . $fwe_period ); ?>" class="fwe-cell fwe-cell--<?php echo esc_attr( $fwe_class ); ?>" title="<?php echo esc_attr( fwe_period_label( $fwe_period ) . ' — ' . $fwe_title ); ?>" aria-label="<?php echo esc_attr( fwe_member_name( $fwe_member ) . ', ' . fwe_period_label( $fwe_period ) . ' — ' . $fwe_title ); ?>" data-testid="cell-<?php echo esc_attr( $fwe_member->id . '-' . $fwe_period ); ?>"<?php echo $fwe_paid ? ' data-fwe-confirm="' . esc_attr__( 'Annuler ce paiement ?', 'famille-we' ) . '"' : ''; ?>>
										<?php echo $fwe_paid ? fwe_icon( 'check', 14 ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</button>
								</td>
							<?php endforeach; ?>
							<td class="fwe-right"><?php echo fwe_dues_badge( $fwe_dues ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<th><?php esc_html_e( 'Total encaissé', 'famille-we' ); ?></th>
						<?php foreach ( $fwe_months as $fwe_period ) : ?>
							<th class="fwe-center fwe-xs"><?php echo isset( $totals[ $fwe_period ] ) ? esc_html( fwe_money( $totals[ $fwe_period ] ) ) : '—'; ?></th>
						<?php endforeach; ?>
						<th class="fwe-right" data-testid="grid-year-total"><?php echo esc_html( fwe_money( $fwe_year_paid ) ); ?></th>
					</tr>
				</tfoot>
			</table>
		</div>
	<?php endif; ?>
</div>
