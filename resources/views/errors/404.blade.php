<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,500i,700|Roboto:400,400i,500,700" rel="stylesheet">
    <!-- Document Title
        ============================================= -->
        <title>Pohon Dana | 404</title>
        <!-- Style Canvas -->
        <link rel="stylesheet" href="{{ asset('css/pohondana/bootstrap.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/pohondana/style.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/pohondana/swiper.css') }}" type="" pe="text/css" />
        <link rel="stylesheet" href="{{ asset('css/pohondana/dark.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/pohondana/font-icons.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/pohondana/animate.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/pohondana/magnific-popup.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/pohondana/responsive.css') }}" type="text/css" />
        <link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/pohondana/custom-style.css') }}?ver={{ date('m.d.h') }}" type="text/css" />
        <link rel="shortcut icon" href="{{ asset('images/pohondana/favicon.png') }}" />
</head>
<body class="stretched" data-loader-html="<img src='{{ asset('images/pohondana/logo_pd-ojk.png') }}' alt='Loading Page' style='position:absolute; width:200px; auto; top:50%; left:50%; margin-left:-100px; margin-top:-50px;'">

    <!-- Document Wrapper
        ============================================= -->
        <div id="wrapper" class="clearfix">

            <section id="slider" class="slider-parallax swiper_wrapper full-screen force-full-screen clearfix">
                <div class="slider-parallax-inner">
                    <div class="swiper-container swiper-parent">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide dark" style="background-image: url('{{ asset('images/pohondana/slider-home.jpg') }}');">
                                <div class="container clearfix">
                                    <div class="slider-caption slider-caption-center slider-caption-home">
                                        <h2 data-caption-animate="fadeInUp"><img style="width: 5em;" src="{{ asset('images/pohondana/logo-bottom-white.png') }}"></h2>
                                        <h2 data-caption-animate="fadeInUp">HALAMAN TIDAK DITEMUKAN</h2>
                                        <div class="register-choice">
                                            <a href="{{url ('/') }}" class="btn-primary-mayapada btn-left-home">Kembali Ke Home</a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <a href="#" data-scrollto="#content" data-offset="100" class="dark one-page-arrow"><i class="icon-angle-down infinite animated fadeInDown"></i></a> -->

                </div>
            </section>

        </div><!-- #wrapper end -->

    <!-- Go To Top
        ============================================= -->
        <div id="gotoTop" class="icon-angle-up"></div>

    <!-- External JavaScripts
        ============================================= -->
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

      .martop-50{
        margin-top: 50px;
      }

      .marbot-15{
        margin-bottom: 15px;
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
          width: 125px;
          border:none; 
          color: #009344; 
          font-weight: bold;
      }

      .width-bulan{
          width: 35px;
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

      .text-justify{
        text-align: justify;
        text-justify: inter-word;
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

<script type="text/javascript" src="{{ asset('js/pohondana/jquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/pohondana/plugins.js') }}"></script>

    <!-- Footer Scripts
        ============================================= -->
        <script type="text/javascript" src="{{ asset('js/pohondana/functions.js') }}"></script>
        <script src="{{ asset('js/pohondana/swiper.min.js') }}"></script>

        <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js'></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js'></script>
        <script src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js'></script>

        <script type="text/javascript" src="{{ asset('js/jquery.ui.touch-punch.min.js')}}"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
        <script type="text/javascript" src="{{ asset('js/id.js')}}"></script>
        <script type="text/javascript">

            $( document ).ready(function() {
                numeral.locale('id');

                replaceString = (value) => {
                  while(value.indexOf('.') > 0){
                    value = value.replace(".", "");
                }
                return value;
            }

            function update(slider, val) {  
                $perday = 0 ;
                $amount1 = replaceString($("#amount").val());
                $dayscount = $("#days").val();
                $interest = 0.015 * $dayscount;
                $amount2 = parseInt($amount1) + $interest * parseInt($amount1) + (parseInt($dayscount) * ($perday/100));
                $amount3 = parseInt($amount2) - parseInt($amount1);
                $amount5 = parseInt($amount2) / parseInt($dayscount);
                $apr = (($amount2-$amount1 / $amount1 ) / ((parseInt($dayscount)/365) * 10000));
                $a1 = numeral($amount1).format('0,0.00');
                $a2 = numeral($amount2).format('0,0.00');
                $a3 = numeral($amount3).format('0,0.00');
                $a5 = numeral($amount5).format('0,0.00');
                $("#amount").val($a1);
                $("#amount2").val($a2);
                $("#amount3").val($a3);
                $("#amount4").val($apr);
                $("#amount5").val($a5);
                $("#date").text(addDaysToDate(parseInt($("#days").val())));

                console.log('amount: ' + parseInt(replaceString($("#amount").val())));
                console.log('amount: ' + $("#days").val());
            }

        // debugger;

        $("#slider1").slider({
            max:15000000,
            min:1000000,
            step:1000000,
            slide: function(event, ui) {  
                $("#amount").val(ui.value);
                update();
            }    
        });

        $("#slider2").slider({
            max:30,
            min:1,

            slide: function(event, ui) {  
                $("#days").val(ui.value);
                $("#date").text(addDaysToDate(parseInt($("#days").val())));
                $("#amount3").val(ui.value);
                update();
            }
        });

        function addDaysToDate(days) {
            var mths = new Array("Jan", "Feb", "Mar",
                "Apr", "May", "Jun", "Jul", "Aug", "Sep",
                "Oct", "Nov", "Dec");

            var d = new Date();

            d.setMonth(d.getMonth()+days);

            var currD = d.getDate();
            var currM = d.getMonth();
            var currY = d.getFullYear();

            return mths[currM] + " " + currD + ", " + currY;
        }

        $("#days").val($("#slider2").slider("value"));

        $("#days").change(function(event) {
            var data = $("#days").val();
            if (data.length > 0)
            {
                if (parseInt(data) >= 0 && parseInt(data) <= 31)
                {
                    $("#slider2").slider("option", "value", data);
                }
                else
                {
                    if (parseInt(data) < 1)
                    {
                        $("#days").val("1");
                        $("#slider2").slider("option", "value", "1");
                    }
                    if (parseInt(data) > 31)
                    {
                        $("#days").val("31");
                        $("#slider2").slider("option", "value", "31");
                    }
                }
            }
            else
            {
                $("#slider2").slider("option", "value", "1");
            }
            $("#date").text(addDaysToDate(parseInt($("#days").val())));
        });

        update();
        
    });
</script>

</body>
</html>