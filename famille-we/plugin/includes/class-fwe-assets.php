<?php
/**
 * Feuilles de style, scripts, polices et images du design « Organic & Earthy ».
 *
 * @package FamilleWE
 */

defined( 'ABSPATH' ) || exit;

/**
 * Ressources front et admin.
 */
class FWE_Assets {

	/** Hooks. */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register' ) );
	}

	/** Enregistre les ressources (chargées seulement là où le plugin s'affiche). */
	public static function register() {
		// phpcs:disable WordPress.WP.EnqueuedResourceParameters.MissingVersion -- polices externes versionnées par leur fournisseur.
		wp_register_style( 'fwe-font-cabinet', 'https://api.fontshare.com/v2/css?f[]=cabinet-grotesk@500,700,800,900&display=swap', array(), null );
		wp_register_style( 'fwe-font-work', 'https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&display=swap', array(), null );
		// phpcs:enable
		wp_register_style( 'fwe', FWE_URL . 'assets/css/fwe.css', array( 'fwe-font-cabinet', 'fwe-font-work' ), FWE_VERSION );
		wp_register_script( 'fwe', FWE_URL . 'assets/js/fwe.js', array(), FWE_VERSION, true );
		wp_localize_script(
			'fwe',
			'FWE',
			array(
				'copied'      => __( 'Copié !', 'famille-we' ),
				'chooseImage' => __( 'Choisir une image', 'famille-we' ),
				'useImage'    => __( 'Utiliser cette image', 'famille-we' ),
			)
		);
	}

	/** Charge les ressources. */
	public static function enqueue() {
		if ( ! wp_style_is( 'fwe', 'registered' ) ) {
			self::register();
		}
		wp_enqueue_style( 'fwe' );
		wp_enqueue_script( 'fwe' );
	}

	/**
	 * Images d'ambiance (modifiables via le filtre « fwe_image_url »).
	 *
	 * @param string $key login|event|location.
	 */
	public static function image( $key ) {
		$images = array(
			'login'    => 'https://images.pexels.com/photos/4262410/pexels-photo-4262410.jpeg?auto=compress&cs=tinysrgb&w=1600',
			'event'    => 'https://images.unsplash.com/photo-1778376644521-f86a4304dda6?auto=format&fit=crop&w=1200&q=80',
			'location' => 'https://images.pexels.com/photos/822321/pexels-photo-822321.jpeg?auto=compress&cs=tinysrgb&w=1600',
		);
		$url    = isset( $images[ $key ] ) ? $images[ $key ] : '';
		return apply_filters( 'fwe_image_url', $url, $key );
	}
}
