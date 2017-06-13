<h3><?php echo $Title ?></h3>
<?php if(isset($link['chief']) && isset($person)){?>
<div class="row">
	<div class="span12">
		<ul class="breadcrumb">
			<li><?php echo anchor($link['chief'],'Chief') ?> <span class="divider">/</span></li>
			<li class="active"><?php echo $person ?></li>
		</ul>
	</div>
</div>
<?php }
	
?>

<div class="row">
	<div class="span2">Period</div>
	<div class="span1"><?=$Periode->Tahun; ?></div>
	<div class="span1"><?php echo anchor('period', 'Change Period', 'class="fancybox" data-fancybox-type="iframe"'); ?></div>
</div>
<div class="row">
	<div class="span2">Start Date</div>
	<div class="span4"><?=format_timedate($Periode->BeginDate); ?></div>
</div>
<div class="row">
	<div class="span2">End Date</div>
	<div class="span4"><?=format_timedate($Periode->EndDate); ?></div>
</div>

<div class="row">
	<div class="span6">
		<div class="row">
	<div class="span2">NIK</div>
	<div class="span4"><?=$userDetail->NIK ;?></div>
</div>
<div class="row">
	<div class="span2">Name</div>
	<div class="span4"><?=$userDetail->Fullname; ?></div>
</div>
<div class="row">
	<div class="span2">Position</div>
	<div class="span4">

		<?php 

		$attributes = array('class' => 'form-inline', 'id' => 'genForm'); echo form_open($action,$attributes);
		if (isset($PositionList_SAP) or isset($PositionList_nonSAP))
		{

		?>
		<select class="input-medium" name="SlcPost" id="SlcPost">
		<?php
		foreach ($PositionList_SAP as $row) {
			if($Holder==('1.'.$row->HolderID)){
				echo '<option selected="selected" value="1.'.$row->HolderID.'">'.$row->PositionName.'</option>';

			}else{
				echo '<option value="1.'.$row->HolderID.'">'.$row->PositionName.'</option>';
			}
		}

		foreach ($PositionList_nonSAP as $row) {
			if($Holder==('0.'.$row->HolderID)){
				echo '<option selected="selected" value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';

			}else{
				echo '<option value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';
			}
		}

		foreach ($PositionAssignmentList_nonSAP as $row) {
			if($Holder==('0.'.$row->HolderID)){
				echo '<option selected="selected" value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';

			}else{
				echo '<option value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';
			}
		}
		
		foreach ($PositionAssignmentList_SAP as $row) {
			if($Holder==('0.'.$row->HolderID)){
				echo '<option selected="selected" value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';

			}else{
				echo '<option value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';
			}
		}

	?></select>

	<?php
		}
		elseif(isset($PositionName))
		{
			echo $PositionName;
		}
	?>
	 </div>
</div>

<div class="row">
	<div class="span2">Month</div>
	<div class="span4">
		<select name="slcMonth" id="slcMonth" class="input-medium"><?php
			for($i=1;$i<=12;$i++)
			{
				if($i==$month)
				{
					//echo '<option selected="selected" value="'.$i.'">'.date('M',mktime(0,0,0,$i,1,2000)).'</option>';
					echo '<option selected="selected" value="'.$i.'">'.date('M',mktime(0,0,0,$i,1,2000)).'</option>';

				}
				else
				{
					echo '<option value="'.$i.'">'.date('M',mktime(0,0,0,$i,1,2000)).'</option>';

				}
			}
		?></select>

	</div>
</div>
<div class="row">
	<div class="span1 offset2">
	<input type="submit" value="View" class="btn">
	</div>
</div>
</form>
	</div>
	<div class="span6">
		<div class="row">
			<div class="span2">Subordinate</div>
			<div class="span4" style="height:250px;overflow:scroll;overflow-x:hidden;">
			<?php 
				if(count($subordinate)!=0)
				{
					echo $subordinate; 
				} 
			?>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="span12">
<?php 
if ($notif_text!='')
{
	echo '<div class="alert '.$notif_type.'">';
	echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
	echo $notif_text;
	echo '</div>';
	
}
?>
	</div>
</div>


