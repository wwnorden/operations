<?php

namespace WWN\Operations\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;
use WWN\Operations\OperationArticle;

/**
 * Erweiterung Page-Controller
 *
 * @copyright Copyright (c) wwnorden
 * @package wwn-operations
 * @access public
 */
class PageControllerExtension extends Extension
{
    /**
     * @return \SilverStripe\ORM\DataList
     */
    public function GetLatestOperation(): ?DataList
    {
        $article = DataObject::get(OperationArticle::class, '', 'Date DESC', '', 1);
        return $article;
    }
}
