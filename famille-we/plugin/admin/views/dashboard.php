<?php
/**
 * Vue : tableau de bord du bureau.
 *
 * @package FamilleWE
 * @var array    $s              Réglages.
 * @var string   $current        Mois courant.
 * @var array    $counts         Membres par statut.
 * @var int      $active_count   Membres actifs.
 * @var array    $late           Membres en retard (member, dues).
 * @var int      $critical_count Membres au-delà du seuil.
 * @var float    $late_amount    Montant total en retard.
 * @var float    $month_total    Encaissé pour le mois courant.
 * @var float    $month_expected Attendu pour le mois courant.
 * @var float    $year_total     Encaissé sur l'année.
 * @var string[] $chart_periods  Périodes du graphique.
 * @var array    $chart_totals   Totaux par période.
 * @var object[] $events         Prochains événements.
 * @var int      $upcoming_count Nombre d'événements à venir.
 * @var object[] $cases          Cas de soutien ouverts.
 * @var array    $case_totals    Totaux des cas.
 * @var object[] $pending        Adhésions en attente.
 */

defined( 'ABSPATH' ) || exit;

$fwe_month_pct = $month_expected > 0 ? min( 100, round( $month_total / $month_expected * 100 ) ) : 0;
$fwe_chart_max = max( 1, max( array_merge( array( 0 ), array_values( $chart_totals ) ) ) );
?>
<header class="fwe-page-head">
	<div>
		<p class="fwe-label"><?php echo esc_html( $s['association_name'] . ' · ' . $s['region'] ); ?></p>
		<h1 class="fwe-h1"><?php esc_html_e( 'Tableau de bord', 'famille-we' ); ?></h1>
		<p class="fwe-sub">
			<?php
			/* translators: %s: mois courant. */
			printf( esc_html__( 'Situation de la famille en %s', 'famille-we' ), esc_html( fwe_period_label( $current ) ) );
			?>
		</p>
	</div>
	<div class="fwe-head-actions">
		<a class="fwe-btn fwe-btn--outline" href="<?php echo esc_url( FWE_Admin::url( 'fwe-membres', array( 'view' => 'new' ) ) ); ?>" data-testid="dashboard-add-member"><?php echo fwe_icon( 'plus', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Ajouter un membre', 'famille-we' ); ?></a>
		<a class="fwe-btn fwe-btn--primary" href="<?php echo esc_url( FWE_Admin::url( 'fwe-cotisations' ) ); ?>" data-testid="dashboard-record-payment"><?php echo fwe_icon( 'wallet', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Enregistrer un paiement', 'famille-we' ); ?></a>
	</div>
</header>

<section class="fwe-grid fwe-grid--4">
	<article class="fwe-card fwe-stat fwe-rise" style="--i:0" data-testid="stat-members">
		<div class="fwe-stat__icon"><?php echo fwe_icon( 'users' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<p class="fwe-label"><?php esc_html_e( 'Membres actifs', 'famille-we' ); ?></p>
		<p class="fwe-stat__value" data-testid="stat-members-value"><?php echo esc_html( number_format_i18n( $active_count ) ); ?></p>
		<p class="fwe-stat__meta">
			<?php
			if ( $counts['en_attente'] ) {
				/* translators: %d: nombre. */
				echo esc_html( sprintf( _n( '+ %d adhésion à valider', '+ %d adhésions à valider', $counts['en_attente'], 'famille-we' ), $counts['en_attente'] ) );
			} else {
				/* translators: %d: nombre. */
				echo esc_html( sprintf( _n( '%d inactif', '%d inactifs', $counts['inactif'], 'famille-we' ), $counts['inactif'] ) );
			}
			?>
		</p>
	</article>

	<article class="fwe-card fwe-stat fwe-rise" style="--i:1" data-testid="stat-month">
		<div class="fwe-stat__icon fwe-stat__icon--primary"><?php echo fwe_icon( 'wallet' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<p class="fwe-label">
			<?php
			/* translators: %s: mois. */
			printf( esc_html__( 'Cotisations · %s', 'famille-we' ), esc_html( fwe_period_label( $current, true ) ) );
			?>
		</p>
		<p class="fwe-stat__value" data-testid="stat-month-value"><?php echo esc_html( fwe_money( $month_total ) ); ?></p>
		<div class="fwe-progress" role="progressbar" aria-valuenow="<?php echo esc_attr( $fwe_month_pct ); ?>" aria-valuemin="0" aria-valuemax="100"><span style="width:<?php echo esc_attr( $fwe_month_pct ); ?>%"></span></div>
		<p class="fwe-stat__meta">
			<?php
			/* translators: 1: montant attendu, 2: pourcentage. */
			printf( esc_html__( 'sur %1$s attendus · %2$s%%', 'famille-we' ), esc_html( fwe_money( $month_expected ) ), esc_html( $fwe_month_pct ) );
			?>
		</p>
	</article>

	<article class="fwe-card fwe-stat fwe-rise" style="--i:2" data-testid="stat-late">
		<div class="fwe-stat__icon fwe-stat__icon--danger"><?php echo fwe_icon( 'alert' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<p class="fwe-label"><?php esc_html_e( 'Retards de cotisation', 'famille-we' ); ?></p>
		<p class="fwe-stat__value" data-testid="stat-late-value"><?php echo esc_html( number_format_i18n( count( $late ) ) ); ?></p>
		<p class="fwe-stat__meta">
			<?php
			/* translators: 1: nombre de membres, 2: seuil en mois, 3: montant. */
			printf( esc_html__( '%1$d au-delà de %2$d mois · %3$s dus', 'famille-we' ), (int) $critical_count, (int) $s['late_threshold'], esc_html( fwe_money( $late_amount ) ) );
			?>
		</p>
	</article>

	<article class="fwe-card fwe-stat fwe-rise" style="--i:3" data-testid="stat-events">
		<div class="fwe-stat__icon fwe-stat__icon--secondary"><?php echo fwe_icon( 'calendar' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<p class="fwe-label"><?php esc_html_e( 'Événements à venir', 'famille-we' ); ?></p>
		<p class="fwe-stat__value" data-testid="stat-events-value"><?php echo esc_html( number_format_i18n( $upcoming_count ) ); ?></p>
		<p class="fwe-stat__meta"><?php echo $events ? esc_html( $events[0]->title . ' · ' . fwe_format_date( $events[0]->starts_at ) ) : esc_html__( 'Aucun événement programmé', 'famille-we' ); ?></p>
	</article>
</section>

<section class="fwe-grid fwe-grid--3">
	<article class="fwe-card fwe-span-2 fwe-rise" style="--i:4" data-testid="chart-cotisations">
		<div class="fwe-card__head">
			<div>
				<h2 class="fwe-h3"><?php esc_html_e( 'Cotisations encaissées', 'famille-we' ); ?></h2>
				<p class="fwe-muted"><?php esc_html_e( '12 derniers mois, par mois de cotisation', 'famille-we' ); ?></p>
			</div>
			<div class="fwe-card__aside">
				<p class="fwe-label">
					<?php
					/* translators: %s: année. */
					printf( esc_html__( 'Total %s', 'famille-we' ), esc_html( substr( $current, 0, 4 ) ) );
					?>
				</p>
				<p class="fwe-figure" data-testid="year-total"><?php echo esc_html( fwe_money( $year_total ) ); ?></p>
			</div>
		</div>
		<div class="fwe-chart" role="img" aria-label="<?php esc_attr_e( 'Histogramme des cotisations encaissées sur 12 mois', 'famille-we' ); ?>">
			<?php foreach ( $chart_periods as $fwe_period ) : ?>
				<?php
				$fwe_value = isset( $chart_totals[ $fwe_period ] ) ? $chart_totals[ $fwe_period ] : 0;
				$fwe_h     = $fwe_value > 0 ? max( 4, round( $fwe_value / $fwe_chart_max * 100 ) ) : 0;
				?>
				<div class="fwe-chart__col<?php echo $fwe_period === $current ? ' is-current' : ''; ?>" title="<?php echo esc_attr( fwe_period_label( $fwe_period ) . ' : ' . fwe_money( $fwe_value ) ); ?>">
					<span class="fwe-chart__value"><?php echo $fwe_value > 0 ? esc_html( fwe_money( $fwe_value ) ) : ''; ?></span>
					<span class="fwe-chart__bar" style="height:<?php echo esc_attr( $fwe_h ); ?>%"></span>
					<span class="fwe-chart__label"><?php echo esc_html( fwe_month_names( true )[ (int) substr( $fwe_period, 5, 2 ) ] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</article>

	<article class="fwe-card fwe-rise" style="--i:5" data-testid="dashboard-late-list">
		<div class="fwe-card__head">
			<h2 class="fwe-h3"><?php esc_html_e( 'Retards à suivre', 'famille-we' ); ?></h2>
			<a class="fwe-link" href="<?php echo esc_url( FWE_Admin::url( 'fwe-retards' ) ); ?>"><?php esc_html_e( 'Tout voir', 'famille-we' ); ?></a>
		</div>
		<?php if ( ! $late ) : ?>
			<div class="fwe-empty">
				<?php echo fwe_icon( 'check', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<p><?php esc_html_e( 'Tout le monde est à jour. Bravo la famille !', 'famille-we' ); ?></p>
			</div>
		<?php else : ?>
			<ul class="fwe-list">
				<?php foreach ( array_slice( $late, 0, 6 ) as $fwe_row ) : ?>
					<li class="fwe-list__item">
						<span class="fwe-avatar"><?php echo esc_html( fwe_initials( $fwe_row['member'] ) ); ?></span>
						<div class="fwe-list__body">
							<a href="<?php echo esc_url( FWE_Admin::url( 'fwe-membres', array( 'view' => 'edit', 'id' => $fwe_row['member']->id ) ) ); ?>"><strong><?php echo esc_html( fwe_member_name( $fwe_row['member'] ) ); ?></strong></a>
							<span class="fwe-muted"><?php echo esc_html( fwe_money( $fwe_row['dues']['amount_late'] ) ); ?></span>
						</div>
						<?php echo fwe_dues_badge( $fwe_row['dues'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</article>
</section>

<section class="fwe-grid fwe-grid--3">
	<article class="fwe-card fwe-rise" style="--i:6" data-testid="dashboard-events">
		<div class="fwe-card__head">
			<h2 class="fwe-h3"><?php esc_html_e( 'Prochains événements', 'famille-we' ); ?></h2>
			<a class="fwe-link" href="<?php echo esc_url( FWE_Admin::url( 'fwe-evenements' ) ); ?>"><?php esc_html_e( 'Gérer', 'famille-we' ); ?></a>
		</div>
		<?php if ( ! $events ) : ?>
			<p class="fwe-muted"><?php esc_html_e( 'Aucun événement programmé. Annoncez la prochaine AG ou le prochain festival !', 'famille-we' ); ?></p>
		<?php else : ?>
			<ul class="fwe-list">
				<?php foreach ( $events as $fwe_event ) : ?>
					<li class="fwe-list__item">
						<span class="fwe-date-chip"><strong><?php echo esc_html( gmdate( 'j', strtotime( $fwe_event->starts_at ) ) ); ?></strong><?php echo esc_html( fwe_month_names( true )[ (int) gmdate( 'n', strtotime( $fwe_event->starts_at ) ) ] ); ?></span>
						<div class="fwe-list__body">
							<strong><?php echo esc_html( $fwe_event->title ); ?></strong>
							<span class="fwe-muted"><?php echo esc_html( $fwe_event->location ? $fwe_event->location : fwe_format_date( $fwe_event->starts_at, true ) ); ?></span>
						</div>
						<?php echo fwe_badge( fwe_label( 'event_type', $fwe_event->type ), 'ag' === $fwe_event->type ? 'primary' : 'secondary' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</article>

	<article class="fwe-card fwe-rise" style="--i:7" data-testid="dashboard-support">
		<div class="fwe-card__head">
			<h2 class="fwe-h3"><?php esc_html_e( 'Soutien en cours', 'famille-we' ); ?></h2>
			<a class="fwe-link" href="<?php echo esc_url( FWE_Admin::url( 'fwe-soutien' ) ); ?>"><?php esc_html_e( 'Gérer', 'famille-we' ); ?></a>
		</div>
		<?php if ( ! $cases ) : ?>
			<p class="fwe-muted"><?php esc_html_e( 'Aucun cas de soutien ouvert.', 'famille-we' ); ?></p>
		<?php else : ?>
			<ul class="fwe-list">
				<?php foreach ( array_slice( $cases, 0, 4 ) as $fwe_case ) : ?>
					<?php
					$fwe_t   = $case_totals[ (int) $fwe_case->id ];
					$fwe_pct = $fwe_case->target_amount > 0 ? min( 100, round( $fwe_t['confirmed'] / $fwe_case->target_amount * 100 ) ) : 0;
					?>
					<li class="fwe-list__item fwe-list__item--stack">
						<div class="fwe-list__row">
							<span class="fwe-support-icon fwe-support-icon--<?php echo esc_attr( $fwe_case->type ); ?>"><?php echo fwe_support_icon( $fwe_case->type ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<a href="<?php echo esc_url( FWE_Admin::url( 'fwe-soutien', array( 'case' => $fwe_case->id ) ) ); ?>"><strong><?php echo esc_html( $fwe_case->title ); ?></strong></a>
						</div>
						<?php if ( $fwe_case->target_amount > 0 ) : ?>
							<div class="fwe-progress fwe-progress--secondary"><span style="width:<?php echo esc_attr( $fwe_pct ); ?>%"></span></div>
						<?php endif; ?>
						<span class="fwe-muted">
							<?php
							echo esc_html( fwe_money( $fwe_t['confirmed'] ) );
							if ( $fwe_case->target_amount > 0 ) {
								echo ' / ' . esc_html( fwe_money( $fwe_case->target_amount ) );
							}
							if ( $fwe_t['declared'] > 0 ) {
								/* translators: %s: montant. */
								echo ' · ' . esc_html( sprintf( __( '%s à confirmer', 'famille-we' ), fwe_money( $fwe_t['declared'] ) ) );
							}
							?>
						</span>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</article>

	<article class="fwe-card fwe-rise" style="--i:8" data-testid="dashboard-pending">
		<div class="fwe-card__head">
			<h2 class="fwe-h3"><?php esc_html_e( 'Adhésions à valider', 'famille-we' ); ?></h2>
		</div>
		<?php if ( ! $pending ) : ?>
			<p class="fwe-muted"><?php esc_html_e( 'Aucune demande en attente.', 'famille-we' ); ?></p>
		<?php else : ?>
			<ul class="fwe-list">
				<?php foreach ( $pending as $fwe_member ) : ?>
					<li class="fwe-list__item">
						<span class="fwe-avatar"><?php echo esc_html( fwe_initials( $fwe_member ) ); ?></span>
						<div class="fwe-list__body">
							<strong><?php echo esc_html( fwe_member_name( $fwe_member ) ); ?></strong>
							<span class="fwe-muted"><?php echo esc_html( $fwe_member->email ); ?></span>
						</div>
						<form method="post" action="<?php echo esc_url( FWE_Admin::post_url() ); ?>">
							<?php FWE_Admin::form_fields( 'approve_member', 'fwe_approve_member_' . $fwe_member->id ); ?>
							<input type="hidden" name="id" value="<?php echo esc_attr( $fwe_member->id ); ?>">
							<input type="hidden" name="back" value="famille-we">
							<button type="submit" class="fwe-btn fwe-btn--secondary fwe-btn--sm" data-testid="approve-member-<?php echo esc_attr( $fwe_member->id ); ?>"><?php esc_html_e( 'Valider', 'famille-we' ); ?></button>
						</form>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</article>
</section>
