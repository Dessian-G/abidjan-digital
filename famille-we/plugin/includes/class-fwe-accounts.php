<?php
/**
 * Comptes : lien entre utilisateurs WordPress et fiches membres.
 *
 * @package FamilleWE
 */

defined( 'ABSPATH' ) || exit;

/**
 * Gestion des comptes membres.
 */
class FWE_Accounts {

	/** Hooks. */
	public static function init() {
		add_action( 'wp_login', array( __CLASS__, 'on_login' ), 10, 2 );
		add_filter( 'login_redirect', array( __CLASS__, 'login_redirect' ), 10, 3 );
		add_filter( 'show_admin_bar', array( __CLASS__, 'show_admin_bar' ) );
		add_action( 'delete_user', array( __CLASS__, 'on_delete_user' ) );
	}

	/**
	 * À la connexion, rattache le compte à une fiche membre ayant le même e-mail.
	 *
	 * @param string  $login Identifiant.
	 * @param WP_User $user  Utilisateur.
	 */
	public static function on_login( $login, $user ) {
		self::link_user( $user );
	}

	/**
	 * Rattache un utilisateur à la fiche membre correspondant à son e-mail.
	 *
	 * @param WP_User $user Utilisateur.
	 * @return object|null Membre rattaché.
	 */
	public static function link_user( $user ) {
		if ( ! $user instanceof WP_User ) {
			return null;
		}
		$member = FWE_Repo::get_member_by_user( $user->ID );
		if ( $member ) {
			return $member;
		}
		$member = FWE_Repo::get_member_by_email( $user->user_email );
		if ( $member && empty( $member->user_id ) ) {
			FWE_Repo::save_member( array( 'user_id' => $user->ID ), $member->id );
			return FWE_Repo::get_member( $member->id );
		}
		return null;
	}

	/**
	 * Les membres (non bureau) sont redirigés vers leur espace après connexion.
	 *
	 * @param string           $redirect_to URL demandée.
	 * @param string           $requested   URL demandée (brute).
	 * @param WP_User|WP_Error $user        Utilisateur.
	 */
	public static function login_redirect( $redirect_to, $requested, $user ) {
		if ( $user instanceof WP_User && in_array( 'fwe_membre', (array) $user->roles, true ) && ! user_can( $user, 'fwe_manage' ) ) {
			if ( ! $requested || false !== strpos( $requested, 'wp-admin' ) ) {
				return fwe_espace_url();
			}
		}
		return $redirect_to;
	}

	/**
	 * Masque la barre d'administration pour les simples membres.
	 *
	 * @param bool $show Afficher.
	 */
	public static function show_admin_bar( $show ) {
		$user = wp_get_current_user();
		if ( $user->exists() && in_array( 'fwe_membre', (array) $user->roles, true ) && ! user_can( $user, 'fwe_manage' ) && ! user_can( $user, 'edit_posts' ) ) {
			return false;
		}
		return $show;
	}

	/**
	 * Détache la fiche membre quand un compte WordPress est supprimé.
	 *
	 * @param int $user_id Utilisateur.
	 */
	public static function on_delete_user( $user_id ) {
		$member = FWE_Repo::get_member_by_user( $user_id );
		if ( $member ) {
			FWE_Repo::save_member( array( 'user_id' => null ), $member->id );
		}
	}

	/**
	 * Identifiant de connexion unique dérivé de l'e-mail.
	 *
	 * @param string $email E-mail.
	 */
	public static function unique_login( $email ) {
		$base = sanitize_user( strstr( $email, '@', true ), true );
		$base = $base ? $base : 'membre';
		$login = $base;
		$i     = 2;
		while ( username_exists( $login ) ) {
			$login = $base . $i;
			++$i;
		}
		return $login;
	}

	/**
	 * Crée un compte WordPress pour un membre et lui envoie le lien pour choisir
	 * son mot de passe.
	 *
	 * @param object $member Membre.
	 * @return int|WP_Error Identifiant utilisateur.
	 */
	public static function invite( $member ) {
		if ( ! is_email( $member->email ) ) {
			return new WP_Error( 'no_email', 'no_email' );
		}
		if ( email_exists( $member->email ) ) {
			return new WP_Error( 'email_exists', 'email_exists' );
		}
		$user_id = wp_insert_user(
			array(
				'user_login' => self::unique_login( $member->email ),
				'user_email' => $member->email,
				'user_pass'  => wp_generate_password( 24 ),
				'first_name' => $member->first_name,
				'last_name'  => $member->last_name,
				'role'       => 'fwe_membre',
			)
		);
		if ( is_wp_error( $user_id ) ) {
			return $user_id;
		}
		FWE_Repo::save_member( array( 'user_id' => $user_id ), $member->id );
		wp_new_user_notification( $user_id, null, 'user' );
		return $user_id;
	}
}
