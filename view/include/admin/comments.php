<table class="table table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th>id / Author</th>
            <th>Date</th>
            <th>Text</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($comments as $c) { ?>
        <tr id="<?php echo $c->id(); ?>">
            <td><?php echo $c->id(); ?></td>
            <td rowspan="2"><?php echo $c->date('d/m/y H:i'); ?></td>
            <td rowspan="2"><?php echo htmlentities($c->text()); ?></td>
            <td rowspan="2">
                <button type="button" class="btn btn-sm" data-remove="comment"><span class="glyphicon glyphicon-remove"></span></button>
            </td>
        </tr>
        <tr>
            <td><?php echo $c->author(); ?></td>
        </tr>
<?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th>id / Author</th>
            <th>Date</th>
            <th>Text</th>
            <th>Actions</th>
        </tr>
    </tfoot>
</table>

