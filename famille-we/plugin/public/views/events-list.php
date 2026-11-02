<?php
/**
 * Vue publique : cartes des événements à venir.
 *
 * @package FamilleWE
 * @var object[] $events Événements.
 */

defined( 'ABSPATH' ) || exit;

if ( ! $events ) : ?>
	<div class="fwe-card fwe-empty fwe-empty--pad">
		<?php echo fwe_icon( 'calendar', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<p><?php esc_html_e( 'Aucun événement programmé pour le moment.', 'famille-we' ); ?></p>
	</div>
<?php else : ?>
	<div class="fwe-grid fwe-grid--3" data-testid="events-list">
		<?php foreach ( $events as $fwe_ev ) : ?>
			<?php $fwe_ts = strtotime( $fwe_ev->starts_at ); ?>
			<article class="fwe-card fwe-event-card fwe-event-card--<?php echo esc_attr( $fwe_ev->type ); ?>" data-testid="event-card-<?php echo esc_attr( $fwe_ev->id ); ?>">
				<div class="fwe-event-card__img" style="background-image:url('<?php echo esc_url( $fwe_ev->image_url ? $fwe_ev->image_url : FWE_Assets::image( 'event' ) ); ?>')">
					<span class="fwe-date-chip fwe-date-chip--float"><strong><?php echo esc_html( gmdate( 'j', $fwe_ts ) ); ?></strong><?php echo esc_html( fwe_month_names( true )[ (int) gmdate( 'n', $fwe_ts ) ] ); ?></span>
				</div>
				<div class="fwe-event-card__body">
					<?php echo fwe_badge( fwe_label( 'event_type', $fwe_ev->type ), 'ag' === $fwe_ev->type ? 'primary' : ( 'festival' === $fwe_ev->type ? 'warning' : 'secondary' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<h3 class="fwe-h3"><?php echo esc_html( $fwe_ev->title ); ?></h3>
					<p class="fwe-muted fwe-sm fwe-meta-line"><?php echo fwe_icon( 'clock', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( fwe_format_date( $fwe_ev->starts_at, true ) ); ?></p>
					<?php if ( $fwe_ev->location ) : ?>
						<p class="fwe-muted fwe-sm fwe-meta-line"><?php echo fwe_icon( 'pin', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $fwe_ev->location ); ?></p>
					<?php endif; ?>
					<?php if ( $fwe_ev->description ) : ?>
						<p class="fwe-sm"><?php echo esc_html( wp_trim_words( $fwe_ev->description, 26 ) ); ?></p>
					<?php endif; ?>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
