<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once VENDORPATH . "/Captcha/autoload.php";


include(VENDORPATH . '/Captcha/CaptchaBuilderInterface.php');
include(VENDORPATH . '/Captcha/PhraseBuilderInterface.php');
include(VENDORPATH . '/Captcha/CaptchaBuilder.php');
include(VENDORPATH . '/Captcha/PhraseBuilder.php');
use Gregwar\Captcha\CaptchaBuilder;

class Captcha
{
    public function output()
    {
        $app = CaptchaBuilder::create();
        header('Content-type: image/jpeg');
        $app->setDistortion(false)->setMaxFrontLines(0)->setMaxFrontLines(1)->build()->output();
        return $app->getPhrase();
    }
}
