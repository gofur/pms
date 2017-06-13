<?php $attributes = array('id' => 'genFrom'); echo form_open($process,$attributes)?>
<h3><?=$title?></h3>
<input type="hidden" name="TxtFeedbackDetailID" id="TxtFeedbackDetailID" value="<?php echo isset($old->FeedbackDetailID)?$old->FeedbackDetailID:''?>">
<input type="hidden" name="TxtFeedbackPointID" id="TxtFeedbackPointID" value="<?php echo isset($old->FeedbackPointID)?$old->FeedbackPointID:''?>">
<input type="hidden" name="TxtStatusPoint" id="TxtStatusPoint" value="<?php echo isset($old->StatusPoint)?$old->StatusPoint:'' ?>" readonly>
<?php 

	if(isset($old->StatusPoint)==1){
		$readonly="readonly";
	}
	else
	{
		$readonly=""; 
	}
?>
<div class="row">
	<div class="span3">Feedback Aspect</div>
	<div class="span9">
		<input type="text" name="TxtFeedbackAspect" id="TxtFeedbackAspect" value="<?php echo $FeedbackAspect;?>" readonly>
	</div>
</div>
<div class="row">
	<div class="span3">Feedback Point</div>
	<div class="span9"><textarea rows="4" cols="100" name="txtFeedbackPoint" id="txtFeedbackPoint" <?php echo $readonly ?>><?php echo isset($old->FeedbackPoint)?$old->FeedbackPoint:''?></textarea></div>
</div>
<div class="row">
	<div class="span3">Evidence</div>
	<div class="span9"><textarea rows="4" cols="100" name="txtEvidence" id="txtEvidence" <?php echo $readonly ?>><?php echo isset($old->Evidence)?$old->Evidence:''?></textarea></div>
</div>
<div class="row">
	<div class="span3">Cause :</div>
	<div class="span9"><textarea rows="4" cols="100" name="txtCause" id="txtCause" <?php echo $readonly ?>><?php echo isset($old->Cause)?$old->Cause:''?></textarea></div>
</div>
<div class="row">
	<div class="span3">Alternative Solution</div>
	<div class="span9"><textarea rows="4" cols="100" name="txtAltSolution" id="txtAltSolution" <?php echo $readonly ?>><?php echo isset($old->AlternativeSolution)?$old->AlternativeSolution:''?></textarea></div>
</div>
<div class="row">
	<div class="span3">Due Date</div>
	<div class="span9"><input type="text" class="input-large" name="TxtDueDate" id="TxtDueDate" value="<?php echo isset($old->DueDate)?$old->DueDate:''?>" <?php echo $readonly ?>></div>
</div>
<div class="row">
	<div class="span3">Actual Date</div>
	<div class="span9"><input type="text" class="input-small" name="TxtActualDate" id="TxtActualDate" value="<?php echo isset($old->ActualDate)?$old->ActualDate:''?>"></div>
</div>
<div class="row">
	<div class="span3">Check List</div>
	<div class="span9">
		<input type="checkbox" name="chkList" class="input-small" value="1" <input type="checkbox" <?php if( isset($old->CheckList) and $old->CheckList==1){echo 'checked="checked"';}?> value="1"></div>
</div>
<div class="row">
	<div class="span3">Notes</div>
	<div class="span9"><textarea rows="4" cols="100" name="txtNotesADP" id="txtNotesADP"><?php echo isset($old->Notes)?$old->Notes:''?></textarea></textarea></div>
</div>
<div class="row">
	<div class="span9 offset3">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
</form>