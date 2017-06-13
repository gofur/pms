<div class="row">
	<div class="span12">
		<?php echo form_open($process,'id="exceptionFrom"');?>
		<h3><?php echo $title ?></h3>


<div class="row">
	<div class="span2">Start Date</div>
	<div class="span10"><?php echo form_input('TxtStartDate', date('Y-01-01'), 'class="input-small" id="TxtStartDate"');?></div>

</div>
<div class="row">
	<div class="span2">End Date</div>
	<div class="span10"><?php echo form_input('TxtEndDate', '9999-12-31', 'class="input-small" id="TxtEndDate"');?></div>

</div>
<div class="row">
	<div class="span6">
		<div class="row">
			<div class="span1"><h4>Superior</h4></div>
			<div class="span5"></div>
		</div>

		<div class="row">
			<div class="span2">Unit</div>
			<div class="span4"><select name="slc_org_1" class="slc_org input-xlarge" id="slc_org_1">
			</select></div>
		</div>
		<div class="row">
			
			<div class="span4 offset2"><select name="slc_org_2" class="slc_org input-xlarge" id="slc_org_2">
			</select></div>
		</div>
		<div class="row">
			
			<div class="span4 offset2"><select name="slc_org_3" class="slc_org input-xlarge" id="slc_org_3">
			</select></div>
		</div>
		<div class="row">
			
			<div class="span4 offset2"><select name="slc_org_4" class="slc_org input-xlarge" id="slc_org_4">
			</select></div>
		</div>
		<div class="row">
			
			<div class="span4 offset2"><select name="slc_org_5" class="slc_org input-xlarge" id="slc_org_5">
			</select></div>
		</div>
		<div class="row">
			
			<div class="span4 offset2"><select name="slc_org_6" class="slc_org input-xlarge" id="slc_org_6">
			</select></div>
		</div>
		<div class="row">

                        <div class="span4 offset2"><select name="slc_org_7" class="slc_org input-xlarge" id="slc_org_7">
                        </select></div>
                </div>
		<div class="row">
			<div class="span2">Position</div>
			<div class="span4"><select name="slc_position" class="input-xlarge" id="slc_position">
			</select></div>
		</div>
	</div>
	<div class="span6">
		<div class="row">
			<div class="span1"><h4>Subordinate</h4></div>
			<div class="span5"></div>
		</div>

		<div class="row">
			<div class="span2">Unit</div>
			<div class="span4"><select name="slc_org_sub_1" class="slc_org_sub input-xlarge" id="slc_org_sub_1">
			</select></div>
		</div>
		<div class="row">
			
			<div class="span4 offset2"><select name="slc_org_sub_2" class="slc_org_sub input-xlarge" id="slc_org_sub_2">
			</select></div>
		</div>
		<div class="row">
			
			<div class="span4 offset2"><select name="slc_org_sub_3" class="slc_org_sub input-xlarge" id="slc_org_sub_3">
			</select></div>
		</div>
		<div class="row">
			
			<div class="span4 offset2"><select name="slc_org_sub_4" class="slc_org_sub input-xlarge" id="slc_org_sub_4">
			</select></div>
		</div>
		<div class="row">
			
			<div class="span4 offset2"><select name="slc_org_sub_5" class="slc_org_sub input-xlarge" id="slc_org_sub_5">
			</select></div>
		</div>
		<div class="row">
			
			<div class="span4 offset2"><select name="slc_org_sub_6" class="slc_org_sub input-xlarge" id="slc_org_sub_6">
			</select></div>
		</div>
		
		<div class="row">

                        <div class="span4 offset2"><select name="slc_org_sub_7" class="slc_org_sub input-xlarge" id="slc_org_sub_7">
                        </select></div>
                </div>

		<div class="row">
			<div class="span2">Position</div>
			<div class="span4" id="post_sub" style="max-height:200px;overflow:scroll;"></div>
		</div>
	</div>
</div>
<div class="row ">
	<div class="span10 offset2">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('admin/exceptionReportingStructure','Cancel', 'class="btn"'); ?>
	</div>
</div>
<?php echo form_close() ?>
	</div>
</div>
