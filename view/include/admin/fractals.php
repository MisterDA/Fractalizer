<section class="row" id="fractals">
    <div class="col-md-12">
        <div class="page-header">
            <h2>Fractals <small>Draw them</small></h2>
        </div>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Date</th>
                    <th>Votes</th>
                    <th>Formula</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
<?php foreach ($fractals as $f) { ?>
                <tr id="<?php echo $f->id(); ?>">
                    <td><?php echo $f->id(); ?></td>
                    <td><?php echo htmlentities($f->title()); ?></td>
                    <td><?php echo $f->author(); ?></td>
                    <td><?php echo $f->date('d/m/y H:i'); ?></td>
                    <td><?php echo $f->votes(); ?></td>
                    <td><?php echo htmlentities($f->formula()); ?></td>
                    <td>
                        <div class="btn-group" role="group">

                            <button type="button" class="btn btn-sm" data-action="view" data-toggle="modal" data-target="#modal-<?php echo $f->id(); ?>">
                                <span class="glyphicon glyphicon-eye-open"></span>
                            </button>
                            <button type="button" class="btn btn-sm" data-action="edit" data-toggle="modal" data-target="#modal-<?php echo $f->id(); ?>">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </button>
                            <button type="button" class="btn btn-sm" data-remove="fractal">
                                <span class="glyphicon glyphicon-remove"></span>
                            </button>
                        </div>

<div class="modal fade" id="modal-<?php echo $f->id(); ?>" tabindex="-1" role="dialog" aria-labelledby="label-<?php echo $f->id(); ?>" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="label-<?php echo $f->id(); ?>"><?php echo htmlentities($f->title()); ?> by <?php echo htmlentities($um->get($f->author())->name()); ?></h4>
      </div>
      <div class="modal-body">
        <div><canvas width="400" height="300" data-formula="<?php echo htmlentities($f->formula()); ?>"></canvas></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

                    </td>
                </tr>
<?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>id</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Date</th>
                    <th>Votes</th>
                    <th>Formula</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
        </table>
    </div>
</section>

