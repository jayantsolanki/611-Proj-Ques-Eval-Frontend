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
  <link rel="shortcut icon" href="{!! asset('favicon.ico') !!}">
  @yield('styles')

</head>

<body>
  @include('includes.members.navbar')
  <!-- Container -->
  <div class=" container-fluid" style="margin-top: 85px;min-height:400px;">
    <!-- Content -->
    <div class="row" style="top-margin: 50px">
        @yield('content')
    </div>
  </div>
  <!-- footer -->
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