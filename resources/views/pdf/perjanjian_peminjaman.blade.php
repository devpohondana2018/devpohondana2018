<!DOCTYPE html>
<html>
    <head>
        <title>Perjanjian Penyelenggara Dengan Pendana</title>
        <style>
            body{
            padding: 0px 40px;
            font-family: "Courier New", Courier, monospace;
            font-size: 12px;
           }
           h1 {
            font-size: 15px;
            text-align: center;
           }
        </style>
    </head>
    <body>
        
        <h1>PERJANJIAN KERJASAMA<br>
        ANTARA<br>
        PT. POHON DANA INDONESIA<br>
        DENGAN<br>
        {{$investment->user->name}}</h1>

        <p>Perjanjian  ini  dibuat tanggal {{date('d', strtotime($investment->payment_date))}} bulan {{date('m', strtotime($investment->payment_date))}} tahun {{date('Y', strtotime($investment->payment_date))}} oleh dan antara:</p>
        
        <ol>
          <li>PT. POHON DANA INDONESIA, sebuah perseroan terbatas yang didirikan berdasarkan hukum 
            Negara Republik Indonesia, berkedudukan di Mayapada Tower 1 Lt.8, Jalan Jenderal Sudirman 
            kav.28, Jakarta Selatan (untuk selanjutnya disebut <b>‘Pihak Pertama’</b>) dan</li>
          <li>{{$investment->user->name}}, dengan No. Identitas {{$investment->user->id_no}} beralamat {{$investment->user->home_address}} (untuk 
          selanjutnya disebut <b>‘Pihak Kedua’</b>)</li>
        </ol>         
                
        <p>Pihak Pertama dan Pihak Kedua untuk selanjutnya secara bersama-sama disebut <b>‘Para Pihak’</b> dan
        secara sendiri-sendiri disebut <b>‘Pihak’</b>.</p>

        <p>Para Pihak selanjutnya menerangkan sebagai berikut :</p>

        <ol>
          <li>Pihak Pertama adalah perusahaan penyelenggara Layanan Pinjam Meminjam Uang Berbasis
          Teknologi Informasi dalam bentuk website dengan nama Pohon Dana.</li>
          <li>Pihak Kedua adalah orang, badan hukum, dan/atau badan usaha yang berkomitmen dan memenuhi
          syarat sebagai Pendana dalam Layanan Pinjam Meminjam Uang berbasis Teknologi Informasi.</li>
        </ol>

        <p>Berdasarkan hal-hal tersebut diatas, para pihak dengan ini sepakat dan setuju untuk pengadakan
        Perjanjian dengan syarat dan ketentuan sebagai berikut :</p>
        
        <h1>PASAL 1<br>
        RUANG LINGKUP</h1>

        <p>Para Pihak sepakat dan setuju untuk mengadakan kerjasama dalam hal pengunaan website dan mobile
        apps Pihak Pertama (selanjutnya disebut <b>‘situs Pohon Dana’</b>) yang akan digunakan oleh Pihak Kedua
        dengan suatu imbalan jasa tertentu untuk kepentingan Pihak Kedua.</p>

        <h1>PASAL 2<br>
        KETENTUAN PENDANAAN</h1>

        <p>Dalam perjanjian ini, Pihak Kedua sepakat untuk memberikan pendanaan pada situs Pohon Dana
        dengan nilai pendanaan sebesar Rp {{ number_format($investment->amount_invested) }} dengan tingkat return (bunga) yang akan
        diterima oleh Pihak Kedua sebesar {{ number_format($investment->invest_rate ) }}% perbulan dalam jangka waktu selama {{ number_format($investment->loan->tenor->month ) }} bulan,
        dimana dana pinjaman tersebut harus di setorkan ke rekening escrow PT. Pohon Dana Indonesia paling
        lambat tanggal {{$investment->loan->date_expired->format('d')}} bulan {{$investment->loan->date_expired->format('m')}} tahun {{$investment->loan->date_expired->format('Y')}}.</p>

        <p>Pihak Pertama akan menyalurkan pendanaan yang dilakukan Pihak Kedua kepada peminjam (borrower)
        yang ditunjuk/dipilih oleh Pihak Kedua dengan ketentuan sebagai peminjaman berikut :</p>
        <p>
            Tujuan Pinjaman : {{ $investment->loan->description }}<br>
            Nominal Pinjaman : Rp. {{ number_format($investment->loan->amount_requested ) }}<br>
            Jangka waktu Pinjaman : {{ number_format($investment->loan->tenor->month ) }} bulan<br>
            Bunga Pinjaman : {{ number_format($investment->loan->interest_rate ) }}%<br>
            Platform Fee : Rp. {{ number_format($investment->loan->provision_fee ) }}
        </p>

        <p>
        Pengembalian dana pinjaman kepada Pihak Kedua akan dilakukan setelah pembayaran dari peminjam
        dilakukan sesuai dengan kesepakatan diatas, dimana atas keterlambatan pembayaran oleh Peminjam
        akan dikenakan denda sebesar 4‰ per hari, dihitung dari tanggal jatuh tempo sampai dengan tanggal
        pembayaran.</p>
        
        <h1>PASAL 3<br>
        SYARAT & KETENTUAN</h1>

            <ol>
                <li>Pihak Kedua memahami, mengakui, dan setuju bahwa peran Pihak Pertama hanya sebagai
                fasilitator dan bersifat administrative yang mengatur perjanjian antara Peminjam dan Pemberi
                Pinjaman dalam Perjanjian Pinjaman terkait, sesuai dengan Peraturan Otoritas Jasa Keuangan
                Nomor 77/POJK.01/2016 tentang Layanan Pinjam Meminjam Uang Berbasis Teknologi
                Informasi:</li>
                <li>Pihak Kedua wajib memberikan informasi yang benar dan akurat secara materi dan sesuai
                dengan tanggal dokumen diberikan atau tanggal yang tertera pada dokumen;</li>
                <li>Pihak Kedua berhak memilih dan memutuskan siapa peminjam dari data list market place situs
                Pohon dana yang ada diberikan pinjaman/pendanaan.</li>
                <li>Pihak Pertama berhak memberikan jasa dengan pengoperasian website POHON DANA yang
                akan memfasilitasi proses pemberian Pinjaman/Pendanaan:</li>
                <li>Pihak Pertama berhak meminta Pihak Kedua untuk melakukan penyetoran dana pinjaman yang
                telah disepakati sesuai dengan perjanjian kerjasama ini ke rekening escrow Pihak Pertama;</li>
                <li>Pihak Pertama berkewajiban menyalurkan dana pinjaman yang sesuai dengan perjanjian
                kerjasama ini kepada peminjam yang ditunjuk ataupun dipilih oleh Pihak Kedua;</li>
                <li>Pihak Pertama berkewajiban menyalurkan kembali dana pengembalian pinjaman dari Peminjam
                ke rekening milik Pihak Kedua sebagai pengembalian Dana Pinjaman</li>
                <li>Pihak Pertama tidak bertanggung jawab atas dan ketika terjadi kegagalan bayar yang timbul
                atas kesepakatan Layanan Pinjam Meminjam Uang Berbasis Teknologi ini, yang mungkin akan
                mengakibatkan :</li>
                <ol>
                    <li>terjadinya kehilangan keuntungan, bisnis atau pendapatan bagi Pihak Kedua</li>
                    <li>timbulnya biaya atau beban, baik secara tidak langsung atau langsung, diderita oleh
                    Pihak Kedua yang disebabkan oleh Peminjam sebagai hasil atau dalam hubungan
                    dengan ketentuan pengunaan situs Pohon Dana;</li>
                </ol>
                <li>Apabila terdapat perbedaan dan pertentangan antara Syarat dan Ketentuan dalam situs Pohon
                Dana dan perjanjian kerjasama ini, maka peraturan dalam perjanjian ini yang berlaku.</li>
            </ol>
        
        <h1>PASAL 4<br>
        MASA BERLAKU DAN PENGAKHIRAN PERJANJIAN</h1>

        <ol>
            <li>Perjanjian ini berlaku sejak tanggal Perjanjian ini sampai dengan akhir jangka waktu
            pengembalian dana pinjaman atau pelunasan, yang mana terlebih dahulu.</li>            
            <li>Apabila Pihak Pertama tidak dapat dan dinyatakan secara hukum tidak dapat melanjutkan
            kegiatan operasional sebagai penyelenggara Layanan Pinjam Meminjam Uang Berbasis
            Teknologi Informasi, maka akan dilakukan pengakhiran perjanjian, dengan ketentuan Hak dan
            Kewajiban akan diselesaikan sesuai dengan ayat 3 pada pasal ini.</li>
            <li>Jika pada saat berakhirnya Perjanjian ini masih ada Hak dan Kewajiban PARA PIHAK
            berdasarkan Perjanjian ini yang belum diselesaikan maka PARA PIHAK tetap terikat sampai Hak
            dan Kewajiban tersebut terpenuhi.</li>
        </ol>
        
        <h1>PASAL 5<br>
        FORCE MAJEURE</h1>

        <ol>
            <li>Force Majeure adalah kejadian-kejadian yang terjadi diluar kehendak dan kekuasaan PARA
            PIHAK yang secara langsung dan material dapat mempengaruhi pelaksanaan kewajiban PARA
            PIHAK berdasarkan Perjanjian ini, termasuk namun tidak terbatas pada terjadinya peristiwa alam
            seperti gempa bumi, angin topan, banjir, tanah longsor, tsunami, sambaran petir, gunung meletus
            dan bencana alam lainnya, kebakaran, huru- hara, terorisme, sabotase, embargo dan
            pemogokan massal, perang baik yang dinyatakan atau tidak, ketentuan atau kebijkasanaan
            Negara yang wajib ditaati.</li>            
            <li>Dalam hal terjadi Force Majeure, maka pihak yang terkena force majeure wajib memberitahukan
            hal tersebut secara tertulis kepada pihak lainnya dalam waktu selambat-lambatnya 3 (tiga) hari
            kerja terhitung sejak tanggal kejadian force majeure, selanjutnya PARA PIHAK akan melakukan
            musyawarah untuk mufakat.</li>
            <li>Apabila pihak yang mengalami force majeure tidak melaksanakan kewajibannya sebagaimana
            ditentukan dalam ayat (2) diatas, maka force majeure tidak akan diakui oleh pihak lainnya, dan
            segala kerugian, resiko dan konsekuensi yang mungkin timbul akibat force majeure menjadi
            beban dan tanggung jawab pihak yang mengalami force majeure.</li>
        </ol>
        
        <h1>PASAL 6<br>
        PENYELESAIAN PERSELISIHAN</h1>

        <ol>
            <li>PARA PIHAK sepakat untuk menyelesaikan setiap perselisihan sehubungan dengan
            pelaksanaan Perjanjian ini, secara musyawarah untuk mencapai mufakat, baik dengan
            mengunakan jasa mediator independen maupun melalui pembicaraan di antara wakil-wakil PARA
            PIHAK.</li>            
            <li>Apabila penyelesaian perselisihan secara musyawarah tersebut tidak berhasil mencapai kata
            mufakat, maka PARA PIHAK sepakat untuk menyelesaikan perselisihan tersebut melalui Badan
            Abritrase Nasional Indonesia (BANI).</li>
            <li>PARA PIHAK sepakat bahwa putusan yang dihasilkan oleh BANI dalam penyelesaian
            perselisihan yang terjadi adalah bersifat final dan mempunyai kekuatan hukum yang mengikat
            bagi PARA PIHAK.</li>
        </ol>        

    </body>
</html>