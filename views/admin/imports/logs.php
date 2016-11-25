<?php echo head(array('title' => __('Import logs'))); ?>

<div id="primary">
    <?php echo flash(); ?>
    <h2><?php echo __('Import logs'); ?></h2>

    <?php if (!empty($logs)): ?>
        <table>
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Message</th>
                    <th>Severity</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?php echo $log->added; ?></td>
                        <td><?php echo $log->message; ?></td>
                        <td><?php echo $log->severity; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php echo pagination_links(); ?>
    <?php else: ?>
        <p>No logs yet. <a href="">Refresh page</a></p>
    <?php endif; ?>
</div>

<?php echo foot(); ?>
