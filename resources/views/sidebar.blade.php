<style>
.leftsidenav {
    height: 100%;
    width: 0;
    position: fixed;
    z-index: 1;
    top: 0;
    left: 0;
    background-color: #eee;
    overflow-x: hidden;
    transition: 0.5s;
    padding-top: 60px;
}
.leftsidenav a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 14px;
  color: #818181;
  display: block;
  transition: 0.3s;
}
.leftsidenav a:hover {
  color: #f1f1f1;
}
.leftsidenav .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
  margin-left: 50px;
}
@media screen and (max-height: 450px) {
  .leftsidenav {padding-top: 15px;}
  .leftsidenav a {font-size: 14px;}
}
button.btn{
	border-radius: 0px;
}
</style>
<div class="col-md-4 sidenav-setting">
<div id="myleftsidenav" class="leftsidenav">
  <a href="javascript:void(0)" class="closebtn"  onclick="closeleftNav()">&times;</a>
    <a href="/myprofile" class="list-group-item list-group-item-action "> <span class="left_nav_icon"><i class="fa fa-user" aria-hidden="true"></i></span> My Profile</a>
	  
              <a href="/member-download" class="list-group-item list-group-item-action"><span class="left_nav_icon"><i class="fa fa-download" aria-hidden="true"></i></span> Downloads</a>
              <a href="/member-plans" class="list-group-item list-group-item-action"><span class="left_nav_icon"><i class="fa fa-tasks" aria-hidden="true"></i></span> Plans</a>
              <a href="/purchase-history" class="list-group-item list-group-item-action"><span class="left_nav_icon"><i class="fa fa-list" aria-hidden="true"></i></span> Purchase history</a>
			  <a href="/favorites" class="list-group-item list-group-item-action"><span class="left_nav_icon"><i class="fa fa-heart" aria-hidden="true"></i></span> My favorites</a>
			  <a href="/change-password" class="list-group-item list-group-item-action"><span class="left_nav_icon"><i class="fa fa-key" aria-hidden="true"></i></span> Change Password</a>
			  <a href="/logout" class="list-group-item list-group-item-action"><span class="left_nav_icon"><i class="fa fa-lock" aria-hidden="true"></i></span> Logout</a>
</div>
<span style="font-size:30px;cursor:pointer" onclick="openleftNav()" class="sidebar_left">&#9776;</span>
		     <div class="list-group " id="list-menu">
              <a href="/myprofile" class="list-group-item list-group-item-action "> <span class="left_nav_icon"><i class="fa fa-user" aria-hidden="true"></i></span> My Profile</a>
         
              <a href="/member-download" class="list-group-item list-group-item-action"><span class="left_nav_icon"><i class="fa fa-download" aria-hidden="true"></i></span> Downloads</a>
              <a href="/member-plans" class="list-group-item list-group-item-action"><span class="left_nav_icon"><i class="fa fa-tasks" aria-hidden="true"></i></span> Active Plans</a>
              <a href="/purchase-history" class="list-group-item list-group-item-action"><span class="left_nav_icon"><i class="fa fa-list" aria-hidden="true"></i></span> Purchase history</a>
			  <a href="/favorites" class="list-group-item list-group-item-action"><span class="left_nav_icon"><i class="fa fa-heart" aria-hidden="true"></i></span> My favorites</a>
			  <a href="/change-password" class="list-group-item list-group-item-action"><span class="left_nav_icon"><i class="fa fa-key" aria-hidden="true"></i></span> Change Password</a>
			  <a href="/logout" class="list-group-item list-group-item-action"><span class="left_nav_icon"><i class="fa fa-lock" aria-hidden="true"></i></span> Logout</a>
            </div> 
		</div>
		
		<script>
			$(function(){
    var current = location.pathname;
    $('#list-menu a, #myleftsidenav a').each(function(){
        var $this = $(this);
        // if the current path is like this link, make it active
        if($this.attr('href').indexOf(current) !== -1){
            $this.addClass('active');
        }
    })
})

function openleftNav() {
  document.getElementById("myleftsidenav").style.width = "250px";
}

function closeleftNav() {
  document.getElementById("myleftsidenav").style.width = "0";
}
		</script>
		
		
