<hr>
<?php echo form_open($process, ''); ?>
<div class="row">
	<div class="span12">
		<div class="btn-group pull-right">
			<?php echo anchor($link_add, '<i class="icon-plus"></i>', 'class="btn fancybox" data-fancybox-type="iframe" 	title="Add Relation"'); ?>
		</div>
	</div>
</div>
<div class="row">
	<div class="span12">
		<table id="tbl_rkk_rel" class="table table-hover">
			<thead>
				<tr>
					<th>Rel ID</th>
					<th>RKK ID</th>
					<th>NIK - Fullname</th>
					<th>Position</th>
					<th>Rel. Begin</th>
					<th>Rel. End</th>
					<th>Select</th>
				</tr>
			</thead>
			<tbody>
			<?php
				foreach ($rel_ls as $row) {
					$key = $row->isSAP .'|'. $row->PositionID;
					echo '<tr>';
					echo '<td>'.$row->R_RKKID.'</td>';
					echo '<td>'.$row->RKKID.'</td>';
					echo '<td>'.$row->NIK .' - '. $row->Fullname.'</td>';
					echo '<td>'.$post_ls[$key].'</td>';
					echo '<td>'.substr($row->BeginDate,0,10).'</td>';
					echo '<td>'.substr($row->EndDate,0,10).'</td>';
					echo '<td>'.form_checkbox('chk_rel[]',$row->R_RKKID , FALSE).'</td>';
					echo '</tr>';
				}
			?>	
			</tbody>
		</table>
	</div>
</div>
<?php $this->load->view('template/rm_form'); ?>
<div class="row">
	<div class="span12">
		<div class="form-actions">
		  <button type="submit" id="btn_process" class="btn" title="Delimit/Remove Relation">Process</button>
		</div>
	</div>
</div>
<?php echo form_close();?>
<script type="text/javascript">
  $(document).ready(function(){
  	$('#tbl_rkk_rel').dataTable();
  	
    $(".datepicker").datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      changeYear: true
    });
    $('.disabled').attr('disabled',true);
      $('#btn_process').attr('disabled',true);

    $('#rd_delimit').click(function(event) {
      $('#dt_end').attr('disabled',false);
      $('#btn_process').attr('disabled',false);
    });

    $('#rd_remove').click(function(event) {
      $('#dt_end').attr('disabled',true);
      $('#btn_process').attr('disabled',false);

      /* Act on the event */
    });
  });
</script>