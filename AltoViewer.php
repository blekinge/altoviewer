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
 
class AltoViewer 
{
    protected $_altoDir;
    
    protected $_imageDir;
    
    protected $_altoDom;
    
    protected $_imageSize;
    
    protected $_fileId;
    
    protected $_vScale;
    
    protected $_hScale;
    
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
    
    protected function _loadAlto($altoFilename) 
    {
        $this->_altoDom = new DOMDocument;
        $this->_altoDom->load($altoFilename);    
    }

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

    public function getPrintSpace() 
    {
        $printSpace = $this->_altoDom->getElementsByTagName('PrintSpace');
        $p = new AltoElement($printSpace->item(0));
        $p->scale($this->_vScale, $this->_hScale);
        return $p;
    }
    protected function _setImageSize() 
    {
        $this->_imageSize = getimagesize(($this->_imageDir . DIRECTORY_SEPARATOR . $this->_fileId . '.tif.png'));
    }
    
    public function getImageSize() 
    {
        return $this->_imageSize;
    }
    
    public function render() 
    {
    }
    
    
}

$vScale = $_REQUEST['vScale'];
$hScale = $_REQUEST['hScale'];
$image = $_REQUEST['image'];

$showStrings =  (bool) $_REQUEST['strings'];
$showTextLines = (bool) $_REQUEST['textLines'];
$showTextBlocks = (bool) $_REQUEST['textBlocks'];
$showPrintSpace = (bool) $_REQUEST['printSpace'];

$altoViewer = new AltoViewer('./alto', './images', $image, $vScale, $hScale);
$altoViewer->render();
$imageSize = $altoViewer->getImageSize();
$strings = $altoViewer->getStrings();
$textLines = $altoViewer->getTextLines();
$textBlocks = $altoViewer->getTextBlocks();
$printSpace = $altoViewer->getPrintSpace();

$scaledHeight = $imageSize[1] * $vScale;
$scaledWidth = $imageSize[0] * $hScale;

?>

<html>
    <body>
        <style>
            #image {
                position: absolute;
                border: solid 1px #000;
            }
            .highlight-string {
                border: solid 1px #f0f;
                position: absolute;
                background-color: #00C;
                /* IE */
                filter:alpha(opacity=50); 
                /*Mozilla / Firefox */
                -moz-opacity: 0.5;
                /* Safari and Konqueror */
                -khtml-opacity: 0.5;
                /* CSS 3*/
                opacity: 0.5;
            }
            .highlight-line {
                border: solid 1px #f00;
                position: absolute;
                background-color: #99FF99;
                /* IE */
                filter:alpha(opacity=50); 
                /*Mozilla / Firefox */
                -moz-opacity: 0.5;
                /* Safari and Konqueror */
                -khtml-opacity: 0.5;
                /* CSS 3*/
                opacity: 0.5;
            }
            .highlight-block {
                border: solid 1px #1f1;
                position: absolute;
                background-color: #FFCCFF;
                /* IE */
                filter:alpha(opacity=50); 
                /*Mozilla / Firefox */
                -moz-opacity: 0.5;
                /* Safari and Konqueror */
                -khtml-opacity: 0.5;
                /* CSS 3*/
                opacity: 0.5;
            }
            .highlight-printspace {
                border: solid 1px #00f;
                position: absolute;
                background-color: #00CCFF;
                /* IE */
                filter:alpha(opacity=50); 
                /*Mozilla / Firefox */
                -moz-opacity: 0.5;
                /* Safari and Konqueror */
                -khtml-opacity: 0.5;
                /* CSS 3*/
                opacity: 0.5;
            }
        </style>
        <div id="info">
            <table>
                <tr>
                    <th>Image</th> <td><?php echo $image; ?></td>
                </tr>
                <tr>
                    <th>vScale</th> <td><?php echo $vScale; ?></td>
                </tr>
                <tr>
                    <th>hScale</th> <td><?php echo $hScale; ?></td>
                </tr>
            </table>
        </div>
        <div id="image">
            <img 
                src="images/<?php echo $image; ?>.tif.png" 
                width="<?php echo $scaledWidth; ?>" 
                height="<?php echo $scaledHeight; ?>" />
            
            <?php if ($showStrings) { ?>
                <?php foreach ($strings as $string) { ?>
                    <div class="highlight-string" 
                        style=" left: <?php echo $string->getHPos(); ?>px; 
                                top: <?php echo $string->getVPos(); ?>px; 
                                width: <?php echo $string->getWidth(); ?>px; 
                                height: <?php echo $string->getHeight(); ?>px; 
                                filter: alpha(opacity=50)" >
                    </div>
                <?php } ?>
            <?php } ?>
            
            <?php if ($showTextLines) { ?>
                <?php foreach ($textLines as $textLine) { ?>
                    <div class="highlight-line" 
                        style=" left: <?php echo $textLine->getHPos(); ?>px; 
                                top: <?php echo $textLine->getVPos(); ?>px; 
                                width: <?php echo $textLine->getWidth(); ?>px; 
                                height: <?php echo $textLine->getHeight(); ?>px; 
                                filter: alpha(opacity=50)" >
                    </div>
                <?php } ?>
            <?php } ?>
            
            <?php if ($showTextBlocks) { ?>
                <?php foreach ($textBlocks as $textBlock) { ?>
                    <div class="highlight-block" 
                        style=" left: <?php echo $textBlock->getHPos(); ?>px; 
                                top: <?php echo $textBlock->getVPos(); ?>px; 
                                width: <?php echo $textBlock->getWidth(); ?>px; 
                                height: <?php echo $textBlock->getHeight(); ?>px; 
                                filter: alpha(opacity=50)" >
                    </div>
                <?php } ?>
            <?php } ?>

            <?php if ($showPrintSpace) { ?>
                <div class="highlight-printspace" 
                    style=" left: <?php echo $printSpace->getHPos(); ?>px; 
                            top: <?php echo $printSpace->getVPos(); ?>px; 
                            width: <?php echo $printSpace->getWidth(); ?>px; 
                            height: <?php echo $printSpace->getHeight(); ?>px; 
                            filter: alpha(opacity=50)" >
                </div>
            
            <?php } ?>


        </div>
    </body>
</html>