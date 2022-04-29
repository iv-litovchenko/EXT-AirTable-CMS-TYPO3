<?php
/**
 * @author        Ivan Litovchenko (i-litovan@yandex.ru)
 * @subpackage    tx_spiderindexing_output
 *
 * This package includes all hook implementations.
 *
 * // 1. Ставим префикс для страниц
// 'defaultToHTMLsuffixOnPrev' => 1,
// 'acceptHTMLsuffix' => 1,

// 2 добавляем "encodeSpURL_postProc" + "decodeSpURL_preProc"
// 3 убираем абсолютный путь - ставим ./ typoscript
// 4 рассоединяем CSS , JS

function user_encodeSpURL_postProc(&$params, &$ref) {
$params['URL'] = str_replace('http://arclg.iv-litovchenko.ru/','',$params['URL']);
$params['URL'] = str_replace('/', '@', $params['URL']);
$params['URL'] = preg_replace('/^.@/is', '', $params['URL']);
$params['URL'] = "./".$params['URL'];
}

function user_decodeSpURL_preProc(&$params, &$ref) {
$params['URL'] = str_replace('@', '/', $params['URL']);
}

// Настройка realurl
$TYPO3_CONF_VARS['EXTCONF']['realurl']['encodeSpURL_postProc'] = array('user_encodeSpURL_postProc');
$TYPO3_CONF_VARS['EXTCONF']['realurl']['decodeSpURL_preProc'] = array('user_decodeSpURL_preProc');
 *
 *
 */

ini_set('max_execution_time', 0);
class tx_spiderindexing
{

    public $content = "";
    public $allListUrl = array();
    public $allListUrlWithParent;
    public $allListUrlInfo; // mime_type, http_code
    public $allListUrlDeleted;

    public $startUrlScheme;
    public $startUrlHost;

    public $scriptStartTime;
    public $scriptEndTime;
    public $scriptmomoryGetUsageEnd;

    /**
     * Function executed from the Scheduler.
     * Hides all content elements of a page
     *
     * @return    boolean    TRUE if success, otherwise FALSE
     */
    public function execute($startUrlHost = '')
    {
        $success = false;
        $data = array();

        $this->sitestartpage = $startUrlHost;


        $parseStartUrl = parse_url($this->sitestartpage);
        $this->startUrlScheme = $parseStartUrl['scheme'];
        $this->startUrlHost = $parseStartUrl['host'];


        #if ($parseStartUrl['port'] != 0) {
        #    $this->startUrlHost .= ":" . $parseStartUrl['port'];
        #} // также добавляем порт

        $content = $this->crawler_getContent($this->sitestartpage);
        $this->crawler_get_links($content);

        // Сохраняем страницы
        #foreach($this->allListUrl as $k => $v){
        #print 'Save page: '.$v.'<br />';
        #}
        print 'Finish';
    }

    public function crawler_getContent($url)
    {
        #if (count($this->allListUrl) > 3) {
        #    print "<pre>";
        #    print_r($this->allListUrl);
        #    exit();
        #}

        # $this->removeUrlIndexFromDatabseOnMd5($url); // удаляем url из БД
        # return file_get_contents($url);

        $content = $this->file_get_contents_curl($url);

        $fileName = str_replace('http://arclg.iv-litovchenko.ru/./','',$url);
        file_put_contents('_site-save_/'.$fileName, $content);
        print 'Save page: '.$url.'<br />';

        return $content;
    }

    public function dom($content, $tag, $attr)
    {
        $dom = new DOMDocument;
        $dom->loadHtml($content);
        $result = array();
        $nodes = $dom->getElementsByTagName($tag);
        foreach ($nodes as $node) {
            $value = $node->getAttribute($attr);
            $result[md5($value)] = str_replace('http://arclg.iv-litovchenko.ru/','./',urldecode($value));
        }
        return $result;
    }

    public function crawler_get_links($content, $parentUrl = false)
    {
        if (!empty($content)) {
            // preg_match_all('/(alt|href|src)=("[^"]*")/is', $content, $links);


            $result = array();
            $r1 = $this->dom($content,'a','href');
            $r2 = $this->dom($content,'img','src');
            $r3 = $this->dom($content,'link','href');
            $r4 = $this->dom($content,'script','src');

            $result = $r1 + $r2 + $r3 + $r4;

            #print "<pre>";
            #print_r($result);
            #exit();

            foreach ($result as $key => $newurl) {
                if (!empty($newurl)) {
                    $temp = parse_url($newurl);


                    #if ($temp['host'] == $this->startUrlHost) { // $temp['host'] == null or // $GLOBALS['_SERVER']['SERVER_NAME']){

                    # Из-за того, что нет возможности использовать $GLOBALS['_SERVER'];
                    # $urlHost = $GLOBALS['_SERVER']['HTTP_X_FORWARDED_PROTO'] ."://". $GLOBALS['_SERVER']['HTTP_HOST'] . "/";
                    $urlHost = $this->startUrlScheme . "://" . $this->startUrlHost . "/";
                    $resultUrl = $urlHost . preg_replace('/^\//', "", $temp['path']);

                    // Проверяем путь по файлу...
                    $temp2 = parse_url($resultUrl);
                    $temp2 = $temp2['path'];
                    $temp2 = trim($temp2,'/'); // удаляем слэш слева если он есть
                    $temp2 = preg_replace('/^./is','',$temp2);
                    if (!file_exists($GLOBALS['_SERVER']['DOCUMENT_ROOT'].$temp2)) { // при услови, что по данному адресу не

                        if(preg_match('/.html$/is',$temp2)){
                            if ($this->allListUrl[md5($resultUrl)] == null) {
                                $this->allListUrl[md5($resultUrl)] = $resultUrl;
                                $content = $this->crawler_getContent($resultUrl);
                                $this->crawler_get_links($content);
                            }
                        }

                    } elseif(trim($temp2) != '' && file_exists($GLOBALS['_SERVER']['DOCUMENT_ROOT'].$temp2)) {
                        $dirName = dirname($GLOBALS['_SERVER']['DOCUMENT_ROOT'].$temp2);
                        $dirName2 = dirname($temp2);
                        #$newDirName = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/_site-save_'.$dirName2;

                        #print $dirName."<br />";
                        #print $newDirName."<br />";

                        $origFile = ltrim($temp2,'/');
                        $newFile = '_site-save_'.$temp2;

                        #print ltrim($origFile,'/')."<br />";
                        #print $newFile."<br />";

                        // @mkdir($newDirName,777,true);
                        $this->createUploadDirectories('_site-save_'.$dirName2);
                        copy($origFile, $newFile);
                        print 'Copy file: '.$origFile.'<br />';


                        if(preg_match('/.css$/is',$temp2)){
                            if ($this->allListUrl[md5($resultUrl)] == null) {
                                #$this->allListUrl[md5($resultUrl)] = $resultUrl;

                                #print $content;
                                #exit();
                                #$content = $this->crawler_getContent($resultUrl);
                                #$this->crawler_get_links($content);
                            }
                        }

                    }
                }
            }
        }
    }

    function createUploadDirectories($upload_path=null){
        if($upload_path==null) return false;
        $upload_directories = explode('/',$upload_path);
        $createDirectory = array();
        foreach ($upload_directories as $upload_directory){
            $createDirectory[] = $upload_directory;
            $createDirectoryPath = implode('/',$createDirectory);
            if(!file_exists($createDirectoryPath)){
                mkdir($createDirectoryPath);// Create the folde if not exist and giv
            }
            // print $createDirectoryPath .'<br />';
        }
        return true;
    }

    public function file_get_contents_curl($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // запрещяем 301/302-редиректы
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // times out after 4s
        curl_setopt($ch, CURLOPT_USERAGENT, "TYPO3 Spider Bot"); // who am i

        // curl_setopt($ch, CURLOPT_POST, 1); // set POST method
        // curl_setopt($ch, CURLOPT_POSTFIELDS, "url=index%3Dbooks&field-keywords=PHP+MYSQL"); // add POST fields
        // curl_setopt($curl, CURLOPT_USERAGENT, 'Opera 10.00'); //представляемся серверу браузером Opera версии 10.00

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    // на 404-ошибку не проверяем!, т.к. исключение пойдет в output!
    /*
    public function get_url_info($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE); // запрещяем 301/302-редиректы
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "TYPO3 Spider Bot"); // who am i
        curl_exec($ch);
        return array(
            'http_code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
            'mime_type' => curl_getinfo($ch, CURLINFO_CONTENT_TYPE)
        );
    }
    */

    public function crawler_get_AllListUrl()
    {
        return $this->allListUrl;
    }

    public function crawler_get_AllListUrlWithParent()
    {
        return $this->allListUrlWithParent;
    }


}


// $a = new tx_spiderindexing;
// $a->execute('http://arclg.iv-litovchenko.ru/home.html');


?>
