$(document).ready(function() {
	$('#email').change(function() {
		var email = $(this).val();
		if(email.length > 8) {
			$('#available').html('<span class="avai">Kiểm tra sự hợp lệ...</span>');

			$.ajax({
				type: "get",
				url: "check.php",
				data: "email="+ email,
				success: function(response) {
					if(response == "YES") {
						$('#available').html('<span class="avai">Email hợp lệ.</span>');
					} else if (response == "NO") {
						$('#available').html('<span class="not-avai">Email KHÔNG hợp lệ.</span>');
					}
				}
			});
		} else {
			$('#available').html('<span class="not-avai">Email quá ngắn.</span>');
		}
	});
});