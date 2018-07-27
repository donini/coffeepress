jQuery( document ).ready(function() {

	// Get the page-id in the body classes.
	function getPageId() {
		var bodyElement = jQuery( "body[class*='page-id-']" );
		if (bodyElement.length > 0) {
			bodyClasses = bodyElement.context.body.classList;
			var regExId = /page-id-/;
			for (var i = 0; i < bodyClasses.length; i++) {
				if (regExId.test(bodyClasses[i])) {
					var id = bodyClasses[i];
					return id.replace( 'page-id-', '' );
				}
			};
		}
	}

	// Verify if we need to show the message and where.
	jQuery.ajax({
		method: "POST",
		url: "//coffeepress/wp-admin/admin-ajax.php",
		data: { action: "insert_message", post_id: getPageId(), agreed: getCookie( 'info-message' ) }
	  }).done(function( message ) {
		jQuery('body').prepend( message );
	});

	// Closes the message and create the cookie.
	jQuery('body').on('click', '.message.alert .btn-close', function() {
		var agreed = getCookie( 'info-message' );
		if (true != agreed) {
			setCookie( 'info-message', true, 182 );
			jQuery('.message.alert').fadeOut();
		}
	});

	// Get the cookie value.
	function setCookie(cname, cvalue, exdays) {
		var d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		var expires = "expires="+ d.toUTCString();
		document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}
	
	// Set the cookie value.
	function getCookie(cname) {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	}
});