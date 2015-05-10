<section class="row" id="users">
    <div class="col-md-12">
        <div class="page-header">
            <h2>Users <small>Bind them</small></h2>
        </div>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
<?php foreach ($users as $u) { ?>
                <tr id="<?php echo $u->id(); ?>">
                    <td><?php echo $u->id(); ?></td>
                    <td><?php echo htmlspecialchars($u->name()); ?></td>
                    <td><?php echo htmlspecialchars($u->email()); ?></td>
                    <td>
                        <button type="button" class="btn btn-sm" data-remove="user"><span class="glyphicon glyphicon-remove"></span></button>
                    </td>
                </tr>
<?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
        </table>
    </div>
</section>

