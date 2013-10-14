$(document).ready(function() {
	$("#hasSlipCheckbox").change(checkSlipCheckbox);
	
	function checkSlipCheckbox() {
		var checked = $("#hasSlipCheckbox").is(":checked");
		$("#slipNumber").attr("disabled", !checked);
	};
	
	// Is the field checked?
	checkSlipCheckbox();
});