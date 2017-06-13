
<form name="pilihanarea">
	<div class="row">
		<div class="span10">
			<a href="javascript:history.go(-1)">[Go Back]</a>
		</div>
		<div class="span11">

			<table class="table table-bordered table-striped table-hover popup" >
				<thead><tr><th>Organization ID</th><th>Organization Name</th><th></th></tr></thead>
				<tbody><?php

				foreach ($organization_list as $row) {
					echo '<tr>';
					echo '<td width=200>'.anchor('admin/aspect_setting/pop_up_org/'.$row->OrganizationID,$row->OrganizationID).'</td>';
					echo '<td width=200>'.$row->OrganizationName.'</td>';
					echo '<input type="hidden" id="pilih_name" name="pilih_name" value="'.$name_org.'">';
					echo '<input type="hidden" id="pilih_org_name" name="pilih_org_name" value="'.$row->OrganizationName.'">';
					echo '<input type="hidden" id="start_date" name="start_date" value="'.format_timedate($row->BeginDate).'">';
					echo '<input type="hidden" id="end_date" name="end_date" value="'.format_timedate($row->EndDate).'">';
					echo '<td width=30><input type="radio" id="pilih" name="pilih" onClick="sendValue(this.form.pilih);" value="'.$row->OrganizationID.'"></td></tr>';
				/*echo '<td>'.anchor('admin/aspect_setting/edit/'.$row->aspect_setting_id,'<i class="icon-pencil"></i>','title="Edit"  class="fancybox" data-fancybox-type="iframe"').'</td>';
				echo '<td>'.anchor('admin/aspect_setting/delimit/'.$row->aspect_setting_id,'<i class="icon-ban-circle"></i>', 'title="Delimit"  class="fancybox" data-fancybox-type="iframe"').'</td>';*/
				echo '</tr>';
			}
			?></tbody>
		</table>

	</div>
</div>
</form>


<script type="text/javascript">


function sendValue(s){


	var selvalue = document.pilihanarea.pilih;
	var selvalue_name = document.pilihanarea.pilih_name;
	var selvalue_org_name = document.pilihanarea.pilih_org_name;
	var selvalue_start_date = document.pilihanarea.start_date;
	var selvalue_end_date = document.pilihanarea.end_date;

	for (i=0; i<selvalue.length; i++)
	{
		if(selvalue[i].checked==true)
		{
			//var ex1 = document.getElementById('pilih');
			//alert('Area yang dipilih adalah ' + selvalue_name[i].value + '.')
			window.opener.document.getElementById('txt_organization_id').value=selvalue[i].value; 
			window.opener.document.getElementById('txt_start_date_org').value=selvalue_start_date[i].value; 
			window.opener.document.getElementById('txt_end_date_org').value=selvalue_end_date[i].value; 
			window.opener.document.getElementById('txt_organization_name').value=selvalue_name[i].value+' - '+selvalue_org_name[i].value; 
			window.opener.document.getElementById('txt_org_name').value=selvalue_name[i].value+' - '+selvalue_org_name[i].value; 
			this.window.close(); 
			//window.close(); 
		}
	}
}



/*function sendValue(s){
var selvalue = document.pilihanarea.pilih;

alert('aaa');
for (i=0; i<selvalue.length; i++)

	if (selvalue[i].checked == true)
	{
		alert(‘ Area yang dipilih adalah ‘ + selvalue[i].value + ‘.’)
		window.opener.document.isikantor.kdarea.value = selvalue[i].value; <!– mengirim nilai ke window utama, dg nilai dari obj.radio –>
		window.close(); <!– tutup window pop-up –>
	}
}*/
</script>		