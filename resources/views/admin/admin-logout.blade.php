<div class="top_nav">
          <div class="nav_menu">
            <nav>
             <!--<div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>-->
				 
                <ul class="nav navbar-nav navbar-right">
                <li role="presentation" class="dropdown_cus">
                  <a href="javascript:;" class=" dropdown-toggle info-number" data-toggle="dropdown_cus">
                   <?=Session::get('name')?>, Admin</a>
                    <div class="dropdown_content">
					  <div class="profile_top_pic">
						<div class="setting">
						  <a href="{{ url('/admin/profile') }}">Profile</a>
						  <a href="{{ url('/admin/changepassword') }}">Change Password</a>
						  <a href="{{ url('/admin/logout') }}" >Logout</a>
						</div>
					  </div>
					</div>           
                </li>
              </ul>
             
            </nav>
          </div>
        </div>