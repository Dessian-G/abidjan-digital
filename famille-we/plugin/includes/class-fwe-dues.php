<?php
/**
 * Calcul de la situation de cotisation d'un membre.
 *
 * Règles :
 * - une cotisation est due chaque mois, à partir du plus tardif entre le mois
 *   d'adhésion et le « début du suivi » (réglage), jusqu'au mois courant inclus ;
 * - le mois courant est « à payer » mais pas encore « en retard » ;
 * - un mois passé non payé est « en retard » ;
 * - au-delà du seuil (3 mois par défaut, strictement supérieur), la situation
 *   devient « critique » et déclenche l'alerte automatique.
 *
 * @package FamilleWE
 */

defined( 'ABSPATH' ) || exit;

/**
 * Situation de cotisation.
 */
class FWE_Dues {

	/**
	 * Mois à partir duquel la cotisation est due pour ce membre.
	 *
	 * @param object $member Membre.
	 */
	public static function start_period_for( $member ) {
		$start = fwe_setting( 'start_period' );
		$start = fwe_valid_period( $start ) ? $start : fwe_current_period();
		if ( ! empty( $member->join_date ) && preg_match( '/^\d{4}-\d{2}/', $member->join_date ) ) {
			$joined = substr( $member->join_date, 0, 7 );
			if ( fwe_valid_period( $joined ) && $joined > $start ) {
				$start = $joined;
			}
		}
		return $start;
	}

	/**
	 * Situation d'un membre.
	 *
	 * @param object   $member       Membre.
	 * @param string[] $paid_periods Périodes payées.
	 * @return array
	 */
	public static function compute( $member, array $paid_periods ) {
		$current   = fwe_current_period();
		$amount    = (float) fwe_setting( 'monthly_amount' );
		$threshold = max( 1, (int) fwe_setting( 'late_threshold' ) );
		$start     = self::start_period_for( $member );
		$paid_set  = array_flip( $paid_periods );

		if ( 'actif' !== $member->status ) {
			return array(
				'start'        => $start,
				'unpaid'       => array(),
				'late'         => array(),
				'months_late'  => 0,
				'amount_due'   => 0.0,
				'amount_late'  => 0.0,
				'state'        => 'inactif',
				'current_paid' => isset( $paid_set[ $current ] ),
			);
		}

		$due    = fwe_periods_between( $start, $current );
		$unpaid = array_values(
			array_filter(
				$due,
				function ( $p ) use ( $paid_set ) {
					return ! isset( $paid_set[ $p ] );
				}
			)
		);
		$late   = array_values(
			array_filter(
				$unpaid,
				function ( $p ) use ( $current ) {
					return $p < $current;
				}
			)
		);
		$months_late = count( $late );

		if ( 0 === $months_late ) {
			$state = 'a_jour';
		} elseif ( $months_late > $threshold ) {
			$state = 'critique';
		} else {
			$state = 'en_retard';
		}

		return array(
			'start'        => $start,
			'unpaid'       => $unpaid,
			'late'         => $late,
			'months_late'  => $months_late,
			'amount_due'   => count( $unpaid ) * $amount,
			'amount_late'  => $months_late * $amount,
			'state'        => $state,
			'current_paid' => isset( $paid_set[ $current ] ),
		);
	}

	/**
	 * Situation de plusieurs membres en une seule requête.
	 *
	 * @param object[] $members Membres.
	 * @return array member_id => situation.
	 */
	public static function summary_for( array $members ) {
		$paid = FWE_Repo::paid_periods_by_member( wp_list_pluck( $members, 'id' ) );
		$out  = array();
		foreach ( $members as $member ) {
			$out[ (int) $member->id ] = self::compute( $member, isset( $paid[ (int) $member->id ] ) ? $paid[ (int) $member->id ] : array() );
		}
		return $out;
	}

	/**
	 * Situation d'un seul membre.
	 *
	 * @param object $member Membre.
	 */
	public static function for_member( $member ) {
		$summary = self::summary_for( array( $member ) );
		return $summary[ (int) $member->id ];
	}

	/**
	 * Liste lisible des mois : « août, sept., oct. 2026 ».
	 *
	 * @param string[] $periods Périodes.
	 * @param int      $max     Nombre maximum affiché.
	 */
	public static function periods_text( array $periods, $max = 6 ) {
		$labels = array_map(
			function ( $p ) {
				return fwe_period_label( $p, true );
			},
			array_slice( $periods, 0, $max )
		);
		$text   = implode( ', ', $labels );
		if ( count( $periods ) > $max ) {
			/* translators: %d: nombre de mois supplémentaires. */
			$text .= ' ' . sprintf( __( 'et %d autres', 'famille-we' ), count( $periods ) - $max );
		}
		return $text;
	}
}
