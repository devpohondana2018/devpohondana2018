var emailDuplicate = false;
var mobileDuplicate = false;
var ktpduplicate = false;
var npwpduplicate = false;
var averageAmount = false;
var website_url = false;
var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab
var checkbox = $("#checkbox-form").is(":checked");
var path = $('input[name="autocomplete_url"]').val();
var validation = {
	isEmail: function(str) {
		var pattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return pattern.test(String(str).toLowerCase()); // returns a boolean
    },
    isPassword: function(str) {
    	var pattern = /^.{6,}$/;
        return pattern.test(str); // returns a boolean
    },
    isKodePos: function(str) {
    	var pattern = /^[0-9]{5}$/;
        return pattern.test(str); // returns a boolean
    },
    isKTP: function(str) {
    	var pattern = /^[0-9]{16}$/;
        return pattern.test(str); // returns a boolean
    },
    isNPWP: function(str) {
    	var pattern = /^[0-9]{15}$/;
        return pattern.test(str); // returns a boolean
    },
    isLamaBekerja: function(str) {
    	var pattern = /^[0-9]{1,}$/;
        return pattern.test(str); // returns a boolean
    },
    isPasswordConfirm: function(str1, str2) {
    	return str1 === str2;
    },
    isEmpty: function(str1, str2) {
    	return str1 === str2;
    },
    isCheckBox: function(str1, str2) {
    	return str1 === str2;
    },
    isBulanKerja: function(str) {
    	var pattern = /^([0-9]|10|11)$/;
        return pattern.test(str); // returns a boolean
    },
    isPhone: function(str) {
    	var pattern = /^(^0)(\d{3,4}-?){2}\d{3,4}$/;
    	return pattern.test(str);
    },
    isTelephone: function(str) {
    	var pattern = /^(^0)(\d{3,4}-?){2}\d{3,4}$/;
    	return pattern.test(str);
    },
    isURL: function(str) {
    	var pattern = /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/;
    	return pattern.test(str);
    }
};


function replaceString(value) {
	while (value.indexOf('.') > 0) {
		value = value.replace(".", "");
	}
	return value;
}

function validateImage(id) {
	var formData = new FormData();

	var file = document.getElementById(id).files[0];

	formData.append("Filedata", file);
	var t = file.type.split('/').pop().toLowerCase();
	if (t != "jpeg" && t != "jpg" && t != "gif" && t != "svg" && t != "png") {
		swal("Tipe file harus berupa .jpg, .jpeg, .gif, .svg, .png", "Tipe file gambar salah", "error");
		document.getElementById(id).value = '';
		return false;
	}
	if (file.size > 10024000) {
		swal("Ukuran gambar maksimal 10 MB", "Terjadi kesalahan", "error");
		document.getElementById(id).value = '';
		return false;
	}
	return true;
}

$('#website_url').inputmask("url", {

	"autoUnmask": false,
	"clearMaskOnLostFocus": false,

});

$("#amount_requested").maskMoney({
	thousands: ".",
	precision: 0
});

$("#amount_requested").keyup(function() {
	var value = $('#amount_requested').val();
	value = replaceString(value);
});

$("#current_equity").maskMoney({
	thousands: ".",
	precision: 0
});
$("#current_equity").keyup(function() {
	var value = $('#current_equity').val();
	value = replaceString(value);
});

$("#current_asset").maskMoney({
	thousands: ".",
	precision: 0
});

$("#current_asset").keyup(function() {
	var value = $('#current_asset').val();
	value = replaceString(value);
});

$("#current_inventory").maskMoney({
	thousands: ".",
	precision: 0
});
$("#current_inventory").keyup(function() {
	var value = $('#current_inventory').val();
	value = replaceString(value);
});

$("#current_receivable").maskMoney({
	thousands: ".",
	precision: 0
});
$("#current_receivable").keyup(function() {
	var value = $('#current_receivable').val();
	value = replaceString(value);
});

$("#current_debt").maskMoney({
	thousands: ".",
	precision: 0
});
$("#current_debt").keyup(function() {
	var value = $('#current_debt').val();
	value = replaceString(value);
});

$("#regForm").submit(function(e) {
	e.preventDefault();
	var amount_requested = $('#amount_requested').val();
	var current_equity = $('#current_equity').val();
	var current_asset = $('#current_asset').val();
	var current_inventory = $('#current_inventory').val();
	var current_receivable = $('#current_receivable').val();
	var current_debt = $('#current_debt').val();
	$('#amount_requested').val(parseInt(replaceString(amount_requested)));
	$('#current_equity').val(parseInt(replaceString(current_equity)));
	$('#current_asset').val(parseInt(replaceString(current_asset)));
	$('#current_inventory').val(parseInt(replaceString(current_inventory)));
	$('#current_receivable').val(parseInt(replaceString(current_receivable)));
	$('#current_debt').val(parseInt(replaceString(current_debt)));
	return false;
});


function checkPasswordMatch() {
	var password = $("#password").val();
	var confirmPassword = $("#password-confirm").val();

	if (password != confirmPassword) {
		$("span#message_confirm_password").removeClass('valid-feedback');
		$("span#message_confirm_password").addClass('invalid-feedback');
		$("#message_confirm_password").html("Password tidak sama");
		$("#password").addClass('invalid');
		$("#password-confirm").addClass('invalid');
	} else {
		$("span#message_confirm_password").removeClass('invalid-feedback');
		$("span#message_confirm_password").addClass('valid-feedback');
		$("#message_confirm_password").html("Passwords telah sesuai.");
		$("#password").removeClass('invalid');
		$("#password-confirm").removeClass('invalid');
	}
}

$("#password-confirm").keyup(checkPasswordMatch);

function checkAmountRequested() {
	var amount_requested = $("#amount_requested").val();
	var amount_requested_new = amount_requested.split('.').join("");

	if(amount_requested_new < 1000000) {
		$("span#message_confirm_amount").removeClass('valid-feedback');
		$("span#message_confirm_amount").addClass('invalid-feedback');
		$("#message_confirm_amount").html("Minimal Pinjaman Rp. 1.000.000,00");
		$("#amount_requested").addClass('invalid');
		averageAmount = false;
		console.log("validasi atas salah 1 jt");
	} else if(amount_requested_new > 2000000000){
		$("span#message_confirm_amount").removeClass('valid-feedback');
		$("span#message_confirm_amount").addClass('invalid-feedback');
		$("#message_confirm_amount").html("Maksimal Pinjaman Rp. 2.000.000.000,00");
		$("#amount_requested").addClass('invalid');
		averageAmount = false;
		console.log("validasi atas salah 2 m");
	} else {
		$("span#message_confirm_amount").removeClass('invalid-feedback');
		$("span#message_confirm_amount").addClass('valid-feedback');
		$("#message_confirm_amount").html("Pinjaman sesuai");
		$("#amount_requested").removeClass('invalid');		
		averageAmount = true;
		console.log("validasi benar sekali");	
	}
}

$("#amount_requested").keyup(checkAmountRequested);

function checkURL(){
	var url = $("#website_url").val();

	if (validation.isURL(url) == true) {
		$("#website_url").removeClass('invalid');
		$("span#message_website").removeClass('invalid-feedback');
		$("span#message_website").addClass('valid-feedback');
		$("#message_website").html("Format URL benar");
		website_url = false;
	} else {
		$("#website_url").addClass('invalid');
		$("span#message_website").addClass('invalid-feedback');
		$("span#message_website").removeClass('valid-feedback');
		$("#message_website").html("Format URL salah");
		website_url = true;
	}

}

$("#website_url").keyup(checkURL);

function duplicateMobile(element) {
	var mobile_phone = $(element).val();
	$.ajax({
		type: "GET",
		url: $('input[name="checkmobile_url"]').val(),
		data: {
			"mobile_phone": mobile_phone
		},
		dataType: "json",
		success: function(res) {
			if (validation.isPhone(mobile_phone) == true) {
				$("span#message_handphone").removeClass('invalid-feedback');
				$("span#message_handphone").html("");
				if (res.exist) {
					$("span#message_handphone").addClass('invalid-feedback');
					$("span#message_handphone").html("Nomor Handphone sudah terpakai, silahkan masukkan nomor baru");
					$("#mobile_phone").addClass('invalid');
					mobileDuplicate = true;
				} else {
					$("span#message_handphone").removeClass('invalid-feedback');
					$("span#message_handphone").html("");
					$("#mobile_phone").removeClass('invalid');
					mobileDuplicate = false;
				}
			} else if (validation.isPhone(mobile_phone) == false) {
				$("span#message_handphone").addClass('invalid-feedback');
				$("span#message_handphone").html("Format nomor handphone salah");
				$("#mobile_phone").addClass('invalid');
				mobileDuplicate = true;
			}
		},
		error: function(jqXHR, exception) {}
	});
}

function duplicateEmail(element) {
	var email = $(element).val();
	$.ajax({
		type: "GET",
		url: $('input[name="checkemail_url"]').val(),
		data: {
			"email": email
		},
		dataType: "json",
		success: function(res) {
			if (validation.isEmail(email) == true) {
				$("span#emailUsed").removeClass('invalid-feedback');
				$("span#emailUsed").html("");
				if (res.exist) {
					$("span#emailUsed").addClass('invalid-feedback');
					$("span#emailUsed").html("Email sudah terpakai, silahkan masukkan email baru");
					$("#email").addClass('invalid');
					emailDuplicate = true;
				} else {
					$("span#emailUsed").removeClass('invalid-feedback');
					$("span#emailUsed").html("");
					$("#email").removeClass('invalid');
					emailDuplicate = false;
				}
			} else if (validation.isEmail(email) == false) {
				$("span#emailUsed").addClass('invalid-feedback');
				$("span#emailUsed").html("Format email salah");
				$("#email").addClass('invalid');
				emailDuplicate = true;
			}
		},
		error: function(jqXHR, exception) {}
	});
}

function duplicateKTP(element) {
	var id_no = $(element).val();
	$.ajax({
		type: "GET",
		url: $('input[name="checkKTP_url"]').val(),
		data: {
			"id_no": id_no
		},
		dataType: "json",
		success: function(res) {
			if (validation.isKTP(id_no) == true) {
				$("#id_no").removeClass('invalid');
				$("span#KTPout").removeClass('invalid-feedback');
				$("span#KTPout").html("");
				if (res.exist) {
					$("span#KTPout").addClass('invalid-feedback');
					$("span#KTPout").html("No. KTP sudah terpakai, silahkan masukkan No. KTP baru");
					$("#id_no").addClass('invalid');
					ktpduplicate = true;
				} else {
					$("span#KTPout").removeClass('invalid-feedback');
					$("span#KTPout").html("");
					$("#id_no").removeClass('invalid');
					ktpduplicate = false;
				}
			} else if (validation.isKTP(id_no) == false) {
				$("span#KTPout").addClass('invalid-feedback');
				$("span#KTPout").html("Isian KTP harus berupa angka dan terdiri dari 16 digit");
				$("#id_no").addClass('invalid');
				ktpduplicate = true;
			}
		},
		error: function(jqXHR, exception) {}
	});
}

function duplicateNPWP(element) {
	var npwp_no = $(element).val();
	$.ajax({
		type: "GET",
		url: $('input[name="checkNPWP_url"]').val(),
		data: {
			"npwp_no": npwp_no
		},
		dataType: "json",
		success: function(res) {
			if (validation.isNPWP(npwp_no) == true) {
				$("#npwp_no").removeClass('invalid');
				$("span#NPWPout").removeClass('invalid-feedback');
				$("span#NPWPout").html("");
				if (res.exist) {
					$("span#NPWPout").addClass('invalid-feedback');
					$("span#NPWPout").html("No. NPWP sudah terpakai, silahkan masukkan No. NPWP baru");
					$("#npwp_no").addClass('invalid');
					npwpduplicate = true;
				} else {
					$("span#NPWPout").removeClass('invalid-feedback');
					$("span#NPWPout").html("");
					$("#npwp_no").removeClass('invalid');
					npwpduplicate = false;
				}
			} else if (validation.isNPWP(npwp_no) == false) {
				$("span#NPWPout").addClass('invalid-feedback');
				$("span#NPWPout").html("Isian NPWP harus berupa angka dan terdiri dari 15 digit");
				$("#npwp_no").addClass('invalid');
				npwpduplicate = true;
			}
		},
		error: function(jqXHR, exception) {}
	});
}

$('#home_state').on('change', function(e) {
	var name = e.target.value;
	$.get('/district?name=' + name, function(data) {
		$('#home_city').empty();
		$('#home_city').append('<option value="0" disable="true" selected="true>-- Pilih Kota/Kabupaten --</option>');

		$.each(data, function(index, regenciesObj) {
			$('#home_city').append('<option value="' + regenciesObj.name + '">' + regenciesObj.name + '</option>');
		})
	});
});

function showTab(n) {
    // This function will display the specified tab of the form ...
    var x = document.getElementsByClassName("tab");
    x[n].style.display = "block";
    // ... and fix the Previous/Next buttons:
    if (n == 0) {
    	document.getElementById("prevBtn").style.display = "none";
    } else {
    	document.getElementById("prevBtn").style.display = "inline";
    }
    if (n == (x.length - 1)) {
    	document.getElementById("nextBtn").innerHTML = "Daftar";
    	$('#nextBtn').attr("disabled", "true");
    	$('#nextBtn').attr('type', 'submit');
    	$("span#message_checkbox-form").html("Silahkan Centang dan setujui Syarat dan Ketentuan");
    	$("span#message_checkbox-form").addClass("invalid-feedback");
    	$('#checkbox-form').click(function() {
    		if ($(this).is(':checked')) {
    			$('#nextBtn').removeAttr("disabled");
    			$("span#message_checkbox-form").html("");
    			$("span#message_checkbox-form").removeClass("invalid-feedback");
    		} else {
    			$('#nextBtn').attr("disabled", "true");
    			$("span#message_checkbox-form").html("Silahkan Centang dan setujui Syarat dan Ketentuan");
    			$("span#message_checkbox-form").addClass("invalid-feedback");
    		}
    	});
    } else {
    	document.getElementById("nextBtn").innerHTML = "Selanjutnya";
    	$('#nextBtn').removeAttr("disabled");
    }
    // ... and run a function that displays the correct step indicator:
    fixStepIndicator(n)
}

function nextPrev(n) {
    // This function will figure out which tab to display
    var x = document.getElementsByClassName("tab");
    // Exit the function if any field in the current tab is invalid:
    if (n == 1 && !validateForm()) return false;
    // Hide the current tab:
    // Increase or decrease the current tab by 1:
    // if you have reached the end of the form... :
    if ( (currentTab + n) >= x.length) {
    	if(grecaptcha.getResponse() == "") {
    		return;
    	}
        //...the form gets submitted:
        var amount_requested = $('#amount_requested').val();
        var current_equity = $('#current_equity').val();
        var current_asset = $('#current_asset').val();
        var current_inventory = $('#current_inventory').val();
        var current_receivable = $('#current_receivable').val();
        var current_debt = $('#current_debt').val();
        $('#amount_requested').val(parseInt(replaceString(amount_requested)));
        $('#current_equity').val(parseInt(replaceString(current_equity)));
        $('#current_asset').val(parseInt(replaceString(current_asset)));
        $('#current_inventory').val(parseInt(replaceString(current_inventory)));
        $('#current_receivable').val(parseInt(replaceString(current_receivable)));
        $('#current_debt').val(parseInt(replaceString(current_debt)));

        setTimeout(function() {
        	document.getElementById("regForm").submit();
        }, 500);

        $('.card-body').hide();
        $("#form-register-pd").append('<div id="form-after-submit" class="col-md-12">' +
        	'<div class="col-md-6 col-md-offset-3 center">' +
        	'<div class="content-popup">' +
        	'<h4 class="center infomasi-popup">Infomasi</h4>' +
        	'<br>' +
        	'<img style="width: 150px; margin-bottom: 30px;" src="../images/pohondana/logo_pd-ojk.png">' +
        	'<p>' +
        	'Mohon tidak memuat kembali halaman ini' +
        	'<br> ' +
        	'Silahkan tunggu beberapa saat' +
        	'</p>' +
        	'<img style="width: 100px;" src="../images/pohondana/loading-bar.gif">' +
        	'</div>' +
        	'</div>' +
        	'</div>');
        window.scrollTo(0,0);
        currentTab = currentTab + n;

        return false;
    }else{
    	console.log('asd', 'asdasd');
    	x[currentTab].style.display = "none";
    	currentTab = currentTab + n;
    // Otherwise, display the correct tab:
    showTab(currentTab);
}
}

function validateForm() {
	var x, y, z, i, valid = true;
	x = document.getElementsByClassName("tab");
	y = x[currentTab].getElementsByTagName("input");
	z = x[currentTab].getElementsByTagName("select");
	var pass1 = document.getElementById('password');
	var pass2 = document.getElementById('password-confirm');

	for (i = 0; i < y.length; i++) {
		console.log('validate - ' + y.length);
		console.log('currentTab - ' + currentTab);

		if (i == 1 && currentTab == 0) {
			console.log('value = ' + y[i].value);
			console.log('is empty? ' + validation.isEmpty(y[i].value, ""));
			if (validation.isEmpty(y[i].value, "") == true) {
				y[i].className += " invalid";
				valid = false;
				console.log('false');
			} else {
				valid = true;
				console.log('true');
			}
		}
        // Validasi No. Handphone
        else if (i == 3 && currentTab == 0) {

        	if (validation.isEmpty(y[i].value, "") == true) {
        		y[i].className += " invalid";
        		valid = false;

        		console.log('invalid - ');

        	}
        	if (mobileDuplicate == true) {
        		valid = false;
        	}
        }

        // Validasi KTP
        else if (i == 4 && currentTab == 0) {
        	if (validation.isEmpty(y[i].value, "") == true) {
        		y[i].className += " invalid";
        		valid = false;
        	}
        	if (ktpduplicate == true) {
        		valid = false;
        	}
        }

        // Validasi Email
        else if (i == 6 && currentTab == 0) {
        	console.log('check - ');
        	if (validation.isEmpty(y[i].value, "") == true) {
        		y[i].className += " invalid";
        		valid = false;

        		console.log('invalid - ');

        	}
        	if (emailDuplicate == true) {
        		valid = false;
        	}
        	console.log('valid - ');
        }

        // Validasi Password
        else if (i == 7 && currentTab == 0) {
        	if (validation.isPassword(y[i].value) == true) {
        		$("span#message_password").removeClass("invalid-feedback");
        		$("span#message_password").html("");
        		$("#password").removeClass('invalid');
        	} else {
        		y[i].className += " invalid";
        		valid = false;
        		$("span#message_password").addClass("invalid-feedback");
        		$("span#message_password").html("Isian password minimal 6 karakter");
        	}
        }
        // Validasi Password Confirm
        else if (i == 8 && currentTab == 0) {
        	if (validation.isPasswordConfirm(pass1.value, y[i].value) == true && validation.isPassword(pass1.value) == true) {
        		$("#password").removeClass('invalid');
        		$("#password-confirm").removeClass('invalid');
        	} else {
        		valid = false;
        		y[i].className += " invalid";
        	}
        }
        // Validasi Alamat dan Pilih Provinsi
        else if (i == 0 && currentTab == 1) {
        	if (validation.isEmpty(y[i].value, "") == false) {
        		$("span#message_address").html("");
        		$("#home_address").removeClass('invalid');
        	} else {
        		y[i].className += " invalid";
        		valid = false;
        		$("span#message_address").html("Silahkan Isi Alamat Perusahaan dengan Lengkap");
        	}
        	if (validation.isEmpty(z[i].value, "") == false) {
        		$("span#message_province").html("");
        		$("#home_state").removeClass('invalid');
        	} else {
        		z[i].className += " invalid";
        		valid = false;
        		$("span#message_province").html("Silahkan Pilih Provinsi");
        	}
        }
        // Validasi Kode Pos dan Pilih Kota / Kabupaten
        else if (i == 1 && currentTab == 1) {
        	if (validation.isKodePos(y[i].value) == true) {
        		$("span#kodePosOut").html("");
        		$("#home_postal_code").removeClass('invalid');
        	} else {
        		y[i].className += " invalid";
        		valid = false;
        		$("span#kodePosOut").html("Isian kode pos harus berupa angka dan terdiri dari 5 digit");
        	}
        	if (validation.isEmpty(z[i].value, "") == false) {
        		$("span#message_district").html("");
        		$("#home_city").removeClass('invalid');
        	} else {
        		y[i].className += " invalid";
        		valid = false;
        		$("span#message_district").html("Silahkan Pilih Kota/Kabupaten");
        	}
        }

        // validasi No Telp
        else if (i == 2 && currentTab == 1) {
        	if (validation.isTelephone(y[i].value) == true) {
        		$("span#message_telephone").html("");
        		$("#home_phone").removeClass('invalid');
        	} else {
        		y[i].className += " invalid";
        		valid = false;
        		$("span#message_telephone").html("Format No. Telepon Perusahaan Salah");
        	}
        }

        // validasi website URL
        else if (i == 3 && currentTab == 1) {
        	if (validation.isEmpty(y[i].value, "") == true) {
        		y[i].className += " invalid";
        		valid = false;
        	}
        	if (website_url == true) {
        		valid = false;
        	}
        }

        // Validasi NPWP dan Pilih Jenis Perusahaan
        else if (i == 0 && currentTab == 2) {
        	if (validation.isEmpty(y[i].value, "") == true) {
        		y[i].className += " invalid";
        		valid = false;
        	}

        	if (npwpduplicate == true) {
        		valid = false;
        	}

        	if (validation.isEmpty(z[i].value, "") == false) {
        		$("span#message_jenis_perusahaan").html("");
        		$("#company_type").removeClass('invalid');
        	} else {
        		z[i].className += " invalid";
        		valid = false;
        		$("span#message_jenis_perusahaan").html("Silahkan Pilih Jenis Perusahaan");
        	}
        }
        // Validasi Pilih Industri dan Upload NPWP
        else if (i == 1 && currentTab == 2) {
        	if (validation.isEmpty(y[i].value, "") == true) {
        		y[i].className += " invalid";
        		valid = false;
        	} else {
        		$("#npwp_doc").removeClass('invalid');
        	}

        	if (validation.isEmpty(z[i].value, "") == false) {
        		$("span#message_industry").html("");
        		$("#company_industry").removeClass('invalid');
        	} else {
        		z[i].className += " invalid";
        		valid = false;
        		$("span#message_industry").html("Silahkan Pilih Jenis Industri");        	
        	}
        }

        // Validasi Domisili Perusahaan dan Upload Akta Perusahaan
        else if (i == 2 && currentTab == 2) {
        	if (validation.isEmpty(y[i].value, "") == true) {
        		y[i].className += " invalid";
        		valid = false;
        	} else {
        		$("#akta_doc").removeClass('invalid');
        	}

        	if (validation.isEmpty(z[i].value, "") == false) {
        		$("span#message_status_domisili").html("");
        		$("#home_ownership").removeClass('invalid');
        	} else {
        		z[i].className += " invalid";
        		valid = false;
        		$("span#message_status_domisili").html("Silahkan Pilih Status Domisili Perusahaan");
        	}
        }
        // Validasi Amount Requested
        else if(i == 0 && currentTab == 4){
        	if (averageAmount == false) {
        		valid = false;
        	}
        }
        else if (y[i].className == 'invalid') {
        	valid = false;
        }
        else if (y[i].value == '') {
        	y[i].className += " invalid";
        	valid = false;
        }

    }
    // If the valid status is true, mark the step as finished and valid:
    if (valid) {
    	document.getElementsByClassName("step")[currentTab].className += " finish";

    }
    return valid; // return the valid status
}

function fixStepIndicator(n) {
    // This function removes the "active" class of all steps...
    var i, x = document.getElementsByClassName("step");
    for (i = 0; i < x.length; i++) {
    	x[i].className = x[i].className.replace(" active", "");
    }
    //... and adds the "active" class to the current step:
    x[n].className += " active";
}