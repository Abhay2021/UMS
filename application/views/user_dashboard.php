<div class="card pl-5">
<div class="row"><h2>User Dashboard</h2></div>
<?php $img = base_url()."uploads/user/".$this->session->image; if(!$img){$img='';} ?>
<div class="row mt-2 mb-2"> <img src="<?php echo $img; ?>" class="img-circle" alt="user-img" height="70px" width="70px"> <h2 class="ml-2"> <?php echo $this->session->username; ?></h2></div>
<div class="row justify-content-end"> 
<?php if($this->session->admin && $this->session->logged_in && $this->session->id){?>
    <a href="<?php echo base_url(); ?>admin/dashboard" class="btn btn-primary" >Dashboard</a>
<?php } ?>
<a href="<?php echo base_url("user/logout"); ?>" class="btn btn-danger mr-5" >Log out</a>
</div>
<?php
if($response){ 
?>
<div class="row"><h3>Current weather Details</h3></div>
<hr>
<div class="row">
<div class="col-md-6">
<div class="row"><h5>City: <?php echo $city; ?></h5></div>
<div class="row"><h5>Wheather: <?php echo $response['weather'][0]['main']; ?></h5></div>
<div class="row"><h5>Description: <?php echo $response['weather'][0]['description']; ?></h5></div>
<div class="row"><h5>Temperature: <?php echo kelvinToCelsius($response['main']['temp']); ?><sup>o</sup>C</h5></div>
<div class="row"><h5>Humidity: <?php echo $response['main']['humidity']; ?>%</h5></div>
</div>
<div class="col-md-6">
<div class="row"><h5>Visibility: <?php echo $response['visibility']; ?> meter</h5></div>
<div class="row"><h5>Wind Speed: <?php echo $response['wind']['speed']; ?> m/s</h5></div>
<div class="row"><h5>pressure: <?php echo $response['main']['pressure']; ?> hPa</h5></div>
<div class="row"><h5>cloudiness: <?php echo $response['clouds']['all']; ?>%</h5></div>
</div>
</div>
<?php }
?>
</div>