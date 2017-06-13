<?php echo form_open('','id="kpiFrom"');?>
<h3><?php echo $title ?></h3>
<!--<input type="hidden" name="TxtRKKID" id="TxtRKKID" value="<?php echo isset($old->CaraHitungID)?$old->CaraHitungID:''?>">-->
<div class="row">
	<div class="span3">Generic KPI</div>
	<div class="span9"><select></select></div>
</div>
<div class="row">
	<div class="span3">KPI 1</div>
	<div class="span9"><input type="text" class="input-small" name="TxtEndDate" id="TxtEndDate" value=""> </div>
</div>

<div class="row">
	<div class="span3">Description</div>
	<div class="span9"><input type="text" class="input-small" name="TxtCaraHitung" id="TxtCaraHitung" value="<?php echo isset($old->CaraHitung)?$old->CaraHitung:''?>"> </div>
</div>

<div class="row">
	<div class="span3">Satuan</div>
	<div class="span9">
		<select name="SlcSatuanID" id="SlcSatuanID" class="input-medium">
		<option value=""></option><?php 
		foreach ($satuanType as $row) 
		{
			if(isset($old->SatuanID) and $row->SatuanID==$old->SatuanID){
				echo '<option selected="selected" value="'.$row->SatuanID.'">'.$row->Satuan.'</option>';

			}else{
				echo '<option value="'.$row->SatuanID.'">'.$row->Satuan.'</option>';
			}
		}
	?></select>
	</div>
</div>

<div class="row">
	<div class="span3">Cara Hitung</div>
	<div class="span9">
		<select name="SlcCaraHitungID" id="SlcCaraHitungID" class="input-medium">
		<option value=""></option><?php 
		foreach ($countType as $row) 
		{
			if(isset($old->CaraHitungID) and $row->CaraHitungID==$old->CaraHitungID){
				echo '<option selected="selected" value="'.$row->CaraHitungID.'">'.$row->CaraHitung.'</option>';

			}else{
				echo '<option value="'.$row->CaraHitungID.'">'.$row->CaraHitung.'</option>';
			}
		}
	?></select>
	</div>
</div>

<div class="row">
	<div class="span3">YTD Type</div>
	<div class="span9">
		<select name="SlcYTD" id="SlcYTD" class="input-medium">
			<option value=""></option>
		<?php 
			foreach ($ytdType as $row) 
			{
				if(isset($old->YTDID) and $row->CaraHitungID==$old->YTDID){
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
	<div class="span3">Bobot</div>
	<div class="span9"><input type="text" class="input-small" name="TxtCaraHitung" id="TxtCaraHitung" value="<?php echo isset($old->CaraHitung)?$old->CaraHitung:''?>"> </div>
</div>

<div class="row">
	<div class="span3">Baseline</div>
	<div class="span9"><input type="text" class="input-small" name="TxtCaraHitung" id="TxtCaraHitung" value="<?php echo isset($old->CaraHitung)?$old->CaraHitung:''?>"> </div>
</div>

<div class="row">
	<div class="span3">Target Akhir Tahun</div>
	<div class="span9"><select></select></div>
</div>

<div class="row">
	<div class="span3">KPI Child References</div>
	<div class="span9">
		<select class="input-medium" name="SlcKPIChild" id="SlcKPIChild">
		<option value=""></option>
		<?php
			$option = array('Accumulation'=>'Accumulation','Average'=>'Average','Proportional'=>'Proportional');
			foreach ($option as $key => $value) {
				if (isset($old->Operator)and $key==$old->Operator ){
					echo '<option selected="selected" value="'.$key.'">'.$value.'</option>';
				}else{
					echo '<option value="'.$key.'">'.$value.'</option>';
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