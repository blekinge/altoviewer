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
        $this->_id = $element->getAttribute('default:ID');
        $this->_hPos = $element->getAttribute('default:HPOS');
        $this->_vPos = $element->getAttribute('default:VPOS');
        $this->_height = $element->getAttribute('default:HEIGHT');
        $this->_width = $element->getAttribute('default:WIDTH');
    }
    
    public function scale($vScale, $hScale) 
    {
        $this->_hPos   = floor($this->_hPos  * $hScale);
        $this->_vPos   = floor($this->_vPos  * $vScale);
        $this->_height = ceil($this->_height * $vScale);
        $this->_width  = ceil($this->_width  * $hScale);
    }
    
    public function getHPos() 
    {
        return $this->_hPos;
    }
    
    public function getVPos() 
    {
        return $this->_vPos;
    }

    public function getHeight() 
    {
        return $this->_height;
    }
    
    public function getWidth() 
    {
        return $this->_width;
    }
}