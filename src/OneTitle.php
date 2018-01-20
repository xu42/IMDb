<?php

namespace Xu42\Imdb;

use Xu42\Utilities\DesignPattern\Singleton;

class OneTitle
{
    use Singleton;

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
    private $oneTitleWebPage = '';


    public function get($title)
    {
        return $this->getMsgOfOneTitle($title);
    }

    /**
     * Get sources of Webpage with one IMDb's title
     * @return String
     */
    private function getWebPageOfOneTitle()
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

        $this->oneTitleWebPage = curl_exec($ch);
        curl_close($ch);
        return $this->oneTitleWebPage;
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

        if (strlen($this->oneTitleWebPage) == 0) {
            $this->getWebPageOfOneTitle();
        }

        // title
        preg_match('#<title>(.*?) -#', $this->oneTitleWebPage, $oneTitleTitle);

        // rating Value
        preg_match('#ratingValue">(\d\.\d)<#', $this->oneTitleWebPage, $oneTitleRatingValue);

        // rating Count
        preg_match('#ratingCount">(.*?)<#', $this->oneTitleWebPage, $oneTitleRatingCount);

        // content Rating
        preg_match('#contentRating" content="(.*?)"#', $this->oneTitleWebPage, $oneTitleContentRating);

        // datePublished
        preg_match('#datePublished" content="(.*?)"#', $this->oneTitleWebPage, $oneTitleDatePublished);

        // Poster small
        preg_match('#Poster"\nsrc="(.*?)"#', $this->oneTitleWebPage, $oneTitlePosterSmall);
        // Poster big
        if (empty($oneTitlePosterSmall[1])) {
            $oneTitlePosterBig = "";
        } else {
            $oneTitlePosterBig = strstr($oneTitlePosterSmall[1], '_', true) . '.jpg';
        }

        // description
        preg_match('#description">(.*?)<#s', $this->oneTitleWebPage, $oneTitleDescription);

        // Director
        preg_match('#Director:(.*?)</span#s', $this->oneTitleWebPage, $oneTitleDirectorDirty);
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
        preg_match('#Writers:(.*?)</div#s', $this->oneTitleWebPage, $oneTitleWritersDirty);
        preg_match_all('#/name/(.*?)\?#', $oneTitleWritersDirty[0], $oneTitleWritersId);
        preg_match_all('#name">(.*?)</span#', $oneTitleWritersDirty[0], $oneTitleWritersName);
        preg_match_all('#\((.*?)\)#', $oneTitleWritersDirty[0], $oneTitleWritersWhat);
        $oneTitleWriters = null;
        for ($i = 0; $i < count($oneTitleWritersId[1]); $i++) {
            $oneTitleWriters[] = [
                $oneTitleWritersId[1][$i],
                $oneTitleWritersName[1][$i],
                $oneTitleWritersWhat[1][$i]
            ];
        }

        // cast_list
        preg_match('#cast_list">(.*?)<\/table#s', $this->oneTitleWebPage, $oneTitleCastDirty);
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
                'id' => trim($oneTitleCastId[1] ?? null),
                'name' => trim($oneTitleCastName[1] ?? null),
                'photo_small' => trim($oneTitleCastPhotoSmall[1] ?? null),
                'photo_big' => trim($oneTitleCastPhotoBig)
            ];
        }

        // Shortline
        preg_match_all('#<p>\n(.*?)<em#', $this->oneTitleWebPage, $oneTitleStoryline);

        // Taglines
        preg_match('#Taglines:</h4>\n(.*?)</div#s', $this->oneTitleWebPage, $oneTitleTagLines);

        // Genres
        preg_match_all('#href="/genre/(.*?)\?ref_=tt_stry_gnr#', $this->oneTitleWebPage, $oneTitleGenres);

        // Details
        preg_match('#href="/country/\w.*\?ref_=tt_dt_dt"\nitemprop=\'url\'>(\w.*)<#', $this->oneTitleWebPage, $oneTitleDetailsCountry);
        preg_match('#href="/language/\w.*\?ref_=tt_dt_dt"\nitemprop=\'url\'>(\w.*)<#', $this->oneTitleWebPage, $oneTitleDetailsLanguage);
        preg_match('#Release Date:</h4>(.*?)\n#', $this->oneTitleWebPage, $oneTitleDetailsReleaseDate);
        $oneTitleDetails = [
            'country' => trim($oneTitleDetailsCountry[1] ?? null),
            'language' => trim($oneTitleDetailsLanguage[1] ?? null),
            'release_date' => trim($oneTitleDetailsReleaseDate[1] ?? null)
        ];

        // Box Office
        preg_match('#Budget:</h4>\s(.*?)\s\n#', $this->oneTitleWebPage, $oneTitleBoxOfficeBudget);
        preg_match('#Gross:</h4>\s(.*?)\s\n#', $this->oneTitleWebPage, $oneTitleBoxOfficeGross);
        $oneTitleBoxOffice = [
            'budget' => trim($oneTitleBoxOfficeBudget[1] ?? null),
            'gross' => trim($oneTitleBoxOfficeGross[1] ?? null)
        ];

        // Technical Specs
        preg_match('#Technical Specs</h3>(.*?)technical specs#s', $this->oneTitleWebPage, $oneTitleTechnicalSpecsDirty);
        preg_match('#>(\d.*\smin)<#', $oneTitleTechnicalSpecsDirty[1], $oneTitleTechnicalSpecsDuration);
        preg_match('#sound_mixes=(\w.*)&#', $oneTitleTechnicalSpecsDirty[1], $oneTitleTechnicalSpecsSound);
        preg_match('#colors=(\w.*)&#', $oneTitleTechnicalSpecsDirty[1], $oneTitleTechnicalSpecsColor);
        preg_match('#Aspect Ratio:</h4>\s(.*?)\n#', $oneTitleTechnicalSpecsDirty[1], $oneTitleTechnicalSpecsRatio);
        $oneTitleTechnicalSpecs = [
            'duration' => trim($oneTitleTechnicalSpecsDuration[1] ?? null),
            'sound_mix' => trim($oneTitleTechnicalSpecsSound[1] ?? null),
            'color' => trim($oneTitleTechnicalSpecsColor[1] ?? null),
            'aspect_ratio' => trim($oneTitleTechnicalSpecsRatio[1] ?? null)
        ];

        $oneTitle = [
            'title' => trim($oneTitleTitle[1] ?? null),
            'rating_value' => trim($oneTitleRatingValue[1] ?? null),
            'rating_count' => trim($oneTitleRatingCount[1] ?? null),
            'content_rating' => trim($oneTitleContentRating[1] ?? null),
            'date_published' => trim($oneTitleDatePublished[1] ?? null),
            'poster_small' => trim($oneTitlePosterSmall[1] ?? null),
            'poster_big' => trim($oneTitlePosterBig ?? null),
            'description' => trim($oneTitleDescription[1] ?? null),
            'director' => $oneTitleDirector ?? null,
            'writers' => $oneTitleWriters ?? null,
            'cast' => $oneTitleCast ?? null,
            'storyline' => trim($oneTitleStoryline[1][0] ?? null),
            'taglines' => trim($oneTitleTagLines[1] ?? null),
            'genres' => $oneTitleGenres[1] ?? null,
            'details' => $oneTitleDetails ?? null,
            'box_office' => $oneTitleBoxOffice ?? null,
            'technical_specs' => $oneTitleTechnicalSpecs ?? null,
        ];
        return $oneTitle;
    }
}
