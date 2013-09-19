    <!DOCTYPE html>
    <html lang="id">
    <head>

        <!-- start: Meta -->
        <meta charset="utf-8">
        @yield('seo')
        <!-- end: Meta -->
        
        <!-- start: Mobile Specific -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- end: Mobile Specific -->
        
        <!-- start: CSS -->
        {{HTML::style('packages/yusidabcs/checkout/css/bootstrap.min.css')}}    
        {{HTML::style('packages/yusidabcs/checkout/css/bootstrap-responsive.min.css')}}
        {{HTML::style('packages/yusidabcs/checkout/css/jquery.noty.css')}}
        {{HTML::style('packages/yusidabcs/checkout/css/noty_theme_default.css')}}
        {{HTML::style('packages/yusidabcs/checkout/css/style.css')}}
        {{HTML::style('packages/yusidabcs/checkout/css/style-responsive.css')}}
        {{HTML::style('packages/yusidabcs/checkout/css/data-dialog.css')}}
        {{HTML::style('packages/yusidabcs/checkout/css/jquery-ui.css')}}
        <!--<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>-->
        
        <!-- end: CSS -->
        

        <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
            <link id="ie-style" href="css/ie.css" rel="stylesheet">
            <![endif]-->

        <!--[if IE 9]>
            <link id="ie9style" href="css/ie9.css" rel="stylesheet">
            <![endif]-->
            
            <!-- start: Favicon -->
            <link rel="shortcut icon" href="img/favicon.ico">
            <!-- end: Favicon -->


            <style type="text/css">
            body {
            }
            .navbar-inner{
                background: none repeat scroll 0% 0% rgb(59, 59, 65);
            }
            /* Custom container */
            .container-narrow {
                margin: 0 auto;
                max-width: 940px;
            }
            .container-narrow > hr {
                margin: 30px 0;
            }

            /* Main marketing message and sign up button */
            .jumbotron {
                margin: 60px 0;
                text-align: center;
            }
            .jumbotron h1 {
                font-size: 72px;
                line-height: 1;
            }
            .jumbotron .btn {
                font-size: 21px;
                padding: 14px 24px;
            }

            /* Supporting marketing content */
            .marketing {
                margin: 10px 0 -10px 0;
            }
            .marketing p + h4 {
                margin-top: 28px;
            }
            </style>
            
            <style type="text/css">

            .step-title {
                min-height: 20px;
                float:left;
                border-radius: 0;
            }
            .next-button, .submit-button, .back-button {
                float:right;
                margin:3px;
            }
            @media (max-width:600px) {
                .step-content {
                    margin-top: 10px;
                }
            }

            </style>

            <!-- Pines Form -->
            <link href="css/pform.css" media="all" rel="stylesheet" type="text/css" />
            <link href="css/pform-bootstrap.css" media="all" rel="stylesheet" type="text/css" />
        </head>

        <body>
            <div class="navbar navbar-fixed-top">
                <div class="navbar-inner">
                    <div class="container-fluid container-narrow">
                        <!-- start: Header Menu -->
                        <a class="brand" href="{{URL::to('')}}"><span>{{$kontak->nama}}</span></a>
                        <div class="nav-no-collapse header-nav">
                            <ul class="nav pull-right">
                                <li><a class="btn" href="{{URL::to('')}}">
                                        Lanjut Belanja &rarr;
                                    </a></li>       
                                @if (Sentry::check())
                                    <li><a class="btn" href="#">
                                    Welcome {{Sentry::getUser()->nama}}
                                    </a></li>       
                                @endif                                            
                            </ul>
                        </div>
                        <!-- end: Header Menu -->
                        
                    </div>
                </div>
            </div>
            <br>
            <div class="container container-narrow">
                <div class="row-fluid">
                        <div id="content" class="span12">
                            <div class="box span12">
                                <div class="">                                    
                                    @yield('content')                                         
                                </div>
                            </div><!--/span-->

                        <br>
                        </div><!--/row-->

                    <div class="clearfix"></div>
                    <small>Copyright 2013 by {{$kontak->nama}}</small>
                    <br><br>     
                </div><!--/.fluid-container-->
            </div>
                
   <div id="cart_dialog" class="content_dialog" style="display:none"><img src="{{URL::to('img/spinner-mini.gif')}}" style="position:relative;margin:90% 10px 0px 10px"></div>            
    <!-- start: JavaScript-->

    <script type="text/javascript">
    var URL = "{{ URL::to('')}}";
    </script>           

    {{HTML::script("packages/yusidabcs/checkout/js/jquery-1.9.1.min.js")}}

    {{HTML::script("packages/yusidabcs/checkout/js/jquery-ui.js")}}

    {{HTML::script("packages/yusidabcs/checkout/js/jquery-migrate-1.0.0.min.js")}}

    {{HTML::script("packages/yusidabcs/checkout/js/jquery-ui-1.10.0.custom.min.js")}}

    {{HTML::script("packages/yusidabcs/checkout/js/jquery.ui.touch-punch.js")}}

    {{HTML::script("packages/yusidabcs/checkout/js/modernizr.js")}}

    {{HTML::script("packages/yusidabcs/checkout/js/bootstrap.min.js")}}

    {{HTML::script("packages/yusidabcs/checkout/js/jquery.cookie.js")}}

    {{HTML::script("packages/yusidabcs/checkout/js/jquery.chosen.min.js")}}

    {{HTML::script("packages/yusidabcs/checkout/js/jquery.uniform.min.js")}}

    {{HTML::script("packages/yusidabcs/checkout/js/retina.js")}}

    {{HTML::script("packages/yusidabcs/checkout/js/custom.js")}}

    {{HTML::script("packages/yusidabcs/checkout/js/jquery.noty.js")}}

    {{HTML::script("packages/yusidabcs/checkout/js/js.js")}}

</body>
</html>
