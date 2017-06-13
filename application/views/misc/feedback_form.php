<?php $attributes = array('id' => 'genFrom'); echo form_open($process,$attributes)?>
<h3><?=$title?></h3>
<input type="hidden" name="TxtRKKID" id="TxtRKKID" value="<?php echo isset($RKKID)?$RKKID:'' ?>">
<input type="hidden" name="TxtOrgID" id="TxtOrgID" value="<?php echo isset($OrgID)?$OrgID:'' ?>">
<div class="row">
	<div class="span3">Feedback Aspect</div>
	<div class="span9">
		<select name="SlcFeedbackAspect" id="SlcFeedbackAspect">
		<option value=""></option>
	<?php
		foreach ($FeedbackAspectList as $row) {
			if(isset($Old->FeedbackAspectID) and $row->FeedbackAspectID==$Old->FeedbackAspectID){
				echo '<option selected="selected" value="'.$row->FeedbackAspectID.'">'.$row->FeedbackAspect.'</option>';
			}else{
				echo '<option value="'.$row->FeedbackAspectID.'">'.$row->FeedbackAspect.'</option>';
				
			}
		}
	?>
	</select></div>
</div>
<div class="row">
	<div class="span3">Feedback Point</div>
	<div class="span9"><textarea rows="4" cols="100" name="txtFeedbackPoint" id="txtFeedbackPoint"></textarea></div>
</div>
<div class="row">
	<div class="span3">Evidence</div>
	<div class="span9"><textarea rows="4" cols="100" name="txtEvidence" id="txtEvidence"></textarea></div>
</div>
<div class="row">
	<div class="span3">Cause :</div>
	<div class="span9"><textarea rows="4" cols="100" name="txtCause" id="txtCause"></textarea></div>
</div>
<div class="row">
	<div class="span3">Alternative Solution</div>
	<div class="span9"><textarea rows="4" cols="100" name="txtAltSolution" id="txtAltSolution"></textarea></div>
</div>
<div class="row">
	<div class="span3">Due Date</div>
	<div class="span9"><input type="text" class="input-large" name="TxtDueDate" id="TxtDueDate" value=""></div>
</div>
<div class="row">
	<div class="span3">Actual Date</div>
	<div class="span9"><input type="text" class="input-small" name="TxtActualDate" id="TxtActualDate" value=""></div>
</div>
<div class="row">
	<div class="span3">Check List</div>
	<div class="span9">
		<input type="checkbox" name="chkList" class="input-small" value="1"></div>
</div>
<div class="row">
	<div class="span3">Notes</div>
	<div class="span9"><textarea rows="4" cols="100" name="txtNotesADP" id="txtNotesADP"></textarea></div>
</div>
<div class="row">
	<div class="span9 offset3">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
</form>