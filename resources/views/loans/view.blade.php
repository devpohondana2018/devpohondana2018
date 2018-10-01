@extends('layouts.header-dashboard') @section('content')
<div class="container">

    <div class="row">
        <div class="col-md-6 col-xs-12">
            <h1 class="mb-4">Informasi Pinjaman</h1>
            @include('includes.notification')
            <div class="card">
                <div class="card-header" style="padding-left:0;">
                    <strong>Pinjaman #{{$loan->id}}</strong>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p>{{$loan->description}}</p>
                        </div>
                    </div>
                    <div class="card-body-detail">

                        {{-- Tampilkan info singkat hanya jika status masih pending --}}

                        @if($loan->status_id == '1')

                        <div class="row nopadding">

                            <div class="col-md-6">
                                <dt>Pokok Pinjaman:</dt>
                            </div>

                            <div class="col-md-6">
                                <dd>Rp {{number_format($loan->amount_requested,2)}}</dd>
                            </div>
                        </div>

                        <div class="row nopadding">
                            <div class="col-md-6">
                                <dt>Tanggal Pengajuan:</dt>
                            </div>

                            <div class="col-md-6">
                                <dt>{{$loan->created_at->format('d/m/Y')}}</dt>
                            </div>
                        </div>

                        <div class="row nopadding">
                            <div class="col-md-6">
                                <dt>Tenor Pinjaman:</dt>
                            </div>

                            <div class="col-md-6">
                                <dt>{{$loan->tenor->month}} bulan</dt>
                            </div>
                        </div>

                        <hr>

                        @endif

                        {{-- Tampilkan semua info hanya jika status belum approved --}}

                        @if($loan->status_id == '2')

                        <div class="row nopadding">

                            <div class="col-md-6">
                                <dt>Pokok Pinjaman:</dt>
                            </div>

                            <div class="col-md-6">
                                <dd>Rp {{number_format($loan->amount_requested,2)}}</dd>
                            </div>
                        </div>

                        <div class="row nopadding">

                            <div class="col-md-6">
                                <dt>Bunga Pinjaman:</dt>
                            </div>

                            <div class="col-md-6">
                                <dd>Rp {{number_format($loan->interest_fee,2)}}</dd>
                            </div>
                        </div>

                        <div class="row nopadding">

                            <div class="col-md-6">
                                <dt>Pokok + Bunga:</dt>
                            </div>

                            <div class="col-md-6">
                                <dd>Rp {{number_format($loan->amount_total,2)}}</dd>
                            </div>
                        </div>

                        <div class="row nopadding">

                            <div class="col-md-6">
                                <dt>Platform Fee:</dt>
                            </div>

                            <div class="col-md-6">
                                <dd>Rp {{number_format($loan->provision_fee,2)}}</dd>
                            </div>
                        </div>

                        <div class="row nopadding">

                            <div class="col-md-6">
                                <dt>Balance Pinjaman:</dt>
                            </div>

                            <div class="col-md-6">
                                <dd>Rp {{number_format( $balance ,2)}}</dd>
                            </div>
                        </div>

                        <hr>

                        <div class="row nopadding">
                            <div class="col-md-6">
                                <dt>Tanggal Pengajuan:</dt>
                            </div>

                            <div class="col-md-6">
                                <dt>{{$loan->created_at->format('d/m/Y')}}</dt>
                            </div>
                        </div>

                        <div class="row nopadding">
                            <div class="col-md-6">
                                <dt>Tenor Pinjaman:</dt>
                            </div>

                            <div class="col-md-6">
                                <dt>{{$loan->tenor->month}} bulan</dt>
                            </div>
                        </div>

                        <div class="row nopadding">
                            <div class="col-md-6">
                                <dt>Rate Bunga Pinjaman:</dt>
                            </div>

                            <div class="col-md-6">
                                <dt>{{@$loan->interest_rate}}%</dt>
                            </div>
                        </div>

                        <div class="row nopadding">
                            <div class="col-md-6">
                                <dt>Rate Pendana:</dt>
                            </div>

                            <div class="col-md-6">
                                <dt>{{@$loan->invest_rate}}%</dt>
                            </div>
                        </div>

                        <div class="row nopadding">

                            <div class="col-md-6">
                                <dt>Total Pendanaan:</dt>
                            </div>

                            <div class="col-md-6">
                                <dd>Rp {{number_format( $loan->amount_funded,2)}}</dd>
                            </div>
                        </div>

                        <hr>

                        @endif

                    </div>
                </div>

                <div class="card-footer">
                    @if($loan->status_id == '2')
                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-md-6 center">
                            <!--  -->
                            <a href="{{action('LoanController@acceptLoan', $loan['id'])}}" class="btn-approve btn-action-approve bold">Setujui Pinjaman</a>
                            <!-- <a href="#" class="btn-approve btn-action-approve bold">Setujui Pinjaman</a> -->
                        </div>

                        <div class="col-md-6 center">
                            <!--  -->
                            <a href="{{action('LoanController@declineLoan', $loan['id'])}}" class="btn-decline btn-action-decline bold">Tolak Pinjaman</a>
                            <!-- <a href="#" class="btn-decline btn-action-decline bold">Tolak Pinjaman</a> -->
                        </div>
                    </div>

                    @endif

                    @if($loan->status_id == '3')
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{url('member/loans/doc/'.$loan['id'])}}" class="btn-approve bold" target="_blank">Lihat Dokumen Pinjaman</a>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <p style="margin-bottom: 0px;">Keterangan status pinjaman:</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <p><strong>{{$loan->status->description}}</strong></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-6 col-xs-12">
            @if($loan->status_id == '2' || $loan->status_id == '3') @if(!empty($currentPendingInstallment))
            <h1 class="mb-4">Pembayaran Selanjutnya</h1>
            <div class="card card-default mb-3">
                <div class="card-body">
                    <div class="pending-payment">

                        @if($midtrans->isProduction())
                        <div class="payment-image">
                            <img src="https://upload.wikimedia.org/wikipedia/id/thumb/e/e0/BCA_logo.svg/1280px-BCA_logo.svg.png" height="30px">
                        </div>
                        <div class="amount">Rp. {{ number_format($currentPendingInstallment['amount'], 0, '.', '.') }}</div>
                        <div class="va-number">VA Number : {{ $userVa->va_number }} </div>
                        <div class="due-date">
                            {{ \Carbon\Carbon::createFromTimestamp($currentPendingInstallment['due_date'])->format('d/m/Y') }}
                        </div>
                        <button class="btn btn-sm btn-primary" data-toggle="modal" href='#modal-payment-instruction'>Instruksi Pembayaran</button>
                        @else
                        <div class="payment-description">
                            Mohon lakukan pembayaran ke Nomor Rekening dibawah ini
                        </div>
                        <div class="payment-image">
                            <img src="https://upload.wikimedia.org/wikipedia/id/thumb/e/e0/BCA_logo.svg/1280px-BCA_logo.svg.png" height="30px">
                        </div>
                        <div class="amount">Rp. {{ number_format($currentPendingInstallment['amount'], 0, '.', '.') }}</div>
                        <div class="va-number">No. Rek. 5455.650.272</div>
                        <div>An: PT pohon dana indonesia</div>
                        <div class="due-date">Jatuh Tempo : {{ \Carbon\Carbon::createFromTimestamp($currentPendingInstallment['due_date'])->format('d/m/Y') }}
                        </div>
                        <!-- <button class="btn btn-sm btn-primary" data-toggle="modal" href='#modal-payment-confimation'>Konfirmasi Pembayaran</button> -->
                        @endif
                    </div>

                </div>
            </div>
            @endif @if($loan->installments->count() > 0)
            <h1 class="mb-4">Daftar Cicilan Pinjaman</h1>
            <div class="card card-default mb-3">
                <div class="card-body">
                    <!-- <div class="progress mb-3">
                        <div class="progress-bar progress-bar-striped" role="progressbar" style="width: {{round(($loan->amount_paid/$loan->amount_requested)*100)}}%;">
                            {{round(($loan->amount_paid/$loan->amount_requested)*100)}}%
                        </div>
                    </div> -->
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
                            @foreach($installments as $installment)
                            <tr>
                                <td>{{$installment->tenor}}</td>
                                <td>Rp {{number_format($installment->amount,2)}}</td>
                                <td>{{$installment->due_date->format('d/m/Y')}}</td>
                                <td>{{strtoupper($installment->paid == 1 ? 'paid' : 'unpaid')}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif @endif

        </div>
    </div>

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
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($currentPendingInstallment))

    <div class="modal fade" id="modal-payment-confimation">
        <div class="modal-dialog modal-lg">
            <form method="post" id="form-payment-confirmation" action="{{ route('loan-payment-confimation', ['id' => $currentPendingInstallment['id']]) }}" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Konfimasi Pembayaran</h4>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <div class="input-group image-payment-picker">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-success-pohondana btn-image-picker">Pilih Gambar</button>
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
            </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Kirim</button>
            </div>
            </div>
            </form>
        </div>
    </div>

    @endif
</div>
@endsection @section('javascript')
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
        btnActionApprove: '.btn-action-approve',
        btnActionDecline: '.btn-action-decline',
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
        $(comp.btnActionApprove).click(function(e) {
            onActionClick(e, this)
        });
        $(comp.btnActionDecline).click(function(e) {
            onActionClick(e, this)
        });

    }

    onActionClick = (e, context) => {
        //$(context).parent().parent().parent().hide();
        $(context).parent().parent().hide();
        $('.card-body').find('.row').hide();
        $('.card-body').append(preloader);
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