<?php 
	$this->load->view('template/top_popup_1_view');
?>
<h3>KPI Relationship</h3>
<div class="row">
	<div class="span2">Strategic Objective</div>
	<div class="span8"><?php echo $kpi_A->SasaranStrategis; ?></div>
</div>

<div class="row">
	<div class="span2">KPI Start</div>
	<div class="span8"><?php echo $kpi_A->KPI_BeginDate; ?></div>
</div>

<div class="row">
	<div class="span2">KPI End</div>
	<div class="span8"><?php  echo $kpi_A->KPI_EndDate; ?></div>
</div>

<div class="row">
	<div class="span2">KPI</div>
	<div class="span8"><?php echo $kpi_A->KPI; ?></div>
</div>
<div class="row">
	<div class="span2">Description</div>
	<div class="span8"><?php echo $kpi_A->Description; ?></div>
</div>
<hr>
<?php echo form_open($process, '', $hidden);?>
<div class="row">
	<div class="span2">Begin</div>
	<div class="span8"><?php echo form_input('dt_begin', '', 'class="datepicker input-small"');?></div>
</div>
<div class="row">
	<div class="span2">End</div>
	<div class="span8"><?php echo form_input('dt_end', '', 'class="datepicker input-small"');?></div>
</div>
<div class="row">
	<div class="span2">Reference</div>
	<div class="span8"><?php echo form_dropdown('slc_ref', $ref_ls, '','class="input-medium" id="slc_ref"');?></div>
</div>
<div class="row">
	<div class="span10">
		<div class="accordion" id="accordion2">
		<?php
			foreach ($rkk_B as $row) {
				echo '<div class="accordion-group">';
			  echo '<div class="accordion-heading">';
			  echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse_'.$row->RKKID.'">'.$row->NIK . ' - '.$row->Fullname .' ('.$post_ls[$row->RKKID].')</a>';
			  echo '</div>';
			  echo '<div id="collapse_'.$row->RKKID.'" class="accordion-body collapse">';
			  echo '<div class="accordion-inner">';
			  echo '<table class="table table-hover">';
				echo '<thead>';
				echo '<tr>';
				echo '<th>SO</th>';
				echo '<th>KPI</th>';
				echo '<th>Select</th>';
				echo '<th class="hidden">Ref. Weight</th>';
				echo '</tr>';
				echo '</thead>';
				echo '<tbody>';

			  foreach ($kpi_B_ls[$row->RKKID] as $kpi_B) {
			  	echo '<tr>';
			  	echo '<td>'.$kpi_B->SasaranStrategis.'</td>';
			  	echo '<td>'.$kpi_B->KPI.'</td>';
			  	echo '<td>'.form_checkbox('chk_kpi[]', $kpi_B->KPIID, FALSE).'</td>';
			  	echo '<td class="hidden">'.form_number('nm_ref_weight_'.$kpi_B->KPIID,0,'class="input-small" min=0 max=100 step=0.01').'</td>';
			  	echo '</tr>';
			  }

			  echo '</tbody>';
			  echo '</table>';
			  echo '</div>';
			  echo '</div>';
  			echo '</div>';
			}
		?>	
		</div>
	</div>
</div>

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
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('.hidden').hide();
	$('#slc_ref').change(function(event) {
		if ($(this).val()==3) {
			$('.hidden').show();

		} else {
			$('.hidden').hide();

		}
	});
});
</script>