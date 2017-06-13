<?php $this->load->view('template/top_popup_1_view'); ?>
<h3>RKK Transfer</h3>
<div class="row">
  <div class="span10">
    Select one to be Source. <br>All KPI at This RKK will be <b>replaced</b> with KPI from the Source. 
  </div>
</div>
<?php echo form_open($process, '', $hidden); ?>
  <div class="row">
    <div class="span10">
      <table class="table">
        <thead>
          <tr>
            <th></th>
            <th>Position</th>
            <th>Start</th>
            <th>End</th>
          </tr>
        </thead>
        <tbody>
        <?php
          foreach ($source as $key => $row) {
            echo '<tr>';
            echo '<td>'.form_radio('rd_source',$key,FALSE,'class="rd_source" data-rkk="'.$key.'"') .'</td>';
            echo '<td>'.$row['post'].'</td>';
            echo '<td>'.$row['start'].'</td>';
            echo '<td>'.$row['end'].'</td>';
            echo '</tr>';
          }
        ?>
        </tbody>

      </table>
    </div>
  </div>
  <div class="form-actions">
    <button type="submit" class="btn btn-primary">Submit</button>
</div>
<?php echo form_close(); ?>
<?php $this->load->view('template/bottom_popup_1_view'); ?>
