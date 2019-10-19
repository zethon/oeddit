
    <link rel="stylesheet" href="../includes/jqwidgets-ver3.9.1/jqwidgets/styles/jqx.base.css" type="text/css" />
    <script type="text/javascript" src="../includes/jqwidgets-ver3.9.1/scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="../includes/jqwidgets-ver3.9.1/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="../includes/jqwidgets-ver3.9.1/jqwidgets/jqxchart.core.js"></script>
    <script type="text/javascript" src="../includes/jqwidgets-ver3.9.1/jqwidgets/jqxdraw.js"></script>
    <script type="text/javascript" src="../includes/jqwidgets-ver3.9.1/jqwidgets/jqxdata.js"></script>
    <script type="text/javascript">
        $(document).ready(function () 
        {        
            // prepare chart data
            var sampleData = <?php echo json_encode($regs)?>;
            // prepare jqxChart settings
            var settings = {
                title: "Top 5 most populated countries",
                description: "Statistics for 2011",
                showLegend: true,
                enableAnimations: true,
                padding: { left: 20, top: 5, right: 20, bottom: 5 },
                titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
                source: sampleData,
                xAxis:
                {
                    dataField: 'day',
                    gridLines: { visible: true },
                    flip: false
                },
                valueAxis:
                {
                    flip: true,
                    labels: {
                        visible: true
                    }
                },
                colorScheme: 'scheme01',
                seriesGroups:
                    [
                        {
                            type: 'column',
                            orientation: 'horizontal',
                            columnsGapPercent: 30,
                            series: [
                                    { 
                                        dataField: 'regs' 
                                    }
                                ]
                        }
                    ]
            };            
            // select the chartContainer DIV element and render the chart.
            $('#chartContainer').jqxChart(settings);
        });
    </script>
  <div id='chartContainer' style="width:1000px; height: 400px;"/>