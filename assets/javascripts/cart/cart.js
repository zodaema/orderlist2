$(function () {
    'use strict'
	

	// $('.counter-plus').on('click',function(){
	// 	var $qty=$(this).closest('div').find('.qty');
	// 	var currentVal = parseInt($qty.val());
	// 	if (!isNaN(currentVal)) {
	// 	$qty.val(currentVal + 1);
	// 	}
	// });
	// $('.counter-minus').on('click',function(){
	// 	var $qty=$(this).closest('div').find('.qty');
	// 	var currentVal = parseInt($qty.val());
	// 	if (!isNaN(currentVal) && currentVal > 0) {
	// 	$qty.val(currentVal - 1);
	// 	}
	// });
    
    
});

// $(document).on('change','input[name=amount_spin]',function(){
//     let currentVal = parseInt($(this).val());
//     let id_cart = parseInt($(this).attr('id-cart'));
//     $.updateCart(id_cart,currentVal);
// });

$.updateCart = function(id_cart){
    // console.log(id_cart+currentVal);
    // let this_subtotal = $('span[name=subtotal][id-cart='+id_cart+']');
    // this_subtotal.html('0');
	let target = $("span[name=subtotal][id-cart="+id_cart+"]");
	target.hide(); 
	target.load("# span[name=subtotal][id-cart="+id_cart+"]");
	target.show(); 
}

$('a#cart2_amount_edit').editable({
	mode: 'inline',
	type: 'number',
	min: '1',
	max: '1000',
	clear: false,
	send: 'always',
	showbuttons: false,
	onblur: 'submit',
	url: 'source/shop.php',
	params:{'action':'cart_edit_amount'},
	ajaxOptions: {
		type: "POST",
		dataType: 'html'
	},
	success: function(data){
		if(data == 'success'){
			id_cart = parseInt($(this).attr("data-pk"));
			$.updateCart(id_cart);
		}
		else{
			return data;
		}
	}
});