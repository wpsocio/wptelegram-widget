( function( $ ) {
	'use strict';
	const widget = {};
	widget.configure = () => {
		widget.wrap = $( '.wptelegram-widget-wrap' );
		widget.iframe = $( '.wptelegram-widget-message iframe' );
	};
	widget.init = () => {
		widget.configure();
		widget.iframe.on( 'load', widget.set_iframe );
		// widget.iframe.on( 'load', widget.resize_iframe );
		widget.iframe.on( 'resize_iframe', widget.resize_iframe );

		$( window ).on( 'resize', () => {
			widget.iframe.trigger( 'resize_iframe' );
		} );
	};
	widget.set_iframe = () => {
		const $this = $( this );
		if (
			$this
				.contents()
				.find( 'body' )
				.is( ':empty' )
		) {
			$this.parent().remove();
			// reconfigure
			widget.configure();
		} else {
			$this.trigger( 'resize_iframe' );
		}
	};
	widget.resize_iframe = () => {
		// this.style.height = this.contentWindow.document.body.scrollHeight + 'px';
		$( this ).height(
			$( this )
				.contents()
				.find( 'body' )
				.height()
		);
	};

	// trigger on $(document).ready();
	$( widget.init );
} )( jQuery );
