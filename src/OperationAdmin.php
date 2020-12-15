<?php

namespace WWN\Operations;

use SilverStripe\Admin\ModelAdmin;

/**
 * Administration operations
 *
 * @package wwn-operations
 * @access  public
 */
class OperationAdmin extends ModelAdmin
{
    /**
     * menuicon svg
     */
    private static $menu_icon_class = 'font-icon-rocket';

    /**
     * @var string
     */
    private static $menu_title = 'Einsätze';

    /**
     * @var string
     */
    private static $url_segment = 'einsaetze';

    /**
     * @var string[]
     */
    private static $managed_models = [
        'WWN\Operations\OperationArticle',
        'WWN\Operations\OperationLink',
        'WWN\Operations\OperationForce',
        'WWN\Operations\OperationalStatistics',
        'WWN\Operations\OperationType',
    ];

    /**
     * @var string[]
     */
    private static $model_importers = [
        OperationArticle::class => OperationsCsvBulkLoader::class,
    ];
}
