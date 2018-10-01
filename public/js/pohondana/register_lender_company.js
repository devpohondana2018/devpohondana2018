function Component() {};
Component.prototype = {
    cardBody: '.card-body',
    animatedDelay: 'slow',
    actionSuccess: ' <i class="fa fa-check success"></i>',
    actionFailed: ' <i class="fa fa-check success"></i>',

    /**** INPUT ****/
    path: 'input[name="autocomplete_url"]',
    inputCompanyId: 'input[name="company_id"]',
    inputName: 'input[name="name"]',
    selectEmploymentType: 'select[name="employment_type"]',
    inputMobilePhone: 'input[name="mobile_phone"]',
    inputIdNo: 'input[name="id_no"]',
    inputIdDoc: 'input[name="id_doc"]',
    inputIdDocBase64: 'textarea[name="id_doc_base_64"]',
    inputEmail: 'input[name="email"]',
    inputPassword: 'input[name="password"]',
    inputConfirmation: 'input[name="password_confirmation"]',
    inputNPWPNo: 'input[name="npwp_no"]',
    inputNPWPDoc: 'input[name="npwp_doc"]',
    inputNPWPDocBase64: 'textarea[name="npwp_doc_base_64"]',
    inputAktaDoc: 'input[name="akta_doc"]',
    inputAktaDocBase64: 'textarea[name="akta_doc_base_64"]',
    inputSIUPDoc: 'input[name="home_doc"]',
    inputSIUPDocBase64: 'textarea[name="home_doc_base_64"]',
    inputTDPDoc: 'input[name="tdp_doc"]',
    inputTDPDocBase64: 'textarea[name="tdp_doc_base_64"]',
    inputAmountInvestment: 'input[name="amount_invesment"]',
    inputRateInvestment: 'input[name="rate_investment"]',
    inputLoanId: 'input[name="loan_id"]',
    selectProvinces: 'select[name="home_state"]',
    selectHomeCity: 'select[name="home_city"]',
    selectGradeInvestment: 'select[name="grade_investment"]',
    inputAccountName: 'input[name="account_name"]',
    inputAmountFunded: 'input[name="amount_invesment"]',
    selectGrade: 'select[name="grade_investment"]',
    selectRate: 'input[name="rate_investment"]',
    inputAmountInvestmentValue: 'input[name="amount_invesment_value"]',

    /**** BUTTON ****/
    btnNextLoanAmount: '.btn-next-investment-amount',
    btnNextInvestmentCriteria: '.btn-next-investment-criteria',
    btnLoanFunding: '.btn-loan-funding',

    /**** TIMELINE ****/
    timelineHeader: '.timeline-header',
    timelineBody: '.timeline-body',
    timelineFooter: '.timeline-footer',
    timelineEntryInvestmentCriteria: '.timeline-entry-investment-criteria',
    timelineEntryInvestmentAmount: '.timeline-entry-investment-amount',
    timelineEntryInvestmentSelectLoan: '.timeline-entry-investment-select-loan',
    timelineEntryInvestmentAmountValue: '.timeline-entry-investment-amount-value',

    /**** PROCESS ****/
    checkEmailMobilePhone: {
        url: '/checkmobilephone',
        data: { mobile_phone: $(this.inputMobilePhone).val() },
        beforeSend: () => { $(comp.inputMobilePhone).parent().append(comp.loader); },
        complete: () => { $(comp.inputMobilePhone).parent().find('.loader').remove(); }
    },
    checkEmailDuplicate: {
        url: '/checkemailV2',
        data: { email: $(this.inputEmail).val() },
        beforeSend: () => { $(comp.inputEmail).parent().append(comp.loader); },
        complete: () => { $(comp.inputEmail).parent().find('.loader').remove(); }
    },
    checkIdDuplicate: {
        url: '/checkKTPV2',
        data: { id_no: $(this.inputIdNo).val() },
        beforeSend: () => { $(comp.inputIdNo).parent().append(comp.loader); },
        complete: () => { $(comp.inputIdNo).parent().find('.loader').remove(); }
    },
    checkNPWPDuplicate: {
        url: '/checkNPWPV2',
        data: { npwp_no: $(this.inputNPWPNo).val() },
        beforeSend: () => { $(comp.inputNPWPNo).parent().append(comp.loader); },
        complete: () => { $(comp.inputNPWPNo).parent().find('.loader').remove(); }
    },
    loader: '<div class="loader">' +
    '<img class="img-loader" src="../images/pohondana/loading-bar.gif">' +
    '</div>',
    dataTable: '.data-tables-pohondana',
    dataUrl: '/marketplacesdata',
    loanSelectMessage: '.loan-select-message',
    loanAmount: 0,
    amountOffset: false,
    debug: false,
}

var comp = new Component();
var form = $("form");
var dataTable = '';

$(document).ready(function() {
    initView();
    setEvent();
    initTable();
    setTypeaheadView();
    getLoanData();
});

function setTypeaheadView() {
    $('#company_id').typeahead({
       hint: true,
       highlight: true,
       minLength: 0,
    },
    {
      async: true,
      source: function (query, processSync, processAsync) {
    return $.ajax({
      url: $(comp.path).val(), 
      type: 'GET',
      data: {query: query},
      dataType: 'json',
      success: function (json) {
        console.log(json);
        var data = [];
        for(var i = 0; i < json.length; i++){
          data.push(json[i].name);
      }
      return processAsync(data);
  }
});
},
limit: 100,
});
}


    initView = () => {

        $(comp.inputAmountInvestment).maskMoney({
            thousands: ".",
            precision: 0
        });

        $(comp.inputAmountFunded).maskMoney({
            thousands: ".",
            precision: 0
        });

        $(comp.inputAmountInvestmentValue).maskMoney({
            thousands: ".",
            precision: 0
        });

    }

    setEvent = () => {
        $(comp.inputName).change(function(e) { onNameChange(e, this) });
        $(comp.selectProvinces).change(function(e) { getDistrict(e, $(this)) });
        $(comp.selectGradeInvestment).change(function(e) { getLoanRate(e, $(this)) });
        $(comp.inputAmountInvestmentValue).keyup(function(e) { checkAmountValue(e, this) });
        $(comp.inputAmountInvestmentValue).change(function(e) { changeAmountValue(e, this) });
        $(comp.inputIdDoc).change(function(e) { 
            var filesToUpload = document.getElementById('id_doc').files;
            var file = filesToUpload[0];
            resizeImage(file, 500, function(result) {
                console.log('result', result);
                $(comp.inputIdDocBase64).val(result);
            });
        });
        $(comp.inputNPWPDoc).change(function(e) { 
            var filesToUpload = document.getElementById('npwp_doc').files;
            var file = filesToUpload[0];
            resizeImage(file, 500, function(result) {
                console.log('result', result);
                $(comp.inputNPWPDocBase64).val(result);
            });
        });
        $(comp.inputAktaDoc).change(function(e) { 
            var filesToUpload = document.getElementById('akta_doc').files;
            var file = filesToUpload[0];
            resizeImage(file, 500, function(result) {
                console.log('result', result);
                $(comp.inputAktaDocBase64).val(result);
            });
        });
        $(comp.inputSIUPDoc).change(function(e) { 
            var filesToUpload = document.getElementById('home_doc').files;
            var file = filesToUpload[0];
            resizeImage(file, 500, function(result) {
                console.log('result', result);
                $(comp.inputSIUPDocBase64).val(result);
            });
        });
        $(comp.inputTDPDoc).change(function(e) { 
            var filesToUpload = document.getElementById('tdp_doc').files;
            var file = filesToUpload[0];
            resizeImage(file, 500, function(result) {
                console.log('result', result);
                $(comp.inputTDPDocBase64).val(result);
            });
        });

        $(comp.btnNextLoanAmount).click(function(e) { onAmountNext(e, this) });
        $(comp.btnNextInvestmentCriteria).click(function(e) { onGradeNext(e, this) });
        $(comp.selectGrade).change(function(e) { getLoanRate(e, $(this)) });

        $(comp.timelineEntryInvestmentAmount).find(comp.timelineHeader).click(function(e) { onEntryClick(e, $(this)) });
        $(comp.timelineEntryInvestmentCriteria).find(comp.timelineHeader).click(function(e) { onEntryClick(e, $(this)) });
        $(comp.timelineEntryInvestmentSelectLoan).find(comp.timelineHeader).click(function(e) { onEntryClick(e, $(this)) });

        form.submit(function(e) {
            $(comp.inputPassword).attr('type', 'text');
            $(comp.inputConfirmation).attr('type', 'text');
        });
    }

    initTable = () => {
        dataTable = $(comp.dataTable).DataTable({
            searching: false,
            responsive: true,
        });
    }

    handleFiles = (ev, context) => {
        var filesToUpload = document.getElementById('id_doc').files;
        var file = filesToUpload[0];

    // Create an image
    var img = document.createElement("img-data-resize");
    // Create a file reader
    var reader = new FileReader();
    // Set the image once loaded into file reader
    reader.onload = function(e)
    {
        img.src = e.target.result;

        var canvas = document.createElement("canvas");
        //var canvas = $("<canvas>", {"id":"testing"})[0];
        var ctx = canvas.getContext("2d");
        ctx.drawImage(img, 0, 0);

        var MAX_WIDTH = 400;
        var MAX_HEIGHT = 300;
        var width = img.width;
        var height = img.height;

        if (width > height) {
          if (width > MAX_WIDTH) {
            height *= MAX_WIDTH / width;
            width = MAX_WIDTH;
        }
    } else {
      if (height > MAX_HEIGHT) {
        width *= MAX_HEIGHT / height;
        height = MAX_HEIGHT;
    }
}
canvas.width = width;
canvas.height = height;
var ctx = canvas.getContext("2d");
ctx.drawImage(img, 0, 0, width, height);

var dataurl = canvas.toDataURL("image/png");
        //document.getElementById('image').src = dataurl;     
        console.log('image', dataurl);
    }
    // Load files into file reader
    reader.readAsDataURL(file);


    // Post the data
    /*
    var fd = new FormData();
    fd.append("name", "some_filename.jpg");
    fd.append("image", dataurl);
    fd.append("info", "lah_de_dah");
    */
}

resizeImage = function(file, size, callback) {
    var fileTracker = new FileReader;
    fileTracker.onload = function() {
        var image = new Image();
        image.onload = function(){
            var canvas = document.createElement("canvas");
            /*
            if(image.height > size) {
                image.width *= size / image.height;
                image.height = size;
            }
            */
            if(image.width > size) {
                image.height *= size / image.width;
                image.width = size;
            }
            var ctx = canvas.getContext("2d");
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            canvas.width = image.width;
            canvas.height = image.height;
            ctx.drawImage(image, 0, 0, image.width, image.height);
            callback(canvas.toDataURL("image/png"));
        };
        image.src = this.result;
    }
    fileTracker.readAsDataURL(file);
    fileTracker.onabort = function() {
        alert("The upload was aborted.");
    }
    fileTracker.onerror = function() {
        alert("An error occured while reading the file.");
    }
};


onAmountNext = (e, context) => {
    var parent = $(context).parent().parent();
    var inputText = $(comp.inputAmountFunded);
    if (isEmpty(inputText.val())) {
        return;
    }

    inputText.parent().find('span').remove();

    inputText.parent().parent().parent().parent().parent().find('.timeline-icon').removeClass('bg-success');
    inputText.val(replaceString(inputText.val()));  
    comp.amountFunded = replaceString(inputText.val());
    comp.grade = $(comp.selectGrade).val();
    comp.investRate = $(comp.selectRate).val();
    $(comp.inputAmount).val(comp.amountFunded);
    $(comp.inputAmountFix).val(comp.amountFunded);

    getLoanData();
    // hideTimeline(parent);

    //$(comp.timelineEntryInvestmentCriteria).show(comp.animatedDelay);
    $(comp.timelineEntryInvestmentAmount).show(comp.animatedDelay);
    $(comp.timelineEntryInvestmentSelectLoan).show(comp.animatedDelay);
    onEntryClick(null, $(comp.timelineEntryInvestmentSelectLoan).find(comp.timelineHeader));
}

onGradeNext = (e, context) => {
    var parent = $(context).parent().parent();
    comp.grade = $(comp.selectGrade).val();
    comp.investRate = $(comp.selectRate).val();

    getLoanData();
    // hideTimeline(parent);

    $(comp.timelineEntryInvestmentSelectLoan).show(comp.animatedDelay);

    parent.parent().find('.timeline-icon').removeClass('bg-success');
}

onLoanFundingClick = (context) => {
    var parent = $(comp.timelineEntryInvestmentSelectLoan);

    $('.btn-loan-funding').removeClass('btn-success');
    $('.btn-loan-funding').addClass('btn-default');
    $(context).removeClass('btn-default');
    $(context).addClass('btn-success');
    $(comp.inputLoanId).val($(context).data('id'));
    $(comp.loanSelectMessage).html('');
    $('.actions.clearfix').show();

    comp.loanAmount = $(context).data('amount');

    // hideTimeline(parent);
    $(comp.timelineEntryInvestmentAmountValue).show(comp.animatedDelay);
    parent.find('.timeline-icon').removeClass('bg-success');

    return false;
}

checkAmountValue = (e, context) => {
    var value = replaceString($(context).val());
    $(context).parent().find('span').remove();

    comp.amountOffset = false;

    if (value > comp.loanAmount) {
        comp.amountOffset = true;
        $(context).parent().append('<span class="error">Jumlah pendanaan melebihi jumlah sisa peminjaman</div>');
    }

}

changeAmountValue = (e, context) => {
    $(context).val(replaceString($(context).val()));
}

getLoanData = () => {

    $.ajax({
        url: comp.dataUrl,
        type: 'GET',
        dataType: 'JSON',
        data: {
            amount_funded: $(comp.inputAmountFunded).val(),
            grade: $(comp.selectGrade).val(),
            investRate: $(comp.selectRate).val(),
        },
    })
    .done(function(data) {
        comp.debug ? console.log('data', data) : '';
        var dataTables = data.data;
        dataTable.clear();
        dataTables.forEach((value, index) => {
            dataTable.row.add(value).draw( false );
        });
        /*$(comp.btnLoanFunding).click(function(e) { onLoanFundingClick(e, $(this)) });*/
    })
    .fail(function() {
        comp.debug ? console.log("error") : '';
    })
    .always(function() {
        comp.debug ? console.log("complete") : '';
    });
    
}

getLoanRate = (e, context) => {
    $.ajax({
        url: '/loangrage/' + $(context).val(),
        type: 'GET',
        dataType: 'JSON',
    })
    .done(function(data) {
        comp.debug ? console.log("data", data) : '';
        $(comp.inputRateInvestment).val(data.lender_rate);
        $(comp.selectRate).val(data.lender_rate);
    })
    .fail(function() {
        comp.debug ? console.log("error") : '';
    })
    .always(function() {
        comp.debug ? console.log("complete") : '';
    });
    
}

onEntryClick = (e, context) => {
    $(context).parent().find(comp.timelineBody).show(comp.animatedDelay);
    $(context).parent().find(comp.timelineFooter).show(comp.animatedDelay);

    $(context).parent().parent().find('.timeline-icon').addClass('bg-success');
}

onNameChange = (e, context) => {
    $(comp.inputAccountName).val($(context).val());
}

var validator = form.validate({
    ignore: [],
    rules: {
        company_id: {
            required: true,
        },
        name: "required",
        mobile_phone: {
            required: true,
            number: true,
            minlength: 9,
            maxlength: 15,
            remote: comp.checkEmailMobilePhone,
        },
        employment_type: {
            required: true,
        },
        id_no: {
            required: true,
            number: true,
            minlength: 16,
            maxlength: 16,
            remote: comp.checkIdDuplicate,
        },
        id_doc: {
            required: true,
            extension: "jpg|jpeg|png",
            filesize: 11186230,
        },
        email: {
            required: true,
            email: true,   
            remote: comp.checkEmailDuplicate,
            isEmailValid: true,
        },
        password: {
            required: true,
            minlength: 6
        },
        password_confirmation: {
            required: true,
            minlength: 6,
            equalTo: "#password"
        },
        confirm: {
            equalTo: "#password"
        },
        home_address: {
            required: true,
            maxlength: 191
        },
        home_state: {
            required: true
        },
        home_city: {
            required: true
        },
        home_postal_code: {
            required: true,
            number: true,
            min: 10000,
            max: 99999,
        },
        home_phone: {
            required: true,
            number: true
        },
        website_url: {
            required: true,
            isWebsite: true
        },
        company_type: {
            required: true
        },
        company_industry: {
            required: true
        },
        home_ownership: {
            required: true
        },
        npwp_no: {
            required: true,
            number: true,
            min: 100000000000000,
            max: 999999999999999,
            remote: comp.checkNPWPDuplicate,
        },
        npwp_doc: {
            required: true,
            extension: "jpg|jpeg|png",
            filesize: 11186230
        },
        akta_doc: {
            required: true,
            extension: "jpg|jpeg|png",
            filesize: 11186230,
        },
        home_doc: {
            required: true,
            extension: "jpg|jpeg|png",
            filesize: 11186230,
        },
        tdp_doc: {
            required: true,
            extension: "jpg|jpeg|png",
            filesize: 11186230,
        },
        amount_invesment: {
            required: true,
        },
        grade_investment: {
            required: true,
        },
        rate_investment: {
            required: true,
        },
        payment_method: {
            required: true,
        },
        bank_name: {
            required: true,
        },
        account_name: {
            required: true,
        },
        account_number: {
            required: true,
            number: true,
        },
        amount_invesment: {
            required: true,
            /*min: 100000,*/
        },
        amount_invesment_value: {
            required: true,
            minlength: 7,
            /*min: 100000,*/
        },
        agreed: {
            required: true,
        },
    },
    messages: {
        company_id: {
            required: "Silahkan isi nama Perusahaan"
        },
        name: "Masukkan nama",
        employment_type: {
            required: "Silahkan isi Jenis Pekerjaan"
        },
        mobile_phone: {
            required: 'Masukkan nomor telepon',
            number: 'Nomor telepon harus berupa angka',
            minlength: 'Nomor telepon minimal 9 karakter',
            maxlength: 'Panjang nomor telepon tidak boleh lebih dari 15 karakter'
        },
        id_no: {
            required: 'Masukkan nomor identitas',
            number: 'Nomor identitas harus berupa angka',
            minlength: 'Nomor identitas terlalu pendek',
            maxlength: 'Nomor identitas terlalu panjang'
        },
        id_doc: {
            required: 'Pilih gambar KTP',
            extension: "File harus berupa jpg/jpeg/png",
            filesize: "Maksimal ukuran file 10MB"
        },
        email: {
            required: 'E-mail harus diisi',
            email: 'E-mail tidak valid',
            remote: 'E-mail sudah digunakan',
            availableEmail: 'E-mail sudah digunakan',
        },
        password: {
            required: 'Masukkan password',
            minlength: 'Password minimal 6 karakter'
        },
        password_confirmation: {
            required: 'Masukkan konfirmasi password',
            minlength: 'Password minial 6 karakter',
            equalTo: 'Password tidak cocok'
        },
        home_address: {
            required: 'Masukkan alamat',
            maxlength: 'Alamat terlalu panjang'
        },
        home_state: {
            required: 'Pilih provinsi'
        },
        home_city: {
            required: 'Pilih Kabupaten/Kota'
        },
        home_postal_code: {
            required: 'Masukkan Kode Pos',
            number: 'Kode Pos harus berupa angka',
            min: 'Kode Pos Terlalu pendek',
            max: 'Kode Pos terlalu panjang',
        },
        home_phone: {
            required: 'Masukkan No. Telepon Perusahaan',
            number: 'No. Telepon harus berupa angka'
        },
        website_url: {
            required: "Silahkan isi Alamat URL Website Perusahaan"
        },
        company_type: {
            required: "Silahkan isi Jenis Perusahaan"
        },
        company_industry: {
            required: "Silahkan Pilih Industri"
        },
        home_ownership: {
            required: "Silahkan Pilih Status Domisili Perusahaan"
        },
        npwp_no: {
            required: 'Masukkan nomor NPWP Perusahaan',
            number: 'Nomor NPWP harus berupa angka',
            min: 'Nomor identitas terlalu pendek',
            max: 'Nomor identitas terlalu panjang'
        },
        npwp_doc: {
            required: 'Pilih gambar NPWP Perusahaan',
            extension: "File harus berupa jpg/jpeg/png",
            filesize: "Maksimal ukuran file 10MB"
        },
        akta_doc: {
            required: 'Pilih gambar Akta Perusahaan',
            extension: "File harus berupa jpg/jpeg/png",
            filesize: "Maksimal ukuran file 10MB"
        },
        home_doc: {
            required: 'Pilih gambar SIUP Perusahaan',
            extension: "File harus berupa jpg/jpeg/png",
            filesize: "Maksimal ukuran file 10MB"
        },
        tdp_doc: {
            required: 'Pilih gambar TDP Perusahaan',
            extension: "File harus berupa jpg/jpeg/png",
            filesize: "Maksimal ukuran file 10MB"
        },        
        payment_method: {
            required: 'Pilih Metode Pembayaran',
        },
        bank_name: {
            required: 'Pilih Bank',
        },
        account_name: {
            required: 'Masukkan Nama Pemilik Rekening',
        },
        account_number: {
            required: 'Masukkan No. Rekening',
            number: 'No. Rekening harus berupa angka',
        },
        agreed: {
            required: 'Harap setujui',
        },
        amount_fundeds: {
            required: 'Harap masukkan jumlah pendanaan',
        },
        filter_grade: {
            required: 'Harap pilih kriteria pendanaan',
        },
        amount_invesment: {
            required: 'Harap masukkan jumlah pendanaan maksimum',
            number: 'Data masukan harus berupa angka',
            min: 'Jumlah minimum pendanaan Rp. 100.000',
        },
        amount_invesment_value: {
            required: 'Harap masukkan jumlah pendanaan',
            number: 'Data masukan harus berupa angka',
            min: 'Jumlah minimum pendanaan Rp. 100.000',
            minlength: 'Jumlah minimum pendanaan Rp. 1.000.000',
        },

    },
    errorPlacement: function ( error, element ) {
        error.addClass( "help-block" );
        element.parent( ".col-md-6" ).addClass( "has-feedback" );

        if ( element.prop( "type" ) === "checkbox" ) {
            error.insertAfter( element );
        } else {
            error.insertAfter( element );
        }

        if ( !element.next( "span" )[ 0 ] ) {
            $( "<span class='glyphicon glyphicon-remove form-control-feedback'></span>" ).insertAfter( element );
        }
    },
    success: function ( label, element ) {
        if ( !$( element ).next( "span" )[ 0 ] ) {
            $( "<span class='glyphicon glyphicon-ok form-control-feedback'></span>" ).insertAfter( $( element ) );
        }else{
            $(element).parent().find('.form-control-feedback').remove();
            $(element).parent().find('.help-block').remove();
        }

    },
    highlight: function ( element, errorClass, validClass ) {
        $( element ).parents( ".col-md-6" ).addClass( "has-error" ).removeClass( "has-success" );
        $( element ).next( "span" ).addClass( "glyphicon-remove" ).removeClass( "glyphicon-ok" );
    },
    unhighlight: function ( element, errorClass, validClass ) {
        $( element ).parents( ".col-md-6" ).addClass( "has-success" ).removeClass( "has-error" );
        $( element ).next( "span" ).addClass( "glyphicon-ok" ).removeClass( "glyphicon-remove" );
    }

});
form.children("div").steps({
    headerTag: "h3",
    bodyTag: "section",
    transitionEffect: "slideLeft",
    enablePagination: true,
    enableCancelButton: false,
    startIndex: 0,
    labels: {
        cancel: "Cancel",
        current: "current step:",
        pagination: "Pagination",
        finish: "DAFTAR",
        next: "Berikutnya",
        previous: "Sebelumnya",
        loading: "Loading ..."
    },
    onStepChanging: function (event, currentIndex, newIndex) {
        comp.debug ? console.log('newIndex', newIndex) : '';
        comp.debug ? console.log('currentIndex', currentIndex) : '';
        comp.debug ? console.log('form.valid()', form.valid()) : '';

        if (currentIndex > newIndex)
        {
            return true;
        }

        // if (newIndex == 3 && $('#steps-uid-0-p-3').attr('style') == "display: block; left: 0px;"){
        //     $('.actions.clearfix').hide();
        // }
        if (newIndex == 3){
            $('.actions.clearfix').hide();
        }
        if (newIndex == 4) {
            // var amount = replaceString($(comp.inputAmountInvestment).val());
            // $(comp.inputAmountInvestment).val(amount);
            getLoanData();
        }
        if ( $('#steps-uid-0-p-3').attr('style') == "display: block; left: 0px;" ) {
            if($(comp.inputLoanId).val() == '' || $(comp.inputLoanId).val() == null){
                console.log('not valid table');
                $(comp.loanSelectMessage).html('<div class="alert alert-danger center">Pilih loan terlebih dahulu</div>');

                return false;
            }
        }

        if (currentIndex < newIndex)
        {
            // To remove error styles
            form.find('.has-error').removeClass('has-error');
            form.find(".body:eq(" + newIndex + ") label.error").remove();
            form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
        }

        if (comp.amountOffset) {
            return false;
        }

        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
    },
    onFinishing: function (event, currentIndex) {
        form.validate().settings.ignore = ":disabled";
        return form.valid();
    },
    onFinished: function (event, currentIndex) {
        if(grecaptcha.getResponse() == "") {
            return;
        }
        setTimeout(function() {
            form.submit();
        }, 500);
        $(comp.inputIdDoc).remove();
        $(comp.inputNPWPDoc).remove();
        $(comp.cardBody).hide();
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
    }
});



replaceString = (value) => {
    while (value.indexOf('.') > 0) {
        value = value.replace(".", "");
    }
    return value;
}

$.validator.addMethod('filesize', function (value, element, param) {
    if (element.files[0] == null) 
        return true;
    comp.debug ? console.log('size', element.files[0].size) : '';
    return (element.files[0].size <= param);
});

var today = new Date();

$.validator.addMethod('isAdult', function (value, element, param) {
    var year = Number(value.substr(5, 4));
    var month = Number(value.substr(2, 2)) - 1;
    var day = Number(value.substr(0, 2));
    var age = today.getFullYear() - year;
    if (today.getMonth() > month || (today.getMonth() == month && today.getDate() < day)) {
        age--;
    }

    return age >= 18
}, "Usia minimum 18 tahun");

$.validator.addMethod("isDate", function(value, element) {
    return isValidDate(value);
}, "Format tanggal harus dd/mm/yyyy.");

$.validator.addMethod("isWebsite", function(value, element) {
    var pattern = /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/;
    return pattern.test(String(value).toLowerCase());
}, "Format website harus http:// atau https://");

$.validator.addMethod("is_loan_select", function(value, element) {
    return $(comp.inputLoanId).val() != '';
}, "Format tanggal harus dd/mm/yyyy.");

$.validator.addMethod("isEmailValid", function(value, element) {
    var pattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return pattern.test(String(value).toLowerCase());
}, "E-mail tidak valid");

$.validator.addMethod("checkEmail", function(value, element) {
    $.ajax({
        url: $('input[name="checkemail_url"]').val(),
        type: 'GET',
        dataType: 'JSON',
        data: {
            email: value
        },
    })
    .done(function() {
        return true;
        comp.debug ? console.log("success") : '';
    })
    .fail(function() {
        return false;
        comp.debug ? console.log("error") : '';
    });
    
    return false;
}, "Email sudah digunakan");

isValidDate = (dateString) => {
    /*var regEx = /^\d{4}-\d{2}-\d{2}$/;
    if(!dateString.match(regEx)) 
        return false; 
    var d = new Date(dateString);
    if(!d.getTime() && d.getTime() !== 0) 
        return false; 
    return d.toISOString().slice(0,10) === dateString;*/
    var dtRegex = new RegExp(/\b\d{1,2}[\/]\d{1,2}[\/]\d{4}\b/);
    return dtRegex.test(dateString);
}

getDistrict = (e, context) => {
    var parent = $(comp.selectHomeCity).parent();
    parent.append(comp.loader);
    $.ajax({
        url: '/district',
        type: 'GET',
        dataType: 'JSON',
        data: { name: $(context).val() },
    })
    .done(function(data) {
        comp.debug ? console.log("data", data) : '';
        $(comp.selectHomeCity).empty();
        $.each(data, (index, value) => {
            $(comp.selectHomeCity).append($('<option/>', {
                value: value.name,
                text: value.name,
                selected: false
            }));
        });
    })
    .fail(function() {
        comp.debug ? console.log("error") : '';
    })
    .always(function() {
        parent.find('.loader').remove();
    });
}

isEmpty = (value) => {
    var isEmpty = false;
    if (value == null || value == '') {
        isEmpty = true;
    }

    return isEmpty;
}

errorMessage = (message) => {
    return '<span class="error">' + message + '</span>';
}

onGradeChange = (e, context) => {
    comp.debug ? console.log('dasdasdata',  $(context).val()) : '';
    $.ajax({
        url: '/marketplace/lendergrade/' + $(context).val(),
        type: 'GET',
        dataType: 'JSON',
    })
    .done(function(data) {
        $(comp.selectRate).val(data.lender_rate);
        comp.debug ? console.log("success", data) : '';
    })
    .fail(function(error) {
        comp.debug ? console.log("error") : '';
    })
    .always(function() {
        comp.debug ? console.log("complete") : '';
    });
    
}

hideTimeline = (parent) => {
    parent.find(comp.timelineHeader).find('.fa-check').remove();
    parent.find(comp.timelineHeader).append(comp.actionSuccess);
    parent.find(comp.timelineBody).hide(comp.animatedDelay);
    parent.find(comp.timelineFooter).hide(comp.animatedDelay);
}

formatRupiah = (bilangan, prefix) => {
    bilangan = bilangan + '';
    var number_string = bilangan.replace(/[^,\d]/g, '').toString(),
    split   = number_string.split(','),
    sisa    = split[0].length % 3,
    rupiah  = split[0].substr(0, sisa),
    ribuan  = split[0].substr(sisa).match(/\d{1,3}/gi);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
    
    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}