var $j = jQuery.noConflict();

$j(function () {
	$j('#wpecommerce-checkall').click(function () {
		$j('#empty-wpecommerce-tables').find(':checkbox').attr('checked', true);
	});
	$j('#wpecommerce-uncheckall').click(function () {
		$j('#empty-wpecommerce-tables').find(':checkbox').attr('checked', false);
	});

	$j('#wordpress-checkall').click(function () {
		$j('#empty-wordpress-tables').find(':checkbox').attr('checked', true);
	});
	$j('#wordpress-uncheckall').click(function () {
		$j('#empty-wordpress-tables').find(':checkbox').attr('checked', false);
	});
});