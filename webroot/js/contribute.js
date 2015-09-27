var handler = StripeCheckout.configure({
	key: 'pk_test_kK6XjLhBqZYx6Hn5LAMNkIQG',
	image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
	locale: 'auto',
	token: function(token) {
		$.ajax({
			type: "POST",
			url: '/product/contribute/' + $('#group-id').val(),
			data: {
				email: token.email, 
				token: token.id,
				amount: $('#contribute-amount').val()*100
			},
			success: function(r) {
				if (r.info.success) {
					// Do something
				} else {
					console.log('ERROR: ' + r.info.message)
				}
			},
			dataType: 'json'
		});
	}
});


$('#stripe-contribute').on('click', function(e) {
	e.preventDefault();

	// Open Checkout with further options
	handler.open({
		currency: 'USD',
		name: $(this).data('name'),
		email: $(this).data('email'),
		amount: $('#contribute-amount').val()*100,
		panelLabel: 'Contribute'
	});
	e.preventDefault();
});

// Close Checkout on page navigation
$(window).on('popstate', function() {
	handler.close();
});