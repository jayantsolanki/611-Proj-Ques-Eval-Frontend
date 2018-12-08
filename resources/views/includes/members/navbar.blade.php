<!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{{route('loginLand')}}">Question Manager</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav ">
            <li id="dashboard"><a href="{{route('loginLand')}}">Dashboard</a></li>
            <li id="qv"><a href="{{route('quesViewer')}}">Question Viewer</a></li>
            <li id = "question">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Question Manager <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li id="qe"><a href="{{route('quesEditor')}}">Question Editor</a></li>
                <li id="qs"><a href="{{route('quesSet')}}">Question Set</a></li>
                <li id="qsa"><a href="{{route('quesSetAdvance')}}">Question Set (Advance)</a></li>
                <!-- <li id="qexp"><a href="{{route('expQuest')}}">Experimental Questions</a></li> -->
              </ul>
            </li>
            <!-- <li id ="st"><a href="{{route('showStats')}}">Statistics</a></li> -->
            <li id = "analysismenu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Analysis <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li id ="st"><a href="{{route('showStats')}}">Statistics</a></li>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="text text-info">
                <a href="{{route('loginLand')}}">Welcome<strong> {{strstr(Auth::user()->email,'@', true)}}</strong><span class="sr-only">(current)</span>
                </a>
            </li>
           <li id="profile" class="profile">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Profile <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li id ="viewprofile"><a href="{!! route('memberProfile') !!}">View Profile</a></li>
                <li id ="editprofile"><a href="{!! route('memberEditProfile') !!}">Edit Profile</a></li>
              </ul>
            </li>
            <li>
                <a href="{!! route('logout') !!}">Logout</a>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>