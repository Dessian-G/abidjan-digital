<?php
/**
 * Vue publique : tableau de bord du membre.
 *
 * @package FamilleWE
 * @var array    $s             Réglages.
 * @var object   $member        Membre.
 * @var string   $current       Mois courant.
 * @var array    $dues          Situation.
 * @var object[] $payments      12 derniers paiements.
 * @var array    $paid_map      period => paiement.
 * @var string[] $history       12 derniers mois.
 * @var string   $start         Début des cotisations dues.
 * @var float    $year_total    Versé cette année.
 * @var object[] $events        Événements à venir.
 * @var object[] $cases         Cas de soutien ouverts.
 * @var array    $case_totals   Totaux par cas.
 * @var array    $my_by_case    Mes contributions par cas.
 * @var object[] $notifications Notifications in-app.
 * @var int      $unread        Non lues.
 */

defined( 'ABSPATH' ) || exit;

$fwe_post  = admin_url( 'admin-post.php' );
$fwe_short = fwe_month_names( true );
$fwe_memo  = sprintf(
	/* translators: 1: nom, 2: mois. */
	__( 'Cotisation %1$s — %2$s', 'famille-we' ),
	fwe_member_name( $member ),
	fwe_period_label( $dues['unpaid'] ? $dues['unpaid'][0] : $current )
);
?>
<div class="fwe-espace">
	<header class="fwe-hero" style="background-image:url('<?php echo esc_url( FWE_Assets::image( 'location' ) ); ?>')" data-testid="espace-hero">
		<div class="fwe-hero__overlay"></div>
		<div class="fwe-hero__content">
			<div>
				<p class="fwe-label fwe-label--light"><?php echo esc_html( $s['association_name'] . ' · ' . $s['region'] ); ?></p>
				<h1 class="fwe-display fwe-display--sm" data-testid="espace-greeting">
					<?php
					/* translators: %s: prénom. */
					printf( esc_html__( 'Bonjour, %s', 'famille-we' ), esc_html( $member->first_name ) );
					?>
				</h1>
				<p class="fwe-hero__meta">
					<?php echo fwe_icon( 'pin', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo esc_html( trim( $member->city . ', ' . fwe_label( 'state', $member->state ), ', ' ) ); ?>
					·
					<?php
					/* translators: %s: date. */
					printf( esc_html__( 'membre depuis %s', 'famille-we' ), esc_html( fwe_format_date( $member->join_date ) ) );
					?>
				</p>
			</div>
			<div class="fwe-hero__actions">
				<a class="fwe-hero__bell" href="#fwe-notifications" data-testid="espace-bell" aria-label="<?php esc_attr_e( 'Notifications', 'famille-we' ); ?>">
					<?php echo fwe_icon( 'bell', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php if ( $unread ) : ?>
						<span class="fwe-hero__count" data-testid="espace-unread"><?php echo esc_html( $unread ); ?></span>
					<?php endif; ?>
				</a>
				<a class="fwe-btn fwe-btn--glass fwe-btn--sm" href="<?php echo esc_url( wp_logout_url( fwe_espace_url() ) ); ?>" data-testid="logout-link"><?php echo fwe_icon( 'logout', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Déconnexion', 'famille-we' ); ?></a>
			</div>
		</div>
	</header>

	<nav class="fwe-subnav" aria-label="<?php esc_attr_e( 'Sections', 'famille-we' ); ?>">
		<a href="#fwe-cotisations"><?php esc_html_e( 'Cotisations', 'famille-we' ); ?></a>
		<a href="#fwe-evenements"><?php esc_html_e( 'Événements', 'famille-we' ); ?></a>
		<a href="#fwe-soutien"><?php esc_html_e( 'Soutien', 'famille-we' ); ?></a>
		<a href="#fwe-notifications"><?php esc_html_e( 'Notifications', 'famille-we' ); ?></a>
		<a href="#fwe-profil"><?php esc_html_e( 'Profil', 'famille-we' ); ?></a>
	</nav>

	<section class="fwe-grid fwe-grid--4">
		<article class="fwe-card fwe-stat fwe-rise fwe-stat--<?php echo esc_attr( $dues['state'] ); ?>" style="--i:0" data-testid="member-stat-status">
			<p class="fwe-label"><?php esc_html_e( 'Ma situation', 'famille-we' ); ?></p>
			<p class="fwe-stat__value fwe-stat__value--sm">
				<?php
				if ( 'a_jour' === $dues['state'] ) {
					esc_html_e( 'À jour', 'famille-we' );
				} else {
					/* translators: %d: nombre de mois. */
					echo esc_html( sprintf( _n( '%d mois de retard', '%d mois de retard', $dues['months_late'], 'famille-we' ), $dues['months_late'] ) );
				}
				?>
			</p>
			<p class="fwe-stat__meta">
				<?php
				if ( 'a_jour' === $dues['state'] ) {
					echo $dues['current_paid'] ? esc_html__( 'Merci ! Le mois en cours est réglé.', 'famille-we' ) : esc_html( sprintf( /* translators: %s: mois. */ __( 'Pensez à %s.', 'famille-we' ), fwe_period_label( $current ) ) );
				} else {
					echo esc_html( FWE_Dues::periods_text( $dues['late'], 3 ) );
				}
				?>
			</p>
		</article>
		<article class="fwe-card fwe-stat fwe-rise" style="--i:1" data-testid="member-stat-due">
			<p class="fwe-label"><?php esc_html_e( 'Montant à régler', 'famille-we' ); ?></p>
			<p class="fwe-stat__value" data-testid="member-amount-due"><?php echo esc_html( fwe_money( $dues['amount_due'] ) ); ?></p>
			<p class="fwe-stat__meta">
				<?php
				/* translators: %s: montant mensuel. */
				printf( esc_html__( '%s par mois, mois en cours inclus', 'famille-we' ), esc_html( fwe_money( $s['monthly_amount'] ) ) );
				?>
			</p>
		</article>
		<article class="fwe-card fwe-stat fwe-rise" style="--i:2" data-testid="member-stat-year">
			<p class="fwe-label">
				<?php
				/* translators: %s: année. */
				printf( esc_html__( 'Versé en %s', 'famille-we' ), esc_html( substr( $current, 0, 4 ) ) );
				?>
			</p>
			<p class="fwe-stat__value"><?php echo esc_html( fwe_money( $year_total ) ); ?></p>
			<p class="fwe-stat__meta"><?php esc_html_e( 'Merci pour votre fidélité', 'famille-we' ); ?></p>
		</article>
		<article class="fwe-card fwe-stat fwe-rise" style="--i:3" data-testid="member-stat-event">
			<p class="fwe-label"><?php esc_html_e( 'Prochain rendez-vous', 'famille-we' ); ?></p>
			<?php if ( $events ) : ?>
				<p class="fwe-stat__value fwe-stat__value--sm"><?php echo esc_html( $events[0]->title ); ?></p>
				<p class="fwe-stat__meta"><?php echo esc_html( fwe_format_date( $events[0]->starts_at, true ) ); ?></p>
			<?php else : ?>
				<p class="fwe-stat__value fwe-stat__value--sm">—</p>
				<p class="fwe-stat__meta"><?php esc_html_e( 'Aucun événement programmé', 'famille-we' ); ?></p>
			<?php endif; ?>
		</article>
	</section>

	<section id="fwe-cotisations" class="fwe-grid fwe-grid--3">
		<article class="fwe-card fwe-span-2" data-testid="member-history">
			<div class="fwe-card__head">
				<div>
					<h2 class="fwe-h3"><?php esc_html_e( 'Mes cotisations', 'famille-we' ); ?></h2>
					<p class="fwe-muted"><?php esc_html_e( 'Les 12 derniers mois', 'famille-we' ); ?></p>
				</div>
			</div>
			<ol class="fwe-months">
				<?php foreach ( $history as $fwe_period ) : ?>
					<?php
					if ( isset( $paid_map[ $fwe_period ] ) ) {
						$fwe_state = 'paid';
						$fwe_text  = __( 'Payé', 'famille-we' );
					} elseif ( $fwe_period < $start ) {
						$fwe_state = 'none';
						$fwe_text  = __( 'Non dû', 'famille-we' );
					} elseif ( $fwe_period < $current ) {
						$fwe_state = 'late';
						$fwe_text  = __( 'En retard', 'famille-we' );
					} else {
						$fwe_state = 'due';
						$fwe_text  = __( 'À payer', 'famille-we' );
					}
					?>
					<li class="fwe-months__item fwe-months__item--<?php echo esc_attr( $fwe_state ); ?>" title="<?php echo esc_attr( fwe_period_label( $fwe_period ) . ' — ' . $fwe_text ); ?>" data-testid="history-<?php echo esc_attr( $fwe_period ); ?>">
						<span class="fwe-months__dot"><?php echo 'paid' === $fwe_state ? fwe_icon( 'check', 14 ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<span class="fwe-months__label"><?php echo esc_html( $fwe_short[ (int) substr( $fwe_period, 5, 2 ) ] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ol>
			<?php if ( $payments ) : ?>
				<div class="fwe-table-wrap">
					<table class="fwe-table fwe-table--compact" data-testid="member-payments">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Mois', 'famille-we' ); ?></th>
								<th><?php esc_html_e( 'Montant', 'famille-we' ); ?></th>
								<th><?php esc_html_e( 'Payé le', 'famille-we' ); ?></th>
								<th><?php esc_html_e( 'Moyen', 'famille-we' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $payments as $fwe_payment ) : ?>
								<tr>
									<td><?php echo esc_html( ucfirst( fwe_period_label( $fwe_payment->period ) ) ); ?></td>
									<td><strong><?php echo esc_html( fwe_money( $fwe_payment->amount ) ); ?></strong></td>
									<td><?php echo esc_html( fwe_format_date( $fwe_payment->paid_at ) ); ?></td>
									<td><?php echo esc_html( fwe_label( 'method', $fwe_payment->method ) ); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php else : ?>
				<p class="fwe-muted"><?php esc_html_e( 'Aucun paiement enregistré pour le moment.', 'famille-we' ); ?></p>
			<?php endif; ?>
		</article>

		<aside class="fwe-card fwe-pay" data-testid="zelle-card">
			<div class="fwe-pay__head">
				<span class="fwe-stat__icon fwe-stat__icon--primary"><?php echo fwe_icon( 'wallet' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<h2 class="fwe-h3"><?php esc_html_e( 'Payer ma cotisation', 'famille-we' ); ?></h2>
			</div>
			<?php if ( $s['zelle_contact'] ) : ?>
				<p class="fwe-muted"><?php esc_html_e( 'Envoyez votre cotisation par Zelle depuis l’application de votre banque :', 'famille-we' ); ?></p>
				<dl class="fwe-dl">
					<?php if ( $s['treasurer_name'] ) : ?>
						<dt><?php esc_html_e( 'Bénéficiaire', 'famille-we' ); ?></dt>
						<dd data-testid="zelle-treasurer"><?php echo esc_html( $s['treasurer_name'] ); ?></dd>
					<?php endif; ?>
					<dt><?php esc_html_e( 'Zelle', 'famille-we' ); ?></dt>
					<dd class="fwe-copy-row">
						<code data-testid="zelle-contact"><?php echo esc_html( $s['zelle_contact'] ); ?></code>
						<button type="button" class="fwe-icon-btn" data-fwe-copy="<?php echo esc_attr( $s['zelle_contact'] ); ?>" title="<?php esc_attr_e( 'Copier', 'famille-we' ); ?>" data-testid="copy-zelle"><?php echo fwe_icon( 'copy', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="screen-reader-text"><?php esc_html_e( 'Copier', 'famille-we' ); ?></span></button>
					</dd>
					<dt><?php esc_html_e( 'Mémo conseillé', 'famille-we' ); ?></dt>
					<dd class="fwe-copy-row">
						<code data-testid="zelle-memo"><?php echo esc_html( $fwe_memo ); ?></code>
						<button type="button" class="fwe-icon-btn" data-fwe-copy="<?php echo esc_attr( $fwe_memo ); ?>" title="<?php esc_attr_e( 'Copier', 'famille-we' ); ?>" data-testid="copy-memo"><?php echo fwe_icon( 'copy', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="screen-reader-text"><?php esc_html_e( 'Copier', 'famille-we' ); ?></span></button>
					</dd>
					<dt><?php esc_html_e( 'Montant', 'famille-we' ); ?></dt>
					<dd><strong><?php echo esc_html( fwe_money( $dues['amount_due'] > 0 ? $dues['amount_due'] : $s['monthly_amount'] ) ); ?></strong></dd>
				</dl>
				<?php if ( $s['zelle_note'] ) : ?>
					<p class="fwe-hint"><?php echo esc_html( $s['zelle_note'] ); ?></p>
				<?php endif; ?>
				<p class="fwe-hint"><?php esc_html_e( 'Le trésorier enregistre votre paiement dès réception ; il apparaîtra ici.', 'famille-we' ); ?></p>
			<?php else : ?>
				<p class="fwe-muted"><?php esc_html_e( 'Les coordonnées de paiement seront bientôt affichées ici. En attendant, contactez la trésorerie.', 'famille-we' ); ?></p>
			<?php endif; ?>
		</aside>
	</section>

	<section id="fwe-evenements" class="fwe-section">
		<div class="fwe-section__head">
			<h2 class="fwe-h2"><?php esc_html_e( 'Événements à venir', 'famille-we' ); ?></h2>
		</div>
		<?php include FWE_DIR . 'public/views/events-list.php'; ?>
	</section>

	<section id="fwe-soutien" class="fwe-section">
		<div class="fwe-section__head">
			<h2 class="fwe-h2"><?php esc_html_e( 'Solidarité', 'famille-we' ); ?></h2>
			<p class="fwe-muted"><?php esc_html_e( 'Dans la joie comme dans la peine, la famille répond présente.', 'famille-we' ); ?></p>
		</div>
		<?php if ( ! $cases ) : ?>
			<div class="fwe-card fwe-empty fwe-empty--pad">
				<?php echo fwe_icon( 'heart', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<p><?php esc_html_e( 'Aucun appel à la solidarité en ce moment.', 'famille-we' ); ?></p>
			</div>
		<?php else : ?>
			<div class="fwe-grid fwe-grid--3">
				<?php foreach ( $cases as $fwe_case ) : ?>
					<?php
					$fwe_t    = $case_totals[ (int) $fwe_case->id ];
					$fwe_pct  = $fwe_case->target_amount > 0 ? min( 100, round( $fwe_t['confirmed'] / $fwe_case->target_amount * 100 ) ) : 0;
					$fwe_mine = isset( $my_by_case[ (int) $fwe_case->id ] ) ? $my_by_case[ (int) $fwe_case->id ] : 0;
					$fwe_own  = (int) $fwe_case->member_id === (int) $member->id;
					?>
					<article class="fwe-card fwe-case fwe-case--<?php echo esc_attr( $fwe_case->type ); ?>" data-testid="member-case-<?php echo esc_attr( $fwe_case->id ); ?>">
						<div class="fwe-case__head">
							<span class="fwe-support-icon fwe-support-icon--<?php echo esc_attr( $fwe_case->type ); ?>"><?php echo fwe_support_icon( $fwe_case->type ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<div>
								<p class="fwe-label"><?php echo esc_html( fwe_label( 'support_type', $fwe_case->type ) ); ?></p>
								<h3 class="fwe-h3"><?php echo esc_html( $fwe_case->title ); ?></h3>
							</div>
						</div>
						<?php if ( $fwe_case->description ) : ?>
							<p class="fwe-muted"><?php echo esc_html( wp_trim_words( $fwe_case->description, 30 ) ); ?></p>
						<?php endif; ?>
						<p class="fwe-figure"><?php echo esc_html( fwe_money( $fwe_t['confirmed'] ) ); ?>
							<?php if ( $fwe_case->target_amount > 0 ) : ?>
								<span class="fwe-muted fwe-sm">/ <?php echo esc_html( fwe_money( $fwe_case->target_amount ) ); ?></span>
							<?php endif; ?>
						</p>
						<?php if ( $fwe_case->target_amount > 0 ) : ?>
							<div class="fwe-progress fwe-progress--secondary"><span style="width:<?php echo esc_attr( $fwe_pct ); ?>%"></span></div>
						<?php endif; ?>
						<?php if ( $fwe_mine > 0 ) : ?>
							<p class="fwe-thanks" data-testid="my-contribution-<?php echo esc_attr( $fwe_case->id ); ?>">
								<?php echo fwe_icon( 'heart', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php
								/* translators: %s: montant. */
								printf( esc_html__( 'Votre contribution : %s', 'famille-we' ), esc_html( fwe_money( $fwe_mine ) ) );
								?>
							</p>
						<?php endif; ?>
						<?php if ( $fwe_own ) : ?>
							<p class="fwe-hint"><?php esc_html_e( 'La famille est à vos côtés.', 'famille-we' ); ?></p>
						<?php else : ?>
							<form class="fwe-contribute" method="post" action="<?php echo esc_url( $fwe_post ); ?>" data-testid="contribute-form-<?php echo esc_attr( $fwe_case->id ); ?>">
								<input type="hidden" name="action" value="fwe_contribute">
								<input type="hidden" name="case_id" value="<?php echo esc_attr( $fwe_case->id ); ?>">
								<?php wp_nonce_field( 'fwe_contribute' ); ?>
								<label class="fwe-money-input">
									<span>$</span>
									<input class="fwe-input" type="text" inputmode="decimal" name="amount" value="20" aria-label="<?php esc_attr_e( 'Montant en dollars', 'famille-we' ); ?>" data-testid="contribute-amount-<?php echo esc_attr( $fwe_case->id ); ?>">
								</label>
								<button type="submit" class="fwe-btn fwe-btn--secondary" data-testid="contribute-submit-<?php echo esc_attr( $fwe_case->id ); ?>"><?php esc_html_e( 'Je contribue', 'famille-we' ); ?></button>
							</form>
							<p class="fwe-hint"><?php esc_html_e( 'Envoyez ensuite le montant par Zelle ; le trésorier confirmera.', 'famille-we' ); ?></p>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</section>

	<section class="fwe-grid fwe-grid--2">
		<article id="fwe-notifications" class="fwe-card" data-testid="member-notifications">
			<div class="fwe-card__head">
				<h2 class="fwe-h3"><?php esc_html_e( 'Notifications', 'famille-we' ); ?></h2>
				<?php if ( $unread ) : ?>
					<form method="post" action="<?php echo esc_url( $fwe_post ); ?>">
						<input type="hidden" name="action" value="fwe_mark_read">
						<?php wp_nonce_field( 'fwe_mark_read' ); ?>
						<button type="submit" class="fwe-link fwe-link--btn" data-testid="mark-read"><?php esc_html_e( 'Tout marquer comme lu', 'famille-we' ); ?></button>
					</form>
				<?php endif; ?>
			</div>
			<?php if ( ! $notifications ) : ?>
				<p class="fwe-muted"><?php esc_html_e( 'Rien de nouveau pour le moment.', 'famille-we' ); ?></p>
			<?php else : ?>
				<ul class="fwe-feed">
					<?php foreach ( $notifications as $fwe_n ) : ?>
						<li class="fwe-feed__item<?php echo $fwe_n->is_read ? '' : ' is-unread'; ?> fwe-feed__item--<?php echo esc_attr( $fwe_n->type ); ?>" data-testid="notification-<?php echo esc_attr( $fwe_n->id ); ?>">
							<span class="fwe-feed__icon">
								<?php
								$fwe_icons = array(
									'retard'    => 'alert',
									'rappel'    => 'alert',
									'evenement' => 'calendar',
									'ag'        => 'megaphone',
									'soutien'   => 'heart',
								);
								echo fwe_icon( $fwe_icons[ $fwe_n->type ] ?? 'bell', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
							</span>
							<div>
								<strong><?php echo esc_html( $fwe_n->title ); ?></strong>
								<p><?php echo nl2br( esc_html( $fwe_n->message ) ); ?></p>
								<span class="fwe-muted fwe-xs"><?php echo esc_html( fwe_format_date( $fwe_n->created_at, true ) ); ?></span>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</article>

		<form id="fwe-profil" class="fwe-card fwe-form" method="post" action="<?php echo esc_url( $fwe_post ); ?>" data-testid="profile-form">
			<input type="hidden" name="action" value="fwe_update_profile">
			<?php wp_nonce_field( 'fwe_update_profile' ); ?>
			<h2 class="fwe-h3"><?php esc_html_e( 'Mon profil', 'famille-we' ); ?></h2>
			<div class="fwe-form-grid">
				<label class="fwe-field">
					<span class="fwe-label"><?php esc_html_e( 'Prénom', 'famille-we' ); ?></span>
					<input class="fwe-input" type="text" name="first_name" value="<?php echo esc_attr( $member->first_name ); ?>" data-testid="profile-first-name">
				</label>
				<label class="fwe-field">
					<span class="fwe-label"><?php esc_html_e( 'Nom', 'famille-we' ); ?></span>
					<input class="fwe-input" type="text" name="last_name" value="<?php echo esc_attr( $member->last_name ); ?>" data-testid="profile-last-name">
				</label>
				<label class="fwe-field">
					<span class="fwe-label"><?php esc_html_e( 'Téléphone', 'famille-we' ); ?></span>
					<input class="fwe-input" type="tel" name="phone" value="<?php echo esc_attr( $member->phone ); ?>" data-testid="profile-phone">
				</label>
				<label class="fwe-field">
					<span class="fwe-label"><?php esc_html_e( 'Ville', 'famille-we' ); ?></span>
					<input class="fwe-input" type="text" name="city" value="<?php echo esc_attr( $member->city ); ?>" data-testid="profile-city">
				</label>
			</div>
			<label class="fwe-field">
				<span class="fwe-label"><?php esc_html_e( 'État', 'famille-we' ); ?></span>
				<select class="fwe-input" name="state" data-testid="profile-state">
					<?php foreach ( fwe_labels( 'state' ) as $fwe_key => $fwe_label ) : ?>
						<option value="<?php echo esc_attr( $fwe_key ); ?>" <?php selected( $member->state, $fwe_key ); ?>><?php echo esc_html( $fwe_label ); ?></option>
					<?php endforeach; ?>
				</select>
			</label>
			<p class="fwe-hint">
				<?php
				/* translators: %s: e-mail. */
				printf( esc_html__( 'E-mail de connexion : %s', 'famille-we' ), esc_html( $member->email ) );
				?>
			</p>
			<div class="fwe-form-actions">
				<button type="submit" class="fwe-btn fwe-btn--primary" data-testid="profile-save"><?php esc_html_e( 'Enregistrer', 'famille-we' ); ?></button>
			</div>
		</form>
	</section>
</div>
