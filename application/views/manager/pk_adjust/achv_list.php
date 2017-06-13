
	<div class="row">

		<div class="span12">
			<div class="btn-group pull-right">
				<button class="btn" id="btn_lock" title="Lock"><i class="icon-lock"></i></button>
				<button class="btn" id="btn_unlock" title="Unlock"><i class="icon-unlock"></i></button>
				
			</div>

		</div>
	</div>
<?php echo form_open($process, '', $hidden);?>
	<div class="row">

		<div class="span12">
			<div class="btn-group pull-right">
				<button type="submit" class="btn" id="btn_save" title="Save Adjustment"><i class="icon-save"></i></button>
				
			</div>

		</div>
	</div>
	<div class="row">
		<div class="span12">
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th >NIK</th>
						<th >Name</th>
						<th >Organization</th>
						<th >Position</th>
						<?php 
							for ($i=0; $i < $sub_period; $i++) {
								$flag = 0;
								foreach ($aspect_ls as $row) {
									if ($flag <2) {
								 		echo '<th>'.$row->label.' ('.$row->percent.'%)</th>';
								 		$flag++;
								 	}
								} 
								echo '<th>Project</th>';
								echo '<th>Total</th>';
								echo '<th>Category</th>';
								echo '<th>Adjust</th>';
								echo '<th>Notes</th>';
							}
						?>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($temp_list as $row) {
						echo '<tr>';
						echo '<td>'.$row['nik'].'</td>';
						echo '<td>'.$row['fullname'].'</td>';
						echo '<td>'.$row['org_name'].'</td>';
						echo '<td>'.$row['post_name'].'</td>';
				
						$flag = 0;
						foreach ($aspect_ls as $row_2) {
							if ($flag <2) {
								echo '<td>'.$row[$row_2->aspect_id].'</td>';
								$flag++;
							}
						}
						echo '<td>'.$row['project'].'</td>';
						echo '<td>'.$row['total'].'</td>';
						echo '<td>'.$row['category'].'</td>';
						echo '<td>';
						echo $row['adjustment'];
						echo '</td>';
						echo '<td>';
						echo anchor($row['notes_link'], '<i class="icon icon-file-text"></i>', 'class="fancybox-nonrefresh"  data-fancybox-type="iframe"');
						echo '</td>';
						echo form_hidden('hd_total_'.$row['nik'], $row['total']);
						
						echo '</tr>';
					}
					?>
				</tbody>
			</table>

		</div>
	</div>
<?php echo form_close(); ?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#btn_save').hide();
		$('#btn_lock').click(function(event) {
			$('#btn_save').show();

			base_url = '<?php echo base_url(). "index.php/" ?>';

			var scope = $("input[name=rd_scope]:checked").val()
			var org_id = $('#slc_org_0').val();
			var post_id = $('#slc_post').val();
			var max_el = $('.slc_org').length;
			for (var i = 1; i < max_el; i++) {
				if ($('#slc_org_'+i).val() != '') {
					org_id = $('#slc_org_'+i).val();
				};
			};

			$.ajax({
      	url: base_url + 'manager/pk_adjust/lock',
      	type: 'POST',
      	data: {
      		org_id : org_id,
      		post_id : post_id,
      		scope : scope,
      	},
      })
      .done(function(msg) {
      	// $("#box-notif").html(msg);
      })
      .fail(function() {
      	console.log("error");
      })
      .always(function() {
      	console.log("complete");
      });
		});
		$('#btn_unlock').click(function(event) {
			$('#btn_save').hide();
			
			base_url = '<?php echo base_url(). "index.php/" ?>';

			var scope = $("input[name=rd_scope]:checked").val()
			var org_id = $('#slc_org_0').val();
			var post_id = $('#slc_post').val();
			var max_el = $('.slc_org').length;
			for (var i = 1; i < max_el; i++) {
				if ($('#slc_org_'+i).val() != '') {
					org_id = $('#slc_org_'+i).val();
				};
			};

			$.ajax({
      	url: base_url + 'manager/pk_adjust/unlock',
      	type: 'POST',
      	data: {
      		org_id : org_id,
      		post_id : post_id,
      		scope : scope,
      	},
      })
      .done(function(msg) {
      	// $("#box-notif").html(msg);
      })
      .fail(function() {
      	console.log("error");
      })
      .always(function() {
      	console.log("complete");
      });
		});
	});
</script>