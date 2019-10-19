
<link rel="stylesheet" href="../includes/jqwidgets-ver3.9.1/jqwidgets/styles/jqx.base.css" type="text/css" />
<script type="text/javascript" src="../includes/jqwidgets-ver3.9.1/jqwidgets/jqxcore.js"></script>
<script type="text/javascript" src="../includes/jqwidgets-ver3.9.1/jqwidgets/jqxchart.core.js"></script>
<script type="text/javascript" src="../includes/jqwidgets-ver3.9.1/jqwidgets/jqxdraw.js"></script>
<script type="text/javascript" src="../includes/jqwidgets-ver3.9.1/jqwidgets/jqxdata.js"></script>
<script type="text/javascript">
    $(document).ready(function () 
    {        
        // prepare chart data
        var data1 = <?php echo json_encode($atrend); ?>;
        // prepare jqxChart settings
        var settings1 = {
            title: "#Users active in the past 24 hours",
            showLegend: true,
            enableAnimations: true,
            padding: { left: 20, top: 5, right: 20, bottom: 5 },
            titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
            source: data1,
            xAxis:
            {
                dataField: 'hour',
                gridLines: { visible: true },
                labels: {
                    visible: true
                },
                flip: false,
                minValue: 0,
                maxValue: 23,
                unitInterval: 1
            },
            valueAxis:
            {
                flip: false,
                labels: {
                    visible: false
                },
                minValue: 0
            },
            colorScheme: 'scheme01',
            seriesGroups:
                [
                    {
                        type: 'column',
                        series: [
                                { 
                                    dataField: 'active'
                                }
                            ]
                    }
                ]
        };
        // prepare chart data
        var data2 = <?php echo json_encode($strend); ?>;
        // prepare jqxChart settings
        var settings2 = {
            source: data2,
            title: "Daily subscriptions for the past week",
            showLegend: true,
            enableAnimations: true,
            padding: { left: 20, top: 5, right: 20, bottom: 5 },
            titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
            xAxis:
            {
                dataField: 'day',
                gridLines: { visible: true },
                labels: {
                    visible: true
                },
                flip: false,
                minValue: 0,
                maxValue: 6,
                unitInterval: 1
            },
            valueAxis:
            {
                flip: false,
                labels: {
                    visible: true
                },
                minValue: 0
            },
            colorScheme: 'scheme01',
            seriesGroups:
                [
                    {
                        type: 'column',
                        series: [
                                { 
                                    dataField: 'subs'
                                }
                            ]
                    }
                ]
        };
        // prepare chart data
        var data3 = <?php echo json_encode($ptrend); ?>;
        // prepare jqxChart settings
        var settings3 = {
            source: data3,
            title: "Daily New Post Submissions for the past week",
            showLegend: true,
            enableAnimations: true,
            padding: { left: 20, top: 5, right: 20, bottom: 5 },
            titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
            xAxis:
            {
                dataField: 'day',
                gridLines: { visible: true },
                labels: {
                    visible: true
                },
                flip: false,
                minValue: 0,
                maxValue: 6,
                unitInterval: 1
            },
            valueAxis:
            {
                flip: false,
                labels: {
                    visible: true
                },
                minValue: 0
            },
            colorScheme: 'scheme01',
            seriesGroups:
                [
                    {
                        type: 'column',
                        series: [
                                { 
                                    dataField: '#posts'
                                }
                            ]
                    }
                ]
        };
        // prepare chart data
        var data4 = <?php echo json_encode($ctrend); ?>;
        // prepare jqxChart settings
        var settings4 = {
            source: data4,
            title: "Daily New Comment submissions for the past week",
            showLegend: true,
            enableAnimations: true,
            padding: { left: 20, top: 5, right: 20, bottom: 5 },
            titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
            xAxis:
            {
                dataField: 'day',
                gridLines: { visible: true },
                labels: {
                    visible: true
                },
                flip: false,
                minValue: 0,
                maxValue: 6,
                unitInterval: 1
            },
            valueAxis:
            {
                flip: false,
                labels: {
                    visible: true
                },
                minValue: 0
            },
            colorScheme: 'scheme01',
            seriesGroups:
                [
                    {
                        type: 'column',
                        series: [
                                { 
                                    dataField: '#comments'
                                }
                            ]
                    }
                ]
        };
        // select the chartContainer DIV element and render the chart.
        $('#atrend').jqxChart(settings1);
        $('#strend').jqxChart(settings2);
        $('#ctrend').jqxChart(settings3);
        $('#ptrend').jqxChart(settings4);
    });
</script>
<div>
<a href=<?php echo $pg."main" ?> role="button" class="btn btn-primary" style="margin: 0 auto; display:block;">Back</a>
</div>
<hr>
<div id="atrend" style="width:1000px; height:300px;"></div><hr>
<div id="strend" style="width:1000px; height:300px;"></div><hr>
<div id="ctrend" style="width:1000px; height:300px;"></div><hr>
<div id="ptrend" style="width:1000px; height:300px;"></div>