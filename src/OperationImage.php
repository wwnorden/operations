<?php

namespace WWN\Operations;

use SilverStripe\Assets\Image;
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
     * Datenbank Tabellenname
     *
     * @var string
     */
    private static $table_name = 'WWNOperationImage';

    /**
     * Datenbankfelder
     *
     * @var array $db
     */
    private static $db = array(
        'Title' => 'Varchar(100)',
        'SortOrder' => 'Int',
        'Cover' => 'Boolean',
    );

    /**
     * 1:1 verknüpfte Objekte
     *
     * @var array $has_one
     */
    private static $has_one = array(
        'OperationArticle' => OperationArticle::class,
        'Image' => Image::class,
    );

    /**
     * Felder für Standardsortierung
     *
     * @var string|array $default_sort
     */
    private static $default_sort = 'SortOrder';

    /**
     * Feldbezeichnungen anpassen
     *
     * @var array $field_labels
     */
    private static $field_labels = array(
        'Title' => 'Titel',
        'Thumbnail' => 'Vorschau',
    );

    /**
     * Suchefelder
     *
     * @var array $searchable_fields
     */
    private static $searchable_fields = array(
        'Title',
    );

    /**
     * Felder in der Übersicht
     *
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
     * @return \SilverStripe\Forms\FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('OperationArticleID');
        $fields->removeByName('SortOrder');

        return $fields;
    }

    /**
     * @return Image
     */
    public function getThumbnail()
    {
        return $this->Image()->CMSThumbnail();
    }

    public function canView($member = null)
    {
        if (!$member) {
            $member = Member:: currentUser();
        }
        return Permission:: checkMember($member, 'OPERATIONIMAGE_VIEW');
    }

    public function canEdit($member = false)
    {
        if (!$member) {
            $member = Member:: currentUser();
        }
        return Permission:: checkMember($member, 'OPERATIONIMAGE_EDIT');
    }

    public function canCreate($member = false, $context = array())
    {
        if (!$member) {
            $member = Member:: currentUser();
        }
        return Permission:: checkMember($member, 'OPERATIONIMAGE_CREATE');
    }

    public function canDelete($member = false)
    {
        if (!$member) {
            $member = Member:: currentUser();
        }
        return Permission:: checkMember($member, 'OPERATIONIMAGE_DELETE');
    }

    public function providePermissions()
    {
        return array(
            'OPERATIONIMAGE_VIEW' => 'Einsatzbilder ansehen',
            'OPERATIONIMAGE_EDIT' => 'Einsatzbilder bearbeiten',
            'OPERATIONIMAGE_CREATE' => 'Einsatzbilder erstellen',
            'OPERATIONIMAGE_DELETE' => 'Einsatzbilder löschen'
        );
    }

    /**
     * Erlaubt das nachträgliche publishen von Bildern aufgrund eines Bugs
     */
    public function onAfterWrite()
    {
        if ($this->owner->ImageID) {
            $this->owner->Image()->publishSingle();
        }

        parent::onAfterWrite();
    }
}

