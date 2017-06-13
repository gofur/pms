<div class="row">
	<div class="span12">
		<ul class="breadcrumb">
			<li><?php echo anchor ($link_self,'Me')?><span class="divider">/</span></li>
			<li class="active"><?php echo $user_dtl->NIK .' - '. $user_dtl->Fullname  ?></li>
		</ul>
	</div>
</div>

<div class="row">
	<div class="span6">
		<div class="row">
			<div class="span2">NIK</div>
			<div class="span4"><?php echo $user_dtl->NIK ;?></div>
		</div>
		<div class="row">
			<div class="span2">Name</div>
			<div class="span4"><?php echo $user_dtl->Fullname; ?></div>
		</div>
		<div class="row">
			<div class="span2">Position</div>
			<div class="span4"><?php echo $post->PositionName;	?>
			</div>
		</div>

		<div class="row">
			<div class="span2">Start Date</div>
			<div class="span4"><?php echo form_input('dt_filter_start', $filter_start, 'id="dt_filter_start" class="input-small datepicker"');  ?></div>
		</div>
		<div class="row">
			<div class="span2">End Date</div>
			<div class="span4"><?php echo form_input('dt_filter_end', $filter_end, 'id="dt_filter_end" class="input-small datepicker"');  ?></div>
		</div>
		<div class="row">
			<div class="span2"></div>
			<div class="span4"><button class="btn" id="btn_view"><i class="icon-refresh"></i> View</button></div>
		</div>

	</div>
	<i class="icon-spinner icon-spin icon-large icon-4x" id="spin-subordinate"></i>
	<div class="span6" id="box-subordinate">
	</div>
</div>
