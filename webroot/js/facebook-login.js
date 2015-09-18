// This is called with the results from from FB.getLoginStatus().
function statusChangeCallback(response) {
	if (response.status === 'connected') {
		// Remove login button
		$('.facebook-login').hide();

		// Create user item with this information
		$.ajax({
			type: 'PUT',
			url: '/users/' + response.authResponse.userID + '.json',
			data: {
				facebook_user_id: response.authResponse.userID,
				facebook_access_token: response.authResponse.accessToken
		    },
		    dataType: 'json'
		});
	} else {
		$('.facebook-login').removeClass('hidden');
	}
}

// This function is called when someone finishes with the Login
// Button.
function checkLoginState() {
  FB.getLoginStatus(function(response) {
	statusChangeCallback(response);
  });
}

window.fbAsyncInit = function() {
FB.init({
  appId      : '138187349863087',
  cookie     : true,  // enable cookies to allow the server to access
					  // the session
  xfbml      : true,  // parse social plugins on this page
  version    : 'v2.2' // use version 2.2
});

// Now that we've initialized the JavaScript SDK, we call
// FB.getLoginStatus().  This function gets the state of the
// person visiting this page and can return one of three states to
// the callback you provide.  They can be:
//
// 1. Logged into your app ('connected')
// 2. Logged into Facebook, but not your app ('not_authorized')
// 3. Not logged into Facebook and can't tell if they are logged into
//    your app or not.
//
// These three cases are handled in the callback function.

FB.getLoginStatus(function(response) {
	statusChangeCallback(response);
});

};

// Load the SDK asynchronously
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
