<?php
/**
 * Vue : événements (festivals, AG, événements).
 *
 * @package FamilleWE
 * @var array       $s        Réglages.
 * @var object|null $event    Événement en cours de modification.
 * @var object[]    $upcoming Événements à venir.
 * @var object[]    $past     Événements passés.
 */

defined( 'ABSPATH' ) || exit;

$fwe_e = $event ? $event : (object) array(
	'id'          => 0,
	'title'       => '',
	'type'        => 'general',
	'starts_at'   => '',
	'location'    => '',
	'description' => '',
	'image_url'   => '',
);
$fwe_dt = $fwe_e->starts_at ? str_replace( ' ', 'T', substr( $fwe_e->starts_at, 0, 16 ) ) : '';

$fwe_render_list = function ( $events, $empty ) {
	if ( ! $events ) {
		echo '<p class="fwe-muted">' . esc_html( $empty ) . '</p>';
		return;
	}
	echo '<div class="fwe-event-list">';
	foreach ( $events as $ev ) {
		$img = $ev->image_url ? $ev->image_url : FWE_Assets::image( 'event' );
		?>
		<article class="fwe-event-row" data-testid="event-row-<?php echo esc_attr( $ev->id ); ?>">
			<div class="fwe-event-row__img" style="background-image:url('<?php echo esc_url( $img ); ?>')"></div>
			<div class="fwe-event-row__body">
				<div class="fwe-event-row__meta">
					<?php echo fwe_badge( fwe_label( 'event_type', $ev->type ), 'ag' === $ev->type ? 'primary' : ( 'festival' === $ev->type ? 'warning' : 'secondary' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span class="fwe-muted fwe-xs"><?php echo fwe_icon( 'clock', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( fwe_format_date( $ev->starts_at, true ) ); ?></span>
					<?php if ( $ev->location ) : ?>
						<span class="fwe-muted fwe-xs"><?php echo fwe_icon( 'pin', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $ev->location ); ?></span>
					<?php endif; ?>
				</div>
				<h3 class="fwe-h3"><?php echo esc_html( $ev->title ); ?></h3>
				<?php if ( $ev->description ) : ?>
					<p class="fwe-muted"><?php echo esc_html( wp_trim_words( $ev->description, 28 ) ); ?></p>
				<?php endif; ?>
			</div>
			<div class="fwe-row-actions">
				<a class="fwe-icon-btn" href="<?php echo esc_url( FWE_Admin::url( 'fwe-evenements', array( 'edit' => $ev->id ) ) . '#fwe-event-form' ); ?>" title="<?php esc_attr_e( 'Modifier', 'famille-we' ); ?>" data-testid="edit-event-<?php echo esc_attr( $ev->id ); ?>"><?php echo fwe_icon( 'pencil', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="screen-reader-text"><?php esc_html_e( 'Modifier', 'famille-we' ); ?></span></a>
				<form method="post" action="<?php echo esc_url( FWE_Admin::post_url() ); ?>">
					<?php FWE_Admin::form_fields( 'delete_event', 'fwe_delete_event_' . $ev->id ); ?>
					<input type="hidden" name="id" value="<?php echo esc_attr( $ev->id ); ?>">
					<button type="submit" class="fwe-icon-btn fwe-icon-btn--danger" data-fwe-confirm="<?php esc_attr_e( 'Supprimer cet événement ?', 'famille-we' ); ?>" title="<?php esc_attr_e( 'Supprimer', 'famille-we' ); ?>" data-testid="delete-event-<?php echo esc_attr( $ev->id ); ?>"><?php echo fwe_icon( 'trash', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="screen-reader-text"><?php esc_html_e( 'Supprimer', 'famille-we' ); ?></span></button>
				</form>
			</div>
		</article>
		<?php
	}
	echo '</div>';
};
?>
<header class="fwe-page-head">
	<div>
		<p class="fwe-label"><?php esc_html_e( 'Vie de la famille', 'famille-we' ); ?></p>
		<h1 class="fwe-h1"><?php esc_html_e( 'Événements', 'famille-we' ); ?></h1>
		<p class="fwe-sub"><?php esc_html_e( 'Festivals, assemblées générales et rencontres de la famille.', 'famille-we' ); ?></p>
	</div>
</header>

<div class="fwe-grid fwe-grid--3">
	<div class="fwe-span-2 fwe-stack">
		<section class="fwe-card">
			<h2 class="fwe-h3"><?php esc_html_e( 'À venir', 'famille-we' ); ?></h2>
			<?php $fwe_render_list( $upcoming, __( 'Aucun événement à venir.', 'famille-we' ) ); ?>
		</section>
		<section class="fwe-card">
			<h2 class="fwe-h3"><?php esc_html_e( 'Passés', 'famille-we' ); ?></h2>
			<?php $fwe_render_list( $past, __( 'Aucun événement passé.', 'famille-we' ) ); ?>
		</section>
	</div>

	<form id="fwe-event-form" class="fwe-card fwe-form fwe-sticky" method="post" action="<?php echo esc_url( FWE_Admin::post_url() ); ?>" data-testid="event-form">
		<?php FWE_Admin::form_fields( 'save_event' ); ?>
		<input type="hidden" name="id" value="<?php echo esc_attr( $fwe_e->id ); ?>">
		<h2 class="fwe-h3"><?php echo $event ? esc_html__( 'Modifier l’événement', 'famille-we' ) : esc_html__( 'Nouvel événement', 'famille-we' ); ?></h2>
		<label class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Titre', 'famille-we' ); ?> *</span>
			<input class="fwe-input" type="text" name="title" value="<?php echo esc_attr( $fwe_e->title ); ?>" required placeholder="<?php esc_attr_e( 'AG annuelle 2026', 'famille-we' ); ?>" data-testid="event-title">
		</label>
		<label class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Type', 'famille-we' ); ?></span>
			<select class="fwe-input" name="type" data-testid="event-type">
				<?php foreach ( fwe_labels( 'event_type' ) as $fwe_key => $fwe_label ) : ?>
					<option value="<?php echo esc_attr( $fwe_key ); ?>" <?php selected( $fwe_e->type, $fwe_key ); ?>><?php echo esc_html( $fwe_label ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>
		<label class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Date et heure', 'famille-we' ); ?> *</span>
			<input class="fwe-input" type="datetime-local" name="starts_at" value="<?php echo esc_attr( $fwe_dt ); ?>" required data-testid="event-date">
		</label>
		<label class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Lieu', 'famille-we' ); ?></span>
			<input class="fwe-input" type="text" name="location" value="<?php echo esc_attr( $fwe_e->location ); ?>" placeholder="Irving, TX" data-testid="event-location">
		</label>
		<label class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Description / ordre du jour', 'famille-we' ); ?></span>
			<textarea class="fwe-input" name="description" rows="4" data-testid="event-description"><?php echo esc_textarea( $fwe_e->description ); ?></textarea>
		</label>
		<div class="fwe-field">
			<span class="fwe-label"><?php esc_html_e( 'Image (facultatif)', 'famille-we' ); ?></span>
			<div class="fwe-inline">
				<input class="fwe-input" type="url" name="image_url" value="<?php echo esc_attr( $fwe_e->image_url ); ?>" placeholder="https://…" data-fwe-media-target data-testid="event-image">
				<button type="button" class="fwe-btn fwe-btn--outline fwe-btn--sm" data-fwe-media data-testid="event-image-pick"><?php esc_html_e( 'Médiathèque', 'famille-we' ); ?></button>
			</div>
		</div>
		<label class="fwe-check">
			<input type="checkbox" name="notify" value="1" <?php checked( ! $event ); ?> data-testid="event-notify">
			<span><?php esc_html_e( 'Prévenir tous les membres actifs (espace membre + e-mail)', 'famille-we' ); ?></span>
		</label>
		<div class="fwe-form-actions">
			<button type="submit" class="fwe-btn fwe-btn--primary" data-testid="event-save"><?php echo $event ? esc_html__( 'Enregistrer', 'famille-we' ) : esc_html__( 'Publier l’événement', 'famille-we' ); ?></button>
			<?php if ( $event ) : ?>
				<a class="fwe-btn fwe-btn--ghost" href="<?php echo esc_url( FWE_Admin::url( 'fwe-evenements' ) ); ?>"><?php esc_html_e( 'Annuler', 'famille-we' ); ?></a>
			<?php endif; ?>
		</div>
	</form>
</div>
