<?php
/**
 * Tests de la logique métier, exécutés dans un WordPress réel :
 *
 *   wp eval-file famille-we/tests/test-logic.php
 *
 * ATTENTION : vide les tables wp_fwe_* (à lancer uniquement sur un site de test).
 * Les dates sont relatives au mois courant : le test passe quel que soit le jour.
 *
 * @package FamilleWE
 */

// phpcs:disable

$GLOBALS['fwe_failures'] = 0;
$GLOBALS['fwe_passes']   = 0;

function fwe_t( $label, $condition, $detail = '' ) {
	if ( $condition ) {
		++$GLOBALS['fwe_passes'];
		echo "  ok   $label\n";
	} else {
		++$GLOBALS['fwe_failures'];
		echo "  FAIL $label" . ( $detail ? " — $detail" : '' ) . "\n";
	}
}

function fwe_eq( $label, $expected, $actual ) {
	fwe_t( $label, $expected === $actual, 'attendu ' . var_export( $expected, true ) . ', obtenu ' . var_export( $actual, true ) );
}

global $wpdb;
foreach ( array( 'members', 'cotisations', 'events', 'support_cases', 'contributions', 'notifications' ) as $table ) {
	$wpdb->query( "DELETE FROM {$wpdb->prefix}fwe_$table" );
}
$saved_settings = get_option( 'fwe_settings' );

$cur = fwe_current_period();
$p   = function ( $n ) use ( $cur ) {
	return fwe_period_add( $cur, $n );
};

fwe_update_settings(
	array(
		'monthly_amount'  => 20,
		'late_threshold'  => 3,
		'start_period'    => $p( -8 ),
		'auto_notify'     => 1,
		'notify_email'    => 1,
		'enable_sms'      => 0,
		'enable_whatsapp' => 0,
		'treasurer_name'  => 'Trésorier Test',
		'zelle_contact'   => 'tresor@famille-we.test',
	)
);

echo "\n# Périodes, montants, téléphones\n";
fwe_eq( 'janvier - 1 mois = décembre précédent', '2025-12', fwe_period_add( '2026-01', -1 ) );
fwe_eq( 'décembre + 2 mois', '2027-02', fwe_period_add( '2026-12', 2 ) );
fwe_eq( 'mois entre déc. et fév. (changement d’année)', array( '2025-12', '2026-01', '2026-02' ), fwe_periods_between( '2025-12', '2026-02' ) );
fwe_eq( 'plage inversée = vide', array(), fwe_periods_between( '2026-05', '2026-01' ) );
fwe_eq( 'période invalide rejetée', false, fwe_valid_period( '2026-13' ) );
fwe_eq( 'libellé français', 'novembre 2026', fwe_period_label( '2026-11' ) );
fwe_eq( '$20', '$20', fwe_money( 20 ) );
fwe_eq( '$12.50', '$12.50', fwe_money( 12.5 ) );
fwe_eq( '$1,240', '$1,240', fwe_money( 1240 ) );
fwe_eq( 'montant saisi « 20,50 »', 20.5, fwe_parse_amount( '20,50' ) );
fwe_eq( 'montant saisi « $35 »', 35.0, fwe_parse_amount( '$35' ) );
fwe_eq( 'téléphone US 10 chiffres', '+12145550123', fwe_normalize_phone( '(214) 555-0123' ) );
fwe_eq( 'téléphone avec 1 initial', '+14055550199', fwe_normalize_phone( '1-405-555-0199' ) );
fwe_eq( 'téléphone international conservé', '+2250701020304', fwe_normalize_phone( '+225 07 01 02 03 04' ) );

echo "\n# Situation de cotisation\n";
$join = function ( $n ) use ( $p ) {
	return $p( $n ) . '-15';
};
$a = FWE_Repo::save_member( array( 'first_name' => 'Awa', 'last_name' => 'Koné', 'email' => 'awa@test.dev', 'phone' => '(214) 555-0123', 'state' => 'TX', 'join_date' => $join( -10 ), 'status' => 'actif' ) );
$b = FWE_Repo::save_member( array( 'first_name' => 'Bamba', 'last_name' => 'Yao', 'email' => 'bamba@test.dev', 'state' => 'OK', 'join_date' => $join( -2 ), 'status' => 'actif' ) );
$c = FWE_Repo::save_member( array( 'first_name' => 'Chantal', 'last_name' => 'Gbagbo', 'email' => 'chantal@test.dev', 'state' => 'TX', 'join_date' => $join( -1 ), 'status' => 'actif' ) );
$d = FWE_Repo::save_member( array( 'first_name' => 'Didier', 'last_name' => 'Zadi', 'state' => 'TX', 'join_date' => $join( -10 ), 'status' => 'inactif' ) );
$e = FWE_Repo::save_member( array( 'first_name' => 'Esther', 'last_name' => 'Touré', 'state' => 'OK', 'join_date' => $join( -4 ), 'status' => 'actif' ) );

// A : dû depuis le début du suivi (-8), payé de -8 à -5 → retard -4..-1 (4 mois).
foreach ( fwe_periods_between( $p( -8 ), $p( -5 ) ) as $period ) {
	FWE_Repo::record_payment( $a, $period, 20, current_time( 'Y-m-d' ) );
}
// B : dû depuis -2, payé -2 → retard -1 (1 mois).
FWE_Repo::record_payment( $b, $p( -2 ), 20, current_time( 'Y-m-d' ) );
// C : tout payé, y compris le mois courant.
foreach ( fwe_periods_between( $p( -1 ), $cur ) as $period ) {
	FWE_Repo::record_payment( $c, $period, 20, current_time( 'Y-m-d' ) );
}

fwe_eq( 'paiement en double refusé', false, FWE_Repo::record_payment( $c, $cur, 20, current_time( 'Y-m-d' ) ) );

$members = FWE_Repo::list_members();
$sum     = FWE_Dues::summary_for( $members );

fwe_eq( 'A : début = début du suivi (adhésion plus ancienne)', $p( -8 ), $sum[ $a ]['start'] );
fwe_eq( 'A : 4 mois de retard', 4, $sum[ $a ]['months_late'] );
fwe_eq( 'A : situation critique (> 3 mois)', 'critique', $sum[ $a ]['state'] );
fwe_eq( 'A : $80 en retard', 80.0, $sum[ $a ]['amount_late'] );
fwe_eq( 'A : $100 à régler (mois courant inclus)', 100.0, $sum[ $a ]['amount_due'] );
fwe_eq( 'B : début = mois d’adhésion', $p( -2 ), $sum[ $b ]['start'] );
fwe_eq( 'B : 1 mois de retard', 1, $sum[ $b ]['months_late'] );
fwe_eq( 'B : en retard (pas critique)', 'en_retard', $sum[ $b ]['state'] );
fwe_eq( 'C : à jour', 'a_jour', $sum[ $c ]['state'] );
fwe_eq( 'C : mois courant payé', true, $sum[ $c ]['current_paid'] );
fwe_eq( 'D : inactif, rien de dû', 'inactif', $sum[ $d ]['state'] );
fwe_eq( 'E : 4 mois de retard exactement (critique)', array( 4, 'critique' ), array( $sum[ $e ]['months_late'], $sum[ $e ]['state'] ) );

// Seuil strict : exactement 3 mois = pas encore critique.
fwe_update_settings( array( 'late_threshold' => 4 ) );
fwe_eq( 'seuil 4 : 4 mois de retard n’est pas critique', 'en_retard', FWE_Dues::for_member( FWE_Repo::get_member( $a ) )['state'] );
fwe_update_settings( array( 'late_threshold' => 3 ) );

echo "\n# Vérification automatique des retards\n";
$mails = array();
add_filter(
	'pre_wp_mail',
	function ( $null, $atts ) use ( &$mails ) {
		$mails[] = $atts;
		return true;
	},
	10,
	2
);

$alerted = FWE_Notifier::run_late_check();
fwe_eq( 'alerte envoyée à A et E (au-delà de 3 mois)', 2, $alerted );
$rows = FWE_Repo::list_notifications();
$by   = array();
foreach ( $rows as $row ) {
	$by[ $row->member_id ][ $row->channel ] = $row->status;
}
fwe_eq( 'A : in-app envoyé', 'envoye', $by[ $a ]['in_app'] ?? null );
fwe_eq( 'A : e-mail envoyé', 'envoye', $by[ $a ]['email'] ?? null );
fwe_eq( 'A : SMS « non configuré »', 'non_configure', $by[ $a ]['sms'] ?? null );
fwe_eq( 'A : WhatsApp « non configuré »', 'non_configure', $by[ $a ]['whatsapp'] ?? null );
fwe_eq( 'E : pas d’e-mail → « coordonnée manquante »', 'ignore', $by[ $e ]['email'] ?? null );
fwe_t( 'B (1 mois) non alerté', ! isset( $by[ $b ] ) );
fwe_eq( 'un seul e-mail envoyé (A)', 1, count( $mails ) );
fwe_t( 'e-mail adressé à A', isset( $mails[0] ) && 'awa@test.dev' === $mails[0]['to'] );
fwe_t( 'e-mail mentionne le montant et Zelle', isset( $mails[0] ) && false !== strpos( $mails[0]['message'], '$80' ) && false !== strpos( $mails[0]['message'], 'tresor@famille-we.test' ), $mails[0]['message'] ?? '' );
fwe_eq( 'A : 1 notification non lue dans son espace', 1, FWE_Repo::unread_count( $a ) );

fwe_eq( '2e vérification le même mois : aucun doublon', 0, FWE_Notifier::run_late_check() );
fwe_update_settings( array( 'auto_notify' => 0 ) );
fwe_eq( 'alerte automatique désactivée : rien', 0, FWE_Notifier::run_late_check() );
fwe_update_settings( array( 'auto_notify' => 1 ) );
FWE_Repo::mark_all_read( $a );
fwe_eq( 'A : notifications marquées lues', 0, FWE_Repo::unread_count( $a ) );

echo "\n# SMS / WhatsApp via Twilio (API simulée)\n";
fwe_update_settings(
	array(
		'enable_sms'           => 1,
		'enable_whatsapp'      => 1,
		'twilio_sid'           => 'ACtest123',
		'twilio_token'         => 'secret',
		'twilio_sms_from'      => '+12145550100',
		'twilio_whatsapp_from' => '+14155238886',
	)
);
$requests = array();
$fail     = false;
add_filter(
	'pre_http_request',
	function ( $pre, $args, $url ) use ( &$requests, &$fail ) {
		$requests[] = array(
			'url'  => $url,
			'args' => $args,
		);
		if ( $fail ) {
			return array( 'headers' => array(), 'body' => wp_json_encode( array( 'message' => 'The number is unverified' ) ), 'response' => array( 'code' => 400, 'message' => 'Bad Request' ), 'cookies' => array(), 'filename' => null );
		}
		return array( 'headers' => array(), 'body' => '{"sid":"SM1"}', 'response' => array( 'code' => 201, 'message' => 'Created' ), 'cookies' => array(), 'filename' => null );
	},
	10,
	3
);
$res = FWE_Notifier::send( FWE_Repo::get_member( $a ), 'rappel', 'Test', 'Message', array( 'sms', 'whatsapp' ) );
fwe_eq( 'SMS envoyé', 'envoye', $res['sms'] );
fwe_eq( 'WhatsApp envoyé', 'envoye', $res['whatsapp'] );
fwe_eq( '2 appels à Twilio', 2, count( $requests ) );
fwe_t( 'URL Twilio avec le SID', false !== strpos( $requests[0]['url'], '/Accounts/ACtest123/Messages.json' ), $requests[0]['url'] );
fwe_eq( 'SMS : destinataire normalisé', '+12145550123', $requests[0]['args']['body']['To'] );
fwe_eq( 'WhatsApp : préfixe whatsapp:', 'whatsapp:+12145550123', $requests[1]['args']['body']['To'] );
fwe_eq( 'WhatsApp : expéditeur', 'whatsapp:+14155238886', $requests[1]['args']['body']['From'] );
fwe_eq( 'authentification Basic', 'Basic ' . base64_encode( 'ACtest123:secret' ), $requests[0]['args']['headers']['Authorization'] );
$res = FWE_Notifier::send( FWE_Repo::get_member( $e ), 'rappel', 'Test', 'Message', array( 'sms' ) );
fwe_eq( 'membre sans téléphone : SMS ignoré, pas d’appel', array( 'ignore', 2 ), array( $res['sms'], count( $requests ) ) );
$fail = true;
$res  = FWE_Notifier::send( FWE_Repo::get_member( $a ), 'rappel', 'Test', 'Message', array( 'sms' ) );
fwe_eq( 'erreur Twilio journalisée en échec', 'echec', $res['sms'] );
$last = FWE_Repo::list_notifications( array( 'limit' => 1 ) );
fwe_eq( 'message d’erreur Twilio conservé', 'The number is unverified', $last[0]->detail );

echo "\n# Soutien\n";
$case = FWE_Repo::save_case( array( 'member_id' => $b, 'type' => 'naissance', 'title' => 'Naissance chez Bamba', 'target_amount' => 300, 'status' => 'ouvert' ) );
FWE_Repo::add_contribution( $case, $a, 50, 'confirmee' );
$k = FWE_Repo::add_contribution( $case, $c, 30, 'declaree' );
$totals = FWE_Repo::case_totals( array( $case ) );
fwe_eq( 'confirmé : $50', 50.0, $totals[ $case ]['confirmed'] );
fwe_eq( 'à confirmer : $30', 30.0, $totals[ $case ]['declared'] );
FWE_Repo::confirm_contribution( $k );
$totals = FWE_Repo::case_totals( array( $case ) );
fwe_eq( 'après confirmation : $80 confirmés', array( 80.0, 0.0, 2 ), array( $totals[ $case ]['confirmed'], $totals[ $case ]['declared'], $totals[ $case ]['count'] ) );

echo "\n# Statistiques & suppression\n";
fwe_eq( 'encaissé ce mois : $20 (Chantal)', 20.0, FWE_Repo::sum_payments( $cur, $cur ) );
fwe_eq( 'membres par statut', array( 'actif' => 4, 'en_attente' => 0, 'inactif' => 1 ), FWE_Repo::count_members_by_status() );
fwe_eq( 'recherche par ville/nom', 1, count( FWE_Repo::list_members( array( 'search' => 'Touré' ) ) ) );
fwe_eq( 'filtre Oklahoma', 2, count( FWE_Repo::list_members( array( 'state' => 'OK' ) ) ) );
FWE_Repo::delete_member( $b );
fwe_t( 'suppression : le cas dont B est bénéficiaire disparaît', null === FWE_Repo::get_case( $case ) );
fwe_eq( 'suppression : cotisations de B effacées', 0, count( FWE_Repo::payments_for_member( $b ) ) );

// Remise en état.
foreach ( array( 'members', 'cotisations', 'events', 'support_cases', 'contributions', 'notifications' ) as $table ) {
	$wpdb->query( "DELETE FROM {$wpdb->prefix}fwe_$table" );
}
update_option( 'fwe_settings', $saved_settings );

printf( "\n%d réussis, %d échecs\n", $GLOBALS['fwe_passes'], $GLOBALS['fwe_failures'] );
if ( $GLOBALS['fwe_failures'] ) {
	exit( 1 );
}
