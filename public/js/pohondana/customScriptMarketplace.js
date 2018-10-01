function Component(){};
Component.prototype = {
	animatedDelay: 'slow',
	actionSuccess: ' <i class="fa fa-check success"></i>',
	actionFailed: ' <i class="fa fa-check success"></i>',
	inputAmountFunded: 'input[name="amount_fundeds"]',
	inputUid: 'input[name="uid"]',
	inputAmount: 'input[name="jumlah"]',
	inputAmountFix: 'input[name="jumlah_fix"]',
	selectGrade: 'select[name="filter_grade"]',
	selectRate: 'input[name="filter_rate"]',
	btnNextLoanAmount: '.btn-next-loan-amount',
	btnNextInvestmentAmount: '.btn-next-investment-amount',
	btnNextInvestmentCriteria: '.btn-next-investment-criteria',
	btnLoanFunding: '.btn-loan-funding',
	timelineHeader: '.timeline-header',
	timelineBody: '.timeline-body',
	timelineFooter: '.timeline-footer',
	timelineEntryInvestmentCriteria: '.timeline-entry-investment-criteria',
	timelineEntryInvestmentAmount: '.timeline-entry-investment-amount',
	timelineEntryInvestmentSelectLoan: '.timeline-entry-investment-select-loan',
	modalLoanDetail: '#modal-loan-detail',
	detailLoanId: '#load-id',
	detailDescription: '#description',
	detailCreatedAt: '#created_at',
	detailAmountTotal: '#amount_total',
	detailAmountPending: '#amount_pending',
	detailTenor: '#tenor',
	detailInterestRate: '#interest_rate',
	detailLoanStatus: '#loan_status',
	detailInvestStatus: '#invest_status',
	formSubmitLoan: 'form.form-submit-loan',
	colSm12: '.col-md-12',
	amountFunded: 0,
	grade: '',
	investRate: '',
	dataUrl: '/marketplace/data',
	dataTable: '.data-tables-pohondana-marketplace',
    inputAmountInvestmentValue: 'input[name="amount_invesment_value"]',
    timelineEntryInvestmentAmountValue: '.timeline-entry-investment-amount-value',
    btnNextInvestmentAmountValue: '.btn-next-investment-amount-value',
    timelineEntryInvestmentFinish: '.timeline-entry-finish',
    loanAmount: 0,
	debug: true,
}

var comp = new Component();
var dataTable = '';

$(document).ready(function() {
	setEvent();
	initTable();
});

$(comp.inputAmountFunded).maskMoney({
    thousands: ".",
    precision: 0
});

$(comp.inputAmountInvestmentValue).maskMoney({
    thousands: ".",
    precision: 0
});

setEvent = () => {

    $(comp.inputAmountInvestmentValue).keyup(function(e) { checkAmountValue(e, this) });
    $(comp.inputAmountInvestmentValue).change(function(e) { changeAmountValue(e, this) });

	$(comp.btnNextInvestmentAmount).click(function(e) { processInvestmentAmount(e, $(this)) });
	$(comp.btnNextInvestmentCriteria).click(function(e) { processInvestmentCriteria(e, $(this)) });
	$(comp.btnNextInvestmentAmountValue).click(function(e) { processInvestmentAmountValue(e, this) })

	$(comp.timelineEntryInvestmentAmount).find(comp.timelineHeader).click(function(e) { onEntryClick(e, $(this)) });
	$(comp.timelineEntryInvestmentCriteria).find(comp.timelineHeader).click(function(e) { onEntryClick(e, $(this)) });
	$(comp.timelineEntryInvestmentAmountValue).find(comp.timelineHeader).click(function(e) { onEntryClick(e, $(this)) });
	$(comp.timelineEntryInvestmentFinish).find(comp.timelineHeader).click(function(e) { onEntryClick(e, $(this)) });
	$('.table-loan-content').find(comp.timelineHeader).click(function(e) { onEntryClick(e, $(this)) });
	$(comp.selectGrade).change(function(e) { onGradeChange(e, $(this)) });

	/*$(comp.formSubmitLoan).submit(function(e){
		e.preventDefault();

		comp.amountFunded = replaceString($(comp.inputAmount).val());

		$(this).submit();
	});*/
}

initTable = () => {
	dataTable = $(comp.dataTable).DataTable( {
        /*"processing": true,
        "serverSide": true,
        "autoWidth": true,
        "ajax": {
        	url : "",
			type: 'GET',
			dataType: 'JSON',
			error: function(error) {
				console.log('error', error);
			}
        },
        "deferLoading": 1,
        "pageLength": 10, */
        searching: false,
		paging: true,
    });
    /*dataTable.ajax.url(comp.dataUrl).load();
    dataTable.draw();*/
}

processInvestmentAmount = (e, context) => {
	var parent = $(context).parent().parent();
	var inputText = $(comp.inputAmountFunded);
	if (inputText.val() == '') {
		inputText.parent().find('span').remove();
		inputText.parent().append('<span class="error">Masukkan nominal investasi</span>');
		return;
	}

	inputText.parent().parent().parent().parent().parent().find('.timeline-icon').removeClass('bg-success');
	inputText.parent().find()
	inputText.val(replaceString(inputText.val()));	
	comp.amountFunded = replaceString(inputText.val());
	comp.grade = $(comp.selectGrade).val();
	comp.investRate = $(comp.selectRate).val();
	//$(comp.inputAmount).val(comp.amountFunded);
	$(comp.inputAmountFix).val(comp.amountFunded);

    getLoanData();
	// hideTimeline(parent);

	//$(comp.timelineEntryInvestmentCriteria).show(comp.animatedDelay);
	$(comp.timelineEntryInvestmentAmount).show(comp.animatedDelay);
    $(comp.timelineEntryInvestmentSelectLoan).show(comp.animatedDelay);
    onEntryClick(null, $(comp.timelineEntryInvestmentSelectLoan).find(comp.timelineHeader));
}

processInvestmentCriteria = (e, context) => {
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
	var id = $(context).data('id');
	$(comp.inputUid).val(id);

	/*$(comp.modalLoanDetail).modal('show');
	getLoan(id);
	console.log(' onclick : id', id);*/

    comp.loanAmount = $(context).data('amount');
    $(comp.inputAmountInvestmentValue).val('0');

    // hideTimeline(parent);
    onEntryClick(null, $(comp.timelineEntryInvestmentAmountValue).find(comp.timelineHeader));
	$(comp.timelineEntryInvestmentFinish).hide(comp.animatedDelay);

    $(comp.timelineEntryInvestmentAmountValue).show(comp.animatedDelay);
    parent.find('.timeline-icon').removeClass('bg-success');

}

processInvestmentAmountValue = (e, context) => {
	var parent = $(context).parent().parent();
    var value = replaceString($(context).val());
    
    $(context).parent().find('br').remove();
    $(context).parent().find('span').remove();

    if (value > comp.loanAmount) {
        comp.amountOffset = true;
        $(context).parent().append('<br><span class="error">Jumlah pendanaan melebihi jumlah sisa peminjaman</div>');
    	return;
    }

    if (parseInt($(comp.inputAmountInvestmentValue).val()) < 1000000 || $(comp.inputAmountInvestmentValue).val() == "") {
        comp.amountOffset = true;
        $(context).parent().append('<br><span class="error">Minimum pendanaan Rp. 1.000.000</div>');
    	return;
    }

	// hideTimeline(parent);

	$(comp.timelineEntryInvestmentFinish).show(comp.animatedDelay);
    onEntryClick(null, $(comp.timelineEntryInvestmentFinish).find(comp.timelineHeader));

	parent.parent().find('.timeline-icon').removeClass('bg-success');
}

checkAmountValue = (e, context) => {
    var value = replaceString($(context).val());
    $(context).parent().find('span').remove();

    if (value > comp.loanAmount) {
        comp.amountOffset = true;
        $(context).parent().append('<span class="error">Jumlah pendanaan melebihi jumlah sisa peminjaman</div>');
        return;
    }

    $('.payment-amount').html('Rp. ' + formatRupiah(parseInt(value)));
    $(comp.inputAmount).val(value);
}

changeAmountValue = (e, context) => {
    $(context).val(replaceString($(context).val()));
}

onEntryClick = (e, context) => {
	$(context).parent().find(comp.timelineBody).show(comp.animatedDelay);
	$(context).parent().find(comp.timelineFooter).show(comp.animatedDelay);

	$(context).parent().parent().find('.timeline-icon').addClass('bg-success');
}

replaceString = (value) => {
    while (value.indexOf('.') > 0) {
        value = value.replace(".", "");
    }
    return value;
}

getLoanData = () => {

	console.log(comp.amountFunded);
	console.log(comp.grade);
	console.log(comp.investRate);
	$.ajax({
		url: comp.dataUrl,
		type: 'GET',
		dataType: 'JSON',
		data: {
			amount_funded: comp.amountFunded,
			grade: comp.grade,
			investRate: comp.investRate,
		},
	})
	.done(function(data) {
		console.log('data', data);
		var dataTables = data.data;
		dataTable.clear();
		dataTables.forEach((value, index) => {
			dataTable.row.add(value).draw( false );
		});
		/*$(comp.btnLoanFunding).click(function(e) { onLoanFundingClick(e, $(this)) });*/
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}

getLoan = (id) => {
	$(comp.modalLoanDetail).find(comp.colSm12).hide();
	$(comp.modalLoanDetail).find('.modal-body').append('<div class="image-loader"><div class="loader"></div></div>');
	$.ajax({
		url: '/marketplace/loanapi/' + id,
		type: 'GET',
		dataType: 'JSON',
	})
	.done(function(data) {
		$(comp.modalLoanDetail).find(comp.colSm12).show('slow');
		$(comp.modalLoanDetail).find('.image-loader').remove();
		comp.debug ? console.log('data', data) : '';
		var amountFunded = data.amount_funded;
		var investStatus = amountFunded != null ? Math.round( (data.amount_funded/data.amount_total) * 100 ) : 0;

		var name = data.name;

		$(comp.detailLoanId).text('#' + id);
		$(comp.detailDescription).text(data.description);
		$(comp.detailAmountTotal).text(formatRupiah(parseInt(data.amount_total), 'Rp.'));
		$(comp.detailAmountPending).text(formatRupiah(parseInt(data.amount_total - data.amount_funded), 'Rp.'));
		$(comp.detailCreatedAt).text(data.created_date);
		$(comp.detailTenor).text(data.month + ' Bulan');
		$(comp.detailInterestRate).text( (data.interest_rate == null ? 0 : data.interest_rate) + '%');
		$(comp.detailLoanStatus).text(data.name);
		$(comp.detailInvestStatus).text(investStatus + '%');


	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}

onGradeChange = (e, context) => {
	console.log('dasdasdata',  $(context).val());
	$.ajax({
		url: '/marketplace/lendergrade/' + $(context).val(),
		type: 'GET',
		dataType: 'JSON',
	})
	.done(function(data) {
		$(comp.selectRate).val(data.lender_rate);
		console.log("success", data);
	})
	.fail(function(error) {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
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