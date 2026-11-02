<?php
/**
 * Vue : détail d'un cas de soutien et de ses contributions.
 *
 * @package FamilleWE
 * @var array    $s             Réglages.
 * @var object   $case          Cas.
 * @var array    $totals        Totaux du cas.
 * @var object[] $contributions Contributions.
 * @var object[] $members       Membres actifs.
 */

defined( 'ABSPATH' ) || exit;

$fwe_pct = $case->target_amount > 0 ? min( 100, round( $totals['confirmed'] / $case->target_amount * 100 ) ) : 0;
?>
<header class="fwe-page-head">
	<div>
		<a class="fwe-back" href="<?php echo esc_url( FWE_Admin::url( 'fwe-soutien' ) ); ?>"><?php echo fwe_icon( 'left', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Tous les cas', 'famille-we' ); ?></a>
		<p class="fwe-label"><?php echo esc_html( fwe_label( 'support_type', $case->type ) ); ?> · <?php echo esc_html( fwe_format_date( $case->created_at ) ); ?></p>
		<h1 class="fwe-h1" data-testid="case-title-heading"><?php echo esc_html( $case->title ); ?></h1>
		<p class="fwe-sub">
			<?php
			/* translators: %s: nom du membre. */
			printf( esc_html__( 'Pour %s', 'famille-we' ), esc_html( trim( $case->first_name . ' ' . $case->last_name ) ) );
			?>
		</p>
	</div>
	<div class="fwe-head-actions">
		<?php echo fwe_badge( fwe_label( 'case_status', $case->status ), 'ouvert' === $case->status ? 'success' : 'neutral' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<a class="fwe-btn fwe-btn--outline" href="<?php echo esc_url( FWE_Admin::url( 'fwe-soutien', array( 'edit' => $case->id ) ) . '#fwe-case-form' ); ?>"><?php esc_html_e( 'Modifier', 'famille-we' ); ?></a>
		<form method="post" action="<?php echo esc_url( FWE_Admin::post_url() ); ?>">
			<?php FWE_Admin::form_fields( 'delete_case', 'fwe_delete_case_' . $case->id ); ?>
			<input type="hidden" name="id" value="<?php echo esc_attr( $case->id ); ?>">
			<button type="submit" class="fwe-btn fwe-btn--danger" data-fwe-confirm="<?php esc_attr_e( 'Supprimer ce cas et toutes ses contributions ?', 'famille-we' ); ?>" data-testid="delete-case"><?php esc_html_e( 'Supprimer', 'famille-we' ); ?></button>
		</form>
	</div>
</header>

<div class="fwe-grid fwe-grid--3">
	<div class="fwe-span-2 fwe-stack">
		<section class="fwe-card" data-testid="case-summary">
			<div class="fwe-grid fwe-grid--3 fwe-grid--tight">
				<div>
					<p class="fwe-label"><?php esc_html_e( 'Reçu (confirmé)', 'famille-we' ); ?></p>
					<p class="fwe-figure" data-testid="case-confirmed"><?php echo esc_html( fwe_money( $totals['confirmed'] ) ); ?></p>
				</div>
				<div>
					<p class="fwe-label"><?php esc_html_e( 'Promis (à confirmer)', 'famille-we' ); ?></p>
					<p class="fwe-figure" data-testid="case-declared"><?php echo esc_html( fwe_money( $totals['declared'] ) ); ?></p>
				</div>
				<div>
					<p class="fwe-label"><?php esc_html_e( 'Objectif', 'famille-we' ); ?></p>
					<p class="fwe-figure"><?php echo $case->target_amount > 0 ? esc_html( fwe_money( $case->target_amount ) ) : '—'; ?></p>
				</div>
			</div>
			<?php if ( $case->target_amount > 0 ) : ?>
				<div class="fwe-progress fwe-progress--secondary fwe-progress--lg"><span style="width:<?php echo esc_attr( $fwe_pct ); ?>%"></span></div>
			<?php endif; ?>
			<?php if ( $case->description ) : ?>
				<p class="fwe-quote"><?php echo nl2br( esc_html( $case->description ) ); ?></p>
			<?php endif; ?>
		</section>

		<section class="fwe-card fwe-card--flush">
			<div class="fwe-card__head fwe-card__head--pad"><h2 class="fwe-h3"><?php esc_html_e( 'Contributions', 'famille-we' ); ?></h2></div>
			<?php if ( ! $contributions ) : ?>
				<div class="fwe-empty fwe-empty--pad"><p><?php esc_html_e( 'Aucune contribution pour le moment.', 'famille-we' ); ?></p></div>
			<?php else : ?>
				<div class="fwe-table-wrap">
					<table class="fwe-table" data-testid="contributions-table">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Membre', 'famille-we' ); ?></th>
								<th><?php esc_html_e( 'Montant', 'famille-we' ); ?></th>
								<th><?php esc_html_e( 'Date', 'famille-we' ); ?></th>
								<th><?php esc_html_e( 'Statut', 'famille-we' ); ?></th>
								<th class="fwe-right"><?php esc_html_e( 'Actions', 'famille-we' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $contributions as $fwe_k ) : ?>
								<tr data-testid="contribution-row-<?php echo esc_attr( $fwe_k->id ); ?>">
									<td>
										<strong><?php echo esc_html( $fwe_k->member_id ? trim( $fwe_k->first_name . ' ' . $fwe_k->last_name ) : __( 'Don anonyme / externe', 'famille-we' ) ); ?></strong>
										<?php if ( $fwe_k->note ) : ?>
											<span class="fwe-muted fwe-xs fwe-block"><?php echo esc_html( $fwe_k->note ); ?></span>
										<?php endif; ?>
									</td>
									<td><strong><?php echo esc_html( fwe_money( $fwe_k->amount ) ); ?></strong></td>
									<td><?php echo esc_html( fwe_format_date( $fwe_k->created_at ) ); ?></td>
									<td><?php echo fwe_badge( fwe_label( 'contribution_status', $fwe_k->status ), 'confirmee' === $fwe_k->status ? 'success' : 'warning', 'contribution-status-' . $fwe_k->id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
									<td class="fwe-right">
										<div class="fwe-row-actions">
											<?php if ( 'confirmee' !== $fwe_k->status ) : ?>
												<form method="post" action="<?php echo esc_url( FWE_Admin::post_url() ); ?>">
													<?php FWE_Admin::form_fields( 'confirm_contribution', 'fwe_contribution_' . $fwe_k->id ); ?>
													<input type="hidden" name="id" value="<?php echo esc_attr( $fwe_k->id ); ?>">
													<button type="submit" class="fwe-btn fwe-btn--secondary fwe-btn--sm" data-testid="confirm-contribution-<?php echo esc_attr( $fwe_k->id ); ?>"><?php esc_html_e( 'Confirmer la réception', 'famille-we' ); ?></button>
												</form>
											<?php endif; ?>
											<form method="post" action="<?php echo esc_url( FWE_Admin::post_url() ); ?>">
												<?php FWE_Admin::form_fields( 'delete_contribution', 'fwe_contribution_' . $fwe_k->id ); ?>
												<input type="hidden" name="id" value="<?php echo esc_attr( $fwe_k->id ); ?>">
												<button type="submit" class="fwe-icon-btn fwe-icon-btn--danger" data-fwe-confirm="<?php esc_attr_e( 'Supprimer cette contribution ?', 'famille-we' ); ?>" title="<?php esc_attr_e( 'Supprimer', 'famille-we' ); ?>"><?php echo fwe_icon( 'trash', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="screen-reader-text"><?php esc_html_e( 'Supprimer', 'famille-we' ); ?></span></button>
											</form>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endif; ?>
		</section>
	</div>

	<form class="fwe-card fwe-form fwe-sticky" method="post" action="<?php echo esc_url( FWE_Admin::post_url() ); ?>" data-testid="add-contribution-form">
		<?php FWE_Admin::form_fields( 'add_contribution' ); ?>
		<input type="hidden" name="case_id" value="<?php echo esc_attr( $case->id ); ?>">
		<h2 class="fwe-h3"><?php esc_html_e( 'Enregistrer une contribution', 'famille-we' ); ?></h2>
		<label class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Contributeur', 'famille-we' ); ?></span>
			<select class="fwe-input" name="member_id" data-testid="contribution-member">
				<option value="0"><?php esc_html_e( 'Don anonyme / externe', 'famille-we' ); ?></option>
				<?php foreach ( $members as $fwe_member ) : ?>
					<option value="<?php echo esc_attr( $fwe_member->id ); ?>"><?php echo esc_html( fwe_member_name( $fwe_member ) ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>
		<label class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Montant ($)', 'famille-we' ); ?> *</span>
			<input class="fwe-input" type="text" inputmode="decimal" name="amount" required placeholder="50" data-testid="contribution-amount">
		</label>
		<label class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Statut', 'famille-we' ); ?></span>
			<select class="fwe-input" name="status" data-testid="contribution-status">
				<option value="confirmee"><?php esc_html_e( 'Reçue', 'famille-we' ); ?></option>
				<option value="declaree"><?php esc_html_e( 'Promise (à confirmer)', 'famille-we' ); ?></option>
			</select>
		</label>
		<label class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Note', 'famille-we' ); ?></span>
			<input class="fwe-input" type="text" name="note" data-testid="contribution-note">
		</label>
		<div class="fwe-form-actions">
			<button type="submit" class="fwe-btn fwe-btn--primary" data-testid="contribution-save"><?php esc_html_e( 'Ajouter', 'famille-we' ); ?></button>
		</div>
	</form>
</div>
