<h3>RKK Delimit</h3>

<div class="row">
	<div class="span2">NIK</div>
	<div class="span4"><?php echo
 $user->NIK ;?></div>
</div>
<div class="row">
	<div class="span2">Name</div>
	<div class="span4"><?php echo
 $user->Fullname; ?></div>
</div>
<div class="row">
	<div class="span2">Position</div>
	<div class="span4"><?php echo
 $post->PositionName; ?></div>
</div>

<?php echo form_open($action,'',$hidden) ?>
<div class="row">
	<div class="span2">RKK & IDP End Date</div>
	<div class="span10"><?php echo $end_date ?></div>
</div>
<div class="row">
	<div class="span12">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>NIK</th>
					<th>Name</th>
					<th>Position</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				foreach ($sub as $row) {
					echo '<tr>';
					echo '<td>'.$row['nik'].'</td>';
					echo '<td>'.$row['name'].'</td>';
					echo '<td>'.$row['post'].'</td>';
					echo '</tr>';
				}
			?>
			</tbody>
		</table>

	</div>
</div>

<div class="row">
	<div class="span12">
		<?php echo anchor('manager/rkk_delimit', 'Cancel', 'class="btn pull-right"') .' ';?>
		 <input type="submit" value="Delimit" class="btn btn-warning pull-right">
	</div>
</div>
<?php echo form_close(); ?>

