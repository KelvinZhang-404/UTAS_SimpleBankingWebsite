window.onload = function(){
	document.getElementById("submitId").addEventListener('click', function(event){
		var password = document.getElementById("pwd");
		var password_match = document.getElementById("pwd_match");
		if(password.value != password_match.value) {
			console.log("ddd");
			password_match.setCustomValidity("Password does not Match");
		}else {
			password_match.setCustomValidity('');
		}
	});
}

