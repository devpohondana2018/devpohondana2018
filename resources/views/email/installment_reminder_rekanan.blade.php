<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>Installment Reminder</title> 
    <style>
    html,
    body {
        margin: 0 auto !important;
        padding: 0 !important;
        height: 100% !important;
        width: 100% !important;
    }
    * {
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
    }
    div[style*="margin: 16px 0"] {
        margin: 0 !important;
    }
    table,
    td {
        mso-table-lspace: 0pt !important;
        mso-table-rspace: 0pt !important;
    }
    table {
        border-spacing: 0 !important;
        border-collapse: collapse !important;
        table-layout: fixed !important;
        margin: 0 auto !important;
    }
    table table table {
        table-layout: auto;
    }
    img {
        -ms-interpolation-mode:bicubic;
    }
    *[x-apple-data-detectors],  /* iOS */
    .x-gmail-data-detectors,    /* Gmail */
    .x-gmail-data-detectors *,
    .aBn {
        border-bottom: 0 !important;
        cursor: default !important;
        color: inherit !important;
        text-decoration: none !important;
        font-size: inherit !important;
        font-family: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
    }
    .a6S {
        display: none !important;
        opacity: 0.01 !important;
    }
    img.g-img + div {
        display: none !important;
    }
    .button-link {
        text-decoration: none !important;
    }
    @media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
        .email-container {
            min-width: 320px !important;
        }
    }
    @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
        .email-container {
            min-width: 375px !important;
        }
    }
    @media only screen and (min-device-width: 414px) {
        .email-container {
            min-width: 414px !important;
        }
    }
</style>
<style>
.button-td,
.button-a {
    transition: all 100ms ease-in;
}
.button-td:hover,
.button-a:hover {
    background: #555555 !important;
    border-color: #555555 !important;
}
@media screen and (max-width: 600px) {

    .email-container {
        width: 100% !important;
        margin: auto !important;
    }
    .fluid {
        max-width: 100% !important;
        height: auto !important;
        margin-left: auto !important;
        margin-right: auto !important;
    }
    .stack-column,
    .stack-column-center {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        direction: ltr !important;
    }
    .stack-column-center {
        text-align: center !important;
    }
    .center-on-narrow {
        text-align: center !important;
        display: block !important;
        margin-left: auto !important;
        margin-right: auto !important;
        float: none !important;
    }
    table.center-on-narrow {
        display: inline-block !important;
    }
    .email-container p {
        font-size: 17px !important;
    }
}
</style>
</head>
<body width="100%" style="margin: 0; mso-line-height-rule: exactly;">
    <center style="width: 100%; background: #fff; text-align: left;">
    <!--[if mso | IE]>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#222222">
    <tr>
    <td>
    <![endif]-->

    <!-- Email Header : BEGIN -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="600" style="border-bottom: 1px solid #d9d9d9; margin: auto;" class="email-container">
        <tr>
            <td style="padding: 20px 0; text-align: center">
                <img src="{{ asset('images/pohondana/logo-pohondana-new.png') }}" width="200" height="50" alt="alt_text" border="0" style="height: auto; background: #fff; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
            </td>
        </tr>
    </table>
    <!-- Email Header : END -->

    <!-- Email Body : BEGIN -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="600" style="margin: auto;" class="email-container">

            <!-- START CONTENT DYNAMIC -->
            <tr>
                <td bgcolor="#ffffff" style="padding: 20px 40px 10px; text-align: left;">
                    <h3 style="margin: 0; font-family: sans-serif; font-size: 16px; line-height: 125%; color: #333333; font-weight: bold;">Yang Terhormat Bapak/Ibu <span> {{ucwords($loan->user->name)}}</span></h3>
                </td>
            </tr>

            <tr>
                <td bgcolor="#ffffff" style="padding: 0 40px 10px; margin-top: 10px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; text-align: left;">
                    @php
                        $date=date_create($installment->due_date);
                        $currentDay   = date_format($date,"d");
                        $currentMonth = date_format($date,"m");
                        $currentYear  = date_format($date,"Y");
                        $bulan = [
                            'Januari',
                            'Februari',
                            'Maret',
                            'April',
                            'Mei',
                            'Juni',
                            'Juli',
                            'Agustus',
                            'September',
                            'Oktober',
                            'November',
                            'Desember',
                        ];
                        $currentMonth = $bulan[intval($currentMonth) - 1];
                        $fullDate = $currentDay . ' ' . $currentMonth . ' ' . $currentYear;
                    @endphp
                    <p style="margin: 0px 0px 15px 0px;">Jumlah Angsuran Pinjaman Anda yang akan jatuh tempo pada tanggal {{ $fullDate }} adalah sebesar Rp. {{ number_format($installment->amount, 0, '.', '.') }}</p>
                    <p>Pembayaran akan di potong dari gaji Anda bulan {{ $currentMonth }}</p>
                </td>
            </tr>
            <tr>
                <td bgcolor="#ffffff" style="padding: 0 40px 30px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                    <!-- Button : BEGIN -->
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: auto">
                        <tr>
                            <td style="border-radius: 3px; background: #222222; text-align: center;" class="button-td">
                                <a href="{{ url('member/loans/'.$loan->id) }}" style="background: #222222; border: 15px solid #222222; font-family: sans-serif; font-size: 13px; line-height: 110%; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;" class="button-a">
                                    &nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#ffffff;">Informasi Cicilan Pinjaman</span>&nbsp;&nbsp;&nbsp;&nbsp;
                                </a>
                            </td>
                        </tr>
                    </table>

                    <p style="margin: 0; padding-top: 30px">Hubungi kami untuk mengetahui informasi lebih lanjut. Klik <a href="{{route('kontak-kami')}}">disini</a>
                        <br>
                        <br>
                        <br>
                        Hormat Kami, <br>
                        <strong>Pohon Dana</strong>
                    </p>
                    <!-- Button : END -->
                </td>
            </tr>

            <!-- END CONTENT DYNAMIC -->

            <tr>
                <td bgcolor="#ffffff" style="padding: 10px 40px 20px; text-align: left; border-top: 1px solid #d9d9d9;">
                    <p style="margin: 0; font-family: sans-serif; font-size: 14px; line-height: 125%; color: #333333; font-weight: normal;">PT. Pohon Dana Indonesia, <span style=" font-style: italic;">All Rights Reserved</span>
                        <br>
                        Layanan Hot Line : (021) 5229660 
                    </p>
                </td>
            </tr>
            <!-- 1 Column Text + Button : END -->

    <!--[if mso | IE]>
    </td>
    </tr>
    </table>
<![endif]-->
</center>
</body>
</html>