<?php
    echo head(array('title' => __('Import')));
?>
<div id="primary">
    <?php echo flash(); ?>

    <h2><?php echo __('Available importers'); ?></h2>
    <a class="button small green" href="<?php echo url('import/importers/add'); ?>"><?php echo __('Add an importer'); ?></a>
    <?php if (!empty($importers)): ?>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Reader</th>
                    <th>Processor</th>
                    <th colspan="2"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($importers as $importer): ?>
                    <tr>
                        <td><a href="<?php echo url("import/importers/{$importer->id}/start"); ?>"><?php echo $importer->name; ?></a></td>
                        <td>
                            <?php $reader = $importer->getReader(); ?>
                            <?php echo $reader->getLabel(); ?>
                            <?php if ($reader instanceof Import_Configurable): ?>
                                (<a href="<?php echo url("import/importers/{$importer->id}/configure-reader"); ?>">Configure</a>)
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php $processor = $importer->getProcessor(); ?>
                            <?php echo $processor->getLabel(); ?>
                            <?php if ($processor instanceof Import_Configurable): ?>
                                (<a href="<?php echo url("import/importers/{$importer->id}/configure-processor"); ?>">Configure</a>)
                            <?php endif; ?>
                        </td>
                        <td><a href="<?php echo url("import/importers/{$importer->id}/edit"); ?>">Edit</a></td>
                        <td><a href="<?php echo url("import/importers/{$importer->id}/delete"); ?>">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>There is no importers yet. <a href="<?php echo url('import/importers/add'); ?>">Create a new importer</a></p>
    <?php endif; ?>

    <?php if (!empty($imports)): ?>
        <h2><?php echo __('Last imports'); ?></h2>

        <a href="<?php echo url('import/imports'); ?>"><?php echo __('See all imports'); ?></a>

        <?php echo $this->partial('imports/imports-table.php', array(
            'imports' => $imports,
        )); ?>
    <?php endif; ?>
</div>
<?php
    echo foot();
?>
