<h3>Subordinate Achivement</h3>
<div class="row">
	<div class="span2">Period</div>
	<div class="span1">
		<?php echo $period->Tahun ?>
	</div>
	<div class="span1"><?php echo anchor('period', 'Change Period', 'class="fancybox" data-fancybox-type="iframe"'); ?></div>
</div>
	<div class="row well">
		<div class="span5">
			<strong>Status</strong><br>
			<span class="label">Not Yet Submitted</span>
			<span class="label label-warning">Pending Approval</span>
			<span class="label label-important">Rejected</span>
			<span class="label label-success">Approved</span>
		</div>
		<div class="span5">
			<strong>TPC</strong><br>
			<table>
				<tr>
				<td>Current TPC</td>
				</tr>
				<tr style="border-top:solid 1px black">
				<td>YTD TPC</td>
				</tr>
			</table>
		</div>
	</div>
