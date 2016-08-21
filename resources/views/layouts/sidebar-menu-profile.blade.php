<!-- menu profile quick info -->
<div class="profile">
  <div class="profile_pic">
    <img src="{{ asset('images/user-default-profile.png') }}" alt="profile" class="img-circle profile_img">
  </div>
  <div class="profile_info">
    <span>Welcome,</span>
    <h2>{{ Auth::user()->username }}</h2>
  </div>
</div>
<br/>	
<p></p>
<div class="clearfix"></div>
<!-- /menu profile quick info -->