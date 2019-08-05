<div class="card pl-5">
<div class="row"><h2>Your City Whether Details</h2></div>
<div class="row text-right"> <a href="<?php echo base_url("user/logout"); ?>" class="btn btn-danger" >Log out</a>

</div>
<?php
if($response){ 
?>
<div class="">
<h5>City: <?php echo $city; ?></h5><br />
<h5>Wheather: <?php echo $response['weather'][0]['main']; ?></h5><br />
<h5>Description: <?php echo $response['weather'][0]['description']; ?></h5><br />
<h5>Temperature: <?php echo $response['main']['temp']; ?> Kelvin</h5><br />
<h5>Humidity: <?php echo $response['main']['humidity']; ?>%</h5><br />
</div>
<?php }
?>
</div>