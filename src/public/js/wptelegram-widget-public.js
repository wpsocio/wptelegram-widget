(function( $ ) {
	'use strict';
	var widget = {};
    widget.configure = function(){
        widget.wrap = $( '.wptelegram-widget-wrap' );
        widget.iframe = $('.wptelegram-widget-message iframe');
    };
    widget.init = function () {
        widget.configure();
        widget.iframe.on( 'load', widget.set_iframe );
        // widget.iframe.on( 'load', widget.resize_iframe );
        widget.iframe.on( 'resize_iframe', widget.resize_iframe );

        $(window).on('resize', function(){
	    	widget.iframe.trigger('resize_iframe');
	    } );
    };
    widget.set_iframe = function(){
        var $this = $(this);
        if ($this.contents().find('body').is(':empty')){
            $this.parent().remove();
            // reconfigure
            widget.configure();
        } else {
            $this.trigger('resize_iframe');
        }
    };
    widget.resize_iframe = function() {
        // this.style.height = this.contentWindow.document.body.scrollHeight + 'px';
        $(this).height( $(this).contents().find('body').height() );
    };

    // trigger on $(document).ready();
    $(widget.init);

})( jQuery );
