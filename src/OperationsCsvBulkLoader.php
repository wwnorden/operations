<?php

namespace WWN\Operations;

use SilverStripe\Dev\CsvBulkLoader;
use SilverStripe\ORM\DataObject;

/**
 * OpertionsCsvBulkLoader
 *
 * @package wwn-operations
 * @access public
 */
class OperationsCsvBulkLoader extends CsvBulkLoader
{
    public $columnMap = [
        'message' => 'Title',
        'description' => 'Content',
        'date' => 'Date',
        'number' => 'Number',
        'beginning' => '->setBegin',
        'ending' => '->setEnd',
        'links/0/source' => '->setLinkByTitle',
        'links/1/source' => '->setLink1',
        'links/2/source' => '->setLink2',
        'links/3/source' => '->setLink3',
        'brigades/0' => '->setForce',
        'brigades/1' => '->setForce',
        'brigades/2' => '->setForce',
        'brigades/3' => '->setForce',
        'brigades/4' => '->setForce',
        'brigades/5' => '->setForce',
        'brigades/6' => '->setForce',
        'brigades/7' => '->setForce',
        'brigades/8' => '->setForce',
        'brigades/9' => '->setForce',
        'brigades/10' => '->setForce',
    ];

    public static function setBegin(&$obj, $val, $record)
    {
        if (!empty($val)) {
            $format = 'd.m.Y H:i:s';
            $date = $record['Date'] . ' ' . trim(str_replace('Uhr', '', $val));
            $date = date($format, strtotime($date));
            $obj->Begin = $date;
        }
    }

    public static function setEnd(&$obj, $val, $record)
    {
        if (!empty($val)) {
            $format = 'd.m.Y H:i:s';
            $date = $record['Date'] . ' ' . trim(str_replace('Uhr', '', $val));
            $date = date($format, strtotime($date));
            $obj->End = $date;
        }
    }

    public static function setLinkByTitle(&$obj, $val, $record)
    {
        if (!empty($val)) {
            $obj->write();
            $link = new OperationLink();
            $link->Title = $val;
            $link->Source = $val;
            $link->OperationArticleID = $obj->ID;
            $link->URL = $record['links/0/url'];
            $link->write();
        }
    }

    public static function setLink1(&$obj, $val, $record)
    {
        if (!empty($val)) {
            $link = new OperationLink();
            $link->Title = $val;
            $link->Source = $val;
            $link->OperationArticleID = $obj->ID;
            $link->URL = $record['links/1/url'];
            $link->write();
        }
    }

    public static function setLink2(&$obj, $val, $record)
    {
        if (!empty($val)) {
            $link = new OperationLink();
            $link->Title = $val;
            $link->Source = $val;
            $link->OperationArticleID = $obj->ID;
            $link->URL = $record['links/2/url'];
            $link->write();
        }
    }

    public static function setLink3(&$obj, $val, $record)
    {
        if (!empty($val)) {
            $link = new OperationLink();
            $link->Title = $val;
            $link->Source = $val;
            $link->OperationArticleID = $obj->ID;
            $link->URL = $record['links/3/url'];
            $link->write();
        }
    }

    public static function setForce(&$obj, $val, $record)
    {
        if (!empty($val)) {
            $filter = "Title = '" . $val . "'";
            $force = DataObject::get(OperationForce::class, $filter)->first();
            if (!empty($force->Title)) {
                $obj->OperationForces()->add($force);
            } else {
                $force = new OperationForce();
                $force->Title = $val;
                $obj->OperationForces()->add($force);
            }
        }
    }
}
