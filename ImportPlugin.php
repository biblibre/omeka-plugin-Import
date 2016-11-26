<?php

class ImportPlugin extends Omeka_Plugin_AbstractPlugin
{
    /**
     * @var array Hooks for the plugin.
     */
    protected $_hooks = array(
        'install',
        'uninstall',
        'initialize',
        'define_routes',
    );

    /**
     * @var array Filters for the plugin.
     */
    protected $_filters = array(
        'admin_navigation_main',
    );

    /**
     * Install the plugin.
     */
    public function hookInstall()
    {
        $db = $this->_db;

        $db->query("
            CREATE TABLE IF NOT EXISTS `{$db->prefix}import_importers` (
                `id` int unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `reader` varchar(255) NOT NULL,
                `reader_config` text NULL DEFAULT NULL,
                `processor` varchar(255) NOT NULL,
                `processor_config` text NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        $db->query("
            CREATE TABLE IF NOT EXISTS `{$db->prefix}import_imports` (
                `id` int unsigned NOT NULL AUTO_INCREMENT,
                `importer_id` int unsigned NOT NULL,
                `reader_params` text NULL DEFAULT NULL,
                `processor_params` text NULL DEFAULT NULL,
                `status` varchar(255) NULL DEFAULT NULL,
                `started` timestamp NULL DEFAULT NULL,
                `ended` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY (`importer_id`),
                CONSTRAINT `{$db->prefix}import_imports_fk_importer_id`
                  FOREIGN KEY (`importer_id`) REFERENCES `{$db->prefix}import_importers` (`id`)
                  ON DELETE RESTRICT ON UPDATE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        $db->query("
            CREATE TABLE IF NOT EXISTS `{$db->prefix}import_logs` (
                `id` int unsigned NOT NULL AUTO_INCREMENT,
                `import_id` int unsigned NOT NULL,
                `severity` int NOT NULL DEFAULT 0,
                `message` text NOT NULL,
                `params` text NULL DEFAULT NULL,
                `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY (`import_id`),
                CONSTRAINT {$db->prefix}import_logs_fk_import_id
                  FOREIGN KEY (`import_id`) REFERENCES `{$db->prefix}import_imports` (`id`)
                  ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");
    }

    /**
     * Uninstall the plugin.
     */
    public function hookUninstall()
    {
        $db = $this->_db;

        $db->query("DROP TABLE IF EXISTS `{$db->prefix}import_logs`");
        $db->query("DROP TABLE IF EXISTS `{$db->prefix}import_imports`");
        $db->query("DROP TABLE IF EXISTS `{$db->prefix}import_importers`");
    }

    /**
     * Add the translations.
     */
    public function hookInitialize()
    {
        add_translation_source(dirname(__FILE__) . '/languages');

        $events = Zend_EventManager_StaticEventManager::getInstance();
        $events->attach(ImportPlugin::class, 'readers', array($this, 'getReaders'));
        $events->attach(ImportPlugin::class, 'processors', array($this, 'getProcessors'));
    }

    public function getReaders()
    {
        $readers = array(
            'csv' => array(
                'name' => 'CSV',
                'factory' => 'Import_Reader_CsvReaderFactory',
            ),
        );

        return $readers;
    }

    public function getProcessors()
    {
        $processors = array(
            'items' => array(
                'name' => 'Items',
                'factory' => 'Import_Processor_ItemsProcessorFactory',
            ),
        );

        return $processors;
    }

    public function hookDefineRoutes($args)
    {
        $router = $args['router'];

        $router->addRoute(
            'import-id',
            new Zend_Controller_Router_Route(
                'import/:controller/:id/:action',
                array(
                    'module' => 'import',
                )
            )
        );
    }

    /**
     * Add the Simple Pages link to the admin main navigation.
     *
     * @param array Navigation array.
     * @return array Filtered navigation array.
     */
    public function filterAdminNavigationMain($nav)
    {
        $nav[] = array(
            'label' => __('Import'),
            'uri' => url('import'),
        );
        return $nav;
    }
}
