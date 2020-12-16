<?php

namespace WWN\Operations;

use SilverStripe\Assets\Image;
use SilverStripe\Forms\DatetimeField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBDatetime;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use Symbiote\GridFieldExtensions\GridFieldTitleHeader;
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
     * @var string[]
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

    /**
     * @var string[]
     */
    private static $has_one = [
        'OperationType' => OperationType::class,
    ];
    
    /**
     * @var string[]
     */
    private static $has_many = [
        'Links' => OperationLink::class,
        'OperationImages' => OperationImage::class,
    ];

    /**
     * @var string[]
     */
    private static $many_many = [
        'OperationForces' => OperationForce::class,
        'Vehicles' => Vehicle::class,
    ];

    /**
     * @var array[]
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
     * @var string[]
     */
    private static $default_sort = [
        'Begin' => 'DESC',
        'ID' => 'DESC',
    ];

    /**
     * @var string[]
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
     * @var string[]
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
     * @return string|null
     */
    public function getBeginFormatted(): ?string
    {
        return $this->formatDateTime('Begin');
    }

    /**
     * @return string|null
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

        // Main tab
        $mainFields = [
            'Begin' => $this->configDatetime('Begin'),
            'End' => $this->configDatetime('End'),
        ];
        $fields->addFieldsToTab('Root.Main', $mainFields);

        // sorting images
        $images = GridField::create(
            'OperationImages',
            _t('WWN\Operations\OperationImage.PLURALNAME','Operation images'),
            $this->OperationImages(),
            GridFieldConfig::create()->addComponents(
                new GridFieldToolbarHeader(),
                new GridFieldAddNewButton('toolbar-header-right'),
                new GridFieldDetailForm(),
                new GridFieldDataColumns(),
                new GridFieldEditButton(),
                new GridFieldDeleteAction('unlinkrelation'),
                new GridFieldDeleteAction(),
                new GridFieldOrderableRows('SortOrder'),
                new GridFieldTitleHeader(),
                new GridFieldAddExistingAutocompleter('before', ['Title'])
            )
        );
        $fields->addFieldsToTab('Root.OperationImages',
            [
                $images
            ]
        );

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
