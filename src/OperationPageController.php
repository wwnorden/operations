<?php

namespace WWN\Operations;

use Exception;
use PageController;
use SilverStripe\Core\Convert;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\View\ArrayData;

/**
 * OperationsPage controller
 *
 * @package wwn-operations
 * @access  public
 */
class OperationPageController extends PageController
{
    private static $allowed_actions = [
        'showOperationsPerYear',
    ];

    private static $url_handlers = [
        '$Year!' => 'showOperationsPerYear',
    ];

    /**
     * all operations per given year
     *
     * @return PaginatedList
     * @throws Exception
     */
    public function PaginatedOperations()
    {
        $filter = array(
            'YEAR(Begin)' => Convert::raw2sql($this->getRequest()
                ->param('Year')),
        );
        try {
            $articles =
                DataObject::get(OperationArticle::class, $filter, 'Begin DESC');
        } catch (Exception $e) {
            echo 'Message: '.$e->getMessage();
        }

        return new PaginatedList($articles, $this->getRequest());
    }

    /**
     * Overview
     *
     * @return ArrayList
     */
    public function PaginatedOperationsPerYear()
    {
        $result = DB::query(
            'SELECT YEAR(Begin) as Year,
                COUNT(*) AS Operations
                FROM WWNOperationArticle
                GROUP BY year DESC')->map();

        $operationsPerYear = new ArrayList();
        foreach ($result as $year => $numberOperations) {
            $operationsPerYear->push(
                new ArrayData(
                    [
                        'Year' => $year,
                        'Operations' => $numberOperations,
                        'Image' => $this->getCoverImage($year),
                    ]
                )
            );
        }

        return $operationsPerYear;
    }

    /**
     * @return DBHTMLText
     * @throws Exception
     */
    public function showOperationsPerYear(): DBHTMLText
    {
        $year = Convert::raw2sql($this->getRequest()->param('Year'));
        $customise = array(
            'ExtraBreadcrumb' => ArrayData::create(array(
                'Title' => $year,
                'Link' => $this->Link($year),
            )),
            'Year' => $year,
            'PaginatedOperations' => $this->PaginatedOperations(),
        );
        $renderWith = array(
            'WWN/Operations/OperationsPerYear',
            'Page',
        );

        return $this->customise($customise)->renderWith($renderWith);
    }

    /**
     * @param $year
     *
     * @return DataObject|null
     */
    private function getCoverImage($year): ?DataObject
    {
        $filter = array(
            'YEAR(WWNOperationArticle.Begin)' => $year,
            'WWNOperationImage.Cover' => true,
        );
        return DataObject::get(OperationImage::class)
            ->leftJoin(
                'WWNOperationArticle',
                "\"WWNOperationArticle\".\"ID\" = \"WWNOperationImage\".\"OperationArticleID\""
            )
            ->where($filter)
            ->first();
    }
}
