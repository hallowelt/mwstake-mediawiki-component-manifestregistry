( function( mw, $, bs, undefined ) {

	function _someInternalFunction() {
		alert( 'Hallo Welt!' );
	}

	bs.util.registerNamespace( 'bs.checklists.util' );

	bs.checklists.util = {
		somePublicInterface: _someInternalFunction
	}

} )( mediaWiki, jQuery, blueSpice );
