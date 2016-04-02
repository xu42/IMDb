<?php
/**
 * @file        OneTitleMsg.php
 * @author      xu42 <xu42.cn@gmail.com>
 * @link        http://blog.xu42.cn/
 */

/**
 * Class OneTitleMsg
 *
 * 一个在IMDb的title的信息
 */
class OneTitleMsg {

    /**
     * 类常量，title 信息页的Url前缀
     */
    const one_title_imdb_prefix_url = 'http://www.imdb.com/title/';

    /**
     * 类常量，title 在豆瓣的json信息页Url前缀
     */
    const one_title_douban_prefix_url = 'https://movie.douban.com/j/subject_suggest?q=';

    /**
     * 私有变量，title 在IMDb的唯一标识，eg: tt0111161
     * @var string
     */
    private $_title = '';

    /**
     * 私有变量，title 信息页的Url，eg: http://www.imdb.com/title/tt0111161/?ref_=nv_mv_dflt_1
     * @var string
     */
    private $_one_title_imdb_url = '';

    /**
     * 私有变量，title 在豆瓣的json信息页的Url，eg: https://movie.douban.com/j/subject_suggest?q=tt0111161
     * @var string
     */
    private $_one_title_douban_url = '';

    /**
     * 私有变量，title 信息页的网页源代码
     * @var string
     */
    private $_one_title_webpage = '';

    /**
     * 私有变量， title 在豆瓣json格式数据
     * @var string
     */
    private $_one_title_douban = '';

    /**
     * OneTitleMsg constructor.
     */
    public function __construct($title)
    {
        $this->_title = $title;
        $this->_one_title_imdb_url = self::one_title_imdb_prefix_url . $title . '/?ref_=nv_mv_dflt_1';
        $this->_one_title_douban_url = self::one_title_douban_prefix_url . $title;
    }

    /**
     * 获取一个title的信息页源码
     * @return mixed
     */
    private function getWebpageOfOneTitle()
    {
        $request_headers_imdb = [
            'Host: www.imdb.com',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Upgrade-Insecure-Requests: 1',
            'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.108 Safari/537.36',
            'Referer: http://www.imdb.com',
            'Accept-Language: zh-CN,zh;q=0.8'
        ];
        $request_headers_douban = [
            'Host: movie.douban.com',
            'method: GET',
            'accept: */*',
            'accept-language: zh-CN,zh;q=0.8',
            'referer: https://movie.douban.com/',
            'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.108 Safari/537.36',
            'x-requested-with: XMLHttpRequest'
        ];

        $ch_imdb = curl_init();
        curl_setopt($ch_imdb, CURLOPT_URL, $this->_one_title_imdb_url);
        curl_setopt($ch_imdb, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch_imdb, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_imdb, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch_imdb, CURLOPT_HTTPHEADER, $request_headers_imdb);

        $ch_douban = curl_init();
        curl_setopt($ch_douban, CURLOPT_URL, $this->_one_title_douban_url);
        curl_setopt($ch_douban, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch_douban, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_douban, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch_douban, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch_douban, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch_douban, CURLOPT_HTTPHEADER, $request_headers_douban);

        // 不知什么原因，使用curl并行执行总是会不定时出现无法获取网页内容的现象
        // 因此改为顺序执行
        // 豆瓣对请求频率有限制，建议停用豆瓣请求，这样也可以加快抓取速度

//        $mh = curl_multi_init();
//        curl_multi_add_handle($mh, $ch_imdb);
//        curl_multi_add_handle($mh, $ch_douban);
//
//        $running = null;
//        do {
//            curl_multi_exec($mh,$running);
//        }while($running > 0);
//
//        curl_multi_remove_handle($mh, $ch_imdb);
//        curl_multi_remove_handle($mh, $ch_douban);
//
//        $this->_one_title_webpage = curl_multi_getcontent($ch_imdb);
//        $this->_one_title_douban = curl_multi_getcontent($ch_douban);
//        curl_multi_close($mh);

        $this->_one_title_webpage = curl_exec($ch_imdb);
        $this->_one_title_douban = curl_exec($ch_douban);
        curl_close($ch_imdb);
        curl_close($ch_douban);
        return NULL;
    }

    public function getMsgOfOneTitle()
    {
        if(strlen($this->_one_title_webpage) == 0) $this->getWebpageOfOneTitle();

        // 名字
        preg_match('#<title>(.*?) -#', $this->_one_title_webpage, $one_title_title);
        $one_title_title_cn = json_decode($this->_one_title_douban, TRUE)['0']['title'];

        // 评分星级 满分10
        preg_match('#ratingValue">(\d\.\d)<#', $this->_one_title_webpage, $one_title_ratingValue);

        // 评分人数
        preg_match('#ratingCount">(.*?)<#', $this->_one_title_webpage, $one_title_ratingCount);

        // 内容等级
        preg_match('#contentRating" content="(.*?)"#', $this->_one_title_webpage, $one_title_contentRating);

        // 上映时间
        preg_match('#datePublished" content="(.*?)"#', $this->_one_title_webpage, $one_title_datePublished);

        // 海报 小
        preg_match('#Poster"\nsrc="(.*?)"#', $this->_one_title_webpage, $one_title_posterSmall);
        // 海报 大
        if(empty($one_title_posterSmall[1])){
            $one_title_posterBig = "";
        }else{
            $one_title_posterBig = strstr($one_title_posterSmall[1], '_', TRUE).'.jpg';
        }

        // 描述
        preg_match('#description">(.*?)<#s', $this->_one_title_webpage, $one_title_description);

        // 导演
        preg_match('#Director:(.*?)</span#s', $this->_one_title_webpage, $one_title_director_dirty);
        preg_match_all('#/name/(.*?)\?#', $one_title_director_dirty[0], $one_title_director_id);
        preg_match_all('#name">(.*?)</span#', $one_title_director_dirty[0], $one_title_director_name);
        for($i = 0; $i < count($one_title_director_id[1]); $i++)
        {
            $one_title_director[] = [
                $one_title_director_id[1][$i],
                $one_title_director_name[1][$i]
            ];
        }

        // 编剧
        preg_match('#Writers:(.*?)</div#s', $this->_one_title_webpage, $one_title_writers_dirty);
        preg_match_all('#/name/(.*?)\?#', $one_title_writers_dirty[0], $one_title_writers_id);
        preg_match_all('#name">(.*?)</span#', $one_title_writers_dirty[0], $one_title_writers_name);
        preg_match_all('#\((.*?)\)#', $one_title_writers_dirty[0], $one_title_writers_what);
        for($i = 0; $i < count($one_title_writers_id[1]); $i++)
        {
            $one_title_writers[] = [
                $one_title_writers_id[1][$i],
                $one_title_writers_name[1][$i],
                $one_title_writers_what[1][$i]
            ];
        }

        // 演员
        preg_match('#cast_list">(.*?)<\/table#s', $this->_one_title_webpage, $one_title_cast_dirty);
        preg_match_all('#class="[odd|even](.*?)<\/tr#s', $one_title_cast_dirty[1], $one_title_cast_dirty_tr);
        for($i = 0; $i < count($one_title_cast_dirty_tr[1]); $i++)
        {
            preg_match('#/name/(.*?)/\?#', $one_title_cast_dirty_tr[1][$i], $one_title_cast_id);
            preg_match('#alt="(.*?)"#', $one_title_cast_dirty_tr[1][$i], $one_title_cast_name);
            preg_match('#loadlate="(.*?)"#', $one_title_cast_dirty_tr[1][$i], $one_title_cast_photo_small);
            if(empty($one_title_cast_photo_small[1])){
                $one_title_cast_photo_big = "";
            }else{
                $one_title_cast_photo_big = strstr($one_title_cast_photo_small[1], '_', TRIE).'jpg';
            }

            $one_title_cast[] = [
                'id' => trim($one_title_cast_id[1]),
                'name' => trim($one_title_cast_name[1]),
                'photo_small' => trim($one_title_cast_photo_small[1]),
                'photo_big' => trim($one_title_cast_photo_big)
            ];
        }

        // 短线 （跟 描述 差不多）Shortline
        preg_match_all('#<p>\n(.*?)<em#', $this->_one_title_webpage, $one_title_storyline);

        // 标语 Taglines
        preg_match('#Taglines:</h4>\n(.*?)</div#s', $this->_one_title_webpage, $one_title_taglines);

        // 流派 Genres
        preg_match_all('#href="/genre/(.*?)\?ref_=tt_stry_gnr#', $this->_one_title_webpage, $one_title_genres);

        // 详细信息 国家/语言/发行时间 Details
        preg_match('#href="/country/\w.*\?ref_=tt_dt_dt"\nitemprop=\'url\'>(\w.*)<#', $this->_one_title_webpage, $one_title_details_country);
        preg_match('#href="/language/\w.*\?ref_=tt_dt_dt"\nitemprop=\'url\'>(\w.*)<#', $this->_one_title_webpage, $one_title_details_language);
        preg_match('#Release Date:</h4>(.*?)\n#', $this->_one_title_webpage, $one_title_details_releaseDate);
        $one_title_details = [
            'country' => trim($one_title_details_country[1]),
            'language' => trim($one_title_details_language[1]),
            'release_date' => trim($one_title_details_releaseDate[1])
        ];

        // 预算/实际开销  Box Office
        preg_match('#Budget:</h4>\s(.*?)\s\n#', $this->_one_title_webpage, $one_title_boxoffice_budget);
        preg_match('#Gross:</h4>\s(.*?)\s\n#', $this->_one_title_webpage, $one_title_boxoffice_gross);
        $one_title_boxoffice = [
            'budget' => trim($one_title_boxoffice_budget[1]),
            'gross' => trim($one_title_boxoffice_gross[1])
        ];

        // 制片方 Production Co
        preg_match('#Production Co:</h4>(.*?)</span#s', $this->_one_title_webpage, $one_title_company_dirty);
        preg_match('#/company/(\w.*)\?#', $one_title_company_dirty[0], $one_title_company_id);
        preg_match('#name">(.*?)<#', $one_title_company_dirty[0], $one_title_company_name);
        $one_title_company = [
            'id' => trim($one_title_company_id[1]),
            'name' => trim($one_title_company_name[1])
        ];

        // 技术规格 时长/混音/色彩/宽高比  Technical Specs
        preg_match('#Technical Specs</h3>(.*?)technical specs#s', $this->_one_title_webpage, $one_title_technicalSpecs_dirty);
        preg_match('#>(\d.*\smin)<#', $one_title_technicalSpecs_dirty[1], $one_title_technicalSpecs_duration);
        preg_match('#sound_mixes=(\w.*)&#', $one_title_technicalSpecs_dirty[1], $one_title_technicalSpecs_sound);
        preg_match('#colors=(\w.*)&#', $one_title_technicalSpecs_dirty[1], $one_title_technicalSpecs_color);
        preg_match('#Aspect Ratio:</h4>\s(.*?)\n#', $one_title_technicalSpecs_dirty[1], $one_title_technicalSpecs_ratio);
        $one_title_technicalSpecs = [
            'duration' => trim($one_title_technicalSpecs_duration[1]),
            'sound_mix' => trim($one_title_technicalSpecs_sound[1]),
            'color' => trim($one_title_technicalSpecs_color[1]),
            'aspect_ratio' => trim($one_title_technicalSpecs_ratio[1])
        ];

        $msg =[
            'title'            => is_null($one_title_title[1]) ? "" : trim($one_title_title[1]),
            'title_cn'         => trim($one_title_title_cn),
            'rating_value'     => is_null($one_title_ratingValue[1]) ? "" : trim($one_title_ratingValue[1]),
            'rating_count'     => is_null($one_title_ratingCount[1]) ? "" : trim($one_title_ratingCount[1]),
            'content_rating'   => is_null($one_title_contentRating[1]) ? "" : trim($one_title_contentRating[1]),
            'date_published'   => is_null($one_title_datePublished[1]) ? "" : trim($one_title_datePublished[1]),
            'poster_small'     => is_null($one_title_posterSmall[1]) ? "" : trim($one_title_posterSmall[1]),
            'poster_big'       => is_null($one_title_posterBig) ? "" : trim($one_title_posterBig),
            'description'      => is_null($one_title_description[1]) ? "" : trim($one_title_description[1]),
            'director'         => is_null($one_title_director) ? "" : $one_title_director,
            'writers'          => is_null($one_title_writers) ? "" : $one_title_writers,
            'cast'             => is_null($one_title_cast) ? "" : $one_title_cast,
            'storyline'        => is_null($one_title_storyline[1][0]) ? "" : trim($one_title_storyline[1][0]),
            'taglines'         => is_null($one_title_taglines[1]) ? "" : trim($one_title_taglines[1]),
            'genres'           => is_null($one_title_genres[1]) ? "" : $one_title_genres[1],
            'details'          => is_null($one_title_details) ? "" : $one_title_details,
            'box_office'       => is_null($one_title_boxoffice) ? "" : $one_title_boxoffice,
            'production'       => is_null($one_title_company) ? "" : $one_title_company,
            'technical_specs'  => is_null($one_title_technicalSpecs) ? "" : $one_title_technicalSpecs,
        ];
        return $msg;
    }

}
