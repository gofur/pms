<?php echo form_open($process,'id="genForm"');?>
<h3><?php echo $title ?></h3>
<input type="hidden" name="TxtPositionID" id="TxtPositionID" value="<?php echo isset($old->PositionID)?$old->PositionID:''?>">
<input type="hidden" name="TxtOrganizationID" id="TxtOrganizationID" value="<?php echo isset($old->OrganizationID)?$old->OrganizationID:$head->OrganizationID?>">

<div class="row">
	<div class="span2">Begin Date </div>
	<div class="span10"><input type="text" <?php echo isset($old->BeginDate)?'readonly="readonly"':''?> class="input-small" name="TxtStartDate" id="TxtStartDate" value="<?php echo isset($old->BeginDate)?substr($old->BeginDate,0,10):date('Y/m/d')?>"> </div>
</div>
<div class="row">
	<div class="span2">End Date </div>
	<div class="span10"><input type="text" class="input-small" name="TxtEndDate" id="TxtEndDate" value="<?php echo isset($old->EndDate)?substr($old->EndDate,0,10) :'9999/12/31'?>"> </div>
</div>
<div class="row">
	<div class="span2">Parent Organization</div>
	<div class="span10"><?php echo full_org_text($head->OrganizationID,0,'') ?></div>
</div>
<div class="row">
	<div class="span2">Position Name</div>
	<div class="span10"><input type="text" class="input-large" name="TxtPositionName" id="TxtPositionName" value="<?php echo isset($old->PositionName)?$old->PositionName:''?>"></div>
</div>
<div class="row">
	<div class="span2">Head of Organization</div>
	<div class="span10">
		<?php 
			$arrayName = array('None','Co-Chief','Chief' );

			for ($i=2; $i>= 0 ; $i--) {
				if(isset($old->Chief) and $old->Chief==$i){
					echo '<label class="radio"><input checked type="radio" name="RdChief" id="chief'.$i.'" value="'.$i.'">'.$arrayName[$i].'</label>';

				}else{
					echo '<label class="radio"><input type="radio" name="RdChief" id="chief'.$i.'" value="'.$i.'">'.$arrayName[$i].'</label>';
					
				}
			}
		?>
	</div>
</div>
<div class="row">
	<div class="span2">Position Level</div>
	<div class="span10">
		<select name="SlcPositionGroup" class="input-medium">
			<option value=""></option>
		<?php
		$arrayName = array('Layer 1','Layer 2','Layer 3','Layer 4','Group 1','Group 2','Group 3','Group 4','Group 5');
		foreach ($arrayName as $row) {
			if (isset($old->PositionGroup) and $old->PositionGroup==$row){
				echo '<option selected="selected" value="'.$row.'">'.$row.'</option>';
			}else{
				echo '<option value="'.$row.'">'.$row.'</option>';
			}
		}
	?></select></div>
</div>

<div class="row">
	<div class="span10 offset2">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
<?php form_close();?>