<?php echo head(array('title' => __('Imports'))); ?>

<div id="primary">
    <?php echo flash(); ?>
    <h2><?php echo __('Imports'); ?></h2>

    <?php if (!empty($imports)): ?>
        <?php echo $this->partial('imports/imports-table.php', array(
            'imports' => $imports,
        )); ?>

        <?php echo pagination_links(); ?>
    <?php else: ?>
        <p>No imports yet.</p>
    <?php endif; ?>
</div>

<?php echo foot(); ?>
