<?php
/**
 * @category  Mageants FrequentlyBought
 * @package   Mageants_FrequentlyBought
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <support@mageants.com>
 */

namespace Mageants\FrequentlyBought\Model\Config\Backend;

use Magento\Config\Model\Config\Backend\Image as Images;

class Image extends Images
{
    /**
     * The tail part of directory path for uploading
     */
    protected const UPLOAD_DIR = 'fbt/images';
    /**
     * Upload max file size in kilobytes
     *
     * @var int
     */
    protected $_maxFileSize = 2048;
    
   /**
    * Return path to directory for upload file
    *
    * @return string
    * @throw \Magento\Framework\Exception\LocalizedException
    */
    protected function _getUploadDir()
    {
        return $this->_mediaDirectory->getAbsolutePath($this->_appendScopeInfo(self::UPLOAD_DIR));
    }
    /**
     * Makes a decision about whether to add info about the scope.
     *
     * @return boolean
     */
    protected function _addWhetherScopeInfo()
    {
        return true;
    }
    /**
     * Getter for allowed extensions of uploaded files.
     *
     * @return string[]
     */
    protected function _getAllowedExtensions()
    {
        return ['jpg', 'jpeg', 'gif', 'png', 'svg'];
    }

    /**
     * Save uploaded file before saving config value
     *
     * Save changes and delete file if "delete" option passed
     *
     * @return $this
     */
    public function beforeSave()
    {
        $value = $this->getValue();
        $deleteFlag = is_array($value) && !empty($value['delete']);
        $fileTmpName = $this->_requestData->getTmpName('fbt_section/bought_together_settings/saperator_image');
        return parent::beforeSave();
    }
}
