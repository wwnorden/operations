<?php

namespace WWN\Operations;

use SilverStripe\Assets\Image;
use SilverStripe\Assets\Storage\DBFile;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;
use SilverStripe\Security\PermissionProvider;

/**
 * OperationType
 *
 * @package wwn-operations
 * @access public
 */
class OperationType extends DataObject implements PermissionProvider
{
    /**
     * @var string
     */
    private static $table_name = 'WWNOperationType';

    /**
     * @var string[]
     */
    private static $db = [
        'Title' => 'Varchar(255)',
        'SortOrder' => 'Int',
    ];

    /**
     * @var string[]
     */
    private static $has_one = [
        'Image' => Image::class,
    ];

    /**
     * @var string[]
     */
    private static $has_many = [
        'OperationArticle' => OperationArticle::class,
    ];

    /**
     * @var string
     */
    private static $default_sort = 'SortOrder';

    /**
     * @var string[]
     */
    private static $field_labels = [
        'Title' => 'Titel',
        'Thumbnail' => 'Vorschau',
    ];

    /**
     * @var string[]
     */
    private static $searchable_fields = [
        'Title',
    ];

    /**
     * @var string[]
     */
    private static $summary_fields = [
        'Title',
        'Thumbnail',
    ];

    /**
     * @var string[]
     */
    private static $owns = [
        'Image',
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('SortOrder');

        $image = $fields->dataFieldByName('Image');
        $image->setFolderName(
            _t(
                'WWN\Operations\Extensions\OperationsSiteConfigExtension.Foldername',
                'Foldername'
            ).'/'.
            _t(
                'WWN\Operations\OperationType.PLURALNAME',
                'OperationTypes'
            ).'/'.str_replace(['/',',','.',' ','_','(',')'], '-', $this->Title)
        );

        return $fields;
    }

    /**
     * @return DBFile|DBHTMLText Either a resized thumbnail, or html for a thumbnail icon
     */
    public function getThumbnail()
    {
        return $this->Image()->CMSThumbnail();
    }
    
    /**
     * @param null $member
     *
     * @return bool|int
     */
    public function canView($member = null)
    {
        if (!$member) {
            $member = Member:: currentUser();
        }
        return Permission:: checkMember($member, 'OPERATIONTYPE_VIEW');
    }

    /**
     * @param bool $member
     *
     * @return bool|int
     */
    public function canEdit($member = false)
    {
        if (!$member) {
            $member = Member:: currentUser();
        }
        return Permission:: checkMember($member, 'OPERATIONTYPE_EDIT');
    }

    /**
     * @param bool  $member
     * @param array $context
     *
     * @return bool|int
     */
    public function canCreate($member = false, $context = [])
    {
        if (!$member) {
            $member = Member:: currentUser();
        }
        return Permission:: checkMember($member, 'OPERATIONTYPE_CREATE');
    }

    /**
     * @param bool $member
     *
     * @return bool|int
     */
    public function canDelete($member = false)
    {
        if (!$member) {
            $member = Member::currentUser();
        }
        return Permission:: checkMember($member, 'OPERATIONTYPE_DELETE');
    }

    /**
     * @return string[]
     */
    public function providePermissions(): array
    {
        return [
            'OPERATIONTYPE_VIEW' => 'View operation type',
            'OPERATIONTYPE_EDIT' => 'Edit operation type',
            'OPERATIONTYPE_CREATE' => 'Create operation type',
            'OPERATIONTYPE_DELETE' => 'Delete operation type'
        ];
    }

    /**
     * Increment SortOrder on save
     */
    public function onBeforeWrite()
    {
        if (! $this->SortOrder) {
            $this->SortOrder = OPERATIONTYPE::get()->max('SortOrder') + 1;
        }
        parent::onBeforeWrite();
    }
    
    /**
     * publish images after creation
     */
    public function onAfterWrite()
    {
        if ($this->owner->ImageID) {
            $this->owner->Image()->publishSingle();
        }

        parent::onAfterWrite();
    }
}

