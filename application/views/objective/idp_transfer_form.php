<h3>IDP Transfer</h3>
<div class="row">
	<div class="span10">
	  <ul class="breadcrumb">
		  <li >1. Select Subordinate <span class="divider">-></span></li>
		  <li class="active">2. Transfered IDP</li>
	  </ul>
	</div>
</div>
<div class="row">
	<div class="span10">
		<div class="well">
			<?php $attributes = array('id' => 'genFrom'); echo form_open($process,$attributes)?>
			<input type="hidden" name="txt_detail_id" id="txt_detail_id" value="<?php echo $Detail_IDP->IDPDetailID ?>">

			<div class="row">
				<div class="span0">Development Area Type 1 :</div>
				<div class="span0"><?php echo $DevelopmentAreaType ?> - <?php echo $development_area_type_desc ?></div>
				<input type="hidden" name="txt_dev_area_id" id="txt_dev_area_id" value="<?php echo $Detail_IDP->DevelopmentAreaType1ID ?>">
				<input type="hidden" name="txt_dev_area" id="txt_dev_area" value="<?php echo $Detail_IDP->DevelopmentAreaType ?>">
			</div>
			<div class="row">
				<div class="span0">Development Program :</div>
				<div class="span0"><?php echo $Detail_IDP->DevelopmentProgram ?> - <?php echo $Detail_IDP->desc_prog ?></div>
				<input type="hidden" name="txt_dev_program_id" id="txt_dev_program_id" value="<?php echo $Detail_IDP->DevelopmentProgramID ?>">
				<input type="hidden" name="txt_dev_program" id="txt_dev_program" value="<?php echo $Detail_IDP->desc_prog ?>">
			</div>
			<div class="row">
				<div class="span0">Planned Time :</div>
				<div class="span0"><?php echo format_timedate($Detail_IDP->Planned_BeginDate) ?> s/d <?php echo format_timedate($Detail_IDP->Planned_EndDate) ?></div>
				<input type="hidden" name="txt_planned_begindate" id="txt_planned_begindate" value="<?php echo $Detail_IDP->Planned_BeginDate ?>">
				<input type="hidden" name="txt_planned_enddate" id="txt_planned_enddate" value="<?php echo $Detail_IDP->Planned_EndDate ?>">
			</div>
			<div class="row">
				<div class="span0">Investment :</div>
				<div class="span0"><?php echo thousand_separator($Detail_IDP->Planned_Investment) ?></div>
				<input type="hidden" name="txt_planned_investment" id="txt_planned_investment" value="<?php echo $Detail_IDP->Planned_Investment ?>">
			</div>
			<div class="row">
				<div class="span0">Notes :</div>
				<div class="span0"><?php echo $Detail_IDP->Notes ?></div>
				<input type="hidden" name="txt_notes" id="txt_notes" value="<?php echo $Detail_IDP->Notes ?>">
			</div>
		</div>
	</div>
</div>

<?php
	$i=1;

	foreach ($Subordinate as $row) {

		if($i%2==1){
			echo '<div class="row">';
		}


		foreach($status_array[$row->UserID] as $row_1){
			
			if($row_1->statusFlag!='3' AND $row_1->NIK!=$my_user_id)
			{
				echo '<div class="span5"><label class="checkbox"><input type="checkbox" name="ChkSubordinate_'.$row->UserID.'" value="1">'.$row->Fullname.' ('.$row->NIK.')</label></div>';					
			}
			else
			{
				echo '<div class="span5"><label class="checkbox"><input type="checkbox" disabled="disable" name="ChkSubordinate_'.$row->UserID.'" value="1">'.$row->Fullname.' ('.$row->NIK.')</label></div>';							
			}
		}
		
		
		
		if($i%2==0){
			echo '</div>';
		}
		$i++;
	}
?>
<div class="row">
	<div class="span8 offset2">
		<button type="submit" class="btn btn-primary">Transfer</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>

</form>