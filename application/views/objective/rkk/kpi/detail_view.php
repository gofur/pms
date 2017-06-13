<?php 
	$this->load->view('template/top_popup_1_view');
?>
<h3>Key Performance Indicator</h3>
<div class="row">
	<div class="span2">Strategic Objective</div>
	<div class="span8"><?php echo $kpi->SasaranStrategis; ?></div>
</div>

<div class="row">
	<div class="span2">KPI Start</div>
	<div class="span8"><?php echo $kpi->KPI_BeginDate; ?></div>
</div>

<div class="row">
	<div class="span2">KPI End</div>
	<div class="span8"><?php  echo $kpi->KPI_EndDate; ?></div>
</div>

<div class="row">
	<div class="span2">KPI</div>
	<div class="span8"><?php echo $kpi->KPI; ?></div>
</div>
<div class="row">
	<div class="span2">Description</div>
	<div class="span8"><?php echo $kpi->Description; ?></div>
</div>
<div class="row">
	<div class="span2">Satuan</div>
	<div class="span8"><?php echo $kpi->Satuan; ?></div>
</div>

<div class="row">
	<div class="span2">Formula</div>
	<div class="span8"><?php  echo $kpi->PCFormula; ?></div>
</div>

<div class="row">
	<div class="span2">Year to Date</div>
	<div class="span8"><?php echo $kpi->YTD; ?></div>
</div>

<div class="row">
	<div class="span2">Weight</div>
	<div class="span8"><?php echo round($kpi->Bobot,2); ?></div>
</div>
<div class="row">
	<div class="span2">Baseline</div>
	<div class="span8"><?php echo $kpi->Baseline; ?></div>
</div>

<div class="row">
	<div class="span10">
		<ul class="nav nav-tabs">
		  <li class="active"><a href="#target" data-toggle="tab">Target</a></li>
		  <?php 
		  if ($c_rel_BA) { 
		  	echo '<li><a href="#from" data-toggle="tab">Cascade From</a></li>';
		  }

		  if ($c_rel_AB) {
		  	echo '<li><a href="#to" data-toggle="tab">Cascade To</a></li>';
		  }
		  ?>
		</ul>
		<div class="tab-content">
		  <div class="tab-pane active" id="target">
		  	<table class="table table-bordered" width="780px">
					<tbody>
						<?php 
						for ($y=0; $y < 3 ; $y++) { 
							echo '<tr>';
							for ($x=1; $x <=4 ; $x++) { 
								echo '<th witdth="195px">'.date('M',mktime(0,0,0,($x+$y*4),1,2000)).'</th>';
							}
							echo '</tr>';
							echo '<tr>';
							for ($x=1; $x <=4 ; $x++) {
								$month_id  = $x+$y*4;
								echo '<td>';
								echo $targets[$month_id];
								echo '</td>';
								unset($month_id);
							}
							echo '</tr>';
						}
						?>
					</tbody>
				</table>
		  </div>
		  <?php if ($c_rel_BA) { ?>
		  <div class="tab-pane" id="from">
		  	<div class="row">
					<div class="span2">KPI</div>
					<div class="span8"><?php echo $kpi_A->KPI; ?></div>
				</div>
				<div class="row">
					<div class="span2">Superior</div>
					<div class="span8"><?php echo $kpi_A->NIK. ' - '. $A_nama .'<br/>'. $A_posisi; ?></div>
				</div>
				<div class="row">
					<div class="span2">Start</div>
					<div class="span8"><?php echo $rel->BeginDate; ?></div>
				</div>
				<div class="row">
					<div class="span2">End</div>
					<div class="span8"><?php echo $rel->EndDate; ?></div>
				</div>
				<div class="row">
					<div class="span2">Ref</div>
					<div class="span8"><?php 

						echo $kpi_A->Reference; 
						if ($kpi_A->ref_id == 3) {
							echo ' ('. $rel->ref_weight .')';
						}
					?></div>
				</div>
		  </div>
		  <?php } ?>
		  <?php if ($c_rel_AB) { ?>
		  <div class="tab-pane" id="to">
		  	<table class="table">
					<thead>
						<tr>
							<th width="200">Subordinate</th>
							<th>KPI</th>
							<th width="150">Begin <br/>End</th>
							<th>Ref</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($casd_ls as $casd) {
							$key = $casd->isSAP .'|'. $casd->PositionID;
							echo '<tr>';
							echo '<td>'.$casd->NIK.' - '.$casd->Fullname.'<br/>'.$post_ls[$key].'</td>';

							echo '<td>'.$casd->KPI.'</td>';
							echo '<td>'.substr($casd->KPI_BeginDate, 0,10).'<br>'.substr($casd->KPI_EndDate, 0,10).'</td>';
							echo '<td>';
							echo $rel_ls[$key]->Reference; 
							if ($rel_ls[$key]->ref_id == 3) {
								echo ' ('. $rel_ls[$key]->ref_weight .')';
							}

							echo '</td>';

							echo '</tr>';
						}
						?>
					</tbody>
				</table>
		  </div>
		  <?php } ?>
		</div>
	</div>
</div>
<?php
	$this->load->view('template/bottom_popup_1_view');
?>