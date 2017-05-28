function validateSignupForm(form) {
	// Checks all fields have been completed
	if (form.nombre.value == '' || form.email.value == '' || form.username.value == '' || form.password.value == '' || form.confirmpassword.value == '') {
		alert('You must fulfill all fields required. Please, try again.');
		return false;
	}

	// Validates name
	validname = /^([A-Za-z\s]{3,60})+$/;
	if (!validname.test(form.name.value)) {
		alert('Complete name must have a length between [3-60]. Please, try again.');
		return false;
	}

	// Validates email
	validemail = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (!validemail.test(form.email.value)) {
		alert('E-mail format is not correct. Please, try again.');
		return false;
		}

	// Validates username
	validuser = /^([a-zA-Z0-9]{4,20})+$/;
	if (!validuser.test(form.username.value)) {
		alert("Username can only contain alphanumeric characters and have a length between [4-20]. Please, try again.");
		return false;
	}

	// Validates password
	if (form.password.value != "" && form.confirmpassword.value != "") {

		// Validates password length
		if (form.password.value.length < 8 || form.password.value.length > 20) {
			alert('Password must have a length between [8-20]. Please, try again.');
			//form.password.focus();
			return false;
		}

		// Validates password characters
		validpassword = /(?=.*[0-9])(?=.*[¡!¿?@#$%^&*/\+_<>])(?=.*[a-z])(?=.*[A-Z]).{8,20}/;
		if (!validpassword.test(form.password.value)) {
			alert('Password must contain at least one nombre, one lowercase, one uppercase and one special character [¡!¿?@#$%^&*/\+_<>]. Please, try again.');
			return false;
		}

		// Validates password and its confirmation
		if (form.password.value != form.confirmpassword.value) {
			alert('Password does not match with its confirmation. Please, try again.');
			form.password.focus();
			return false;
		}
	}
}

// Function that shows dropdown list of available users to send a message to.
$(function() {
	$.get("php/getusers.php").done(function(data){
		var users = JSON.parse(data);

		// Field is autocompleted with available options:
		$("#recipient").autocomplete({
			minLength: 0,
			source: users,
			change: function(event, ui) {
				val = $(this).val();
				exists = $.inArray(val, users);
				// If username introduced does not exist, the field is reset:
				if (exists < 0) {
				$(this).val("");
				return false;
				}
			}
		})
		.on('keydown', function() {
			$(this).autocomplete('search', '')
		});
	});
});

// Function that shows dropdown list of available groups to send a message to.
// The list only shows the groups the user belong to.
$(function() {
	$.get("php/getgroups.php").done(function(data){
		var groups = JSON.parse(data);

		// Field is autocompleted with available options:
		$("#group-recipient").autocomplete({
			minLength: 0,
			source: groups,
			change: function(event, ui) {
				val = $(this).val();
				exists = $.inArray(val, groups);
				// If groupname introduced does not exist, the field is reset:
				if (exists < 0) {
				$(this).val("");
				return false;
				}
			}
		})
		.on('keydown', function() {
			$(this).autocomplete('search', '')
		});
	});
});
