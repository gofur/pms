<div class="row">
	<div class="span6">
		<div class="row">
			<div class="span2">Period</div>
			<div class="span1"><?php echo $period->Tahun; ?></div>
			<div class="span1"><?php echo anchor('period', 'Change Period', 'class="fancybox" data-fancybox-type="iframe"'); ?></div>
		</div>
		<div class="row">
			<div class="span2">Period Start</div>
			<div class="span4"><?php echo substr($period->BeginDate,0,10); ?></div>
		</div>
		<div class="row">
			<div class="span2">Period End</div>
			<div class="span4"><?php echo substr($period->EndDate,0,10); ?></div>
		</div>
		<div class="row">
			<div class="span2">NIK</div>
			<div class="span4"><strong><?php echo $user_dtl->NIK ;?></strong></div>
		</div>
		<div class="row">
			<div class="span2">Name</div>
			<div class="span4"><strong><?php echo $user_dtl->Fullname; ?></strong></div>
		</div>
		<div class="row">
			<div class="span2">Position</div>
			<div class="span4"><?php
			
				echo '<select class="input-large" name="SlcPost" id="SlcPost">';
				foreach ($post_ls_SAP as $row) {
					echo '<option value="1.'.$row->HolderID.'">'.$row->PositionName.'</option>';
				}

				foreach ($post_ls_nonSAP as $row) {
					echo '<option value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';
				}

				foreach ($assign_ls_nonSAP as $row) {
					echo '<option value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';
				}

				foreach ($assign_ls_SAP as $row) {

					echo '<option value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';

				}
				echo '</select>';

			?>
			</div>
		</div>

		<div class="row">
			<div class="span2">Start Date</div>
			<div class="span4"><?php echo form_input('dt_filter_start', $filter_start, 'id="dt_filter_start" class="input-small datepicker"');  ?></div>
		</div>
		<div class="row">
			<div class="span2">End Date</div>
			<div class="span4"><?php echo form_input('dt_filter_end', $filter_end, 'id="dt_filter_end" class="input-small datepicker"');  ?></div>
		</div>
		<div class="row">
			<div class="span2"></div>
			<div class="span4"><button class="btn" id="btn_view"><i class="icon-refresh"></i> View</button></div>
		</div>

	</div>
	<i class="icon-spinner icon-spin icon-large icon-4x" id="spin-subordinate"></i>
	<div class="span6" id="box-subordinate">
	</div>
</div>