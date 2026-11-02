<?php
/**
 * Accès aux données (tables wp_fwe_*).
 *
 * Requêtes volontairement simples (pas de fonctions SQL propres à MySQL) pour
 * rester compatibles avec MySQL/MariaDB comme avec l'intégration SQLite.
 *
 * @package FamilleWE
 */

defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery -- noms de tables internes, valeurs préparées.

/**
 * Dépôt de données du plugin.
 */
class FWE_Repo {

	/**
	 * Nom complet d'une table.
	 *
	 * @param string $name Nom court.
	 */
	public static function t( $name ) {
		global $wpdb;
		return $wpdb->prefix . 'fwe_' . $name;
	}

	/** Date/heure courante du site. */
	public static function now() {
		return current_time( 'mysql' );
	}

	/**
	 * Placeholders %d pour une clause IN.
	 *
	 * @param array $ids Identifiants.
	 */
	private static function in_ids( array $ids ) {
		return implode( ',', array_fill( 0, count( $ids ), '%d' ) );
	}

	/* ---------------------------------------------------------------------
	 * Membres
	 * ------------------------------------------------------------------ */

	/**
	 * Membre par identifiant.
	 *
	 * @param int $id Identifiant.
	 */
	public static function get_member( $id ) {
		global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . self::t( 'members' ) . ' WHERE id = %d', $id ) );
	}

	/**
	 * Membre rattaché à un utilisateur WordPress.
	 *
	 * @param int $user_id Utilisateur.
	 */
	public static function get_member_by_user( $user_id ) {
		global $wpdb;
		if ( ! $user_id ) {
			return null;
		}
		return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . self::t( 'members' ) . ' WHERE user_id = %d ORDER BY id ASC LIMIT 1', $user_id ) );
	}

	/**
	 * Membre par e-mail.
	 *
	 * @param string $email E-mail.
	 */
	public static function get_member_by_email( $email ) {
		global $wpdb;
		if ( ! $email ) {
			return null;
		}
		return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . self::t( 'members' ) . ' WHERE email = %s ORDER BY id ASC LIMIT 1', strtolower( $email ) ) );
	}

	/**
	 * Liste filtrée des membres.
	 *
	 * @param array $args status, state, search.
	 */
	public static function list_members( array $args = array() ) {
		global $wpdb;
		$args   = wp_parse_args(
			$args,
			array(
				'status' => '',
				'state'  => '',
				'search' => '',
			)
		);
		$where  = array( '1=1' );
		$params = array();

		if ( $args['status'] ) {
			$where[]  = 'status = %s';
			$params[] = $args['status'];
		}
		if ( $args['state'] ) {
			$where[]  = 'state = %s';
			$params[] = $args['state'];
		}
		if ( '' !== $args['search'] ) {
			$like    = '%' . $wpdb->esc_like( $args['search'] ) . '%';
			$where[] = '(first_name LIKE %s OR last_name LIKE %s OR email LIKE %s OR phone LIKE %s OR city LIKE %s)';
			array_push( $params, $like, $like, $like, $like, $like );
		}

		$sql = 'SELECT * FROM ' . self::t( 'members' ) . ' WHERE ' . implode( ' AND ', $where ) . ' ORDER BY last_name ASC, first_name ASC';
		return $params ? $wpdb->get_results( $wpdb->prepare( $sql, $params ) ) : $wpdb->get_results( $sql );
	}

	/** Nombre de membres par statut. */
	public static function count_members_by_status() {
		global $wpdb;
		$out  = array(
			'actif'      => 0,
			'en_attente' => 0,
			'inactif'    => 0,
		);
		$rows = $wpdb->get_results( 'SELECT status, COUNT(*) AS c FROM ' . self::t( 'members' ) . ' GROUP BY status' );
		foreach ( (array) $rows as $row ) {
			$out[ $row->status ] = (int) $row->c;
		}
		return $out;
	}

	/**
	 * Crée ou met à jour un membre.
	 *
	 * @param array $data Données.
	 * @param int   $id   Identifiant (0 = création).
	 */
	public static function save_member( array $data, $id = 0 ) {
		global $wpdb;
		$fields = array( 'user_id', 'first_name', 'last_name', 'email', 'phone', 'city', 'state', 'join_date', 'status', 'notes' );
		$row    = array_intersect_key( $data, array_flip( $fields ) );
		if ( isset( $row['email'] ) ) {
			$row['email'] = strtolower( $row['email'] );
		}
		if ( $id ) {
			$wpdb->update( self::t( 'members' ), $row, array( 'id' => $id ) );
			return (int) $id;
		}
		$row['created_at'] = self::now();
		$wpdb->insert( self::t( 'members' ), $row );
		return (int) $wpdb->insert_id;
	}

	/**
	 * Supprime un membre et toutes ses données liées.
	 *
	 * @param int $id Identifiant.
	 */
	public static function delete_member( $id ) {
		global $wpdb;
		$case_ids = $wpdb->get_col( $wpdb->prepare( 'SELECT id FROM ' . self::t( 'support_cases' ) . ' WHERE member_id = %d', $id ) );
		foreach ( $case_ids as $case_id ) {
			self::delete_case( (int) $case_id );
		}
		$wpdb->delete( self::t( 'cotisations' ), array( 'member_id' => $id ) );
		$wpdb->delete( self::t( 'contributions' ), array( 'member_id' => $id ) );
		$wpdb->delete( self::t( 'notifications' ), array( 'member_id' => $id ) );
		$wpdb->delete( self::t( 'members' ), array( 'id' => $id ) );
	}

	/* ---------------------------------------------------------------------
	 * Cotisations
	 * ------------------------------------------------------------------ */

	/**
	 * Périodes payées, groupées par membre.
	 *
	 * @param int[]|null $member_ids Restreindre à ces membres (null = tous).
	 * @return array member_id => string[] périodes.
	 */
	public static function paid_periods_by_member( $member_ids = null ) {
		global $wpdb;
		$sql = 'SELECT member_id, period FROM ' . self::t( 'cotisations' );
		if ( is_array( $member_ids ) ) {
			$member_ids = array_values( array_filter( array_map( 'absint', $member_ids ) ) );
			if ( ! $member_ids ) {
				return array();
			}
			$rows = $wpdb->get_results( $wpdb->prepare( $sql . ' WHERE member_id IN (' . self::in_ids( $member_ids ) . ')', $member_ids ) );
		} else {
			$rows = $wpdb->get_results( $sql );
		}
		$out = array();
		foreach ( (array) $rows as $row ) {
			$out[ (int) $row->member_id ][] = $row->period;
		}
		return $out;
	}

	/**
	 * Paiements d'un membre, du plus récent au plus ancien.
	 *
	 * @param int $member_id Membre.
	 */
	public static function payments_for_member( $member_id ) {
		global $wpdb;
		return $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . self::t( 'cotisations' ) . ' WHERE member_id = %d ORDER BY period DESC', $member_id ) );
	}

	/**
	 * Paiement d'un mois donné.
	 *
	 * @param int    $member_id Membre.
	 * @param string $period    Période.
	 */
	public static function get_payment( $member_id, $period ) {
		global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . self::t( 'cotisations' ) . ' WHERE member_id = %d AND period = %s', $member_id, $period ) );
	}

	/**
	 * Enregistre la cotisation d'un mois (ignorée si déjà payée).
	 *
	 * @param int    $member_id Membre.
	 * @param string $period    Période.
	 * @param float  $amount    Montant.
	 * @param string $paid_at   Date de paiement.
	 * @param string $method    Moyen de paiement.
	 * @param string $note      Note.
	 * @return bool Vrai si un paiement a été ajouté.
	 */
	public static function record_payment( $member_id, $period, $amount, $paid_at, $method = 'zelle', $note = '' ) {
		global $wpdb;
		if ( ! fwe_valid_period( $period ) || self::get_payment( $member_id, $period ) ) {
			return false;
		}
		return (bool) $wpdb->insert(
			self::t( 'cotisations' ),
			array(
				'member_id'   => (int) $member_id,
				'period'      => $period,
				'amount'      => (float) $amount,
				'paid_at'     => $paid_at,
				'method'      => $method,
				'note'        => $note,
				'recorded_by' => get_current_user_id() ? get_current_user_id() : null,
				'created_at'  => self::now(),
			)
		);
	}

	/**
	 * Annule la cotisation d'un mois.
	 *
	 * @param int    $member_id Membre.
	 * @param string $period    Période.
	 */
	public static function delete_payment( $member_id, $period ) {
		global $wpdb;
		return (bool) $wpdb->delete(
			self::t( 'cotisations' ),
			array(
				'member_id' => (int) $member_id,
				'period'    => $period,
			)
		);
	}

	/**
	 * Paiements d'une année : member_id => period => ligne.
	 *
	 * @param int $year Année.
	 */
	public static function payments_in_year( $year ) {
		global $wpdb;
		$rows = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . self::t( 'cotisations' ) . ' WHERE period >= %s AND period <= %s', sprintf( '%04d-01', $year ), sprintf( '%04d-12', $year ) ) );
		$out  = array();
		foreach ( (array) $rows as $row ) {
			$out[ (int) $row->member_id ][ $row->period ] = $row;
		}
		return $out;
	}

	/**
	 * Total encaissé par mois de cotisation.
	 *
	 * @param string $from Période de début.
	 * @param string $to   Période de fin.
	 * @return array period => total.
	 */
	public static function totals_by_period( $from, $to ) {
		global $wpdb;
		$rows = $wpdb->get_results( $wpdb->prepare( 'SELECT period, SUM(amount) AS total FROM ' . self::t( 'cotisations' ) . ' WHERE period >= %s AND period <= %s GROUP BY period', $from, $to ) );
		$out  = array();
		foreach ( (array) $rows as $row ) {
			$out[ $row->period ] = (float) $row->total;
		}
		return $out;
	}

	/**
	 * Somme encaissée sur une plage de périodes.
	 *
	 * @param string $from Début.
	 * @param string $to   Fin.
	 */
	public static function sum_payments( $from, $to ) {
		return array_sum( self::totals_by_period( $from, $to ) );
	}

	/* ---------------------------------------------------------------------
	 * Événements
	 * ------------------------------------------------------------------ */

	/**
	 * Liste d'événements.
	 *
	 * @param string $when  upcoming|past|all.
	 * @param int    $limit Limite (0 = aucune).
	 */
	public static function list_events( $when = 'upcoming', $limit = 0 ) {
		global $wpdb;
		$today = current_time( 'Y-m-d' ) . ' 00:00:00';
		$table = self::t( 'events' );
		if ( 'upcoming' === $when ) {
			$sql = $wpdb->prepare( "SELECT * FROM $table WHERE starts_at >= %s ORDER BY starts_at ASC", $today );
		} elseif ( 'past' === $when ) {
			$sql = $wpdb->prepare( "SELECT * FROM $table WHERE starts_at < %s ORDER BY starts_at DESC", $today );
		} else {
			$sql = "SELECT * FROM $table ORDER BY starts_at DESC";
		}
		if ( $limit ) {
			$sql .= ' LIMIT ' . absint( $limit );
		}
		return $wpdb->get_results( $sql );
	}

	/**
	 * Événement par identifiant.
	 *
	 * @param int $id Identifiant.
	 */
	public static function get_event( $id ) {
		global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . self::t( 'events' ) . ' WHERE id = %d', $id ) );
	}

	/**
	 * Crée ou met à jour un événement.
	 *
	 * @param array $data Données.
	 * @param int   $id   Identifiant.
	 */
	public static function save_event( array $data, $id = 0 ) {
		global $wpdb;
		$row = array_intersect_key( $data, array_flip( array( 'title', 'type', 'starts_at', 'location', 'description', 'image_url' ) ) );
		if ( $id ) {
			$wpdb->update( self::t( 'events' ), $row, array( 'id' => $id ) );
			return (int) $id;
		}
		$row['created_by'] = get_current_user_id();
		$row['created_at'] = self::now();
		$wpdb->insert( self::t( 'events' ), $row );
		return (int) $wpdb->insert_id;
	}

	/**
	 * Supprime un événement.
	 *
	 * @param int $id Identifiant.
	 */
	public static function delete_event( $id ) {
		global $wpdb;
		$wpdb->delete( self::t( 'events' ), array( 'id' => $id ) );
	}

	/* ---------------------------------------------------------------------
	 * Soutien (naissance, mariage, décès)
	 * ------------------------------------------------------------------ */

	/**
	 * Liste des cas de soutien avec le nom du membre concerné.
	 *
	 * @param string $status ouvert|clos|''.
	 */
	public static function list_cases( $status = '' ) {
		global $wpdb;
		$sql = 'SELECT c.*, m.first_name, m.last_name FROM ' . self::t( 'support_cases' ) . ' c LEFT JOIN ' . self::t( 'members' ) . ' m ON m.id = c.member_id';
		if ( $status ) {
			return $wpdb->get_results( $wpdb->prepare( $sql . ' WHERE c.status = %s ORDER BY c.created_at DESC', $status ) );
		}
		return $wpdb->get_results( $sql . ' ORDER BY c.status DESC, c.created_at DESC' );
	}

	/**
	 * Cas de soutien par identifiant.
	 *
	 * @param int $id Identifiant.
	 */
	public static function get_case( $id ) {
		global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( 'SELECT c.*, m.first_name, m.last_name FROM ' . self::t( 'support_cases' ) . ' c LEFT JOIN ' . self::t( 'members' ) . ' m ON m.id = c.member_id WHERE c.id = %d', $id ) );
	}

	/**
	 * Crée ou met à jour un cas de soutien.
	 *
	 * @param array $data Données.
	 * @param int   $id   Identifiant.
	 */
	public static function save_case( array $data, $id = 0 ) {
		global $wpdb;
		$row = array_intersect_key( $data, array_flip( array( 'member_id', 'type', 'title', 'description', 'target_amount', 'status' ) ) );
		if ( $id ) {
			$wpdb->update( self::t( 'support_cases' ), $row, array( 'id' => $id ) );
			return (int) $id;
		}
		$row['created_at'] = self::now();
		$wpdb->insert( self::t( 'support_cases' ), $row );
		return (int) $wpdb->insert_id;
	}

	/**
	 * Supprime un cas et ses contributions.
	 *
	 * @param int $id Identifiant.
	 */
	public static function delete_case( $id ) {
		global $wpdb;
		$wpdb->delete( self::t( 'contributions' ), array( 'case_id' => $id ) );
		$wpdb->delete( self::t( 'support_cases' ), array( 'id' => $id ) );
	}

	/**
	 * Totaux des contributions par cas.
	 *
	 * @param int[] $case_ids Cas.
	 * @return array case_id => [confirmed, declared, count].
	 */
	public static function case_totals( array $case_ids ) {
		global $wpdb;
		$case_ids = array_values( array_filter( array_map( 'absint', $case_ids ) ) );
		$out      = array();
		foreach ( $case_ids as $id ) {
			$out[ $id ] = array(
				'confirmed' => 0.0,
				'declared'  => 0.0,
				'count'     => 0,
			);
		}
		if ( ! $case_ids ) {
			return $out;
		}
		$rows = $wpdb->get_results( $wpdb->prepare( 'SELECT case_id, status, SUM(amount) AS total, COUNT(*) AS c FROM ' . self::t( 'contributions' ) . ' WHERE case_id IN (' . self::in_ids( $case_ids ) . ') GROUP BY case_id, status', $case_ids ) );
		foreach ( (array) $rows as $row ) {
			$key                                = 'confirmee' === $row->status ? 'confirmed' : 'declared';
			$out[ (int) $row->case_id ][ $key ] += (float) $row->total;
			$out[ (int) $row->case_id ]['count'] += (int) $row->c;
		}
		return $out;
	}

	/**
	 * Contributions d'un cas, avec le nom des contributeurs.
	 *
	 * @param int $case_id Cas.
	 */
	public static function contributions_for_case( $case_id ) {
		global $wpdb;
		return $wpdb->get_results( $wpdb->prepare( 'SELECT k.*, m.first_name, m.last_name FROM ' . self::t( 'contributions' ) . ' k LEFT JOIN ' . self::t( 'members' ) . ' m ON m.id = k.member_id WHERE k.case_id = %d ORDER BY k.created_at DESC', $case_id ) );
	}

	/**
	 * Contributions d'un membre.
	 *
	 * @param int $member_id Membre.
	 */
	public static function contributions_for_member( $member_id ) {
		global $wpdb;
		return $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . self::t( 'contributions' ) . ' WHERE member_id = %d ORDER BY created_at DESC', $member_id ) );
	}

	/**
	 * Contribution par identifiant.
	 *
	 * @param int $id Identifiant.
	 */
	public static function get_contribution( $id ) {
		global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . self::t( 'contributions' ) . ' WHERE id = %d', $id ) );
	}

	/**
	 * Ajoute une contribution.
	 *
	 * @param int    $case_id   Cas.
	 * @param int    $member_id Membre.
	 * @param float  $amount    Montant.
	 * @param string $status    declaree|confirmee.
	 * @param string $note      Note.
	 */
	public static function add_contribution( $case_id, $member_id, $amount, $status = 'declaree', $note = '' ) {
		global $wpdb;
		$now = self::now();
		$wpdb->insert(
			self::t( 'contributions' ),
			array(
				'case_id'      => (int) $case_id,
				'member_id'    => $member_id ? (int) $member_id : null,
				'amount'       => (float) $amount,
				'status'       => $status,
				'note'         => $note,
				'created_at'   => $now,
				'confirmed_at' => 'confirmee' === $status ? $now : null,
			)
		);
		return (int) $wpdb->insert_id;
	}

	/**
	 * Confirme une contribution (argent reçu).
	 *
	 * @param int $id Identifiant.
	 */
	public static function confirm_contribution( $id ) {
		global $wpdb;
		$wpdb->update(
			self::t( 'contributions' ),
			array(
				'status'       => 'confirmee',
				'confirmed_at' => self::now(),
			),
			array( 'id' => $id )
		);
	}

	/**
	 * Supprime une contribution.
	 *
	 * @param int $id Identifiant.
	 */
	public static function delete_contribution( $id ) {
		global $wpdb;
		$wpdb->delete( self::t( 'contributions' ), array( 'id' => $id ) );
	}

	/* ---------------------------------------------------------------------
	 * Notifications
	 * ------------------------------------------------------------------ */

	/**
	 * Journalise une notification.
	 *
	 * @param array $row Données.
	 */
	public static function add_notification( array $row ) {
		global $wpdb;
		$row = wp_parse_args(
			$row,
			array(
				'member_id'  => null,
				'type'       => 'annonce',
				'title'      => '',
				'message'    => '',
				'channel'    => 'in_app',
				'status'     => 'envoye',
				'detail'     => '',
				'ref'        => '',
				'is_read'    => 0,
				'created_at' => self::now(),
			)
		);
		$wpdb->insert( self::t( 'notifications' ), $row );
		return (int) $wpdb->insert_id;
	}

	/**
	 * Une alerte avec cette référence existe-t-elle déjà ?
	 *
	 * @param int    $member_id Membre.
	 * @param string $ref       Référence.
	 */
	public static function notification_ref_exists( $member_id, $ref ) {
		global $wpdb;
		return (bool) $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM ' . self::t( 'notifications' ) . ' WHERE member_id = %d AND ref = %s LIMIT 1', $member_id, $ref ) );
	}

	/**
	 * Journal des notifications (administration).
	 *
	 * @param array $args channel, status, limit.
	 */
	public static function list_notifications( array $args = array() ) {
		global $wpdb;
		$args   = wp_parse_args(
			$args,
			array(
				'channel' => '',
				'status'  => '',
				'limit'   => 200,
			)
		);
		$where  = array( '1=1' );
		$params = array();
		if ( $args['channel'] ) {
			$where[]  = 'n.channel = %s';
			$params[] = $args['channel'];
		}
		if ( $args['status'] ) {
			$where[]  = 'n.status = %s';
			$params[] = $args['status'];
		}
		$sql = 'SELECT n.*, m.first_name, m.last_name FROM ' . self::t( 'notifications' ) . ' n LEFT JOIN ' . self::t( 'members' ) . ' m ON m.id = n.member_id WHERE ' . implode( ' AND ', $where ) . ' ORDER BY n.id DESC LIMIT ' . absint( $args['limit'] );
		return $params ? $wpdb->get_results( $wpdb->prepare( $sql, $params ) ) : $wpdb->get_results( $sql );
	}

	/**
	 * Notifications in-app d'un membre.
	 *
	 * @param int $member_id Membre.
	 * @param int $limit     Limite.
	 */
	public static function member_notifications( $member_id, $limit = 20 ) {
		global $wpdb;
		return $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . self::t( 'notifications' ) . " WHERE member_id = %d AND channel = 'in_app' ORDER BY id DESC LIMIT %d", $member_id, $limit ) );
	}

	/**
	 * Nombre de notifications non lues.
	 *
	 * @param int $member_id Membre.
	 */
	public static function unread_count( $member_id ) {
		global $wpdb;
		return (int) $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . self::t( 'notifications' ) . " WHERE member_id = %d AND channel = 'in_app' AND is_read = 0", $member_id ) );
	}

	/**
	 * Marque toutes les notifications d'un membre comme lues.
	 *
	 * @param int $member_id Membre.
	 */
	public static function mark_all_read( $member_id ) {
		global $wpdb;
		$wpdb->update(
			self::t( 'notifications' ),
			array( 'is_read' => 1 ),
			array(
				'member_id' => (int) $member_id,
				'channel'   => 'in_app',
			)
		);
	}

	/**
	 * Date de la dernière alerte/rappel de retard envoyé à chaque membre.
	 *
	 * @return array member_id => datetime.
	 */
	public static function last_reminders() {
		global $wpdb;
		$rows = $wpdb->get_results( 'SELECT member_id, MAX(created_at) AS last_at FROM ' . self::t( 'notifications' ) . " WHERE type IN ('retard','rappel') AND channel = 'in_app' GROUP BY member_id" );
		$out  = array();
		foreach ( (array) $rows as $row ) {
			$out[ (int) $row->member_id ] = $row->last_at;
		}
		return $out;
	}
}
// phpcs:enable
