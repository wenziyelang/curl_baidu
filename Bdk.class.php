<?php

/**
 * @link https://github.com/xuepengdong/
 * @author Xuepeng Dong <wenziyelang@foxmail.com>
 * @since 1.0
 */
class BaiduKeyWordRank {

    private $htmlContents = null;
    public $rankNum = 0;
    public $keyWordlineNum = 0;

    public function __construct() {
        set_time_limit(0);
        error_reporting(0);
    }

    /**
     * 返回类中所有方法的返回值
     * @access string $keyword
     * @access string $siteUrl
     * @return array
     */
    public function KeyWrodReturn($keyword = null, $siteUrl = null) {
        $baiduUrl = "http://www.baidu.com/s?wd=" . urlencode($keyword) . "&pn=0&tn=baiduhome_pg&rn=100&ie=utf-8&usm=2";

        $this->htmlContents = $this->getCurl($baiduUrl);

        $empBao = strstr($this->htmlContents, '抱歉，没有找到与');

        if ($empBao) {
            $returnarray['shoulu'] = '无';

            $returnarray['TuiAll'] = '无';

            $returnarray['TuiRank'] = '无';

            $returnarray['zipai'] = '无结果';

            $returnarray['wenzhangNum'] = '无结果';

            return $returnarray;
        }

        $returnShoulu = $this->IncludedNumber($this->htmlContents); //查找当前关键词在百度收录数
        $semArray = $this->getBaiduExtensionData($this->htmlContents, $siteUrl[0]); //查找当前关键词和域名在百度推广占位和当前页面总推广数
        foreach ((array) $siteUrl as $k => $v) {
            $returnPaiming[$k]["ziran"] = $this->NatureSeo($this->htmlContents, $v); //域名的自然排名
        }

        foreach ((array) $siteUrl as $k => $v) {
            $ArticleContents = $this->getCurl('http://www.baidu.com/s?wd=intitle%3A' . urlencode($keyword) . '%20site%3A' . $v . '&pn=0&tn=baiduhome_pg&ie=utf-8');

            $wenzhangNum[$k]["wenzhangNum"] = $this->getBaiduArticleNum($ArticleContents, $v); //intitle:usa site:bailitop.com
        }

        $returnarray['shoulu'] = $returnShoulu;

        $returnarray['TuiAll'] = $semArray['allRank'];

        $returnarray['TuiRank'] = $semArray['rankNum'];

        $returnarray['zipai'] = $returnPaiming;

        $returnarray['wenzhangNum'] = $wenzhangNum;

        return $returnarray;
    }

    /**
     * 查找当前关键词在百度收录数
     * @access string $keyword
     * @access string $siteUrl
     * @return string
     */
    public function IncludedNumber($htmlContens) {
        $rankrules = '/<div[^>]*?class="nums"[^>]*>[\s\S]*?<\/div>/i';

        preg_match_all($rankrules, $htmlContens, $contentRows);

        $keyWordRow = strip_tags($contentRows[0][0]);

        return preg_replace('/\D/u', '', $keyWordRow);
    }

    /**
     * intitle:usa site:bailitop.com
     * @access string $ArticleContents
     * @access string $siteUrl
     * @return string
     */
    private function getBaiduArticleNum($ArticleContents = null, $siteUrl = null) {
        //preg_match_all('/<span[^>]*?class="nums"[^>]*>[\s\S]*?<\/span>/i', $ArticleContents, $ArticleRow);
        preg_match_all('/<div[^>]*?class="nums"[^>]*>[\s\S]*?<\/div>/i', $ArticleContents, $ArticleRow);

        $ArticleRow = strip_tags($ArticleRow[0][0]);

        if (empty($ArticleRow)) {
            $ArticleRow = 0;
        }

        return preg_replace('/\D/u', '', $ArticleRow);
    }

    /**
     * 域名的自然排名
     * @access string $htmlContents
     * @access string $siteUrl
     * @return int
     */
    public function NatureSeo($htmlContents, $siteUrl) {
        $rules = '/(<div[^>]*?class="result-op c-container"[^>]*>[\s\S]*?<\/div>|<span[^>]*?class="g"[^>]*>[\s\S]*?<\/span>|<table[^>]*?class="result"[^>]*>[\s\S]*?<\/table>|<table[^>]*?class="result-op"[^>]*>[\s\S]*?<\/table>|<div[^>]*?class="result-op c-container xpath-log"[^>]*>[\s\S]*?<\/div>|<div[^>]*?class="g"[^>]*>[\s\S]*?<\/div>)/i';

        preg_match_all($rules, $htmlContents, $contentRow);
        $rowNum = 0;

        foreach ($contentRow['0'] as $key => $val) {
            $htmlContents = strip_tags($val);

            $rowNum = $rowNum + 1;

            if (strstr($htmlContents, $siteUrl)) {
                break;
            }

            if ($key == count($contentRow['0']) - 1) {
                $rowNum = '0';
            }
        }

        return $rowNum;
    }

    /**
     * 查找当前关键词和域名在百度推广占位和当前页面总推广数
     * @access string $siteUrl
     * @access string $htmlContents
     * @return array
     */
    private function getBaiduExtensionData($htmlContents, $siteUrl = null) {
        //preg_match_all('/(<table[^>]*?class="EC_mr15 EC_ppim_top ec_pp_f"[^>]*>[\s\S]*?<\/table>|<div[^>]*?class="ec_pp_f ec_pp_top"[^>]*>[\s\S]*?<\/div>|<div[^>]*?class="ec_pp_f ec_pp_top ec_first"[^>]*>[\s\S]*?<\/div>)/i', $this->htmlContents, $contentRow);
        preg_match_all('/(<div[^>]*?class="ec_meta ec_font_small"[^>]*>[\s\S]*?<\/div>|<div[^>]*?class="pl_l_official"[^>]*>[\s\S]*?<\/div>|<td[^>]*?class="f16 EC_PP EC_header EC_tip_handle"[^>]*>[\s\S]*?<\/td>|<td[^>]*?class="EC_PP EC_nowraped EC_url_bottom"[^>]*>[\s\S]*?<\/td>)/i', $htmlContents, $contentRow);

        if (empty($contentRow) || empty($contentRow['0'])) {
            $this->rankNum = 0;

            return false;
        }

        $arrayUnique = array_unique($contentRow['0']);

        $siteUrlExistsArray = array();

        foreach ($arrayUnique as $key => $val) {
            preg_match_all('/(<span[^>]*?[^>]*>[\s\S]*?<\/span>|<span[^>]*?class="ec_url"[^>]*>[\s\S]*?<\/span>)/i', $val, $siteUrlRow);

            if (empty($siteUrlRow) || empty($siteUrlRow['0']['1'])) {
                continue;
            }

            $siteis = strstr($siteUrlRow['0']['1'], "2013");

            if (empty($siteis)) {
                $siteUrlr = $siteUrlRow['0']['1'];
            } else {
                $siteUrlr = $siteUrlRow['0']['0'];
            }

            if (!in_array($siteUrlr, $siteUrlExistsArray)) {
                $siteUrlExistsArray[] = $siteUrlr;

                $contentRowArray[$key] = strip_tags($val);
            }
        }

        if (empty($contentRowArray)) {
            $this->rankNum = 0;

            return false;
        }

        foreach ($contentRowArray as $key => $val) {
            $this->rankNum = $this->rankNum + 1;

            if (strstr($val, $siteUrl)) {

                break;
            }

            if ($key == count($contentRowArray) - 1) {
                $this->rankNum = 0;
            }
        }
        $contentRowArrayNum = count($contentRowArray);

        if (empty($contentRowArrayNum)) {
            $contentRowArrayNum = 0;
        }

        $BaiduExtension['rankNum'] = $this->rankNum;

        $BaiduExtension['allRank'] = $contentRowArrayNum;

        return $BaiduExtension;
    }

    /**
     * @access string $durl
     * @return string
     */
    function getCurl($durl) {
        if (function_exists('curl_init')) {

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $durl);

            curl_setopt($ch, CURLOPT_TIMEOUT, 5);

            //curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);

            curl_setopt($ch, CURLOPT_REFERER, "http://www.baidu.com/");

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");

            //curl_setopt($c,CURLOPT_USERAGENT,'Mozilla/5.0 (Linux; U; Android 2.3.7; zh-cn; c8650 Build/GWK74) AppleWebKit/533.1 (KHTML, like Gecko)Version/4.0 MQQBrowser/4.5 Mobile Safari/533.1s');

            $r = curl_exec($ch);

            curl_close($ch);

            return $r;
        }
    }

}

?>