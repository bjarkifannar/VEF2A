function formhash(form, password) {
	var p = document.createElement("input");

	form.appendChild(p);
	p.name = "p";
	p.type = "hidden";
	p.value = hex_sha512(password.value);

	password.value = "";
	
	form.submit();
}

function adminFormHash(form, password, max_permission) {
	var p = document.createElement("input");
	var permission = document.createElement("input");

	form.appendChild(p);
	p.name = "p";
	p.type = "hidden";
	p.value = hex_sha512(password.value);

	password.value = "";

	form.appendChild(permission);
	permission.name = "admin_permission";
	permission.type = "hidden";
	permission.value = max_permission.value;

	form.submit();
}