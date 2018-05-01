<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-113067729-2"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-113067729-2');
  </script>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="_token" content="{!! csrf_token() !!}"/>
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>@yield('title')</title>

  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <!-- Fonts -->
  <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/themes/smoothness/jquery-ui.css" />
  <style>
    body {
      background: #FFFFFF
      font-family: Lato, Helvetica, Arial, sans-serif;
      color: #434a54;
    }

    p {
      font-size: 18px;
      line-height: 24px;
      margin: 0 0 23px 0;
    }

    .suggestive {
      font-weight: bold;
      font-size: 28px !important;
      color: #000000;
      text-shadow: 2px 8px 6px rgba(0, 0, 0, 0.2), 0px -5px 35px rgba(255, 255, 255, 0.3);
      margin: 30px 10px 20px 10px;
      text-align: center;
    }

    form {
      margin: 50px auto;
    }
    .input-group,
    .form-group {
      margin: 20px 10px 20px 10px;
    }

    .btn-block {
      width: 50%;
      margin-left: auto;
      margin-right: auto;
    }

    hr {
      border: 0;
      height: 1px;
      background-image: -webkit-linear-gradient(left, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));
      background-image: -moz-linear-gradient(left, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));
      background-image: -ms-linear-gradient(left, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));
      background-image: -o-linear-gradient(left, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));
    }

    a.input-group:hover {
      text-decoration: underline;
    }

    @media(min-width:768px) {
      .portal-form {
        margin-top: 30px;
      }
    }

    @media(min-width:992px) {
      .portal-form {
        margin-top: 60px;
      }
    }

    @media(min-width:1200px) {
      .portal-form {
        margin-top: 120px;
      }
    }

  </style>

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
      @yield('userscript')
      @yield('scripts')
  </head>
  <body>
<!-- Container -->
  <section class="portal-form container-fluid">

    <div class="row">
      @yield('content')
    </div>
  </section>

  <div class="row">
    <div class="col-md-12" style="margin-top:30px;">
      <!-- Footer ================================================== -->

      <div class="container">

        <hr class="soften"/>
        <footer class="footer">
          <p>
            <span class="pull-right">
              <a class="btn btn-primary" href="https://www.facebook.com/jayantsolanki" target="_blank">f</a>
            </span>
          </p>
          <hr class="soften"/>

          <p style="color:black;"><a href="/">Dashboard</a> <br/><br/></p>
        </footer>
      </div><!-- /container -->
    </div>
  </div>

  <!-- Scripts are placed here -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  @yield('scripts')
  </body>
  </html>