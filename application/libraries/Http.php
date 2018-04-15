<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * HttpTool
 */
class Http
{
    public static function get($url, $param = array(), $headers = array(), $cookie = '', $referer = '', $agent = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36')
    {
        if (!empty($param)) {
            $url .= strstr('?', $url) ? '&' : '?';
            $url .= http_build_query($param);
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_USERAGENT, $agent);
        curl_setopt($curl, CURLOPT_REFERER, $referer);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, false);
        curl_setopt($curl, CURLOPT_COOKIE, $cookie);

        $content = curl_exec($curl);
        curl_close($curl);

        return $content;
    }

    public static function post($url, $param = '', $referer = '', $agent = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', $headers = array())
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); //  
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); //
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_USERAGENT, $agent);
        curl_setopt($curl, CURLOPT_REFERER, $referer);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        if (is_array($param)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($param));
        } else {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
        }

        $content = curl_exec($curl);
        curl_close($curl);

        return $content;
    }

    public static function getHeader($url, $param = array())
    {
        if (!empty($param)) {
            $url .= strstr('?', $url) ? '&' : '?';
            $url .= http_build_query($param);
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($curl);
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($content, 0, $headerSize);
        return $header;
    }


    public static function postMultipart($url, $param)
    {
        list($boundary, $multipartbody) = self::build_http_query_multi($param);
        $headers[] = 'Content-Type: multipart/form-data; boundary=' . $boundary;
        return self::post($url, $multipartbody, '', '', $headers);
    }

    /**
     * 组织multipart/form-data格式的数据
     */
    public static function build_http_query_multi($params)
    {
        if (!$params) {
            return '';
        }

        uksort($params, 'strcmp');
        $pairs = array();
        $boundary = '';
        $boundary = uniqid('------------------');
        $MPboundary = '--' . $boundary;
        $endMPboundary = $MPboundary . '--';
        $multipartbody = '';

        foreach ($params as $parameter => $value) {
            if (in_array($parameter, array('pic', 'image'))) {
                $content = $value;
                $filename = 'xxx';
                $multipartbody .= $MPboundary . "\r\n";
                $multipartbody .= 'Content-Disposition: form-data; name="' . $parameter . '"; filename="' . $filename . '"' . "\r\n";
                $multipartbody .= "Content-Type: image/unknown\r\n\r\n";
                $multipartbody .= $content . "\r\n";
            } else {
                $multipartbody .= $MPboundary . "\r\n";
                $multipartbody .= 'content-disposition: form-data; name="' . $parameter . "\"\r\n\r\n";
                $multipartbody .= $value . "\r\n";
            }
        }

        $multipartbody .= $endMPboundary;
        return array($boundary, $multipartbody);
    }

    public static function downloadFile($filePath)
    {
        if (is_file($filePath)) {
            header("HTTP/1.0 200 OK");
            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment;filename=" . urlencode(basename($filePath)));
            header("Pragma:");
            header("Cache-Control: public");
            header("Expires: " . gmdate("D, d M Y H:i:s", strtotime("+2 years")) . " GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s", filemtime($filePath)) . " GMT");
            header("Content-Length: " . filesize($filePath));
            echo file_get_contents($filePath);
            exit;
        }
    }

    public static function addUrlQueryStr($url)
    {
        $__GET = $_GET;
        unset($__GET['module']);
        unset($__GET['action']);
        unset($__GET['_vercode']);
        if (!empty($__GET)) {
            $querystr = http_build_query($__GET);
            if (strpos($url, "?")) {
                $url .= "&" . $querystr;
            } else {
                $url .= "?" . $querystr;
            }
        }
        return $url;
    }

    public static function isIphone()
    {
        return stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false;
    }

    public static function isWeixin()
    {
        return stripos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false;
    }
    

    public static function isMQQBrowser()
    {
        return stripos($_SERVER['HTTP_USER_AGENT'], 'MQQBrowser') !== false;
    }
    
    public static function isUCBrowser()
    {
        return stripos($_SERVER['HTTP_USER_AGENT'], 'UCBrowser') !== false;
    }

    public static function isCrawler()
    {
        if (ini_get('browscap')) {
            $browser = get_browser(NULL, true);
            if ($browser['crawler']) {
                return true;
            }
        } else if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $agent = $_SERVER['HTTP_USER_AGENT'];
            $crawlers = array(
                "/Googlebot/",
                "/Yahoo! Slurp;/",
                "/msnbot/",
                "/Mediapartners-Google/",
                "/Scooter/",
                "/Yahoo-MMCrawler/",
                "/FAST-WebCrawler/",
                "/Yahoo-MMCrawler/",
                "/Yahoo! Slurp/",
                "/FAST-WebCrawler/",
                "/FAST Enterprise Crawler/",
                "/grub-client-/",
                "/MSIECrawler/",
                "/NPBot/",
                "/NameProtect/i",
                "/ZyBorg/i",
                "/worio bot heritrix/i",
                "/Ask Jeeves/",
                "/libwww-perl/i",
                "/Gigabot/i",
                "/bot@bot.bot/i",
                "/SeznamBot/i",
                "/360webscan/",
                '/eagle_eye/',
                '/webscan/',
                '/360-SEC/',
            );
            foreach ($crawlers as $c) {
                if (preg_match($c, $agent)) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function genSign()
    {
        return TIMESTAMP . '_' . md5(TIMESTAMP . '_' . SYS_CODE);
    }

    public static function checkSign($str)
    {
        list($timestamp, $sign) = explode('_', $str);
        if(TIMESTAMP - $timestamp > 60 || md5($timestamp . '_' . SYS_CODE) != $sign) {
            return false;
        }
        return true;
    }
}
