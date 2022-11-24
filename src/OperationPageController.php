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
        $results = DB::query(
            'SELECT YEAR(Begin) as Year,
                Number AS Operations
                FROM WWNOperationArticle
                ORDER By Begin DESC');

        $yearToOpertaions = [];
        foreach ($results as $result) {
            $numberOperations = $result['Operations'];
            if (strpos($result['Operations'], '-') !== false) {
                $strArray = explode('-', $numberOperations);
                $numberOperations = $strArray[1];
            }
            if (isset($yearToOpertaions[$result['Year']])) {
                if ($numberOperations > $yearToOpertaions[$result['Year']]) {
                    $yearToOpertaions[$result['Year']] = $numberOperations;
                }
            } else {
                $yearToOpertaions[$result['Year']] = $numberOperations;
            }
        }

        $operationsPerYear = new ArrayList();
        foreach ($yearToOpertaions as $year => $opertaions) {
            $statsPerYear = OperationalStatistics::get()
                ->filter('Year:StartsWith', $year)
                ->first();

            $image = $this->getCoverImage($year)->Image ?? $statsPerYear->Image ?? false;
            $operationsPerYear->push(
                new ArrayData(
                    [
                        'Year' => $year,
                        'Operations' => $statsPerYear->Number ?? $statsPerYear->Number ?? $opertaions,
                        'Image' => $image,
                    ]
                )
            );
            continue;
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
