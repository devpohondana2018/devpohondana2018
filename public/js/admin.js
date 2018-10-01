$(function() {
	$(".mask_money").maskMoney({thousands:'.', decimal:',', precision:2});
	//$(".mask_money").maskMoney({thousands:".", precision:0});
	$(".mask_money").keyup(function() {
		var value  = $('.mask_money').val();
		value = replaceString(value);
	});

	replaceString = (value) => {
		while(value.indexOf('.') > 0){
			value = value.replace(".", "");
		}
		return value;
	}

	$(".form-horizontal").submit(function(e){  
		e.preventDefault();
		var moneyMask = $('.mask_money').val();
		$('.mask_money').val( parseDouble(replaceString(moneyMask)) );
	});   

});