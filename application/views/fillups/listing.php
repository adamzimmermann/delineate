
<script >
	$(document).ready(function() { 
		$("#table").tablesorter({sortList: [[5,1]]}); 
	});
</script>
<script type="text/javascript" src="/imap.js"></script>



<div id="main">
	
	<h1 id="page-title"><?php print $title ?></h1>
	
	
	<form class="form-stacked" method="post" accept-charset="utf-8" action="" />
	
		<?php
			$options['all'] = 'All';
			foreach($vehicles as $key=>$value) {
				$options[$value['ID']] = $value['make'] . ' ' . $value['model'];
			}
		?>
		<label for="vehicle_ID">Vehicle</label>
		<?php echo form_dropdown('vehicle_ID', $options, 'all'); ?>
		
		
		<?php
			unset($options);
			$options['all'] = 'All';
			foreach($users as $key=>$value) {
				$options[$value['ID']] = $value['first_name'] . ' ' . $value['last_name'];
			}
		?>
		<label for="user_ID">User</label>
		<?php echo form_dropdown('user_ID', $options, 'all'); ?>
		
		
		<input class="primary btn" type="submit" />
		
	</form>
	
	
	
	<table id="table" class="bordered-table zebra-striped">
		<thead>
			<tr>
				<?php $collumns = array(
					'Date',
					'MPG',
					'Price',
					'Gallons',
					'Cost',
					'Mileage',
				); ?>
				<?php foreach($collumns as $collumn): ?>
					<th><?php print $collumn; ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		
		<?php foreach($fillups as $fillup): ?>
			<tr>
				<td><?php print date('n/j/Y', strtotime($fillup->date)); ?></td>
				
				<td><?php print $fillup->mpg; ?></td>
				
				<td><?php print money_format('%.2n', $fillup->price); ?></td>
				
				<td><?php print number_format($fillup->gallons, 1); ?></td>
				
				<td><?php print money_format('%.2n', $fillup->cost); ?></td>
				
				<td><?php print number_format(round($fillup->mileage, 0)); ?></td>
			</tr>
		<?php endforeach; ?>
		
	</table>
	
</div>