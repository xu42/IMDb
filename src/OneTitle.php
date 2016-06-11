<?php

namespace Xu42\Imdb;

class OneTitle {

    /**
     * @var string An string of one title prefix url
     */
    private $oneTitlePrefixUrl = 'http://www.imdb.com/title/';

    /**
     * @var string An string of one title value
     * e.g. tt0111161
     */
    private $oneTitle = '';

    /**
     * @var string An string of one title url
     * e.g. http://www.imdb.com/title/tt0111161
     */
    private $oneTitleUrl = '';

    /**
     * @var string sources of webpage with one title
     */
    private $oneTitleWebpage = '';


    /**
     * Get sources of Webpage with one IMDb's title
     * @return String
     */
    private function getWebpageOfOneTitle()
    {
        $headers = [
            'Host: www.imdb.com',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Upgrade-Insecure-Requests: 1',
            'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.108 Safari/537.36',
            'Referer: http://www.imdb.com',
            'Accept-Language: zh-CN,zh;q=0.8'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->oneTitleUrl);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $this->oneTitleWebpage = curl_exec($ch);
        curl_close($ch);
        return $this->oneTitleWebpage;
    }

    /**
     * Get details of one IMDb's title
     * @param string title An string of one IMDb's title
     * @return array
     */
    public function getMsgOfOneTitle($title)
    {
        $this->oneTitle = $title;
        $this->oneTitleUrl = $this->oneTitlePrefixUrl . $title . '/?ref_=nv_mv_dflt_1';

        if (strlen($this->oneTitleWebpage) == 0) {
            $this->getWebpageOfOneTitle();
        }

        // title
        preg_match('#<title>(.*?) -#', $this->oneTitleWebpage, $oneTitleTitle);

        // rating Value
        preg_match('#ratingValue">(\d\.\d)<#', $this->oneTitleWebpage, $oneTitleRatingValue);

        // rating Count
        preg_match('#ratingCount">(.*?)<#', $this->oneTitleWebpage, $oneTitleRatingCount);

        // content Rating
        preg_match('#contentRating" content="(.*?)"#', $this->oneTitleWebpage, $oneTitleContentRating);

        // datePublished
        preg_match('#datePublished" content="(.*?)"#', $this->oneTitleWebpage, $oneTitleDatePublished);

        // Poster small
        preg_match('#Poster"\nsrc="(.*?)"#', $this->oneTitleWebpage, $oneTitlePosterSmall);
        // Poster big
        if (empty($oneTitlePosterSmall[1])) {
            $oneTitlePosterBig = "";
        } else {
            $oneTitlePosterBig = strstr($oneTitlePosterSmall[1], '_', true) . '.jpg';
        }

        // description
        preg_match('#description">(.*?)<#s', $this->oneTitleWebpage, $oneTitleDescription);

        // Director
        preg_match('#Director:(.*?)</span#s', $this->oneTitleWebpage, $oneTitleDirectorDirty);
        preg_match_all('#/name/(.*?)\?#', $oneTitleDirectorDirty[0], $oneTitleDirectorId);
        preg_match_all('#name">(.*?)</span#', $oneTitleDirectorDirty[0], $oneTitleDirectorName);
        $oneTitleDirector = null;
        for ($i = 0; $i < count($oneTitleDirectorId[1]); $i++) {
            $oneTitleDirector[] = [
                $oneTitleDirectorId[1][$i],
                $oneTitleDirectorName[1][$i]
            ];
        }

        // Writers
        preg_match('#Writers:(.*?)</div#s', $this->oneTitleWebpage, $oneTitleWritersDirty);
        preg_match_all('#/name/(.*?)\?#', $oneTitleWritersDirty[0], $oneTitleWritersId);
        preg_match_all('#name">(.*?)</span#', $oneTitleWritersDirty[0], $oneTitleWritersName);
        preg_match_all('#\((.*?)\)#', $oneTitleWritersDirty[0], $oneTitleWritersWhat);
        $oneTitleWriters = null;
        for($i = 0; $i < count($oneTitleWritersId[1]); $i++) {
            $oneTitleWriters[] = [
                $oneTitleWritersId[1][$i],
                $oneTitleWritersName[1][$i],
                $oneTitleWritersWhat[1][$i]
            ];
        }

        // cast_list
        preg_match('#cast_list">(.*?)<\/table#s', $this->oneTitleWebpage, $oneTitleCastDirty);
        preg_match_all('#class="[odd|even](.*?)<\/tr#s', $oneTitleCastDirty[1], $oneTitleCastDirtyTr);
        $oneTitleCast = null;
        for ($i = 0; $i < count($oneTitleCastDirtyTr[1]); $i++) {
            preg_match('#/name/(.*?)/\?#', $oneTitleCastDirtyTr[1][$i], $oneTitleCastId);
            preg_match('#alt="(.*?)"#', $oneTitleCastDirtyTr[1][$i], $oneTitleCastName);
            preg_match('#loadlate="(.*?)"#', $oneTitleCastDirtyTr[1][$i], $oneTitleCastPhotoSmall);
            if (empty($oneTitleCastPhotoSmall[1])) {
                $oneTitleCastPhotoBig = "";
            } else {
                $oneTitleCastPhotoBig = strstr($oneTitleCastPhotoSmall[1], '_', true) . 'jpg';
            }
            $oneTitleCast[] = [
                'id' => trim($oneTitleCastId[1]),
                'name' => trim($oneTitleCastName[1]),
                'photo_small' => trim($oneTitleCastPhotoSmall[1]),
                'photo_big' => trim($oneTitleCastPhotoBig)
            ];
        }

        // Shortline
        preg_match_all('#<p>\n(.*?)<em#', $this->oneTitleWebpage, $oneTitleStoryline);

        // Taglines
        preg_match('#Taglines:</h4>\n(.*?)</div#s', $this->oneTitleWebpage, $oneTitleTaglines);

        // Genres
        preg_match_all('#href="/genre/(.*?)\?ref_=tt_stry_gnr#', $this->oneTitleWebpage, $oneTitleGenres);

        // Details
        preg_match('#href="/country/\w.*\?ref_=tt_dt_dt"\nitemprop=\'url\'>(\w.*)<#', $this->oneTitleWebpage, $oneTitleDetailsCountry);
        preg_match('#href="/language/\w.*\?ref_=tt_dt_dt"\nitemprop=\'url\'>(\w.*)<#', $this->oneTitleWebpage, $oneTitleDetailsLanguage);
        preg_match('#Release Date:</h4>(.*?)\n#', $this->oneTitleWebpage, $oneTitleDetailsReleaseDate);
        $oneTitleDetails = [
            'country' => trim($oneTitleDetailsCountry[1]),
            'language' => trim($oneTitleDetailsLanguage[1]),
            'release_date' => trim($oneTitleDetailsReleaseDate[1])
        ];

        // Box Office
        preg_match('#Budget:</h4>\s(.*?)\s\n#', $this->oneTitleWebpage, $oneTitleBoxofficeBudget);
        preg_match('#Gross:</h4>\s(.*?)\s\n#', $this->oneTitleWebpage, $oneTitleBoxofficeGross);
        $oneTitleBoxoffice = [
            'budget' => trim($oneTitleBoxofficeBudget[1]),
            'gross' => trim($oneTitleBoxofficeGross[1])
        ];

        // Technical Specs
        preg_match('#Technical Specs</h3>(.*?)technical specs#s', $this->oneTitleWebpage, $oneTitleTechnicalSpecsDirty);
        preg_match('#>(\d.*\smin)<#', $oneTitleTechnicalSpecsDirty[1], $oneTitleTechnicalSpecsDuration);
        preg_match('#sound_mixes=(\w.*)&#', $oneTitleTechnicalSpecsDirty[1], $oneTitleTechnicalSpecsSound);
        preg_match('#colors=(\w.*)&#', $oneTitleTechnicalSpecsDirty[1], $oneTitleTechnicalSpecsColor);
        preg_match('#Aspect Ratio:</h4>\s(.*?)\n#', $oneTitleTechnicalSpecsDirty[1], $oneTitleTechnicalSpecsRatio);
        $oneTitleTechnicalSpecs = [
            'duration' => trim($oneTitleTechnicalSpecsDuration[1]),
            'sound_mix' => trim($oneTitleTechnicalSpecsSound[1]),
            'color' => trim($oneTitleTechnicalSpecsColor[1]),
            'aspect_ratio' => trim($oneTitleTechnicalSpecsRatio[1])
        ];

        $oneTitle =[
            'title'            => is_null($oneTitleTitle[1]) ? "" : trim($oneTitleTitle[1]),
            'rating_value'     => is_null($oneTitleRatingValue[1]) ? "" : trim($oneTitleRatingValue[1]),
            'rating_count'     => is_null($oneTitleRatingCount[1]) ? "" : trim($oneTitleRatingCount[1]),
            'content_rating'   => is_null($oneTitleContentRating[1]) ? "" : trim($oneTitleContentRating[1]),
            'date_published'   => is_null($oneTitleDatePublished[1]) ? "" : trim($oneTitleDatePublished[1]),
            'poster_small'     => is_null($oneTitlePosterSmall[1]) ? "" : trim($oneTitlePosterSmall[1]),
            'poster_big'       => is_null($oneTitlePosterBig) ? "" : trim($oneTitlePosterBig),
            'description'      => is_null($oneTitleDescription[1]) ? "" : trim($oneTitleDescription[1]),
            'director'         => is_null($oneTitleDirector) ? "" : $oneTitleDirector,
            'writers'          => is_null($oneTitleWriters) ? "" : $oneTitleWriters,
            'cast'             => is_null($oneTitleCast) ? "" : $oneTitleCast,
            'storyline'        => is_null($oneTitleStoryline[1][0]) ? "" : trim($oneTitleStoryline[1][0]),
            'taglines'         => is_null($oneTitleTaglines[1]) ? "" : trim($oneTitleTaglines[1]),
            'genres'           => is_null($oneTitleGenres[1]) ? "" : $oneTitleGenres[1],
            'details'          => is_null($oneTitleDetails) ? "" : $oneTitleDetails,
            'box_office'       => is_null($oneTitleBoxoffice) ? "" : $oneTitleBoxoffice,
            'technical_specs'  => is_null($oneTitleTechnicalSpecs) ? "" : $oneTitleTechnicalSpecs,
        ];
        return $oneTitle;
    }
}
