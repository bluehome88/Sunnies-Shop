<?php
/**
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class TbcmsTwoOfferBannerImageResize
{
    // *** Class variables
    private $image;
    private $width;
    private $height;
    private $imageResized;
    public function __construct($fileName)
    {
        // *** Open up the file
        $this->image = $this->openImage($fileName);
        // *** Get width and height
        $this->width  = imagesx($this->image);
        $this->height = imagesy($this->image);
    }
    ## --------------------------------------------------------
    private function openImage($file)
    {
        // *** Get extension
        $extension = Tools::strtolower(strrchr($file, '.'));
        switch ($extension) {
            case '.jpg':
            case '.jpeg':
                $img = @imagecreatefromjpeg($file);
                break;
            case '.gif':
                $img = @imagecreatefromgif($file);
                break;
            case '.png':
                $img = @imagecreatefrompng($file);
                break;
            default:
                $img = false;
                break;
        }
        return $img;
    }
    ## --------------------------------------------------------
    public function resizeImage($newWidth, $newHeight, $type = 0)
    {
         $option = '';
        switch ($type) {
            case 1:
                $option = 'exact';
                break;
            case 2:
                $option = 'portrait';
                break;
            case 3:
                $option = 'landscape';
                break;
            case 4:
                $option = 'auto';
                break;
            case 5:
                $option = 'crop';
                break;
            default:
                $option = 'exact';
                break;
        }
        // *** Get optimal width and height - based on $option
        $optionArray = $this->getDimensions($newWidth, $newHeight, $option);
        $optimalWidth  = $optionArray['optimalWidth'];
        $optimalHeight = $optionArray['optimalHeight'];
        // *** Resample - create image canvas of x, y size
        $this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);
        {// Make the background transparent
             imagealphablending($this->imageResized, false);
                 imagesavealpha($this->imageResized, true);
        }
        $tmp = $this->imageResized;
        imagecopyresampled($tmp, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);
        // *** if option is 'crop', then crop too
        if ($option == 'crop') {
            $this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
        }
    }
    ## --------------------------------------------------------
    private function getDimensions($newWidth, $newHeight, $option)
    {
        switch ($option) {
            case 'exact':
                $optimalWidth = $newWidth;
                $optimalHeight= $newHeight;
                break;
            case 'portrait':
                $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                $optimalHeight= $newHeight;
                break;
            case 'landscape':
                $optimalWidth = $newWidth;
                $optimalHeight= $this->getSizeByFixedWidth($newWidth);
                break;
            case 'auto':
                $optionArray = $this->getSizeByAuto($newWidth, $newHeight);
                $optimalWidth = $optionArray['optimalWidth'];
                $optimalHeight = $optionArray['optimalHeight'];
                break;
            case 'crop':
                $optionArray = $this->getOptimalCrop($newWidth, $newHeight);
                $optimalWidth = $optionArray['optimalWidth'];
                $optimalHeight = $optionArray['optimalHeight'];
                break;
        }
        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }
    ## --------------------------------------------------------
    private function getSizeByFixedHeight($newHeight)
    {
        $ratio = $this->width / $this->height;
        $newWidth = $newHeight * $ratio;
        return $newWidth;
    }
    private function getSizeByFixedWidth($newWidth)
    {
        $ratio = $this->height / $this->width;
        $newHeight = $newWidth * $ratio;
        return $newHeight;
    }
    private function getSizeByAuto($newWidth, $newHeight)
    {
        if ($this->height < $this->width) {
            // *** Image to be resized is wider (landscape)
            $optimalWidth = $newWidth;
            $optimalHeight= $this->getSizeByFixedWidth($newWidth);
        } elseif ($this->height > $this->width) {
            // *** Image to be resized is taller (portrait)
            $optimalWidth = $this->getSizeByFixedHeight($newHeight);
            $optimalHeight= $newHeight;
        } else {
            // *** Image to be resizerd is a square
            if ($newHeight < $newWidth) {
                $optimalWidth = $newWidth;
                $optimalHeight= $this->getSizeByFixedWidth($newWidth);
            } elseif ($newHeight > $newWidth) {
                $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                $optimalHeight= $newHeight;
            } else {
                // *** Sqaure being resized to a square
                $optimalWidth = $newWidth;
                $optimalHeight= $newHeight;
            }
        }
        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }
    ## --------------------------------------------------------
    private function getOptimalCrop($newWidth, $newHeight)
    {
        $heightRatio = $this->height / $newHeight;
        $widthRatio  = $this->width /  $newWidth;
        if ($heightRatio < $widthRatio) {
            $optimalRatio = $heightRatio;
        } else {
            $optimalRatio = $widthRatio;
        }
        $optimalHeight = $this->height / $optimalRatio;
        $optimalWidth  = $this->width  / $optimalRatio;
        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }
    ## --------------------------------------------------------
    private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight)
    {
        // *** Find center - this will be used for the crop
        $cropStartX = ( $optimalWidth / 2) - ( $newWidth /2 );
        $cropStartY = ( $optimalHeight/ 2) - ( $newHeight/2 );
        $crop = $this->imageResized;
        //imagedestroy($this->imageResized);
        // *** Now crop from center to exact requested size
        $this->imageResized = imagecreatetruecolor($newWidth, $newHeight);
        $tmp = $this->imageResized;
        imagecopyresampled($tmp, $crop, 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight, $newWidth, $newHeight);
    }
    ## --------------------------------------------------------
    public function saveImage(&$savePath, $showImage = false, $imageQuality = "100")
    {
        // *** Get extension
        $tmp = explode('.', $savePath);
        $extension = end($tmp);
        $extension = Tools::strtolower('.'.$extension);
        if (!$showImage) {
            switch ($extension) {
                case '.jpg':
                case '.jpeg':
                    if (imagetypes() & IMG_JPG) {
                        @imagejpeg($this->imageResized, $savePath, $imageQuality);
                    }
                    break;
                case '.gif':
                    if (imagetypes() & IMG_GIF) {
                        @imagegif($this->imageResized, $savePath);
                    }
                    break;
                case '.png':
                    // *** Scale quality from 0-100 to 0-9
                    $scaleQuality = round(($imageQuality/100) * 9);
                    // *** Invert quality setting as 0 is best, not 9
                    $invertScaleQuality = 9 - $scaleQuality;
                    if (imagetypes() & IMG_PNG) {
                         @imagepng($this->imageResized, $savePath, $invertScaleQuality);
                    }
                    break;
                // ... etc
                default:
                    // *** No extension - No save.
                    break;
            }
        } else {//for show image
            switch ($extension) {
                case '.jpg':
                case '.jpeg':
                        header('Content-Type: image/jpg');
                        @imagejpeg($this->imageResized);
                    break;
                case '.gif':
                        header('Content-Type: image/gif');
                        @imagegif($this->imageResized);
                    break;
                case '.png':
                        header('Content-Type: image/png');
                        @imagepng($this->imageResized);
                    break;
            }
        }
        imagedestroy($this->imageResized);
    }
    /*public function showImage($savePath, $imageQuality="100")
    {
        // *** Get extension
        $extension = strrchr($savePath, '.');
            $extension = Tools::strtolower($extension);
        //header('Content-Type: image/jpeg');
        switch($extension)
        {
            case '.jpg':
            case '.jpeg':
                if (imagetypes() & IMG_JPG) {
                    imagejpeg($this->imgeResized, $savePath, $imageQuality);
                }
                break;
            case '.gif':
                if (imagetypes() & IMG_GIF) {
                    imagegif($this->imageResized, $savePath);
                }
                break;
            case '.png':
                // *** Scale quality from 0-100 to 0-9
                $scaleQuality = round(($imageQuality/100) * 9);
                // *** Invert quality setting as 0 is best, not 9
                $invertScaleQuality = 9 - $scaleQuality;
                if (imagetypes() & IMG_PNG) {
                     imagepng($this->imageResized, $savePath, $invertScaleQuality);
                }
                break;
            // ... etc
            default:
                // *** No extension - No save.
                break;
        }
        imagedestroy($this->imageResized);
    }*/
}
