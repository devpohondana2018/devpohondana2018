@extends('layouts.header-dashboard')

@section('style')
<style type="text/css">
    .status-Paid {
        background-color: #3498db;
    }
    
    .status-Unpaid {
        background-color: #cc2e2e;
    }
</style>
@endsection

@section('content')
<div class="container">

    <div class="row">
        <div class="col-md-12 title-confirm-pd nopadding">
            @include('includes.notification')
            <div class="col-md-12">
                <h1 class="mb-4 center">Informasi Pendanaan</h1>
            </div>
           <!-- <div class="col-md-6 text-right">
                 @if($investment->status_id == '2')
                <a href="{{action('InvestmentController@acceptInvestment', $investment['id'])}}" class="btn btn-primary btn-sm">Setujui Pendanaan</a>
                <a href="{{action('InvestmentController@declineInvestment', $investment['id'])}}" class="btn btn-primary btn-sm">Tolak Pendanaan</a>
                @endif
            </div> -->
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-body-detail">

                        <div class="row nopadding">
                            <div class="col-md-6">
                                <dt>ID Pendanaan:</dt>
                            </div>
                            <div class="col-md-6">
                                <dd>{{$investment->code}}</dd>
                            </div>
                        </div>

                        <div class="row nopadding">
                            <div class="col-md-6">
                                <dt>Tangga Pendanaan:</dt>
                            </div>
                            <div class="col-md-6">
                                <dd>{{$investment->created_at->format('d/m/Y')}}</dd>
                            </div>
                        </div>

                        <div class="row nopadding">
                            <div class="col-md-6">
                                <dt>Jumlah Pendanaan:</dt>
                            </div>
                            <div class="col-md-6">
                                <dd>Rp {{number_format($investment->amount_invested)}}</dd>
                            </div>
                        </div>

                        <div class="row nopadding">
                            <div class="col-md-6">
                                <dt>Pengembalian Pendanaan:</dt>
                            </div>
                            <div class="col-md-6">
                                <dd>Rp {{number_format( $investment->amount_total,2 ) }}</dd>
                            </div>
                        </div>

                        @if($investment->loan)
                        <div class="row nopadding">
                            <div class="col-md-6">
                                <dt>Tenor Pinjaman:</dt>
                            </div>
                            <div class="col-md-6">
                                <dd>{{@$investment->loan->tenor->month}} bulan</dd>
                            </div>
                        </div>

                        <div class="row nopadding">
                            <div class="col-md-6">
                                <dt>Bunga Pinjaman:</dt>
                            </div>
                            <div class="col-md-6">
                                <dd>{{number_format($investment->loan->interest_rate,2)}}%</dd>
                            </div>
                        </div>
                        @endif

                        <div class="row nopadding">
                            <div class="col-md-6">
                                <dt>Bunga Pendanaan:</dt>
                            </div>
                            <div class="col-md-6">
                                <dd>{{number_format($investment->invest_rate,2)}}%</dd>
                            </div>
                        </div>

                        <div class="row nopadding">
                            <div class="col-md-6">
                                <dt>Status:</dt>
                            </div>
                            <div class="col-md-6">
                                <dd>
                                    @if($investment->paid == 1)
                                        @php $investment->status->name = 'In repayment' @endphp
                                    @endif
                                    @if($investment->paid == 0)
                                        @php $investment->status->name = 'Unpaid' @endphp
                                    @endif
                                    {{$investment->status->name}}
                                </dd>
                            </div>
                        </div>
                        
                        
                        @if($investment->paid == '1')
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ url('member/investments/doc/'.$investment->id) }}" target="_blank" class="btn btn-sm btn-success text-center btn-download align-right"><i class="fa fa-download"></i> Download Perjanjian Pendanaan</a>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        @if($investment->paid == 0)
        <div class="col-md-6 col-xs-12">
            <div class="card card-default mb-3">
                <div class="card-body">
                    <div class="pending-payment">
                        @if($midtrans->isProduction())
                        <div class="payment-image">
                            <img src="https://upload.wikimedia.org/wikipedia/id/thumb/e/e0/BCA_logo.svg/1280px-BCA_logo.svg.png" height="30px">
                        </div>
                        <p class="amount">Rp. {{ number_format($investment->amount_invested, 0, '.', '.') }}</p>
                        <p class="va-number">VA Number : {{ $userVa->va_number }} </p>
                        <p class="due-date">
                            Jatuh Tempo : {{ $investment->created_at->addDay()->format('d/m/Y') }}
                        </p>
                        <button class="btn btn-primary" data-toggle="modal" href='#modal-payment-instruction'>Instruksi Pembayaran</button>
                        @else
                        <p class="payment-description">
                            Anda belum melakukan pembayaran pendanaan.<br>
                            Mohon lakukan pembayaran ke Nomor Rekening dibawah ini sebelum tanggal jatuh tempo
                        </p>
                        <div class="payment-image">
                            <img src="https://upload.wikimedia.org/wikipedia/id/thumb/e/e0/BCA_logo.svg/1280px-BCA_logo.svg.png" height="30px">
                        </div>
                        <p class="amount">Rp. {{ number_format($investment->amount_invested, 0, '.', '.') }}</p>
                        <p class="va-number">No. Rek. 5455.650.272</p>
                        <p>An: PT pohon dana indonesia</p>
                        <p class="due-date">
                            Jatuh Tempo : {{ $investment->created_at->addDay()->format('d/m/Y') }}
                        </p>
                        <!-- <button class="btn btn-primary" data-toggle="modal" href='#modal-payment-confimation'>Konfirmasi Pembayaran</button> -->
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>

    @if($investment->installments && $investment->paid == 1)
    <div class="row martop-50">
        <div class="col-md-12">
            <h1 class="mb-4 center">Daftar Pengembalian Pendanaan</h1>
            <div class="card card-default mb-3">
                <div class="card-body">
                    <table class="data-tables-pohondana display responsive nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>Angsuran</th>
                                <th>Pembayaran</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($investment->installments as $installment)
                            <tr>
                                <td>{{$installment->tenor}}</td>
                                <td>Rp {{number_format($installment->amount)}}</td>
                                <td>{{$installment->due_date->format('d/m/Y')}}</td>
                                <td>{{strtoupper($installment->paid == 1 ? 'paid' : 'unpaid')}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="modal fade" id="modal-payment-instruction">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Instruksi Pembayaran</h4>
                </div>
                <div class="modal-body">
                    <h3>ATM BCA</h3>
                    <ol>
                        <li>Pada menu utama, pilih <b>Transaksi Lainnya</b></li>
                        <li>Pilih <b>Transfer</b></li>
                        <li>Pilih <b>Ke Rek BCA Virtual Account</b></li>
                        <li>Masukkan <b>Nomor Rekening</b> pembayaran (11 digit) Anda lalu tekan <b>Benar</b></li>
                        <li>Masukkan jumlah tagihan yang akan anda bayar</li>
                        <li>Pada halaman konfirmasi transfer akan muncul detail pembayaran Anda. Jika informasi telah sesuai tekan <b>Ya</b></li>
                    </ol>

                    <h3>Klik BCA</h3>
                    <ol>
                        <li>Pilih menu <b>Transfer Dana</b></li>
                        <li>Pilih <b>Transfer ke BCA Virtual Account</b></li>
                        <li><b>Masukkan nomor BCA Virtual Account</b>, atau <b>pilih Dari Daftar Transfer</b></li>
                        <li>Jumlah yang akan ditransfer, nomor rekening dan nama merchant akan muncul di halaman konfirmasi pembayaran, jika informasi benar klik <b>Lanjutkan</b></li>
                        <li>Ambil <b>BCA Token</b> Anda dan masukkan KEYBCA Response <b>APPLI 1</b> dan Klik <b>Submit</b></li>
                        <li>Transaksi Anda selesai</li>
                    </ol>

                    <h3>m-BCA</h3>
                    <ol>
                        <li>Pilih <b>m-Transfer</b></li>
                        <li>Pilih <b>Transfer</b></li>
                        <li>Pilih <b>BCA Virtual Account</b></li>
                        <li>Pilih <b>nomor rekening</b> yang akan digunakan untuk pembayaran</li>
                        <li>Masukkan nomor BCA Virtual Account, lalu pilih <b>OK</b></li>
                        <li>Nomor BCA Virtual Account dan nomor Rekening anda akan terlihat di halaman konfirmasi rekening, kemudian pilih <b>Kirim</b></li>
                        <li>Pilih <b>OK</b> pada halaman konfirmasi pembayaran</li>
                        <li>Masukan jumlah nominal yang akan di transfer dan berita kemudan pilih <b>OK</b></li>
                        <li>Transaksi Anda selesai</li>
                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-success-pohondana" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-payment-confimation">
        <div class="modal-dialog modal-lg">
            <form method="post" id="form-payment-confirmation" action="{{ route('investment-payment-confimation', ['id' => $investment->id]) }}" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Konfimasi Pembayaran</h4>
                </div>
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class="input-group image-payment-picker">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-success btn-success-pohondana btn-image-picker">Pilih Gambar</button>
                        </div>
                        <input type="text" name="image_payment_preview" class="form-control pointer" placeholder="Pilih bukti transfer" disabled required>
                    </div>
                    <p class="help-block">Pilih gambar dari perangkat Anda.</p>
                    <div class="form-group has-feedback div-add-ktp hidden">
                        <input type="file" id="image_payment" name="image_payment" accept="image/x-png, image/jpeg, image/jpg">
                    </div>
                    <div class="image-payment-preview">
                        <img src="#" alt="image-preview" id="image-payment-preview" class=" img img-rounded" style="display: none;">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Kirim</button>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/additional-methods.js"></script>
<script>
    function Component() {};
    Component.prototype = {
        inputImagePayment: 'input[name="image_payment"]',
        inputImagePaymentPreview: 'input[name="image_payment_preview"]',
        imagePaymentPicker: '.image-payment-picker',
        imagePaymentPreview: '#image-payment-preview',
        imagePaymentPreviewDiv: '.image-payment-preview',
    }

    var comp = new Component();
    var formPaymentConfirmation = $('form#form-payment-confirmation');

    $(document).ready(function() {
        setEvent();
    });

    setEvent = () => {
        $(comp.imagePaymentPicker).click(function(e) {
            onImagePaymentClick(e, this)
        });
        $(comp.inputImagePayment).change(function(e) {
            onInputImagePaymentChange(e, this)
        });
        $(comp.imagePaymentPreviewDiv).click(function(e) {
            onImagePaymentClick(e, this)
        });

    }

    onImagePaymentClick = (e, context) => {
        $(comp.inputImagePayment).click();
    }

    onInputImagePaymentChange = (e, context) => {
        console.log('files', context.files)
        if (context.files && context.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(comp.inputImagePaymentPreview).val($(context).val());
                $(comp.imagePaymentPreview).hide('fast');
                $(comp.imagePaymentPreview).show('slow');
                $(comp.imagePaymentPreview).attr('src', e.target.result);
            }
            reader.readAsDataURL(context.files[0]);
        } else {
            $(comp.inputImagePaymentPreview).val('');
            $(comp.imagePaymentPreview).hide('slow');
        }
    }

    formPaymentConfirmation.validate({
        rules: {
            image_payment_preview: {
                required: true,
            }
        },
        errorPlacement: function(error, element) {
            error.addClass("help-block");
            element.parent(".col-md-6").addClass("has-feedback");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element);
            } else {
                error.insertAfter(element);
            }

            if (!element.next("span")[0]) {
                $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
            }
        },
        success: function(label, element) {
            if (!$(element).next("span")[0]) {
                $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
            } else {
                $(element).parent().find('.form-control-feedback').remove();
                $(element).parent().find('.help-block').remove();
            }

        },
        highlight: function(element, errorClass, validClass) {
            $(element).parents(".col-md-6").addClass("has-error").removeClass("has-success");
            $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents(".col-md-6").addClass("has-success").removeClass("has-error");
            $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
        }
    });

    formPaymentConfirmation.submit((e) => {
        if (formPaymentConfirmation.valid()) {
            return true;
        }

        return false;
    });
</script>
@endsection