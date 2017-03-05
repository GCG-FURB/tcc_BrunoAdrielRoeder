<script type="text/javascript" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="http://connect.facebook.net/en_US/all.js"></script>
<script>

	FB.init({
		appID: '1711998152372582',
		cookie: true,
		status: true,
		xfbml: true
	});

	FB.Event.subscribe('auth.authResponseChange', function(response) {
		if (response.status ==='connected') {
			FB:api('/me', function(resposta) {
				$('#nome').html(resposta.name);
			});
		} else  if (response.status === 'not_authorized') {
			FB:login();
		} else {
			$('#nome').html(null);
		}
	});
</script>