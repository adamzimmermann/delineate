<?php //echo form_open('fillup/process'); ?>

<?php echo validation_errors(); ?>

<script>
	$(document).ready(function() {
		$("#datepicker").datepicker();
		$( "#anim" ).change(function() {
			$( "#datepicker" ).datepicker( "option", "showAnim", $( this ).val() );
		});
	});
</script>


<form class="form-stacked" method="post" accept-charset="utf-8" action="/fillups/process" />


<label for="date">Date</label>
<input type="text" name="date" id="datepicker" size="5"/>


<?php $query = $this->db->get('vehicle'); ?>
<?php $vehicles = $query->result_array(); ?>
<?php
foreach($vehicles as $key=>$value) {
	$options[$value['ID']] = $value['make'] . ' ' . $value['model'];
}
?>


<label for="vehicle_ID">Vehicle</label>
<?php echo form_dropdown('vehicle_ID', $options); ?>


<label for="gallons">Gallons</label>
<input type="text" name="gallons" id="gallons" size="5"/>


<label for="price">Price</label>
<input type="text" name="price" id="price" size="5"/>


<label for="mileage">Mileage</label>
<input type="text" name="mileage" id="mileage" size="10"/>


<input class="primary btn" type="submit" />