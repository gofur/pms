<h4><?php echo $post_name ?></h4>
<div class="table-responsive">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th></th>
        <th>NIK</th>
        <th>Name</th>
        <th>Position</th>
        <?php
          for ($month=1; $month <= 12 ; $month++) {
            echo '<th>'.date('M', mktime(0,0,0,$month,1,2000)).'</th>';
          }
        ?>
        <th>Behaviour</th>
      </tr>
    </thead>
    <tbody>
    <?php
      foreach ($sub_ls as $sub) {
        echo '<tr class="data-master" data-rkk='.$sub->RKKID.'>';
        echo '<td><i class="icon-folder-close icon "></i></td>';
        echo '<td>'.$sub->NIK.'</td>';
        echo '<td>'.$sub->Fullname.'</td>';
        echo '<td>'.$sub->post_name.'</td>';
        foreach ($achv_arr[$sub->RKKID] as $key => $value) {
          echo '<td style="text-align:center;text-align:middle">'.$value.'</td>';
        }
        echo '<td>'.$bhv_arr[$sub->RKKID].'</td>';
        echo '</tr>';
        echo '<tr class="data-slave" data-rkk='.$sub->RKKID.'>';
        echo '<td colspan="17" style="padding-left:20px;"><i class="icon-spin icon-4x icon-spinner icon " ></i></td>';
        echo '</tr>';
      }
    ?>
    </tbody>
  </table>

</div>
