<?php

namespace WWN\Operations;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\DataObject;

/**
 * OperationLink
 *
 * @package wwn-operations
 * @access public
 */
class OperationLink extends DataObject
{
    /**
     * @var string
     */
    private static $table_name = 'WWNOperationLink';

    /**
     * @var array $db
     */
    private static $db = [
        'Source' => 'Varchar(255)',
        'Title' => 'Varchar(255)',
        'URL' => 'Varchar(255)',
    ];

    /**
     * @var array $has_one
     */
    private static $has_one = [
        'OperationArticle' => OperationArticle::class,
    ];

    /**
     * @var string|array $default_sort
     */
    private static $default_sort = ['Title'];

    /**
     * Übersichtsfelder
     *
     * @var array $summary_fields
     */
    private static $summary_fields = [
        'Title',
        'URL',
    ];

    /**
     * @return RequiredFields
     */
    public function getCMSValidator(): RequiredFields
    {
        return RequiredFields::create('Title');
    }

    /**
     * @return FieldList
     */
    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        //Main Tab
        $fields->findOrMakeTab('Root.Main');
        $contentFields = array(
            'URL' => $fields->fieldByName('Root.Main.URL')
        );
        $contentFields['URL']->setDescription(_t('URL.Form', 'URL mit http(s) angeben'));
        $fields->addFieldsToTab('Root.Main', $contentFields);

        return $fields;
    }
}
