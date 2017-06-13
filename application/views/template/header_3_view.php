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
			<div class="span4"><?php echo $user_dtl->NIK ;?></div>
		</div>
		<div class="row">
			<div class="span2">Name</div>
			<div class="span4"><?php echo $user_dtl->Fullname; ?></div>
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
			<div class="span2">Month</div>
			<div class="span4"><?php 
			$month_ls = array(
				1  => 'Jan', 
				2  => 'Feb', 
				3  => 'Mar', 
				4  => 'Apr', 
				5  => 'May', 
				6  => 'Jun', 
				7  => 'Jul', 
				8  => 'Aug', 
				9  => 'Sep', 
				10 => 'Oct', 
				11 => 'Nov', 
				12 => 'Dec' 
			);
			echo form_dropdown('slc_month', $month_ls,$month,'class="input-small" id="slc_month"');;  
			?></div>
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