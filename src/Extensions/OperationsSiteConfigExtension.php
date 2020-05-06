<?php

namespace WWN\Operations\Extensions;

use SilverStripe\Assets\Folder;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\ORM\DataExtension;

/**
 * Siteconfig for operations
 *
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
        Folder::find_or_make(
            _t(
                'WWN\Operations\Extensions\OperationsSiteConfigExtension.Foldername',
                'Foldername'
            )
        );

        $fields->findOrMakeTab(
            'Root.Uploads',
            _t(
                'WWN\Operations\Extensions\OperationsSiteConfigExtension.SITECONFIGMENUTITLE',
                'Uploads'
            )
        );
        $operationsFields = array(
            'OperationsImageUploadFolderID' => TreeDropdownField::create(
                'OperationsImageUploadFolderID',
                _t(
                    'WWN\Operations\Extensions\OperationsSiteConfigExtension.has_one_OperationsImageUploadFolder',
                    'Images'
                ),
                Folder::class
            ),
            'OperationsImageUploadFolderByYear' => CheckboxField::create(
                'OperationsImageUploadFolderByYear',
                _t(
                    'WWN\Operations\Extensions\OperationsSiteConfigExtension.db_OperationsImageUploadFolderByYear',
                    'Folder by year'
                )
            ),
        );
        $fields->addFieldsToTab('Root.Uploads', $operationsFields);
        $operationsHeaders = array(
            'OperationsImageUploadFolderID' => _t(
                'WWN\Operations\Extensions\OperationsSiteConfigExtension.UploadFolders',
                'Upload folders'
            )
        );
        foreach ($operationsHeaders as $insertBefore => $header) {
            $fields->addFieldToTab(
                'Root.Uploads',
                HeaderField::create($insertBefore . 'Header', $header),
                $insertBefore
            );
        }
    }

    public function onBeforeWrite()
    {
        if ($this->owner->OperationsImageUploadFolderByYear) {
            Folder::find_or_make(
                _t(
                    'WWN\Operations\Extensions\OperationsSiteConfigExtension.Foldername',
                    'Foldername'
                ).'\\'.date('Y')
            );
        }
    }
}
