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
 
class AltoElement 
{
    protected $_id;
    
    protected $_type;

    protected $_hPos;
    
    protected $_vPos;

    protected $_height;

    protected $_width;
    
    /**
     * @param DOMElement $element ALTO Element 
     */
    public function __construct($element) 
    {
        $this->_type = $element->tagName;
        $this->_id = $element->getAttribute('ID');
        $this->_hPos = $element->getAttribute('HPOS');
        $this->_vPos = $element->getAttribute('VPOS');
        $this->_height = $element->getAttribute('HEIGHT');
        $this->_width = $element->getAttribute('WIDTH');
    }
    
    /**
     * Scale Elements vertically and horizontally
     * @param mixed $vScale Vertical Scale ratio 
     * @param mixed $hScale Horizontal Scale ratio 
     */
    public function scale($vScale, $hScale) 
    {
        $this->_hPos   = floor($this->_hPos  * (float) $hScale);
        $this->_vPos   = floor($this->_vPos  * (float) $vScale);
        $this->_height = ceil($this->_height * (float) $vScale);
        $this->_width  = ceil($this->_width  * (float) $hScale);
    }
    
    /**
     * Get Horizontal Position of Element
     * @return int
     */
    public function getHPos() 
    {
        return $this->_hPos;
    }
    
    /**
     * Get Vertical Position of Element 
     * @return int
     */
    public function getVPos() 
    {
        return $this->_vPos;
    }

    /**
     * Get Height of Element 
     * @return int
     */
    public function getHeight() 
    {
        return $this->_height;
    }
    
    /**
     * Get Width of Element 
     * @return int
     */
    public function getWidth() 
    {
        return $this->_width;
    }
}
