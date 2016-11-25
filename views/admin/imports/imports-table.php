<table>
    <thead>
        <tr>
            <th>Importer</th>
            <th>Status</th>
            <th>Started</th>
            <th>Completed</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($imports as $import): ?>
            <tr>
                <td><?php echo $import->getImporter()->name; ?></td>
                <td><?php echo $import->status; ?></td>
                <td><?php echo $import->started; ?></td>
                <td><?php echo $import->ended; ?></td>
                <td><a href="<?php echo url("import/imports/{$import->id}/logs"); ?>"><?php echo __('See logs'); ?></a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
