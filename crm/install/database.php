<?php echo '<form action="" method="post" accept-charset="utf-8">'; ?>
<?php echo '<input type="hidden" name="step" value="' . $current_step . '">'; ?>
<div class="form-group">
    <label for="hostname" class="control-label">Hostname</label>
    <input type="text" class="form-control" name="hostname" value="localhost">
</div>
<div class="form-group">
    <label for="database" class="control-label">Database Name</label>
    <input type="text" class="form-control" name="database">
</div>
<div class="form-group">
    <label for="username" class="control-label">Username</label>
    <input type="text" class="form-control" name="username">
</div>
<div class="form-group">
    <label for="password" class="control-label"><i class="glyphicon glyphicon-info-sign"
            title='Avoid use of single(&lsquo;) and double(&ldquo;) quotes in your password'></i>
        Password</label>
    <input type="password" class="form-control" name="password">
</div>
<hr class="-tw-mx-4" />
<div class="text-right">
    <button type="submit" class="btn btn-primary">Check Database</button>
</div>
</form>