<?php 
	$this->load->view('template/top_popup_1_view');
?>
<h3>KPI Relationship</h3>
<div class="row">
	<div class="span2">Strategic Objective</div>
	<div class="span8"><?php echo $kpi->SasaranStrategis; ?></div>
</div>

<div class="row">
	<div class="span2">KPI Start</div>
	<div class="span8"><?php echo $kpi->KPI_BeginDate; ?></div>
</div>

<div class="row">
	<div class="span2">KPI End</div>
	<div class="span8"><?php  echo $kpi->KPI_EndDate; ?></div>
</div>

<div class="row">
	<div class="span2">KPI</div>
	<div class="span8"><?php echo $kpi->KPI; ?></div>
</div>
<div class="row">
	<div class="span2">Description</div>
	<div class="span8"><?php echo $kpi->Description; ?></div>
</div>
<hr>
<?php echo form_open($process, '', $hidden);?>

<div class="row">
	<div class="span10">
	<?php
	echo anchor($link_add, '<i class="icon-plus"></i>', 'class="btn pull-right" title="Cascade to Existing KPI"');
	?>
	</div>
</div>
<div class="row">
	<div class="span10">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>NIK - Name</th>
					<th>Position</th>
					<th>KPI</th>
					<th>Rel. Begin</th>
					<th>Rel. End</th>
					<!-- <th>Action</th> -->
					<th>Select</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if (count($kpi_rel)) {
					foreach ($kpi_rel as $row) {
						$key = $row->isSAP .'|'. $row->PositionID;
						echo '<tr>';
						echo '<td>'.$row->NIK .' - '. $row->Fullname.'</td>';
						echo '<td>'.$post_ls[$row->R_KPIID].'</td>';
						echo '<td>'.$row->KPI.'</td>';
						echo '<td>'.$row->BeginDate.'</td>';
						echo '<td>'.$row->EndDate.'</td>';
						// echo '<td>';
						// echo anchor('', '<i class="icon-pencil"></i>', 'class="btn" title="Edit"');
						// echo '</td>';
						echo '<td>'.form_checkbox('chk_rel[]', $row->R_KPIID, FALSE).'</td>';
						echo '</tr>';
					}
					# code...
				} else {
					echo '<tr><td colspan="6">This KPI is avaiable to have Relation with other KPI ;)</td></tr>';
				}
				?>
			</tbody>
		</table>
	</div>
</div>
<?php $this->load->view('template/rm_form'); ?>
<div class="row">
	<div class="span10">
		<div class="form-actions">
		  <button type="submit" class="btn btn-primary">Process</button>
		  <?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
<?php
	$this->load->view('template/bottom_popup_1_view');
?>