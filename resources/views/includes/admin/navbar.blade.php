<!-- Fixed navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
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
          <ul class="nav navbar-nav">
            <li id="dashboard"><a href="{{route('loginLand')}}">Dashboard</a></li>
            <li id="qv"><a href="{{route('quesViewer')}}">Question Viewer</a></li>
            <li id="qe"><a href="{{route('quesEditor')}}">Question Editor</a></li>
            <li id ="st"><a href="{{route('showStats')}}">Statistics</a></li>
            <li id ="tv"><a href="{{route('showTasks')}}">Tasks</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="text text-info">
                <a href="{{route('loginLand')}}">Welcome<strong> {{strstr(Auth::user()->email,'@', true)}}</strong><span class="sr-only">(current)</span>
                </a>
            </li>
            <li class="profile">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Profile <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="{!! route('adminProfile') !!}">View Profile</a></li>
                <li><a href="{!! route('adminEditProfile') !!}">Edit Profile</a></li>
              </ul>
            </li>
            <li  id="usermang">
                <a href="{!! route('userManage') !!}">Manage Users</span>
                </a>
            </li>
            <li>
                <a href="{!! route('logout') !!}">Logout</a>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>