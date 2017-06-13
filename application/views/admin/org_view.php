<h3>Organization & Position</h3>
<div class="row">
	<div class="span12">
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
		}
		echo anchor('admin/org/add_org/0','<i class="icon-plus"></i>',' class="fancybox btn pull-right" data-fancybox-type="iframe" title="Add"');?>
	</div>
</div>
<div class="row">
	<div class="span12">
		<table id="table-tree"class="table table-bordered table-hover">
			<thead><tr><th >Description</th><th width="10">Type</th><th width="100">Begin</th><th width="100">End</th><th width="80">Post. Level</th><th width="180">Action</th></tr></thead>
			<tbody><?php 
				foreach ($list_SAP as $row) {
					if($row['parent']==0){
						echo '<tr id="node-'.$row['node_id'].'">';
						
					}else{
						echo '<tr id="node-'.$row['node_id'].'" class="child-of-node-O'.$row['parent'].'">';

					}
					
					echo '<td>'.$row['description'].' '.$row['headOf'].'</td>';
					echo '<td>'.$row['type'].'</td>';
					echo '<td>'.substr($row['begin'],0,10).'</td>';
					echo '<td>'.substr($row['end'],0,10).'</td>';
					echo '<td>'.$row['post'].'</td>';
					echo '<td></td>';
					echo '</tr>';	
				}
				foreach ($list as $row) {
					if($row['parent']==0){
						echo '<tr id="node-'.$row['node_id'].'">';
						
					}else{
						echo '<tr id="node-'.$row['node_id'].'" class="child-of-node-O'.$row['parent'].'">';

					}
					
					echo '<td>'.$row['description'].' '.$row['headOf'].'</td>';
					echo '<td>'.$row['type'].'</td>';
					echo '<td>'.substr($row['begin'],0,10).'</td>';
					echo '<td>'.substr($row['end'],0,10).'</td>';
					echo '<td>'.$row['post'].'</td>';
					echo '<td>';
					echo anchor($row['edit_link'],'<i class="icon-pencil"></i>','title="Edit" class=" fancybox" data-fancybox-type="iframe"').' ';
					if(isset($row['addChild_link'])){
						echo anchor($row['addChild_link'],'<i class="icon-plus"></i>','title="Add Child" class=" fancybox" data-fancybox-type="iframe"').' ';
					}
					if(isset($row['addPost_link'])){
						echo anchor($row['addPost_link'],'<i class="icon-user"></i>','title="Add Position" class=" fancybox" data-fancybox-type="iframe"').' ';
					}
					echo anchor($row['remove_link'],'<i class="icon-trash"></i>','title="Remove" class=""').' ';

					echo '</td>';
					echo '</tr>';	
				}
			?></tbody>
		</table>
	</div>
</div>