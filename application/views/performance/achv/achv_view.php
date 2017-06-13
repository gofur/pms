<hr>
<input type="hidden" id="rkk_id" value="<?php echo isset($rkk_id)?$rkk_id:'' ?>" >
<input type="hidden" id="achv_id" value="<?php echo isset($achv_head)?$achv_head->RKKAchievementID:'' ?>" >

<div class="row">
	<div class="span6">
		<div class="row">
			<div class="span2">RKK Start</div>
			<div class="span4"><?php echo substr($rkk->BeginDate,0,10); ?></div>
		</div>
		<div class="row">
			<div class="span2">RKK End</div>
			<div class="span4"><?php echo  substr($rkk->EndDate,0,10); ?></div>
		</div>
		<div class="row">
			<div class="span2">Achievement Status</div>
			<div class="span4"><?php 
			$status = array(
				'<span class="label">Draft</span>',
				'<span class="label label-warning">Submited</span>',
				'<span class="label label-important">Rejected</span>',
				'<span class="label label-success">Agreed</span>',
				'<span class="label label-success">Lock</span>',
				'<span class="label label-success">Final</span>'
			);
			echo $status[$achv_head->Status_Flag]; 
			?></div>
		</div>
	</div>
	<?php 
		if (isset($user_A)){

	?>

	<div class="span6">
		<div class="row">
			<div class="span2">Superior</div>
			<div class="span4"><?php echo $user_A->NIK ; ?></div>
		</div>
		<div class="row">
			<div class="span4 offset2"><?php echo $user_A->Fullname; ?></div>
		</div>
		<div class="row">
			<div class="span4 offset2"><?php if(is_null($post_A)) {echo $post_A->PositionName;} ?></div>
		</div>
	</div>
	<?php
		}
	?>
</div>
<div class="row">
	<div class="span12">
		<?php 

		echo '<div class="btn-group pull-right">';
		if (isset($link_submit)) {
			echo anchor($link_submit, '<i class="icon-envelope"></i> Submit', 'class="btn" title="Send Achievement to Superior"');
		}
		if (isset($link_agree) ) {
			echo anchor($link_agree, '<i class="icon-ok"></i> Approve', 'class="btn btn-success" title="Approve this Achievement"');
		}

		if (isset($link_reject)) {
			echo anchor($link_reject, '<i class="icon-remove"></i> Reject', 'class="btn btn-danger" title="Reject this Achievement"');
		}

		if (isset($link_unlock)) {
			echo anchor($link_unlock, '<i class="icon-unlock"></i> Unlock', 'class="btn" title="Unlock Achievement"');
		}
		echo '</div>';
		?>
	</div>
</div>


<hr>
<div class="row">
	<div class="span6">
		<h4 id="title_sum">Executive Summary</h4>
		<textarea class="span6" rows="5" id="txt_summary"><?php echo $achv_head->Summary ?></textarea>

	</div>
	<div class="span6">
		<h4 id="title_note">Notes</h4>
		<textarea class="span6" rows="5" id="txt_notes"><?php echo $achv_head->Notes ?></textarea>
		
	</div>
</div>

<?php 
	
	foreach ($persp_ls as $persp) {
		$data['persp']  = $persp;
		$data['weight'] = $persp_weight[$persp->PerspectiveID];
		$data['so_ls']  = $so_ls[$persp->PerspectiveID];
		$this->load->view('performance/achv/so_list',$data);
	}
?>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".so-kpi").hide();
		$(".toggle-so").toggle(function() {
			$(this).attr('class', 'icon-chevron-down icon-large btn btn-link toggle-so');
			var so_id = $(this).data('so');
			$("#so-kpi-"+so_id).show();

			var base_url = '<?php echo base_url(). "index.php/" ?>';
			$('#so-kpi-'+so_id+" td").load(base_url+'performance/achievement/show_kpi',{
				so_id  : so_id, 
				rkk_id : $("#rkk_id").val(),
				month  : $('#slc_month').val()
			} ,
				function(){
				/* Stuff to do after the page is loaded */
			});
			

		}, function() {
			$(this).attr('class', 'icon-chevron-right icon-large btn btn-link toggle-so');
			var so_id = $(this).data('so')
			$("#so-kpi-"+so_id).hide();
		});


		$('#txt_summary').focusout(function(event) {
			$("#title_sum").html('Executive Summary <i class="icon-save"></i>');

			$.ajax({
				url: '<?php echo base_url() ?>'+'index.php/performance/achievement/save_summary',
				type: 'POST',
				data: {
					achv_id: <?php echo $achv_head->RKKAchievementID ?>,
					summary: $('#txt_summary').val()
				},
			})
			.done(function() {

			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
			  $("#title_sum").html('Executive Summary');

			});
		});


		$('#txt_notes').focusout(function(event) {
			$("#title_note").html('Notes <i class="icon-save"></i>');

			$.ajax({
				url: '<?php echo base_url() ?>'+'index.php/performance/achievement/save_notes',
				type: 'POST',
				data: {
					achv_id: <?php echo $achv_head->RKKAchievementID ?>,
					notes: $('#txt_notes').val()
				},
			})
			.done(function() {

			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
			  $("#title_note").html('Notes');

			});
		});
	});
</script>