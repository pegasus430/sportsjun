<header>
    <div class="topHeadWrap">
        <div class="topHeaderInside">
            <ul>
                <li><a href="#">Supports</a></li>
                <li><a id="top_bar_login" href="javascript:void(0);" data-toggle="modal"
                       data-target="#home-login-modal"><i class="fa fa-sign-in"></i> Login</a></li>
                <li><a href="#" data-toggle="modal" data-target="#myModal">Get Started </a></li>
                <li><a href="#">Pricing</a></li>
            </ul>
        </div>
    </div>
    <div class="menuLogWrap">
        <div class="logoL"><img src="/themes/sportsjun/images/logo.png" width="285px" height="50px"></div>
        <div class="menuright">
            <div id="nav">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse"
                            data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <nav class="nav navbar-nav">
                        <ul>
                            <li><a href="/">HOME</a></li>
                            <li><a href="/sports.html">Sports</a></li>
                            <li><a href="/features.html">FEATURES</a></li>
                            <li><a href="/#howitworks">PLAYERS & TEAMS</a></li>
                            <li><a href="/#tournaments">TOURNAMENTS</a></li>
                            <li><a href="/#marketplace">MARKET PLACE</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>


<div class="searchWrapp total_menu_wrap">
    <form method="GET" action="{{route('public.search.list')}}">
        <div class="">
            <div class="serchIco"><img src="/themes/sportsjun/images/search_ico.png" width="28" height="28"></div>
            <div class="group">
                <input name="what" type="text" required value="{{ $what or '' }}"
                       class="jq-autocomplete"
                       data-source="{{route('public.search.guess')}}"
                >
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Enter your key word <span>( Tournaments, Matches, Player, Location )</span></label>
            </div>
            <input name="search" class="serachBt" value="SEARCH" type="submit">
        </div>
    </form>
</div>