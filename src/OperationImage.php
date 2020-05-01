<?php

namespace WWN\Operations;

use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;
use SilverStripe\Security\PermissionProvider;

/**
 * OperationImage
 *
 * @package wwn-operations
 * @access public
 */
class OperationImage extends DataObject implements PermissionProvider
{
    /**
     * @var string
     */
    private static $table_name = 'WWNOperationImage';

    /**
     * @var array $db
     */
    private static $db = array(
        'Title' => 'Varchar(100)',
        'SortOrder' => 'Int',
        'Cover' => 'Boolean',
    );

    /**
     * @var array $has_one
     */
    private static $has_one = array(
        'OperationArticle' => OperationArticle::class,
        'Image' => Image::class,
    );

    /**
     * @var string|array $default_sort
     */
    private static $default_sort = 'SortOrder';

    /**
     * @var array $field_labels
     */
    private static $field_labels = array(
        'Title' => 'Titel',
        'Thumbnail' => 'Vorschau',
    );

    /**
     * @var array $searchable_fields
     */
    private static $searchable_fields = array(
        'Title',
    );

    /**
     * @var array $summary_fields
     */
    private static $summary_fields = array(
        'Title',
        'Thumbnail',
    );

    /**
     * @var array $owns
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

        return $fields;
    }

    /**
     * @return Image
     */
    public function getThumbnail(): Image
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
        return Permission:: checkMember($member, 'OPERATIONIMAGE_VIEW');
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
        return Permission:: checkMember($member, 'OPERATIONIMAGE_EDIT');
    }

    /**
     * @param bool  $member
     * @param array $context
     *
     * @return bool|int
     */
    public function canCreate($member = false, $context = array())
    {
        if (!$member) {
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
        if (!$member) {
            $member = Member::currentUser();
        }
        return Permission:: checkMember($member, 'OPERATIONIMAGE_DELETE');
    }

    /**
     * @return string[]
     */
    public function providePermissions(): array
    {
        return array(
            'OPERATIONIMAGE_VIEW' => 'Einsatzbilder ansehen',
            'OPERATIONIMAGE_EDIT' => 'Einsatzbilder bearbeiten',
            'OPERATIONIMAGE_CREATE' => 'Einsatzbilder erstellen',
            'OPERATIONIMAGE_DELETE' => 'Einsatzbilder löschen'
        );
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

