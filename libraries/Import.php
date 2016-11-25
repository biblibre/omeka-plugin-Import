<?php

class Import
{
    public static function getReaders()
    {
        return self::get('readers');
    }

    public static function getProcessors()
    {
        return self::get('processors');
    }

    protected static function get($event)
    {
        $items = array();

        $events = Zend_EventManager_StaticEventManager::getInstance();
        $listeners = $events->getListeners(ImportPlugin::class, $event);
        if (false !== $listeners) {
            foreach ($listeners->getIterator() as $listener) {
                $items = array_merge($items, $listener->call());
            }
        }

        return $items;
    }
}
