<?php

return [
    /*
     * DataTables JavaScript global namespace.
     */

    'namespace' => 'HIPMIDataTables',

    /*
     * Default table attributes when generating the table.
     */
    'table' => [
        'class' => 'table',
        'id' => 'dataTableBuilder',
    ],

    /*
     * Html builder script template.
     */
    'script' => 'datatables::script',

    /*
     * Html builder script template for DataTables Editor integration.
     */
    'editor' => 'datatables::editor',
];
