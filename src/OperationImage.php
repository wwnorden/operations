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
 * OperationImage
 *
 * @package wwn-operations
 * @access  public
 */
class OperationImage extends DataObject implements PermissionProvider
{
    /**
     * @var string
     */
    private static $table_name = 'WWNOperationImage';

    /**
     * @var string[]
     */
    private static $db = [
        'Title' => 'Varchar(100)',
        'SortOrder' => 'Int',
        'Cover' => 'Boolean',
    ];

    /**
     * @var string[]
     */
    private static $has_one = [
        'OperationArticle' => OperationArticle::class,
        'Image' => Image::class,
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
        $fields->removeByName('OperationArticleID');
        $fields->removeByName('SortOrder');

        $image = $fields->dataFieldByName('Image');
        $image->setFolderName(
            _t(
                'WWN\Operations\Extensions\OperationsSiteConfigExtension',
                'Foldername'
            ).'/'.str_replace('/', '-', $this->OperationArticle->Number.'-'.$this->OperationArticle->Title)
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
        if (! $member) {
            $member = Member:: currentUser();
        }

        return Permission:: checkMember($member, 'OPERATIONIMAGE_VIEW');
    }

    /**
     * @param bool $member
     *
     * @return bool|int
     */
    public function canEdit($member = false)
    {
        if (! $member) {
            $member = Member:: currentUser();
        }

        return Permission:: checkMember($member, 'OPERATIONIMAGE_EDIT');
    }

    /**
     * @param bool  $member
     * @param array $context
     *
     * @return bool|int
     */
    public function canCreate($member = false, $context = [])
    {
        if (! $member) {
            $member = Member:: currentUser();
        }

        return Permission:: checkMember($member, 'OPERATIONIMAGE_CREATE');
    }

    /**
     * @param bool $member
     *
     * @return bool|int
     */
    public function canDelete($member = false)
    {
        if (! $member) {
            $member = Member::currentUser();
        }

        return Permission:: checkMember($member, 'OPERATIONIMAGE_DELETE');
    }

    /**
     * @return string[]
     */
    public function providePermissions(): array
    {
        return [
            'OPERATIONIMAGE_VIEW' => 'Einsatzbilder ansehen',
            'OPERATIONIMAGE_EDIT' => 'Einsatzbilder bearbeiten',
            'OPERATIONIMAGE_CREATE' => 'Einsatzbilder erstellen',
            'OPERATIONIMAGE_DELETE' => 'Einsatzbilder löschen',
        ];
    }

    /**
     * Increment SortOrder on save
     */
    public function onBeforeWrite()
    {
        if (! $this->SortOrder) {
            $this->SortOrder = OperationImage::get()->max('SortOrder') + 1;
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

