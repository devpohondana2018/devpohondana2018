@extends ('layouts.transparent-header')

@section ('content')

<section class="container simulasi">
  <div class="col-md-6 col-md-offset-3 nopadding">

    <div class="col-md-12 nopadding center" style="margin-bottom: 25px;">
      <h2>Simulasikan Pinjamanmu</h2>
    </div>


    <div class="col-md-12 nopadding marbot-50">
      <div class="col-md-12 nopadding" style="margin-bottom: 15px;">
        <div class="col-md-12 form-group nopadding">
          Jumlah Pinjaman : Rp.
          <input readonly="" class="width-money" type="text" id="amount" value="1000000"/>
        </div>
      </div>

      <div id="slider1" class="col-md-12 nopadding" style>
        <div id="myhandle"></div>
      </div>
    </div>

    <div class="col-md-12 nopadding" style="margin-bottom: 15px;">
      Untuk Jangka Waktu :&nbsp&nbsp
      <input readonly="" class="width-bulan" type="text" id="days" value="5"/>&nbsp Bulan   
    </div>

    <div id="slider2" class="col-md-12 nopadding">
      <div id="myhandle"></div>
    </div>

  </div>

  <div class="col-md-6 col-md-offset-3 marbot-25">
    <div class="form-group col-md-6 nopadding center marbot-50">
      <div class="col-md-12 col-sm-6 col-xs-12 center nopadding">Tanggal Jatuh Tempo</div>
      <div class="col-md-12 col-sm-6 col-xs-12 input-simulasi center nopadding" id="date"></div>
    </div>

    <div class="form-group col-md-6 nopadding center marbot-50">
      <div class="col-md-12 col-sm-6 col-xs-12 center nopadding">Pembayaran Per Bulan</div>
      <div class="col-md-12 col-sm-6 col-xs-12 center nopadding">
        <input readonly="" class="input-simulasi" id="amount5" type="text" />
      </div>
    </div>

    <div class="form-group col-md-6 nopadding center marbot-50">
      <div class="col-md-12 col-sm-6 col-xs-12 center nopadding">Jumlah Pembayaran</div>
      <div class="col-md-12 col-sm-6 col-xs-12 center nopadding">
        <input readonly="" class="input-simulasi" id="amount2" type="text" />
      </div>
    </div>

    <div class="form-group col-md-6 nopadding center marbot-50">
      <div class="col-md-12 col-sm-6 col-xs-12 center nopadding">Bunga</div>
      <div class="col-md-12 col-sm-6 col-xs-12 center nopadding">
        <input readonly="" class="input-simulasi" id="amount3" type="text" />
      </div>
    </div>

  </div>

  <div class="col-md-12 marbot-25">
    <h4>Penolakan</h4>
    <ol class="number">
      <li class="number">Perhitungan di atas hanya untuk tujuan indikatif dan bukan merupakan penawaran fasilitas pinjaman. Pemberian fasilitas pinjaman tergantung pada proses dan kebijaksanaan Pohon Dana.</li>
      <li class="number">Pohon Dana tidak bertanggung jawab atas kesalahan atau kelalaian, dan juga kerugian yang timbul dari penggunaan atau ketergantungan pada perhitungan simulasi ini.</li>
      <li class="number">Untuk membantu perencanaan Anda, kami senang untuk memberikan permintaan melalui layanan pelanggan kami melalui situs Pohon Dana maupun menghubungi kami melalui email atau telpon.</li>
      <li class="number">Jumlah pinjaman maksimum dan jangka waktu pinjaman tergantung pada persyaratan dan peraturan yang berlaku. Pohon Dana berhak untuk meninjau peraturan dan persyaratan fasilitas pinjaman tersebut dari waktu ke waktu.</li>
    </ol>
    <p>Catatan: Pohon Dana telah menerbitkan panduan fasilitas pinjaman dan pendanaan. Anda dianjurkan untuk mempelajari panduan tersebut sebelum melakukan pinjaman maupun pendanaan. Panduan ini tersedia di situs web Pohon Dana dan Otoritas Jasa Keuangan.</p>
  </div>
</section>

<style type="text/css">
#slider1 .ui-slider-handle {
  border-radius: 100%;
  margin-top: -13px;
  height: 45px;
  border: 2px solid #009344;
  width: 45px;
  background-image: url(images/handle.png);
  background-position: -10px -7px;
}

.simulasi p {
  margin-bottom: 0px;
}

.marbot-25{
  margin-bottom: 25px;
}

#slider2 .ui-slider-handle {
  border-radius: 100%;
  margin-top: -13px;
  height: 45px;
  border: 2px solid #009344;
  width: 45px;
  background-image: url(images/handle.png);
  background-position: -10px -7px;
}

.ui-slider {
  margin:20px;
  width: 100%;
}

.input-simulasi{
  border:none; 
  color: #009344; 
  font-weight: bold;
  text-align: center;
}

.width-money{
  width: 115px;
  border:none; 
  color: #009344; 
  font-weight: bold;
}

.width-bulan{
  width: 20px;
  border:none; 
  color: #009344;
  font-weight: bold;
}

.panel.panel-pink {
  border-radius: 0px;
  box-shadow: 0px 0px 10px #888;
  border-color: #EF5160;
}
.panel.panel-pink .panel-heading {
  border-radius: 0px;
  color: #FFF;
  background-color: #EF5160;
}
.panel.panel-pink .panel-body {
  background-color: #F2F2F2;
  color: #4D4D4D;
}

.panel.panel-blue {
  border-radius: 0px;
  box-shadow: 0px 0px 10px #888;
  border-color: #266590;
}
.panel.panel-blue .panel-heading {
  border-radius: 0px;
  color: #FFF;
  background-color: #266590;
}
.panel.panel-blue .panel-body {
  background-color: #F2F2F2;
  color: #4D4D4D;
}

.panel.panel-yellow {
  border-radius: 0px;
  box-shadow: 0px 0px 10px #888;
  border-color: #EFA13D;
}
.panel.panel-yellow .panel-heading {
  border-radius: 0px;
  color: #FFF;
  background-color: #EFA13D;
}
.panel.panel-yellow .panel-body {
  background-color: #F2F2F2;
  color: #4D4D4D;
}

ol.number {list-style-type: upper-greek;}
li.number{
  text-align: justify;
  text-justify: inter-word;
  margin-bottom: 5px;
}

@media only screen and (max-width: 767px){
  .ui-slider {
    margin-top:30px;
    margin-bottom:20px;
    margin-left: 0px;
    margin-right: 0px;
  }
}

@media only screen and (min-width: 768px){
  .ui-slider {
    margin-top:30px;
    margin-bottom:20px;
    margin-left: 0px;
    margin-right: 10px;
  }
}
</style>





@endsection