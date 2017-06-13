<h3>Create KPI</h3>
<div class="row">
	<div class="span10">
	  <ul class="breadcrumb">
		  <li>Number<span class="divider"> / </span></li>
		  <li >Detail<span class="divider"> / </span></li>
		  <li class="active">Target</li>
	  </ul>
	</div>
</div>
<div class="row">
	<div class="span10">
		<?php 
		if ($notif_text!='' AND $notif_type!='')
		{
			echo '<div class="alert alert-block '.$notif_type.'">';
  		echo '<a class="close" data-dismiss="alert" href="#">x</a>';
  		if(isset($notif_title) and $notif_title!=''){
  			echo '<h4>'.$notif_title.'</h4>';
  		}
  		echo $notif_text;
			echo '</div>';
		}?>
	</div>
</div>
<?php 
$attributes = array('id' => 'genFrom'); 
echo form_open($process,$attributes)
?>
<input type="hidden" name="hdn_kpi_num" id="hdn_kpi_num" value="<?php echo $kpi_num?>">