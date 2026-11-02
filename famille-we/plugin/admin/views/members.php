<?php
/**
 * Vue : liste des membres.
 *
 * @package FamilleWE
 * @var array    $s       Réglages.
 * @var object[] $members Membres.
 * @var array    $summary Situations de cotisation.
 * @var array    $filters Filtres actifs.
 * @var array    $counts  Membres par statut.
 */

defined( 'ABSPATH' ) || exit;
?>
<header class="fwe-page-head">
	<div>
		<p class="fwe-label"><?php echo esc_html( $s['association_name'] ); ?></p>
		<h1 class="fwe-h1"><?php esc_html_e( 'Membres', 'famille-we' ); ?></h1>
		<p class="fwe-sub">
			<?php
			/* translators: 1: actifs, 2: en attente, 3: inactifs. */
			printf( esc_html__( '%1$d actifs · %2$d en attente · %3$d inactifs', 'famille-we' ), (int) $counts['actif'], (int) $counts['en_attente'], (int) $counts['inactif'] );
			?>
		</p>
	</div>
	<div class="fwe-head-actions">
		<a class="fwe-btn fwe-btn--primary" href="<?php echo esc_url( FWE_Admin::url( 'fwe-membres', array( 'view' => 'new' ) ) ); ?>" data-testid="add-member-button"><?php echo fwe_icon( 'plus', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Ajouter un membre', 'famille-we' ); ?></a>
	</div>
</header>

<form class="fwe-card fwe-filters" method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>" data-testid="members-filters">
	<input type="hidden" name="page" value="fwe-membres">
	<label class="fwe-field fwe-field--grow">
		<span class="fwe-label"><?php esc_html_e( 'Rechercher', 'famille-we' ); ?></span>
		<input class="fwe-input" type="search" name="s" value="<?php echo esc_attr( $filters['search'] ); ?>" placeholder="<?php esc_attr_e( 'Nom, e-mail, téléphone, ville…', 'famille-we' ); ?>" data-testid="members-search-input">
	</label>
	<label class="fwe-field">
		<span class="fwe-label"><?php esc_html_e( 'État', 'famille-we' ); ?></span>
		<select class="fwe-input" name="state" data-testid="members-state-filter">
			<option value=""><?php esc_html_e( 'Texas & Oklahoma', 'famille-we' ); ?></option>
			<?php foreach ( fwe_labels( 'state' ) as $fwe_key => $fwe_label ) : ?>
				<option value="<?php echo esc_attr( $fwe_key ); ?>" <?php selected( $filters['state'], $fwe_key ); ?>><?php echo esc_html( $fwe_label ); ?></option>
			<?php endforeach; ?>
		</select>
	</label>
	<label class="fwe-field">
		<span class="fwe-label"><?php esc_html_e( 'Statut', 'famille-we' ); ?></span>
		<select class="fwe-input" name="status" data-testid="members-status-filter">
			<option value=""><?php esc_html_e( 'Tous', 'famille-we' ); ?></option>
			<?php foreach ( fwe_labels( 'member_status' ) as $fwe_key => $fwe_label ) : ?>
				<option value="<?php echo esc_attr( $fwe_key ); ?>" <?php selected( $filters['status'], $fwe_key ); ?>><?php echo esc_html( $fwe_label ); ?></option>
			<?php endforeach; ?>
		</select>
	</label>
	<button type="submit" class="fwe-btn fwe-btn--secondary" data-testid="members-filter-submit"><?php echo fwe_icon( 'search', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Filtrer', 'famille-we' ); ?></button>
</form>

<div class="fwe-card fwe-card--flush">
	<?php if ( ! $members ) : ?>
		<div class="fwe-empty fwe-empty--pad">
			<?php echo fwe_icon( 'users', 32 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<p><?php esc_html_e( 'Aucun membre ne correspond. Ajoutez le premier membre de la famille !', 'famille-we' ); ?></p>
		</div>
	<?php else : ?>
		<div class="fwe-table-wrap">
			<table class="fwe-table" data-testid="members-table">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Membre', 'famille-we' ); ?></th>
						<th><?php esc_html_e( 'Contact', 'famille-we' ); ?></th>
						<th><?php esc_html_e( 'Localisation', 'famille-we' ); ?></th>
						<th><?php esc_html_e( 'Adhésion', 'famille-we' ); ?></th>
						<th><?php esc_html_e( 'Statut', 'famille-we' ); ?></th>
						<th><?php esc_html_e( 'Cotisations', 'famille-we' ); ?></th>
						<th class="fwe-right"><?php esc_html_e( 'Actions', 'famille-we' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $members as $fwe_member ) : ?>
						<?php $fwe_dues = $summary[ (int) $fwe_member->id ]; ?>
						<tr data-testid="member-row-<?php echo esc_attr( $fwe_member->id ); ?>">
							<td>
								<div class="fwe-person">
									<span class="fwe-avatar"><?php echo esc_html( fwe_initials( $fwe_member ) ); ?></span>
									<div>
										<a class="fwe-strong-link" href="<?php echo esc_url( FWE_Admin::url( 'fwe-membres', array( 'view' => 'edit', 'id' => $fwe_member->id ) ) ); ?>"><?php echo esc_html( fwe_member_name( $fwe_member ) ); ?></a>
										<?php if ( $fwe_member->user_id ) : ?>
											<span class="fwe-muted fwe-xs"><?php esc_html_e( 'Accès en ligne actif', 'famille-we' ); ?></span>
										<?php endif; ?>
									</div>
								</div>
							</td>
							<td>
								<span class="fwe-block"><?php echo esc_html( $fwe_member->email ? $fwe_member->email : '—' ); ?></span>
								<span class="fwe-muted fwe-xs"><?php echo esc_html( $fwe_member->phone ); ?></span>
							</td>
							<td><?php echo esc_html( trim( $fwe_member->city . ', ' . fwe_label( 'state', $fwe_member->state ), ', ' ) ); ?></td>
							<td><?php echo esc_html( fwe_format_date( $fwe_member->join_date ) ); ?></td>
							<td>
								<?php
								$fwe_tone = array(
									'actif'      => 'success',
									'en_attente' => 'warning',
									'inactif'    => 'neutral',
								);
								echo fwe_badge( fwe_label( 'member_status', $fwe_member->status ), $fwe_tone[ $fwe_member->status ] ?? 'neutral', 'member-status-' . $fwe_member->id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
							</td>
							<td><?php echo fwe_dues_badge( $fwe_dues, 'member-dues-' . $fwe_member->id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							<td class="fwe-right">
								<div class="fwe-row-actions">
									<?php if ( 'en_attente' === $fwe_member->status ) : ?>
										<form method="post" action="<?php echo esc_url( FWE_Admin::post_url() ); ?>">
											<?php FWE_Admin::form_fields( 'approve_member', 'fwe_approve_member_' . $fwe_member->id ); ?>
											<input type="hidden" name="id" value="<?php echo esc_attr( $fwe_member->id ); ?>">
											<button type="submit" class="fwe-btn fwe-btn--secondary fwe-btn--sm" data-testid="approve-member-<?php echo esc_attr( $fwe_member->id ); ?>"><?php esc_html_e( 'Valider', 'famille-we' ); ?></button>
										</form>
									<?php endif; ?>
									<a class="fwe-icon-btn" href="<?php echo esc_url( FWE_Admin::url( 'fwe-membres', array( 'view' => 'edit', 'id' => $fwe_member->id ) ) ); ?>" title="<?php esc_attr_e( 'Modifier', 'famille-we' ); ?>" data-testid="edit-member-<?php echo esc_attr( $fwe_member->id ); ?>"><?php echo fwe_icon( 'pencil', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="screen-reader-text"><?php esc_html_e( 'Modifier', 'famille-we' ); ?></span></a>
									<form method="post" action="<?php echo esc_url( FWE_Admin::post_url() ); ?>">
										<?php FWE_Admin::form_fields( 'delete_member', 'fwe_delete_member_' . $fwe_member->id ); ?>
										<input type="hidden" name="id" value="<?php echo esc_attr( $fwe_member->id ); ?>">
										<button type="submit" class="fwe-icon-btn fwe-icon-btn--danger" title="<?php esc_attr_e( 'Supprimer', 'famille-we' ); ?>" data-fwe-confirm="<?php echo esc_attr( sprintf( /* translators: %s: nom. */ __( 'Supprimer définitivement %s et tout son historique (cotisations, contributions, notifications) ? Pour garder l’historique, passez plutôt le membre en « Inactif ».', 'famille-we' ), fwe_member_name( $fwe_member ) ) ); ?>" data-testid="delete-member-<?php echo esc_attr( $fwe_member->id ); ?>"><?php echo fwe_icon( 'trash', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="screen-reader-text"><?php esc_html_e( 'Supprimer', 'famille-we' ); ?></span></button>
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
