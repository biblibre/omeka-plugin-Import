# Import plugin for Omeka

Yet another import plugin for Omeka. This one intends to be easily extensible
by other plugins.

The two main concepts are readers and processors. Readers read data from
a source (file, url, ...) and make it accessible for processors which turn
these data into Omeka objects (items, collections, files, ...).

This plugin defines a reader for CSV files and a processor that create
items based on a user-defined mapping.

Note : if your only need is to import a CSV file into Omeka items, you should
probably use [CSV Import plugin], which does a perfect job for that.

[CSV Import plugin]: http://omeka.org/add-ons/plugins/csv-import/
