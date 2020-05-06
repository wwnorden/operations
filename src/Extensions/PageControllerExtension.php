<?php

namespace WWN\Operations\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;
use WWN\Operations\OperationArticle;

/**
 * Extension Page-Controller
 *
 * @package wwn-operations
 * @access public
 */
class PageControllerExtension extends Extension
{
    /**
     * @param int $limit
     *
     * @return DataList|null
     */
    public function GetLatestOperation($limit = 1): ?DataList
    {
        return DataObject::get(
            OperationArticle::class,
            '',
            'Date DESC',
            '',
            $limit);
    }
}
