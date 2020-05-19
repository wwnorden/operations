<?php

namespace WWN\Operations;

use SilverStripe\Forms\DatetimeField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBDatetime;
use WWN\Vehicles\Vehicle;

/**
 * OperationArticle
 *
 * @package wwn-operations
 * @access  public
 */
class OperationArticle extends DataObject
{
    /**
     * @var string
     */
    private static $table_name = 'WWNOperationArticle';

    /**
     * @var array $db
     */
    private static $db = [
        'Title' => 'Varchar(150)',
        'Content' => 'HTMLText',
        'Number' => 'Varchar(3)',
        'Location' => 'Varchar(255)',
        'Begin' => 'DBDatetime',
        'End' => 'DBDatetime',
        'People' => 'Int',
    ];

    private static $has_many = [
        'Links' => OperationLink::class,
        'OperationImages' => OperationImage::class,
    ];

    private static $many_many = [
        'OperationForces' => OperationForce::class,
        'Vehicles' => Vehicle::class,
    ];

    /**
     * @var array $indexes
     */
    private static $indexes = [
        'SearchFields' => [
            'type' => 'fulltext',
            'columns' => [
                'Title',
                'Content',
            ],
        ],
    ];

    /**
     * @var string $default_sort
     */
    private static $default_sort = [
        'Begin' => 'DESC',
        'ID' => 'DESC',
    ];

    /**
     * @var array $summary_fields
     */
    private static $summary_fields = [
        'Number',
        'Title',
        'BeginFormatted',
        'EndFormatted',
        'Location',
        'People',
    ];

    /**
     * @var array $searchable_fields
     */
    private static $searchable_fields = [
        'Title',
        'Content',
    ];

    /**
     * @param bool $includerelations
     *
     * @return array
     */
    public function fieldLabels($includerelations = true): array
    {
        $labels = parent::fieldLabels(true);
        $labels['BeginFormatted'] =
            _t('WWN\Operations\OperationArticle.db_Begin', 'Begin');
        $labels['EndFormatted'] =
            _t('WWN\Operations\OperationArticle.db_End', 'End');

        return $labels;
    }

    /**
     * @return DataObject|void
     */
    public function populateDefaults()
    {
        parent::populateDefaults();
        $this->Begin = date('d.m.Y h:m');
        $this->End = date('d.m.Y h:m');
    }

    /**
     * @return false|string
     */
    public function getBeginFormatted(): ?string
    {
        return $this->formatDateTime('Begin');
    }

    /**
     * @return false|string
     */
    public function getEndFormatted(): ?string
    {
        return $this->formatDateTime('End');
    }

    /**
     * @param $field
     *
     * @return false|string
     */
    private function formatDateTime($field)
    {
        return date(
            _t(
                'WWN\Operations\OperationArticle.DateTimeFormatList',
                'm/d/Y H:i'
            ),
            strtotime($this->dbObject($field)->getValue())
        );
    }

    /**
     * @return FieldList $fields
     */
    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        // Content tab
        $fields->findOrMakeTab(
            'Root.ContentTab',
            _t('WWN\Operations\OperationArticle.ContentTab', 'Content')
        );
        $contentFields = [
            'Content' => $fields->fieldByName('Root.Main.Content'),
        ];
        $fields->addFieldsToTab('Root.ContentTab', $contentFields);

        // Main tab
        $mainFields = [
            'Begin' => $this->configDatetime('Begin'),
            'End' => $this->configDatetime('End'),
        ];
        $fields->addFieldsToTab('Root.Main', $mainFields);

        return $fields;
    }

    /**
     * @param $field
     *
     * @return DatetimeField
     */
    private function configDatetime($field): DatetimeField
    {
        $dateTimefield = DatetimeField::create(
            $field,
            _t('WWN\Operations\OperationArticle.db_'.$field, $field)
        )
            ->setHTML5(false)
            ->setDateTimeFormat(
                _t(
                    'WWN\Operations\OperationArticle.DateTimeFormat',
                    'MM/dd/yyyy HH:mm'
                )
            );
        $dateTimefield->setDescription(
            _t(
                'WWN\Operations\OperationArticle.DateTimeDescription',
                'e.g. {format}',
                ['format' => $dateTimefield->getDateTimeFormat()]
            )
        );
        $dateTimefield->setAttribute(
            'placeholder',
            $dateTimefield->getDateTimeFormat()
        );

        return $dateTimefield;
    }

    /**
     * @return RequiredFields
     */
    public function getCMSValidator(): RequiredFields
    {
        return RequiredFields::create('Title');
    }

    /**
     * link to backend edit form
     *
     * @return string|null
     */
    public function EditLink(): ?string
    {
        $editLink = false;
        if ($this->canEdit()) {
            $editLink = Director::baseURL()
                .'admin/operations/OperationArticle/EditForm/field/OperationArticle/item/'
                .$this->ID.'/edit/';
        }

        return $editLink;
    }
}
