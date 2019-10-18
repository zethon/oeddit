

<style>
.huge {
    font-size: 40px;
}
</style>
<!-- Stats -->
<div class="row">
	<div class="col-md-6">
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
	        <a href=<?php echo $pg."trends#atrend" ?> class="">
	            <div class="panel-footer">
	                <span class="pull-left">View Details</span>
	                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                <div class="clearfix"></div>
	            </div>
	        </a>
	    </div>
    </div>
	<div class="col-md-6">
	    <div class="panel panel-success">
	        <div class="panel-heading">
	            <div class="row">
	                <div class="col-xs-3">
	                    <i class="fa fa-user fa-5x"></i>
	                </div>
	                <div class="col-xs-9 text-right">
	                    <div class="huge"><?php echo $subs; ?></div>
	                    <div>New Subscriptions today</div>
	                </div>
	            </div>
	        </div>
	        <a href=<?php echo $pg."trends#strend" ?>>
	            <div class="panel-footer">
	                <span class="pull-left">View Details</span>
	                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                <div class="clearfix"></div>
	            </div>
	        </a>
	    </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo $comms; ?></div>
                        <div>New Comments today</div>
                    </div>
                </div>
            </div>
            <a href=<?php echo $pg."trends#ctrend" ?>>
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo $posts; ?></div>
                        <div>New Posts today</div>
                    </div>
                </div>
            </div>
            <a href=<?php echo $pg."trends#ptrend" ?>>
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>

<div id="atrend-modal" class="modal fade">
    <div class="modal-dialog" role="">
        <div class="modal-content">
            <div class="modal-header">
                <a class="close" data-dismiss="modal">×</a>
                <h3 id="">Activity trend</h3>
            </div>
            <div class="modal-body">
                <div id="atrend" style="width:800px; height:600px;">
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn" data-dismiss="modal">Back</a>
            </div>
        </div>
    </div>
</div>