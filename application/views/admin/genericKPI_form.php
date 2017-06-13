<?php echo form_open($process,'id="genericKpiForm"');?>
<h3><?php echo $title ?></h3>
<input type="hidden" name="TxtKPIID" id="TxtKPIID" value="<?php echo isset($old->KPIGenericID)?$old->KPIGenericID:''?>">
<div class="row">
	<div class="span2">Start Date :</div>
	<div class="span10"><input <?php echo isset($old->BeginDate)?'readonly="readonly"':''?> type="text" class="input-small" name="TxtStartDate" id="TxtStartDate" value="<?php echo isset($old->BeginDate)?substr($old->BeginDate,0,10):date('Y/m/d')?>"> </div>
</div>
<div class="row">
	<div class="span2">End Date :</div>
	<div class="span10"><input type="text" class="input-small" name="TxtEndDate" id="TxtEndDate" value="<?php echo isset($old->EndDate)?substr($old->EndDate,0,10) :'9999/12/31'?>"> </div>
</div>

<div class="row">
	<div class="span2">Perspective </div>
	<div class="span10">
		<select name="SlcPerspectiveID" id="SlcPerspectiveID" class="input-large">
		<?php 
		foreach ($perspectiveType as $row) 
		{
			if(isset($old->PerspectiveID) and $row->PerspectiveID==$old->PerspectiveID){
				echo '<option selected="selected" value="'.$row->PerspectiveID.'">'.$row->Perspective.'</option>';

			}else{
				echo '<option value="'.$row->PerspectiveID.'">'.$row->Perspective.'</option>';
			}
		}
		?>
		</select>
	</div>
</div>
<div class="row">
	<div class="span2">KPI </div>
	<div class="span10"><input type="text" class="input-large" name="TxtKPI" id="TxtKPI" value="<?php echo isset($old->KPI)?$old->KPI:''?>"> </div>
</div>
<div class="row">
	<div class="span2">Description </div>
	<div class="span10"><textarea name="TxtDescription" id="TxtDescription"><?php echo isset($old->Description)?$old->Description:''?></textarea></div>
</div>
<div class="row">
	<div class="span2">Satuan </div>
	<div class="span10">
		<select name="SlcSatuanID" id="SlcSatuanID" class="input-large">
		<?php 
		foreach ($satuanType as $row) 
		{
			if(isset($old->SatuanID) and $row->SatuanID==$old->SatuanID){
				echo '<option selected="selected" value="'.$row->SatuanID.'">'.$row->Satuan.'</option>';

			}else{
				echo '<option value="'.$row->SatuanID.'">'.$row->Satuan.'</option>';
			}
		}
		?>
		</select>
	</div>
</div>
<div class="row">
	<div class="span2">Cara Hitung </div>
	<div class="span10">
		<select name="SlcCaraHitungID" id="SlcCaraHitungID" class="input-medium">
			<option value=""></option>
		<?php 
		//looping ambil dari table carahitung
		foreach ($countType as $row) 
		{
			if(isset($CaraHitungID) and $row->CaraHitungID==$CaraHitungID){
				echo '<option selected="selected" value="'.$row->CaraHitungID.'">'.$row->CaraHitung.'</option>';

			}else{
				echo '<option value="'.$row->CaraHitungID.'">'.$row->CaraHitung.'</option>';
			}
		}
		?>
		</select>
		<div id="hiddenDiv1"></div>
	</div>
</div>
<div class="row">
	<div class="span2">YTD Type </div>
	<div class="span10">
		<select name="SlcYTDID" id="SlcYTDID" class="input-large">
		<?php 
		foreach ($ytdType as $row) 
		{
			if(isset($old->YTDID) and $row->YTDID==$old->YTDID){
				echo '<option selected="selected" value="'.$row->YTDID.'">'.$row->YTD.'</option>';

			}else{
				echo '<option value="'.$row->YTDID.'">'.$row->YTD.'</option>';
			}
		}
		?>
		</select>
	</div>
</div>

<div class="row">
	<div class="span10 offset2">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
<?php form_close();?>