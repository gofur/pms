
<script type="text/javascript">
  $(document).ready(function () {

    // prepare chart data
    var  sampleData = [
      <?php 
        foreach ($bc_ls as $row) {
          echo "{ category:'".$row->short_text."'";
          echo ", $mode:".$row->$mode;
          echo "},";
        }
      ?>
	  ];

    // prepare jqxChart settings
    var settings = {
      title: "<?php echo $title ?>",
      description: "",
      padding: { left: 5, top: 5, right: 5, bottom: 5 },
      titlePadding: { left: 0, top: 0, right: 0, bottom: 10 },
      source: sampleData,
      categoryAxis:
          {
              dataField: 'category',
              displayText: 'Category',
              showGridLines: false
          },
      colorScheme: '<?php echo $color ?>',
      seriesGroups:
          [
              {
                  type: 'column',
                  columnsGapPercent: 30,
                  seriesGapPercent: 0,
                  valueAxis:
                  {
                      minValue: 0
                  },
                  series: [
                          { dataField: '<?php echo $mode ?>'},
                      ]
              }
          ]
    };
    
    // select the chartContainer DIV element and render the chart.
    $('#box-bellcurve-<?php echo $mode ?>').jqxChart(settings);
  });
</script>