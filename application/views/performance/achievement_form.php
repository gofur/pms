<h3><?php echo $title ?></h3>
<?php 
$atr = array( 'id' => 'genform');
echo form_open_multipart($action,$atr) ?>
<input type="hidden" value="<?php echo isset($month)? $month :'' ?>" name="hdn_month">
<input type="hidden" value="<?php echo isset($RKKAchievementID)? $RKKAchievementID :'' ?>" name="hdn_RKKAchievementID">
<input type="hidden" value="<?php echo isset($RKKDetailID)?$RKKDetailID:'' ?>" name="hdn_RKKDetailID">
<input type="hidden" name="hdn_RKKAchievementDetailID" value="<?php echo isset($old->RKKAchievementDetailID)?$old->RKKAchievementDetailID:'' ?>">
<div class="row">
	<div class="span2">Objective</div>
	<div class="span8"><?php echo $head->SasaranStrategis ?></div>
</div>
<div class="row">
	<div class="span2">KPI</div>
	<div class="span8"><?php echo $head->KPI ?></div>
</div>
<div class="row">
	<div class="span2">This Month Target</div>
	<div class="span8"><?php echo thousand_separator($target) ?></div>
</div>
<div class="row">
	<div class="span2">Achievement</div>
	<div class="span2"><input type="text" name="txt_achievement" class="input-small" value="<?php 
	if (isset($old->Achievement))
	{
		echo $old->Achievement;

	}
	else if (isset($sum))
	{
		echo $sum;
	} 
	?>"></div>
	<div class="span1"><label class="checkbox"><input type="checkbox" name="chk_skip" class="input-small" value="1"
	<?php
		if (isset($old->isSkip) and $old->isSkip == 1)
		{
			echo ' checked="checked"';
		}
	?>
		>Skip</label></div>
</div>
<div class="row">
	<div class="span2">Evidence</div>
	<div class="span8">
		<input type="file" name="fl_evidence">
	<?php
	if ($file)
	{
		echo '<a title="Download Eviedence" href="http://'.$_SERVER['HTTP_HOST'].$file.'"><i class="icon-file"></i></a>';
	}
	?>
	</div>
</div>
<div class="row">
	<div class="span2">Notes</div>
	<div class="span8">
		<textarea name="txt_note" id="txt_note" ><?php if (isset($old->note)){ echo trim($old->note);} ?></textarea>
	</div>
</div>
<div class="row">
	<div class="span8 offset2"><input type="submit" class="btn btn-primary" value="Submit"></div>
</div>

</form>
