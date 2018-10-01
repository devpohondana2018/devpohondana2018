var emailDuplicate = false;
var mobileDuplicate = false;
var averageAmount = false;
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
    }
};

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

$(document).ready(function() {
    $(function() {
        $('#PBBKosong').hide();
        $('#home_ownership').change(function() {
            if ($('#home_ownership').val() == 'sendiri') {
                $('#PBBKosong').show();
                var element = document.getElementById("home_doc");
                //element.classList.add("invalid");
                element.classList.remove("kosong");
            } else {
                $('#PBBKosong').hide();
                var element = document.getElementById("home_doc");
                element.classList.add("kosong");
                element.classList.remove("invalid");
            }
        });
    });
});

$("#regForm").submit(function(e) {
    e.preventDefault();
    var salaryValue = $('#employment_salary').val();
    var requestValue = $('#amount_requested').val();

    $('#employment_salary').val(parseInt(replaceString(salaryValue)));
    $('#amount_requested').val(parseInt(replaceString(requestValue)));
    return false;
});

$("#amount_requested").maskMoney({
    thousands: ".",
    precision: 0
});
$("#amount_requested").keyup(function() {
    var value = $('#amount_requested').val();
    value = replaceString(value);
});

$("#employment_salary").maskMoney({
    thousands: ".",
    precision: 0
});
$("#employment_salary").keyup(function() {
    var value = $('#employment_salary').val();
    value = replaceString(value);
});

replaceString = (value) => {
    while (value.indexOf('.') > 0) {
        value = value.replace(".", "");
    }
    return value;
}

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
    console.log('ini' + amount_requested_new);
    if (amount_requested_new < 1000000) {
        $("span#message_confirm_amount").removeClass('valid-feedback');
        $("span#message_confirm_amount").addClass('invalid-feedback');
        $("#message_confirm_amount").html("Minimal Pinjaman Rp. 1.000.000,00");
        $("#amount_requested").addClass('invalid');
        console.log("validasi atas salah 1 jt");
        averageAmount = true;
    } else if(amount_requested_new > 2000000000){
        $("span#message_confirm_amount").removeClass('valid-feedback');
        $("span#message_confirm_amount").addClass('invalid-feedback');
        $("#message_confirm_amount").html("Maksimal Pinjaman Rp. 2.000.000.000,00");
        $("#amount_requested").addClass('invalid');
        console.log("validasi atas salah 2 m");
        averageAmount = true;
    } else {
        $("span#message_confirm_amount").removeClass('invalid-feedback');
        $("span#message_confirm_amount").addClass('valid-feedback');
        $("#message_confirm_amount").html("Pinjaman sesuai");
        $("#amount_requested").removeClass('invalid');
        console.log("validasi benar sekali");
        averageAmount = false;
    }
}

$("#amount_requested").keyup(checkAmountRequested);

function forceNumeric() {
    var $input = $(this);
    $input.val($input.val().replace(/[^\d]+/g, ''));
}

$('#employment_duration').on('propertychange input', 'input[type="number"]', forceNumeric);

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
                } else {
                    $("span#KTPout").removeClass('invalid-feedback');
                    $("span#KTPout").html("");
                    $("#id_no").removeClass('invalid');
                }
            } else {
                $("span#KTPout").addClass('invalid-feedback');
                $("span#KTPout").html("Isian KTP harus berupa angka dan terdiri dari 16 digit");
                $("#id_no").addClass('invalid');
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
                } else {
                    $("span#NPWPout").removeClass('invalid-feedback');
                    $("span#NPWPout").html("");
                    $("#npwp_no").removeClass('invalid');
                }
            } else {
                $("span#NPWPout").addClass('invalid-feedback');
                $("span#NPWPout").html("Isian NPWP harus berupa angka dan terdiri dari 15 digit");
                $("#npwp_no").addClass('invalid');
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
        var salaryValue = $('#employment_salary').val();
        var requestValue = $('#amount_requested').val();

        $('#employment_salary').val(parseInt(replaceString(salaryValue)));
        $('#amount_requested').val(parseInt(replaceString(requestValue)));


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
    console.log('validate');
    // This function deals with validation of the form fields
    var x, y, z, i, valid = true;
    x = document.getElementsByClassName("tab");
    y = x[currentTab].getElementsByTagName("input");
    z = x[currentTab].getElementsByTagName("select");
    var pass1 = document.getElementById('password');
    var pass2 = document.getElementById('password-confirm');
    var dob = $('#dob').val();
    var year = Number(dob.substr(0, 4));
    var month = Number(dob.substr(5, 2)) - 1;
    var day = Number(dob.substr(8, 2));
    var today = new Date();
    var age = today.getFullYear() - year;
    if (today.getMonth() < month || (today.getMonth() == month && today.getDate() < day)) {
        age--;
    }

    for (i = 0; i < y.length; i++) {
        console.log('validate - ' + y.length);
        console.log('currentTab - ' + currentTab);

        if (y[i].type.toLowerCase() != 'type') {

        }
        // Validasi No. Handphone
        if (i == 1 && currentTab == 0) {

            if (validation.isEmpty(y[i].value, "") == true) {
                y[i].className += " invalid";
                valid = false;

                console.log('invalid - ');

            }
            if (mobileDuplicate == true) {
                valid = false;
            }
        }
        //validasi Email
        else if (i == 2 && currentTab == 0) {
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
        else if (i == 3 && currentTab == 0) {
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
        else if (i == 4 && currentTab == 0) {
            if (validation.isPasswordConfirm(pass1.value, y[i].value) == true && validation.isPassword(pass1.value) == true) {
                $("#password").removeClass('invalid');
                $("#password-confirm").removeClass('invalid');
            } else {
                valid = false;
                y[i].className += " invalid";
            }
        }
        // Validasi Kode Pos dan Pilih Provinsi
        else if (i == 0 && currentTab == 1) {
            if (validation.isEmpty(y[i].value, "") == false) {
                $("span#message_address").html("");
                $("#home_address").removeClass('invalid');
            } else {
                y[i].className += " invalid";
                valid = false;
                $("span#message_address").html("Silahkan Isi Alamat Lengkap");
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
        // Validasi Nomor Telephone Rumah dan Status Tempat Tinggal
        else if (i == 2 && currentTab == 1) {

            /*if (validation.isEmpty(y[i].value, "") == false) {
                if (validation.isTelephone(y[i].value) == true) {
                    $("span#message_phone").removeClass("invalid-feedback");
                    $("span#message_phone").html("");
                    $("#mobile_phone").removeClass('invalid');
                } else {
                    y[i].className += " invalid";
                    valid = false;
                    $("span#message_phone").addClass("invalid-feedback");
                    $("span#message_phone").html("Format No. Telepon Rumah Salah");
                }
            }*/

            if (validation.isEmpty(z[i].value, "") == false) {
                $("span#message_status_tinggal").html("");
                $("#home_ownership").removeClass('invalid');
            } else {
                y[i].className += " invalid";
                valid = false;
                $("span#message_status_tinggal").html("Silahkan Pilih Status Tempat Tinggal");
            }
        }

        // Validasi Amount Requested
        else if(i == 0 && currentTab == 2){
            if (averageAmount == true) {
                valid = false;
            } else {
                $("#amount_requested").removeClass('invalid');
            }
        }

        // VALIDASI REKANAN
        else if (i == 1 && currentTab == 3) {
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
        // validasi KTP
        else if (i == 3 && currentTab == 1) {
            if (validation.isEmpty(y[i].value, "") == true) {
                y[i].className += " invalid";
                valid = false;
            } else if (y[i].className == "form-control invalid") {
                valid = false;
            }
        }
        // validasi NPWP
        else if (i == 5 && currentTab == 1) {
            if (validation.isEmpty(y[i].value, "") == true) {
                y[i].className += " invalid";
                valid = false;
            } else if (y[i].className == "form-control invalid") {
                valid = false;
            }
        }
        // Umur 
        else if (i == 8 && currentTab == 1) {
            if (validation.isEmpty(y[i].value, "") == true) {
                $("span#message_dob").html("Silahkan isi tanggal lahir");
                y[i].className += " invalid";
                valid = false;
            } else {
                if (age >= 18) {
                    $("span#message_dob").html("");
                    $("#dob").removeClass('invalid');
                } else if (age < 0) {
                    y[i].className += " invalid";
                    valid = false;
                    $("span#message_dob").html("Format tanggal lahir melebihi waktu saat ini");
                } else if (age >= 0 && age < 18) {
                    $("span#message_dob").html("Umur minimal harus 18 Tahun");
                    y[i].className += " invalid";
                    valid = false;
                } else if (validation.isEmpty(y[i].value, "") == true) {
                    $("span#message_dob").html("Silahkan isi tanggal lahir");
                    y[i].className += " invalid";
                    valid = false;
                }
            }
        }
        // Validasi Upload Slip Gaji
        else if (i == 1 && currentTab == 4) {
            /*if (validation.isEmpty(y[i].value, "") == true) {
                $("#employment_salary_slip").removeClass('invalid');
                y[i].className += " kosong";
            } else {
                y[i].className += " kosong";
            }*/
        }
        // Validasi Lama Bekerja Tahun
        else if (i == 3 && currentTab == 4) {
            if (validation.isLamaBekerja(y[i].value) == true) {
                $("span#lamaBerkerjaTahun").html("");
                $("#employment_duration_year").removeClass('invalid');
            } else {
                y[i].className += " invalid";
                valid = false;
                $("span#lamaBerkerjaTahun").html("Isian berupa angka dimulai dari angka 0");
            }
        }
        // Validasi Lama Bekerja Bulan
        else if (i == 4 && currentTab == 4) {
            if (validation.isBulanKerja(y[i].value) == true) {
                $("span#lamaBerkerjaBulan").html("");
                $("#employment_duration_month").removeClass('invalid');
            } else {
                y[i].className += " invalid";
                valid = false;
                $("span#lamaBerkerjaBulan").html("Isian berupa angka 0 - 11");
            }
        }

        //validaso upload npwp
        else if(i == 6 && currentTab == 1){

        }
        else if(i == 4 && currentTab == 1){
            $("#home_phone").removeClass('invalid');
        }
        else if(y[i].id == 'home_doc' && currentTab == 1){

        }
        else if (y[i].className == "form-control kosong" || y[i].className == "form-control invalid kosong") {
            valid == true;
        } else if (y[i].value == '') {
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