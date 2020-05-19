<?php

namespace WWN\Operations;

use SilverStripe\Admin\ModelAdmin;

/**
 * Administration operations
 *
 * @package wwn-operations
 * @access public
 */
class OperationAdmin extends ModelAdmin
{
    /**
     * menuicon svg
     */
    private static $menu_icon_class = 'font-icon-rocket';

    /**
     * @var string $menu_title
     */
    private static $menu_title = 'Einsätze';

    /**
     * @var string $url_segment
     */
    private static $url_segment = 'einsaetze';

    /**
     * @var array $managed_models
     */
    private static $managed_models = array(
        'WWN\Operations\OperationArticle',
        'WWN\Operations\OperationLink',
        'WWN\Operations\OperationForce',
    );

    private static $model_importers = [
        OperationArticle::class => OperationsCsvBulkLoader::class,
    ];
}
