<div>
<form class="form-horizontal" action="../php/change_pass.php" method="post">
        <div class="form-group">
            <label class="control_label col-sm-2" >Old password</label>
            <div class="col-sm-6">
                <input id="old" class="form-control" name="old_pass" placeholder="Old Password" type="password" required="" autofocus=""/>
            </div>
        </div>
        <div class="form-group">
            <label class="control_label col-sm-2" >New password</label>
            <div class="col-sm-6">
                <input id="new" class="form-control" name="new_pass" placeholder="New Password" type="password" required="" />
            </div>
        </div>
        <div class="form-group">
            <label class="control_label col-sm-2" >Confirm new password</label>
            <div class="col-sm-6">
                <input id="conf" class="form-control" name="confirmation" placeholder="Confirm New Password" type="password" required="" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-default">Change Password</button>
            </div>
        </div>
</form>
</div>