


<div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav sidemenu_nav" id="side-menu">
                        	<li><a class="sidemenu_1" href="{{ url('admin/tournaments') }}">Info</a></li>
							  @if($lef_menu_condition=='display_gallery')
							<li><a class="sidemenu_2" href="{{ url('admin/album/show').'/tournaments'.'/0'.'/'.$action_id }}">Media Gallery</a></li>
							@endif
                       
                    </ul>
                </div>

                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>