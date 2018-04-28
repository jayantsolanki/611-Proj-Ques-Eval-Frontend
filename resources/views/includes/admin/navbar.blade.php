<nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right" style="font-size: 20px; margin-top: 20px;">
                    <li class="page-scroll"><li><a href="{!! route('login') !!}">Change Password</a></li>
                    <li class="page-scroll"><li><a href="{!! route('home') !!}">Welcome
                        <strong>
                            {{Auth::user()->email}}
                        </strong>
                    </li>
                    <li class="page-scroll"><li><a href="{!! route('home') !!}">Profile</a></li>
                    <li class="page-scroll"><li><a href="{!! route('logout') !!}">Logout</a></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
</nav>