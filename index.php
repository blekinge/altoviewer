<?php

/**
 * ALTO File Viewer
 *
 * @package    AltoViewer
 * @author     Dan Field <dof@llgc.org.uk>
 * @copyright  Copyright (c) 2010 National Library of Wales / Llyfrgell Genedlaethol Cymru. 
 * @link       http://www.llgc.org.uk
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 * @version    $Id$
 * @link       http://www.loc.gov/standards/alto/
 * 
 **/

require_once 'lib/AltoViewer.php';
 
$vScale = $_REQUEST['vScale'];
$hScale = $_REQUEST['hScale'];
$image = $_REQUEST['image'];

$altoViewer = new AltoViewer(   '/Users/dof/Development/Source/altoview/alto', 
                                '/Users/dof/Development/Source/altoview/images', 
                                $image, $vScale, $hScale);
$imageSize = $altoViewer->getImageSize();
$strings = $altoViewer->getStrings();
$textLines = $altoViewer->getTextLines();
$textBlocks = $altoViewer->getTextBlocks();
$printSpace = $altoViewer->getPrintSpace();

$scaledHeight = $imageSize[1] * $vScale;
$scaledWidth = $imageSize[0] * $hScale;

?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>          
    </head>
    <body>
        <h4>Toggle Layers</h4>
        <button id="strings" >Strings</button><br />
        <button id="lines" >TextLine</button><br />
        <button id="blocks" >TextBlock</button><br />
        <button id="printspace" >PrintSpace</button><br />
        
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
            <?php foreach ($strings as $string) { ?>
                <div class="highlighter" id="highlight-string" 
                    style=" left: <?php echo $string->getHPos(); ?>px; 
                            top: <?php echo $string->getVPos(); ?>px; 
                            width: <?php echo $string->getWidth(); ?>px; 
                            height: <?php echo $string->getHeight(); ?>px; 
                            filter: alpha(opacity=50)" >
                </div>
            <?php } ?>
            <script>
                $("button[id*=strings]").click(function () {
                $("div[id*=highlight-string]").toggle();
                });    
            </script>
            
            <?php foreach ($textLines as $textLine) { ?>
                <div class="highlighter" id="highlight-line" 
                    style=" left: <?php echo $textLine->getHPos(); ?>px; 
                            top: <?php echo $textLine->getVPos(); ?>px; 
                            width: <?php echo $textLine->getWidth(); ?>px; 
                            height: <?php echo $textLine->getHeight(); ?>px; 
                            filter: alpha(opacity=50)" >
                </div>
            <?php } ?>
            <script>
                $("button[id*=lines]").click(function () {
                $("div[id*=highlight-line]").toggle();
                });    
            </script>
        
            <?php foreach ($textBlocks as $textBlock) { ?>
                <div class="highlighter" id="highlight-block" 
                    style=" left: <?php echo $textBlock->getHPos(); ?>px; 
                            top: <?php echo $textBlock->getVPos(); ?>px; 
                            width: <?php echo $textBlock->getWidth(); ?>px; 
                            height: <?php echo $textBlock->getHeight(); ?>px; 
                            filter: alpha(opacity=50)" >
                </div>
            <?php } ?>
            <script>
                $("button[id*=blocks]").click(function () {
                $("div[id*=highlight-block]").toggle();
                });    
            </script>
            
            <div class="highlighter" id="highlight-printspace" 
                style=" left: <?php echo $printSpace->getHPos(); ?>px; 
                        top: <?php echo $printSpace->getVPos(); ?>px; 
                        width: <?php echo $printSpace->getWidth(); ?>px; 
                        height: <?php echo $printSpace->getHeight(); ?>px; 
                        filter: alpha(opacity=50)" >
            </div>
            <script>
                $("button[id*=printspace]").click(function () {
                $("div[id*=highlight-printspace]").toggle();
                });    
            </script>
            
                    
        </div>
    </body>
</html>