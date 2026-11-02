<?php
/**
 * Données de démonstration (site de test uniquement) :
 *
 *   wp eval-file famille-we/tests/seed-demo.php
 *
 * ATTENTION : vide les tables wp_fwe_* avant de les remplir.
 * Crée aussi le compte membre « awa.kone » / « famille2026 » pour tester l'espace membre.
 *
 * @package FamilleWE
 */

// phpcs:disable

global $wpdb;
foreach ( array( 'members', 'cotisations', 'events', 'support_cases', 'contributions', 'notifications' ) as $table ) {
	$wpdb->query( "DELETE FROM {$wpdb->prefix}fwe_$table" );
}

$cur = fwe_current_period();
$p   = function ( $n ) use ( $cur ) {
	return fwe_period_add( $cur, $n );
};

fwe_update_settings(
	array(
		'start_period'   => $p( -10 ),
		'treasurer_name' => 'Famille WE Treasury',
		'zelle_contact'  => 'tresorerie@famillewe.org',
		'zelle_note'     => 'Indiquez votre nom et le mois dans le mémo Zelle. Merci !',
	)
);

// [prénom, nom, ville, état, adhésion (mois relatifs), mois payés (relatifs), téléphone, statut].
$people = array(
	array( 'Awa', 'Koné', 'Dallas', 'TX', -14, range( -10, 0 ), '(214) 555-0142', 'actif' ),
	array( 'Kouadio', 'N\'Guessan', 'Irving', 'TX', -14, range( -10, -1 ), '(972) 555-0177', 'actif' ),
	array( 'Mariam', 'Traoré', 'Plano', 'TX', -12, range( -10, -6 ), '(469) 555-0110', 'actif' ),
	array( 'Yao', 'Kouassi', 'Garland', 'TX', -9, range( -9, -2 ), '(214) 555-0199', 'actif' ),
	array( 'Fatou', 'Diabaté', 'Arlington', 'TX', -14, range( -10, -5 ), '', 'actif' ),
	array( 'Serge', 'Bamba', 'Oklahoma City', 'OK', -11, range( -10, 0 ), '(405) 555-0123', 'actif' ),
	array( 'Aïcha', 'Ouattara', 'Tulsa', 'OK', -6, range( -6, -1 ), '(918) 555-0155', 'actif' ),
	array( 'Jean-Marc', 'Gnagne', 'Norman', 'OK', -3, array(), '(405) 555-0188', 'actif' ),
	array( 'Christelle', 'Aka', 'Frisco', 'TX', -1, array( -1, 0 ), '(469) 555-0134', 'actif' ),
	array( 'Ibrahim', 'Cissé', 'Mesquite', 'TX', 0, array(), '(214) 555-0166', 'en_attente' ),
	array( 'Rose', 'Kacou', 'Edmond', 'OK', -20, array(), '', 'inactif' ),
);

$ids = array();
foreach ( $people as $person ) {
	list( $first, $last, $city, $state, $joined, $paid, $phone, $status ) = $person;
	$email = strtolower( remove_accents( str_replace( array( ' ', '\'' ), '', $first ) . '.' . str_replace( array( ' ', '\'' ), '', $last ) ) ) . '@exemple.com';
	$id    = FWE_Repo::save_member(
		array(
			'first_name' => $first,
			'last_name'  => $last,
			'email'      => $email,
			'phone'      => $phone,
			'city'       => $city,
			'state'      => $state,
			'join_date'  => $p( $joined ) . '-0' . wp_rand( 1, 9 ),
			'status'     => $status,
		)
	);
	$ids[ $first ] = $id;
	foreach ( $paid as $n ) {
		$period = $p( $n );
		FWE_Repo::record_payment( $id, $period, 20, $period . '-0' . wp_rand( 1, 9 ), 0 === $n % 4 ? 'especes' : 'zelle' );
	}
}

// Compte en ligne pour Awa (test de l'espace membre).
$user = get_user_by( 'login', 'awa.kone' );
if ( $user ) {
	wp_delete_user( $user->ID );
}
$uid = wp_insert_user(
	array(
		'user_login' => 'awa.kone',
		'user_email' => 'awa.kone@exemple.com',
		'user_pass'  => 'famille2026',
		'first_name' => 'Awa',
		'last_name'  => 'Koné',
		'role'       => 'fwe_membre',
	)
);
FWE_Repo::save_member( array( 'user_id' => $uid ), $ids['Awa'] );

// Événements.
$date = function ( $days, $time ) {
	return gmdate( 'Y-m-d', strtotime( current_time( 'Y-m-d' ) . ' ' . sprintf( '%+d', $days ) . ' days' ) ) . ' ' . $time;
};
FWE_Repo::save_event( array( 'title' => 'Assemblée générale annuelle', 'type' => 'ag', 'starts_at' => $date( 12, '15:00:00' ), 'location' => 'Irving Convention Center, Irving TX', 'description' => 'Bilan de l\'année, vote du budget, élection du bureau et questions diverses. Présence de tous les membres souhaitée.' ) );
FWE_Repo::save_event( array( 'title' => 'Festival de la Famille WÊ', 'type' => 'festival', 'starts_at' => $date( 40, '12:00:00' ), 'location' => 'Fair Park, Dallas TX', 'description' => 'Danses, cuisine du pays, défilé en pagne et animations pour les enfants. Venez nombreux avec vos proches !' ) );
FWE_Repo::save_event( array( 'title' => 'Rencontre des familles d\'Oklahoma', 'type' => 'general', 'starts_at' => $date( 25, '17:30:00' ), 'location' => 'Oklahoma City, OK', 'description' => 'Moment convivial pour accueillir les nouveaux arrivants de la région.' ) );
FWE_Repo::save_event( array( 'title' => 'Pique-nique de l\'été', 'type' => 'general', 'starts_at' => $date( -60, '11:00:00' ), 'location' => 'White Rock Lake, Dallas TX', 'description' => 'Grillades et jeux en famille.' ) );

// Soutien.
$c1 = FWE_Repo::save_case( array( 'member_id' => $ids['Yao'], 'type' => 'naissance', 'title' => 'Naissance de la petite Grâce', 'description' => 'Yao et son épouse ont accueilli leur fille Grâce. Félicitations aux parents !', 'target_amount' => 400, 'status' => 'ouvert' ) );
$c2 = FWE_Repo::save_case( array( 'member_id' => $ids['Mariam'], 'type' => 'deces', 'title' => 'Soutien à Mariam Traoré', 'description' => 'Mariam a perdu son père au pays. La famille l\'accompagne pour le rapatriement et les funérailles.', 'target_amount' => 1500, 'status' => 'ouvert' ) );
$c3 = FWE_Repo::save_case( array( 'member_id' => $ids['Serge'], 'type' => 'mariage', 'title' => 'Mariage de Serge Bamba', 'description' => 'Serge se marie ! Participons à la cagnotte de la famille.', 'target_amount' => 500, 'status' => 'clos' ) );
FWE_Repo::add_contribution( $c1, $ids['Serge'], 50, 'confirmee' );
FWE_Repo::add_contribution( $c1, $ids['Kouadio'], 40, 'confirmee' );
FWE_Repo::add_contribution( $c1, $ids['Christelle'], 25, 'declaree' );
FWE_Repo::add_contribution( $c2, $ids['Awa'], 100, 'confirmee' );
FWE_Repo::add_contribution( $c2, $ids['Kouadio'], 100, 'confirmee' );
FWE_Repo::add_contribution( $c2, $ids['Serge'], 150, 'confirmee' );
FWE_Repo::add_contribution( $c2, $ids['Aïcha'], 60, 'declaree' );
FWE_Repo::add_contribution( $c2, 0, 300, 'confirmee', 'Don de l\'église Saint-Paul' );
FWE_Repo::add_contribution( $c3, $ids['Awa'], 50, 'confirmee' );
FWE_Repo::add_contribution( $c3, $ids['Mariam'], 75, 'confirmee' );

// Une annonce déjà envoyée.
foreach ( FWE_Repo::list_members( array( 'status' => 'actif' ) ) as $member ) {
	FWE_Notifier::send( $member, 'annonce', 'Bienvenue sur le nouvel espace Famille WÊ', 'Retrouvez vos cotisations, les événements et les actions de solidarité en un seul endroit.', array( 'in_app' ) );
}

echo "Données de démonstration créées.\n";
