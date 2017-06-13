<?php echo form_open($process,'id="genForm"');?>
<h3><?php echo $title ?></h3>
<input type="hidden" name="TxtOrganizationID" id="TxtOrganizationID" value="<?php echo isset($old->OrganizationID)?$old->OrganizationID:''?>">
<input type="hidden" name="TxtUserID" id="TxtUserID" value="<?php echo isset($old->UserID)?$old->UserID:''?>">
<input type="hidden" name="TxtHolderID" id="TxtHolderID" value="<?php echo isset($old->HolderID)?$old->HolderID:''?>">
<div class="row">
	<div class="span2">Begin Date </div>
	<div class="span10"><input type="text" <?php echo isset($old->BeginDate)?'readonly="readonly"':''?> class="input-small" name="TxtStartDate" id="TxtStartDate" value="<?php echo isset($old->BeginDate)?substr($old->BeginDate,0,10):date('Y/m/d')?>"> </div>
</div>
<div class="row">
	<div class="span2">End Date </div>
	<div class="span10"><input type="text" class="input-small" name="TxtEndDate" id="TxtEndDate" value="<?php echo isset($old->EndDate)?substr($old->EndDate,0,10) :'9999/12/31'?>"> </div>
</div>
<div class="row">
	<div class="span2">NIK</div>
	<div class="span10"><input type="text" class="input-small" name="TxtNIK" id="TxtNIK" <?php echo isset($old->NIK)?'Readonly="readonly"' :''?> value="<?php echo isset($old->NIK)?$old->NIK :''?>"></div>
</div>
<div class="row">
	<div class="span2">Fullname</div>
	<div class="span10"><input type="text" class="input-large" name="TxtFullname" id="TxtFullname" value="<?php echo isset($old->Fullname)?$old->Fullname :''?>"></div>
</div>
<div class="row">
	<div class="span2">Email</div>
	<div class="span10"><input type="text" class="input-large" name="TxtEmail" id="TxtEmail" value="<?php echo isset($old->Email)?$old->Email :''?>"></div>
</div>
<div class="row">
	<div class="span2">Mobile Phone</div>
	<div class="span10"><input type="text" class="input-large" name="TxtMobile" id="TxtMobile" value="<?php echo isset($old->Mobile)?$old->Mobile :''?>"></div>
</div>
<div class="row">
	<div class="span2">Work Contract</div>
	<div class="span10"><select name="SlcStatus" id="SlcStatus" class="input-medium">
		<option value=""></option>
		<?php $arrayName = array(1 => 'Permanent', 2 =>'Contract');
			foreach ($arrayName as $key => $value) {
				if (isset($old->statusFlag) and $old->statusFlag == $key){
					echo '<option selected="selected" value="'.$key.'">'.$value.'</option>';

				}else{
					echo '<option value="'.$key.'">'.$value.'</option>';
					
				}
			}
	?></select>
	</div>
</div>
<div class="row">
	<div class="span2">Role Access</div>
	<div class="span10"><select name="SlcRole" id="SlcRole" class="input-medium">
		<option value=""></option>
		<?php
		foreach ($roleList as $row) {
			if(isset($old->RoleID) and $old->RoleID==$row->RoleID){
				echo '<option selected="selected" value="'.$row->RoleID.'">'. $row->Role .'</option>';

			}else{
				echo '<option value="'.$row->RoleID.'">'. $row->Role .'</option>';

			}
		}		
	?></select>
	</div>
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
<?php }?>
<div id="hiddenDiv1"></div>
<div class="row">
	<div class="span10 offset2">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
<?php form_close();?>