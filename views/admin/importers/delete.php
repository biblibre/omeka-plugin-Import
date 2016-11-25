<?php echo head(array('title' => __('Delete importer'))); ?>

<div id="primary">
    <?php echo flash(); ?>
    <h2><?php echo __('Delete importer (%s)', $importer->name); ?></h2>
    <p><?php echo __('Are you sure you want to delete this importer ?'); ?></p>
    <?php echo $form; ?>
</div>

<?php echo foot(); ?>
