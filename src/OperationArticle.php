<?php

namespace WWN\Operations;

use SilverStripe\Control\Director;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\DataObject;

/**
 * OperationArticle
 *
 * @package wwn-operations
 * @access public
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
        'Date' => 'Date',
        'Number' => 'Varchar(3)',
        'Begin' => 'DBDatetime',
        'End' => 'DBDatetime',
        'People' => 'Int'
    ];

    private static $has_many = [
        'Links' => OperationLink::class,
        'OperationImages' => OperationImage::class,
    ];

    private static $many_many = [
        'OperationForces' => OperationForce::class,
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
        ]
    ];

    /**
     * @var string $default_sort
     */
    private static $default_sort = [
        'Date' => 'DESC',
        'ID' => 'DESC'
    ];

    /**
     * @var array $summary_fields
     */
    private static $summary_fields = [
        'Number',
        'DateFormatted' => 'Datum',
        'Title',
        'People',
    ];

    /**
     * format date
     *
     * @return false|string
     */
    public function getDateFormatted(): ?string
    {
        return date('d.m.Y', strtotime($this->dbObject('Date')->getValue()));
    }

    /**
     * @var array $searchable_fields
     */
    private static $searchable_fields = [
        'Title',
        'Content',
    ];

    /**
     * @return DataObject|void
     */
    public function populateDefaults()
    {
        parent::populateDefaults();
        $this->Date = date('d.m.Y');
        $this->Begin = date('d.m.Y h:m:s');
        $this->End = date('d.m.Y h:m:s');
    }

    /**
     * @return FieldList $fields
     */
    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        $fields->findOrMakeTab('Root.ContentTab', _t('Tab.Content', 'Inhalt'));
        $contentFields = [
            'Content' => $fields->fieldByName('Root.Main.Content')
        ];
        $fields->addFieldsToTab('Root.ContentTab', $contentFields);

        return $fields;
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
            $editLink = Director::baseURL() . 'admin/operations/OperationArticle/EditForm/field/OperationArticle/item/' . $this->ID . '/edit/';
        }
        return $editLink;
    }
}
