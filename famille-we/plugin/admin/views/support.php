<?php
/**
 * Vue : cas de soutien (naissance, mariage, décès).
 *
 * @package FamilleWE
 * @var array       $s       Réglages.
 * @var object[]    $cases   Cas.
 * @var array       $totals  Totaux par cas.
 * @var object[]    $members Membres actifs.
 * @var object|null $edit    Cas en cours de modification.
 */

defined( 'ABSPATH' ) || exit;

$fwe_c = $edit ? $edit : (object) array(
	'id'            => 0,
	'member_id'     => 0,
	'type'          => 'naissance',
	'title'         => '',
	'description'   => '',
	'target_amount' => '',
	'status'        => 'ouvert',
);
?>
<header class="fwe-page-head">
	<div>
		<p class="fwe-label"><?php esc_html_e( 'Solidarité', 'famille-we' ); ?></p>
		<h1 class="fwe-h1"><?php esc_html_e( 'Soutien', 'famille-we' ); ?></h1>
		<p class="fwe-sub"><?php esc_html_e( 'Dans la joie comme dans la peine : naissances, mariages et décès.', 'famille-we' ); ?></p>
	</div>
</header>

<div class="fwe-grid fwe-grid--3">
	<section class="fwe-span-2">
		<?php if ( ! $cases ) : ?>
			<div class="fwe-card fwe-empty fwe-empty--pad">
				<?php echo fwe_icon( 'heart', 32 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<p><?php esc_html_e( 'Aucun cas de soutien pour le moment.', 'famille-we' ); ?></p>
			</div>
		<?php else : ?>
			<div class="fwe-grid fwe-grid--2 fwe-grid--tight">
				<?php foreach ( $cases as $fwe_case ) : ?>
					<?php
					$fwe_t   = $totals[ (int) $fwe_case->id ];
					$fwe_pct = $fwe_case->target_amount > 0 ? min( 100, round( $fwe_t['confirmed'] / $fwe_case->target_amount * 100 ) ) : 0;
					?>
					<article class="fwe-card fwe-case fwe-case--<?php echo esc_attr( $fwe_case->type ); ?><?php echo 'clos' === $fwe_case->status ? ' is-closed' : ''; ?>" data-testid="case-card-<?php echo esc_attr( $fwe_case->id ); ?>">
						<div class="fwe-case__head">
							<span class="fwe-support-icon fwe-support-icon--<?php echo esc_attr( $fwe_case->type ); ?>"><?php echo fwe_support_icon( $fwe_case->type ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<div>
								<p class="fwe-label"><?php echo esc_html( fwe_label( 'support_type', $fwe_case->type ) ); ?></p>
								<h3 class="fwe-h3"><a href="<?php echo esc_url( FWE_Admin::url( 'fwe-soutien', array( 'case' => $fwe_case->id ) ) ); ?>"><?php echo esc_html( $fwe_case->title ); ?></a></h3>
							</div>
							<?php echo fwe_badge( fwe_label( 'case_status', $fwe_case->status ), 'ouvert' === $fwe_case->status ? 'success' : 'neutral' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
						<p class="fwe-figure"><?php echo esc_html( fwe_money( $fwe_t['confirmed'] ) ); ?>
							<?php if ( $fwe_case->target_amount > 0 ) : ?>
								<span class="fwe-muted fwe-sm">/ <?php echo esc_html( fwe_money( $fwe_case->target_amount ) ); ?></span>
							<?php endif; ?>
						</p>
						<?php if ( $fwe_case->target_amount > 0 ) : ?>
							<div class="fwe-progress fwe-progress--secondary"><span style="width:<?php echo esc_attr( $fwe_pct ); ?>%"></span></div>
						<?php endif; ?>
						<p class="fwe-muted fwe-xs">
							<?php
							/* translators: %d: nombre de contributions. */
							echo esc_html( sprintf( _n( '%d contribution', '%d contributions', $fwe_t['count'], 'famille-we' ), $fwe_t['count'] ) );
							if ( $fwe_t['declared'] > 0 ) {
								/* translators: %s: montant. */
								echo ' · ' . esc_html( sprintf( __( '%s à confirmer', 'famille-we' ), fwe_money( $fwe_t['declared'] ) ) );
							}
							?>
						</p>
						<div class="fwe-case__actions">
							<a class="fwe-btn fwe-btn--outline fwe-btn--sm" href="<?php echo esc_url( FWE_Admin::url( 'fwe-soutien', array( 'case' => $fwe_case->id ) ) ); ?>" data-testid="open-case-<?php echo esc_attr( $fwe_case->id ); ?>"><?php esc_html_e( 'Contributions', 'famille-we' ); ?></a>
							<a class="fwe-icon-btn" href="<?php echo esc_url( FWE_Admin::url( 'fwe-soutien', array( 'edit' => $fwe_case->id ) ) . '#fwe-case-form' ); ?>" title="<?php esc_attr_e( 'Modifier', 'famille-we' ); ?>"><?php echo fwe_icon( 'pencil', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="screen-reader-text"><?php esc_html_e( 'Modifier', 'famille-we' ); ?></span></a>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</section>

	<form id="fwe-case-form" class="fwe-card fwe-form fwe-sticky" method="post" action="<?php echo esc_url( FWE_Admin::post_url() ); ?>" data-testid="case-form">
		<?php FWE_Admin::form_fields( 'save_case' ); ?>
		<input type="hidden" name="id" value="<?php echo esc_attr( $fwe_c->id ); ?>">
		<h2 class="fwe-h3"><?php echo $edit ? esc_html__( 'Modifier le cas', 'famille-we' ) : esc_html__( 'Ouvrir un cas de soutien', 'famille-we' ); ?></h2>
		<div class="fwe-segmented" role="radiogroup" aria-label="<?php esc_attr_e( 'Type', 'famille-we' ); ?>">
			<?php foreach ( fwe_labels( 'support_type' ) as $fwe_key => $fwe_label ) : ?>
				<label class="fwe-segmented__item">
					<input type="radio" name="type" value="<?php echo esc_attr( $fwe_key ); ?>" <?php checked( $fwe_c->type, $fwe_key ); ?> data-testid="case-type-<?php echo esc_attr( $fwe_key ); ?>">
					<span><?php echo fwe_support_icon( $fwe_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $fwe_label ); ?></span>
				</label>
			<?php endforeach; ?>
		</div>
		<label class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Membre concerné', 'famille-we' ); ?> *</span>
			<select class="fwe-input" name="member_id" required data-testid="case-member">
				<option value=""><?php esc_html_e( '— Choisir —', 'famille-we' ); ?></option>
				<?php foreach ( $members as $fwe_member ) : ?>
					<option value="<?php echo esc_attr( $fwe_member->id ); ?>" <?php selected( (int) $fwe_c->member_id, (int) $fwe_member->id ); ?>><?php echo esc_html( fwe_member_name( $fwe_member ) ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>
		<label class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Titre', 'famille-we' ); ?></span>
			<input class="fwe-input" type="text" name="title" value="<?php echo esc_attr( $fwe_c->title ); ?>" placeholder="<?php esc_attr_e( 'Automatique si vide', 'famille-we' ); ?>" data-testid="case-title">
		</label>
		<label class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Message à la famille', 'famille-we' ); ?></span>
			<textarea class="fwe-input" name="description" rows="3" data-testid="case-description"><?php echo esc_textarea( $fwe_c->description ); ?></textarea>
		</label>
		<div class="fwe-form-grid">
			<label class="fwe-field">
				<span class="fwe-label"><?php esc_html_e( 'Objectif ($)', 'famille-we' ); ?></span>
				<input class="fwe-input" type="text" inputmode="decimal" name="target_amount" value="<?php echo esc_attr( $fwe_c->target_amount > 0 ? $fwe_c->target_amount : '' ); ?>" placeholder="500" data-testid="case-target">
			</label>
			<label class="fwe-field">
				<span class="fwe-label"><?php esc_html_e( 'Statut', 'famille-we' ); ?></span>
				<select class="fwe-input" name="status" data-testid="case-status">
					<?php foreach ( fwe_labels( 'case_status' ) as $fwe_key => $fwe_label ) : ?>
						<option value="<?php echo esc_attr( $fwe_key ); ?>" <?php selected( $fwe_c->status, $fwe_key ); ?>><?php echo esc_html( $fwe_label ); ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</div>
		<?php if ( ! $edit ) : ?>
			<label class="fwe-check">
				<input type="checkbox" name="notify" value="1" checked data-testid="case-notify">
				<span><?php esc_html_e( 'Informer les membres et les inviter à contribuer', 'famille-we' ); ?></span>
			</label>
		<?php endif; ?>
		<div class="fwe-form-actions">
			<button type="submit" class="fwe-btn fwe-btn--primary" data-testid="case-save"><?php echo $edit ? esc_html__( 'Enregistrer', 'famille-we' ) : esc_html__( 'Ouvrir le cas', 'famille-we' ); ?></button>
			<?php if ( $edit ) : ?>
				<a class="fwe-btn fwe-btn--ghost" href="<?php echo esc_url( FWE_Admin::url( 'fwe-soutien' ) ); ?>"><?php esc_html_e( 'Annuler', 'famille-we' ); ?></a>
			<?php endif; ?>
		</div>
	</form>
</div>
