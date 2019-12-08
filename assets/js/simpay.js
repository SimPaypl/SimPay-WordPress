jQuery("#usluga2").change(function () {
	const selected = jQuery(this).find(':selected').val();
	
	jQuery('.selectsms').hide();
	jQuery('#prices_' + selected).show();
	
	console.log('test');
	
});