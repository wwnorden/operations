<?php

namespace WWN\Operations;

use SilverStripe\Assets\Image;
use SilverStripe\CMS\Forms\SiteTreeURLSegmentField;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\DatetimeField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\View\Requirements;

/**
 * OperationalStatistics
 *
 * @package wwn-operations
 * @access  public
 */
class OperationalStatistics extends DataObject
{
    /**
     * @var string
     */
    private static $table_name = 'WWNOperationalStatistics';

    /**
     * @var array
     */
    private static $db = [
        'Year' => 'Date',
        'Number' => 'Int',
    ];
    /**
     * @var string[]
     */
    private static $has_one = [
        'Image' => Image::class,
    ];

    /**
     * @var string
     */
    private static $default_sort = [
        'Year' => 'DESC',
    ];

    /**
     * @var array
     */
    private static $summary_fields = [
        'YearFormatted',
        'Number',
    ];

    /**
     * @var array
     */
    private static $searchable_fields = [
        'Year',
        'Number',
    ];

    /**
     * @var string[]
     */
    private static $owns = [
        'Image',
    ];

    /**
     * @param bool
     *
     * @return array
     */
    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels(true);
        $labels['YearFormatted'] =
            _t('WWN\Operations\OperationalStatistics.db_Year', 'Year');

        return $labels;
    }

    /**
     * format Year for overview
     *
     * @return false|string
     */
    public function getYearFormatted(): ?string
    {
        return date(
            'Y',
            strtotime($this->dbObject('Year')->getValue())
        );
    }

    /**
     * @return RequiredFields
     */
    public function getCMSValidator(): RequiredFields
    {
        return RequiredFields::create('Year');
    }

    /**
     * @return FieldList
     */
    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        // year
        $year = DateField::create(
            'Year',
            _t('WWN\Operations\OperationalStatistics.db_Year', 'Year')
        )
            ->setHTML5(false)
            ->setDateFormat(
                _t('WWN\Operations\OperationalStatistics.YearFormat',
                    'yyyy'
                )
            );
        $year->setDescription(
            _t(
                'WWN\Operations\OperationalStatistics.YearDescription',
                'e.g. {format}',
                ['format' => $year->getDateFormat()]
            )
        );
        $year->setAttribute(
            'placeholder',
            $year->getDateFormat()
        );
        $mainFields['Year'] = $year;
        $fields->addFieldsToTab('Root.Main', $mainFields);

        $date = new \DateTime($this->Year);
        $date = $date->format('Y');
        $image = $fields->dataFieldByName('Image');
        $image->setFolderName(
            _t(
                'WWN\Operations\Extensions\OperationsSiteConfigExtension.Foldername',
                'Foldername'
            ).'/'.str_replace(['/', ',', '.', ' ', '_', '(', ')'], '-', $date)
        );

        return $fields;
    }
}
