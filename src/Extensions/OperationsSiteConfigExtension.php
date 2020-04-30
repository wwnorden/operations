<?php

namespace WWN\Operations\Extensions;

use SilverStripe\Assets\Folder;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\ORM\DataExtension;

/**
 * Einstellungen Operations
 *
 * @copyright Copyright (c) wwnorden
 * @package wwn-operations
 * @access public
 */
class OperationsSiteConfigExtension extends DataExtension
{
    /**
     * Uploadfolder every year
     *
     * @var array $db
     */
    private static $db = array(
        'OperationsImageUploadFolderByYear' => 'Boolean'
    );

    /**
     * @var array $has_one
     */
    private static $has_one = array(
        'OperationsImageUploadFolder' => Folder::class,
    );

    /**
     * Set upload folder for operations
     *
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        $fields->findOrMakeTab('Root.Uploads', _t('OperationAdmin.SITECONFIGMENUTITLE', 'Uploads'));
        $operationsFields = array(
            'OperationsImageUploadFolderID' => TreeDropdownField::create(
                'OperationsImageUploadFolderID',
                _t('OperationsSiteConfigExtension.has_one_OperationsImageUploadFolder', 'Bilder'),
                Folder::class
            ),
            'OperationsImageUploadFolderByYear' => CheckboxField::create(
                'OperationsImageUploadFolderByYear',
                _t('OperationsSiteConfigExtension.db_OperationsImageUploadFolderByYear',
                    'Unterordner pro Jahr')
            ),
        );
        $fields->addFieldsToTab('Root.Uploads', $operationsFields);
        $operationsHeaders = array(
            'OperationsImageUploadFolderID' => _t('Header.UploadFolders', 'Ordner für Einsatzbilder')
        );
        foreach ($operationsHeaders as $insertBefore => $header) {
            $fields->addFieldToTab(
                'Root.Uploads',
                HeaderField::create($insertBefore . 'Header', $header),
                $insertBefore
            );
        }
    }
}
