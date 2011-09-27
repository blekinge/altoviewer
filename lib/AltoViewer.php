<?php

/**
 * ALTO File Viewer
 *
 * @package    AltoViewer
 * @author     Dan Field <dof@llgc.org.uk>
 * @copyright  Copyright (c) 2010 National Library of Wales / Llyfrgell Genedlaethol Cymru. (http://www.llgc.org.uk)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 * @version    $Id$
 * @link       http://www.loc.gov/standards/alto/
 * 
 **/
 
require_once 'lib/AltoElement.php'; 

class AltoViewer 
{
    protected $_altoDir;
    
    protected $_imageDir;
    
    protected $_altoDom;
    
    protected $_imagephpSize;
    
    protected $_fileId;
    
    protected $_vScale;
    
    protected $_hScale;

    /**
     * Scale Elements vertically and horizontally
     * @param mixed $altoDir Directory holding ALTO files 
     * @param mixed $altoDir Directory holding Image files
     * @param mixed $fileId name of file without suffix 
     * @param float $vScale Vertical scale ratio
     * @param float $hScale Horizontal scale ratio
     */    
    public function __construct($altoDir, $imageDir, $fileId, $vScale, $hScale) 
    {
        $this->_altoDir = $altoDir;
        $this->_imageDir = $imageDir;    
        $this->_fileId = $fileId;    
        $this->_vScale = $vScale;    
        $this->_hScale = $hScale;    
        
        $this->_loadAlto($this->_altoDir . DIRECTORY_SEPARATOR . $this->_fileId . '.xml');
        $this->_setImageSize();
    }

    /**
     * Load ALTO File
     * @param mixed $altoFilename ALTO Filename
     */        
    protected function _loadAlto($altoFilename) 
    {
        $this->_altoDom = new DOMDocument;
        $this->_altoDom->load($altoFilename);    
    }

    /**
     * Get Strings from within the ALTO Document
     * @return array of AltoElements
     */    
    public function getStrings() 
    {
        $strings = $this->_altoDom->getElementsByTagName('String');
        $return = array();
        foreach ($strings as $string) {
            $s = new AltoElement($string);
            $s->scale($this->_vScale, $this->_hScale);
            $return[] = $s;
        }
        return $return;
    }
    
    /**
     * Get TextLines from within the ALTO Document
     * @return array of AltoElements  
     */    
    public function getTextLines() 
    {
        $textLines = $this->_altoDom->getElementsByTagName('TextLine');
        $return = array();
        foreach ($textLines as $textLine) {
            $t = new AltoElement($textLine);
            $t->scale($this->_vScale, $this->_hScale);
            $return[] = $t;
        }
        return $return;
    }
    
    /**
     * Get TextLines from within the ALTO Document
     * @return array of AltoElements
     */    
    public function getTextBlocks() 
    {
        $textBlocks = $this->_altoDom->getElementsByTagName('TextBlock');
        $return = array();
        foreach ($textBlocks as $textBlock) {
            $t = new AltoElement($textBlock);
            $t->scale($this->_vScale, $this->_hScale);
            $return[] = $t;
        }
        return $return;
    }

    /**
     * Get PrintSpace from within the ALTO Document
     * @return AltoElement
     */    
    public function getPrintSpace() 
    {
        $printSpace = $this->_altoDom->getElementsByTagName('PrintSpace');
        $p = new AltoElement($printSpace->item(0));
        $p->scale($this->_vScale, $this->_hScale);
        return $p;
    }
    
    /**
     * Set Image Size
     */    
    protected function _setImageSize() 
    {
        $this->_imageSize = getimagesize(($this->_imageDir . DIRECTORY_SEPARATOR . $this->_fileId . '.tif.png'));
        $layout = $this->_altoDom->getElementsByTagName('Page');
        $altoWidth = $layout->item(0)->getAttribute('WIDTH');
	$imageScale = $altoWidth / $this->_imageSize[0];
	$this->_imageSize[0] = $this->_imageSize[0]*$imageScale;
	$this->_imageSize[1] = $this->_imageSize[1]*$imageScale;
    }
    
    /**
     * Get Image Size
     * @return array
     */
    public function getImageSize() 
    {
        return $this->_imageSize;
    }
    
}
