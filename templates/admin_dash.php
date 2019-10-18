<link rel="stylesheet" href="../includes/jqwidgets-ver3.9.1/jqwidgets/styles/jqx.base.css" type="text/css" />
<script type="text/javascript" src="../includes/jqwidgets-ver3.9.1/jqwidgets/jqxcore.js"></script>
<script type="text/javascript" src="../includes/jqwidgets-ver3.9.1/jqwidgets/jqxchart.core.js"></script>
<script type="text/javascript" src="../includes/jqwidgets-ver3.9.1/jqwidgets/jqxdraw.js"></script>
<script type="text/javascript" src="../includes/jqwidgets-ver3.9.1/jqwidgets/jqxdata.js"></script>
<script type="text/javascript">
    $(document).ready(function () 
    {
        // prepare chart data
        var data1 = <?php echo json_encode($asocs); ?>;
        // prepare jqxChart settings
        var settings1 = {
            title: "Top 10 most active societies today",
            showLegend: true,
            enableAnimations: true,
            padding: { left: 20, top: 5, right: 20, bottom: 5 },
            titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
            source: data1,
            xAxis:
            {
                dataField: 'society',
                gridLines: { visible: true },
                flip: false
            },
            valueAxis:
            {
                flip: true,
                labels: {
                    visible: false
                },
                minValue: 0
            },
            colorScheme: 'scheme03',
            seriesGroups:
                [
                    {
                        type: 'column',
                        orientation: 'horizontal',
                        series: [
                                { 
                                    dataField: '#comments'
                                }
                            ]
                    }
                ]
        };
        // prepare chart data
        var data2 = <?php echo json_encode($gsocs); ?>;
        // prepare jqxChart settings
        var settings2 = {
            title: "Fastest growing societies today",
            showLegend: true,
            enableAnimations: true,
            padding: { left: 20, top: 5, right: 20, bottom: 5 },
            titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
            source: data2,
            xAxis:
            {
                dataField: 'society',
                gridLines: { visible: true },
                flip: false
            },
            valueAxis:
            {
                flip: true,
                labels: {
                    visible: false
                },
                minValue: 0
            },
            colorScheme: 'scheme04',
            seriesGroups:
                [
                    {
                        type: 'column',
                        orientation: 'horizontal',
                        series: [
                                { 
                                    dataField: '#subs'
                                }
                            ]
                    }
                ]
        };            
        // select the chartContainer DIV element and render the chart.
        $('#chartContainer1').jqxChart(settings1);
        $('#chartContainer2').jqxChart(settings2);
    });
</script>

<style>
.huge {
    font-size: 40px;
}
</style>
<div class="row">
	<div class="col-md-4">
	    <div class="panel panel-primary">
	        <div class="panel-heading">
	            <div class="row">
	                <div class="col-xs-3">
	                    <i class="fa fa-user fa-5x"></i>
	                </div>
	                <div class="col-xs-9 text-right">
	                    <div class="huge"><?php echo $active; ?></div>
	                    <div>Active Users</div>
	                </div>
	            </div>
	        </div>
	        <a href=<?php echo $pg."trends#utrend" ?>>
	            <div class="panel-footer">
	                <span class="pull-left">View Details</span>
	                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                <div class="clearfix"></div>
	            </div>
	        </a>
	    </div>
    </div>
	<div class="col-md-4">
	    <div class="panel panel-success">
	        <div class="panel-heading">
	            <div class="row">
	                <div class="col-xs-3">
	                    <i class="fa fa-user fa-5x"></i>
	                </div>
	                <div class="col-xs-9 text-right">
	                    <div class="huge"><?php echo $regs; ?></div>
	                    <div>New Registrations today</div>
	                </div>
	            </div>
	        </div>
	        <a href=<?php echo $pg."trends#rtrend" ?>>
	            <div class="panel-footer">
	                <span class="pull-left">View Details</span>
	                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                <div class="clearfix"></div>
	            </div>
	        </a>
	    </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo $admins; ?></div>
                        <div>Active Admins</div>
                    </div>
                </div>
            </div>
            <a href=<?php echo $pg."trends#atrend" ?>>
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>

<table style="width:100%;">
	<tr>
		<td>			
	<div id="chartContainer1" style="width:500px; height: 400px; float: left;">	
	</div>
		</td>
		<td>			
	<div id="chartContainer2" style="width:500px; height: 400px; float: right;">	
	</div>
		</td>
	</tr>
</table>
