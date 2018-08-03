$('#customer_category_code_select').change(customerCategoryShowProFields);

/* Number for the customer category panel. */
$(document).ready(function (e) {
	$('#number-customer-category').text($('#register-customer-category').prevAll('fieldset').length + 1);
	customerCategoryShowProFields();
});

/* Hiding professional fields if the customer is not a professional one. */
function customerCategoryShowProFields() {
	var data_code = $('#customer_category_code_select option:selected').attr('data-code');
	$('#customer-category-extra-fields').css(
		'display',
		(data_code === 'particular' || data_code === 'none') ? 'none' : 'block'
	);
}
