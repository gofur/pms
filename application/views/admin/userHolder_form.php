<?php echo form_open($process,'id="genForm"');?>
<h3><?php echo $title ?></h3>
<input type="hidden" name="TxtHolderID" id="TxtHolderID" value="<?php echo isset($old->HolderID)?$old->HolderID:''?>">
<input type="hidden" name="TxtNIK" id="TxtNIK" value="<?php echo isset($old->NIK)?$old->NIK:$NIK?>">
<div class="row">
	<div class="span2">Begin Date </div>
	<div class="span10"><input type="text" <?php echo isset($old->Holder_BeginDate)?'readonly="readonly"':''?> class="input-small" name="TxtStartDate" id="TxtStartDate" value="<?php echo isset($old->Holder_BeginDate)?substr($old->Holder_BeginDate,0,10):date('Y/m/d')?>"> </div>
</div>
<div class="row">
	<div class="span2">End Date </div>
	<div class="span10"><input type="text" class="input-small" name="TxtEndDate" id="TxtEndDate" value="<?php echo isset($old->Holder_EndDate)?substr($old->Holder_EndDate,0,10) :'9999/12/31'?>"> </div>
</div>
<div class="row">
	<div class="span2">Main Position</div>
	<div class="span10"><input type="checkbox" <?php if( isset($old->isMain) and $old->isMain==1){echo 'checked="checked"';}?> name="chkMain" value="1" id="chkMain"> </div>
</div>
<?php if(!isset($old)) { ?>
<div class="row">
	<div class="span2">Organization</div>
	<div class="span10"><select name="SlcOrg" id="SlcOrg" class="input-xlarge">
		<option value=""></option>
		<?php
		if(isset($orgRoot)){
			if(isset($old->OrganizationID) and $old->OrganizationID==$orgRoot->OrganizationID){
				echo '<option selected="selected" value="'.$orgRoot->OrganizationID.'">'.$orgRoot->OrganizationName.'</option>';

			}else{
				echo '<option value="'.$orgRoot->OrganizationID.'">'.$orgRoot->OrganizationName.'</option>';
				
			}
		}
		foreach ($orgList as $row) {
			if(isset($old->OrganizationID) and $old->OrganizationID==$row['id']){
				echo '<option selected="selected" value="'.$row['id'].'">'. $row['text'].'</option>';

			}else{
				echo '<option value="'.$row['id'].'">'. $row['text'].'</option>';
				
			}
		}		
	?></select>
	</div>
</div>
<div id="hiddenDiv1"></div>
<?php } ?>
<div class="row">
	<div class="span10 offset2">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
<?php form_close();?>