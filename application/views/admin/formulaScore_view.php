<h3>Formula - Score</h3>
  <ul class="breadcrumb">
		<li><?php echo anchor('admin/formula','Formula')?><span class="divider">/</span></li>
		<li class="active"><?php echo $head->PCFormula?></li>
  </ul>
<div class="row">
	<div class="span6">
		<div class="row">
			<div class="span2"><b>Formula Name</b></div>
			<div class="span4"><?php echo $head->PCFormula ?></div>
		</div>
		<div class="row">
			<div class="span2"><b>Counting Type</b></div>
			<div class="span4"><?php echo $head->CaraHitung ?></div>
		</div>
		<div class="row">
			<div class="span2"><b>Begin Date</b></div>
			<div class="span4"><?php echo date('d M Y',strtotime($head->BeginDate)) ?></div>
		</div>
		<div class="row">
			<div class="span2"><b>End Date</b></div>
			<div class="span4"><?php echo date('d M Y',strtotime($head->EndDate)) ?></div>
		</div>
		  
	</div>
	<div class="span6">
		<div class="row">
			<div class="span2"><b>Skip Constancy</b></div>
			<div class="span4"><?php echo $head->SkipConstancy ?></div>
		</div>
		<div class="row">
			<div class="span2"><b>Operator</b></div>
			<div class="span4"><?php 
				switch ($head->Operator) {
					case 'A':
						echo 'Add';
						break;
					case 'S':
						echo 'Substract';
						break;
					case 'M':
						echo 'Multiply';
						break;
					case 'D':
						echo 'Divien';
						break;		
					default:
						echo '-';
						break;
				}
			?></div>
		</div>
		<div class="row">
			<div class="span2"><b>Preception</b></div>
			<div class="span4"><?php 
				if($head->Perception=='max'){
					echo 'Maximum';
				}elseif ($head->Perception=='min') {
					echo 'Minimum';
				}else{
					echo '-';
				}
			?></div>
		</div>
		<div class="row">
			<div class="span2"><b>Note</b></div>
			<div class="span4"><?php echo $head->Notes ?></div>
		</div>
	</div>
</div>
<hr>
<div class="row">
	<div class="span12">
		<?php 
		if ($notif_text!='' AND $notif_type!='')
		{
			echo '<div class="alert alert-block alert-success">';
  		echo '<a class="close" data-dismiss="alert" href="#">x</a>';
  		echo '<h4 class="alert-heading">Success!</h4>';
  		echo $notif_text;
			echo '</div>';
		}
		echo anchor('admin/formula/add_formulaScore/'.$head->PCFormulaID,'<i class="icon-plus"></i>',' class="fancybox btn pull-right" data-fancybox-type="iframe" title="Add"');?>
	</div>
</div>
<div class="row">
	<div class="span12">
		<table class="table datatable table-bordered table-striped table-hover">
			<thead><tr><th>Scale </th><th>Lower Bound</th><th>Upper Bound</th><th>Percentage</th><th width="150">Begin</th><th width="150">End</th><th width="10"></th><th width="10"></th></tr></thead>
			<tbody><?php
			foreach ($list as $row) {
				echo '<tr>';
				echo '<td>'.$row->PCFormulaScore.'</td>';
				echo '<td style="text-align:right">'.$row->PCLow.'</td>';
				echo '<td style="text-align:right">'.$row->PCHigh.'</td>';
				echo '<td style="text-align:right">'.$row->Percentage.'</td>';
				echo '<td>'.date('Y-m-d',strtotime($row->BeginDate)).'</td>';
				echo '<td>'.date('Y-m-d',strtotime($row->EndDate)).'</td>';
				echo '<td>'.anchor('admin/formula/edit_formulaScore/'.$row->PCFormulaScoreID,'<i class="icon-pencil"></i>','title="Edit"  class="fancybox" data-fancybox-type="iframe"').'</td>';
				echo '<td>'.anchor('admin/formula/delete_formulaScore/'.$row->PCFormulaScoreID,'<i class="icon-trash"></i>', array('title="Delete"','onClick' => "return confirm('Are you sure you want to delete?')")).'</td>';

				echo '</tr>';
			}
			?></tbody>
		</table>
	</div>
</div>