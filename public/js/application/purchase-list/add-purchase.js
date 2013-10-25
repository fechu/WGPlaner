$(document).ready(function() {
	$("#hasSlipCheckbox").change(checkSlipCheckbox);
	
	function checkSlipCheckbox() {
		var checked = $("#hasSlipCheckbox").is(":checked");
		$("#slipNumber").attr("disabled", !checked);
	};
	
	// Hanlde default empty values
	if ($("#slipNumber").val() == 0) {
		$("#slipNumber").val("");
		$("#hasSlipCheckbox").prop("checked", false);
	}
	
	// Is the field checked?
	checkSlipCheckbox();
	

});