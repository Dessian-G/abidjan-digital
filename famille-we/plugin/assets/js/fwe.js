/**
 * Famille WÊ — interactions légères (aucune dépendance).
 * Le plugin fonctionne sans JavaScript ; ce fichier améliore seulement l'expérience.
 */
( function () {
	'use strict';

	var i18n = window.FWE || {};

	// Confirmation avant une action destructive (data-fwe-confirm sur un bouton).
	document.addEventListener( 'click', function ( event ) {
		var trigger = event.target.closest( '[data-fwe-confirm]' );
		if ( trigger && ! window.confirm( trigger.getAttribute( 'data-fwe-confirm' ) ) ) {
			event.preventDefault();
			event.stopPropagation();
		}
	}, true );

	// Onglets connexion / inscription.
	document.querySelectorAll( '[data-fwe-tabs]' ).forEach( function ( tabs ) {
		var root = tabs.parentElement;
		tabs.addEventListener( 'click', function ( event ) {
			var tab = event.target.closest( '[data-fwe-tab]' );
			if ( ! tab ) {
				return;
			}
			event.preventDefault();
			var name = tab.getAttribute( 'data-fwe-tab' );
			tabs.querySelectorAll( '[data-fwe-tab]' ).forEach( function ( other ) {
				var active = other === tab;
				other.classList.toggle( 'is-active', active );
				other.setAttribute( 'aria-selected', active ? 'true' : 'false' );
			} );
			root.querySelectorAll( '[data-fwe-panel]' ).forEach( function ( panel ) {
				panel.hidden = panel.getAttribute( 'data-fwe-panel' ) !== name;
			} );
		} );
	} );

	// Copier dans le presse-papiers (coordonnées Zelle, mémo).
	document.addEventListener( 'click', function ( event ) {
		var button = event.target.closest( '[data-fwe-copy]' );
		if ( ! button ) {
			return;
		}
		var text = button.getAttribute( 'data-fwe-copy' );
		var done = function () {
			var previous = button.getAttribute( 'title' );
			button.setAttribute( 'title', i18n.copied || 'Copié !' );
			button.classList.add( 'is-copied' );
			window.setTimeout( function () {
				button.setAttribute( 'title', previous );
				button.classList.remove( 'is-copied' );
			}, 1600 );
		};
		if ( navigator.clipboard && window.isSecureContext ) {
			navigator.clipboard.writeText( text ).then( done );
		} else {
			var area = document.createElement( 'textarea' );
			area.value = text;
			area.setAttribute( 'readonly', '' );
			area.style.position = 'absolute';
			area.style.left = '-9999px';
			document.body.appendChild( area );
			area.select();
			try {
				document.execCommand( 'copy' );
				done();
			} catch ( e ) {}
			document.body.removeChild( area );
		}
	} );

	// Choisir l'image d'un événement dans la médiathèque WordPress.
	document.addEventListener( 'click', function ( event ) {
		var button = event.target.closest( '[data-fwe-media]' );
		if ( ! button || ! window.wp || ! window.wp.media ) {
			return;
		}
		event.preventDefault();
		var field = button.parentElement.querySelector( '[data-fwe-media-target]' );
		var frame = window.wp.media( {
			title: i18n.chooseImage || 'Choisir une image',
			button: { text: i18n.useImage || 'Utiliser cette image' },
			library: { type: 'image' },
			multiple: false
		} );
		frame.on( 'select', function () {
			var attachment = frame.state().get( 'selection' ).first().toJSON();
			if ( field ) {
				field.value = attachment.url;
			}
		} );
		frame.open();
	} );
}() );
