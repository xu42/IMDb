# IMDb
抓取IMDb电影的评分、内容等级、上映日期、海报、介绍、导演、演员、时长等信息
Crawl IMDb movie rating, content rating, release date, poster, presentation, director, actor, duration and other information

1. 修改demo.php中`$imdb_start`和`$imdb_end`参数;
2. 执行`php demo.php`即可, 如果任务量大需要在后台执行, 可以这样`php demo.php > /dev/null &`

输出到文件中的是JSON格式, 例如：
```json
{
    "title": "The Shawshank Redemption (1994)",
    "title_cn": "肖申克的救赎",
    "rating_value": "9.3",
    "rating_count": "1,632,012",
    "content_rating": "R",
    "date_published": "1994-10-14",
    "poster_small": "http://ia.media-imdb.com/images/M/MV5BODU4MjU4NjIwNl5BMl5BanBnXkFtZTgwMDU2MjEyMDE@._V1_UX182_CR0,0,182,268_AL_.jpg",
    "poster_big": "http://ia.media-imdb.com/images/M/MV5BODU4MjU4NjIwNl5BMl5BanBnXkFtZTgwMDU2MjEyMDE@..jpg",
    "description": "Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.",
    "director": [
        [
            "nm0001104",
            "Frank Darabont"
        ]
    ],
    "writers": [
        [
            "nm0000175",
            "Stephen King",
            "short story \"Rita Hayworth and Shawshank Redemption\""
        ],
        [
            "nm0001104",
            "Frank Darabont",
            "screenplay"
        ]
    ],
    "cast": [
        {
            "id": "nm0000209",
            "name": "Tim Robbins",
            "photo_small": "http://ia.media-imdb.com/images/M/MV5BMTI1OTYxNzAxOF5BMl5BanBnXkFtZTYwNTE5ODI4._V1_UY44_CR1,0,32,44_AL_.jpg",
            "photo_big": "http://ia.media-imdb.com/images/M/MV5BMTI1OTYxNzAxOF5BMl5BanBnXkFtZTYwNTE5ODI4.jpg"
        },
        {
            "id": "nm0000151",
            "name": "Morgan Freeman",
            "photo_small": "http://ia.media-imdb.com/images/M/MV5BMTc0MDMyMzI2OF5BMl5BanBnXkFtZTcwMzM2OTk1MQ@@._V1_UX32_CR0,0,32,44_AL_.jpg",
            "photo_big": "http://ia.media-imdb.com/images/M/MV5BMTc0MDMyMzI2OF5BMl5BanBnXkFtZTcwMzM2OTk1MQ@@.jpg"
        },
        {
            "id": "nm0348409",
            "name": "Bob Gunton",
            "photo_small": "http://ia.media-imdb.com/images/M/MV5BMTc3MzY0MTQzM15BMl5BanBnXkFtZTcwMTM0ODYxNw@@._V1_UY44_CR11,0,32,44_AL_.jpg",
            "photo_big": "http://ia.media-imdb.com/images/M/MV5BMTc3MzY0MTQzM15BMl5BanBnXkFtZTcwMTM0ODYxNw@@.jpg"
        },
        {
            "id": "nm0006669",
            "name": "William Sadler",
            "photo_small": "http://ia.media-imdb.com/images/M/MV5BMTA1NjU3NDg1MTheQTJeQWpwZ15BbWU2MDI4OTcxMw@@._V1_UY44_CR2,0,32,44_AL_.jpg",
            "photo_big": "http://ia.media-imdb.com/images/M/MV5BMTA1NjU3NDg1MTheQTJeQWpwZ15BbWU2MDI4OTcxMw@@.jpg"
        },
        {
            "id": "nm0000317",
            "name": "Clancy Brown",
            "photo_small": "http://ia.media-imdb.com/images/M/MV5BMTUxODY3NjAzMF5BMl5BanBnXkFtZTcwMTQ5MjYwNg@@._V1_UX32_CR0,0,32,44_AL_.jpg",
            "photo_big": "http://ia.media-imdb.com/images/M/MV5BMTUxODY3NjAzMF5BMl5BanBnXkFtZTcwMTQ5MjYwNg@@.jpg"
        },
        {
            "id": "nm0004743",
            "name": "Gil Bellows",
            "photo_small": "http://ia.media-imdb.com/images/M/MV5BMTgxMzc0MDAzNV5BMl5BanBnXkFtZTgwMzUzMTI0MzE@._V1_UY44_CR0,0,32,44_AL_.jpg",
            "photo_big": "http://ia.media-imdb.com/images/M/MV5BMTgxMzc0MDAzNV5BMl5BanBnXkFtZTgwMzUzMTI0MzE@.jpg"
        },
        {
            "id": "nm0001679",
            "name": "Mark Rolston",
            "photo_small": "http://ia.media-imdb.com/images/M/MV5BMTk2NDc0MTUxNV5BMl5BanBnXkFtZTcwMDUzMjE5Mg@@._V1_UX32_CR0,0,32,44_AL_.jpg",
            "photo_big": "http://ia.media-imdb.com/images/M/MV5BMTk2NDc0MTUxNV5BMl5BanBnXkFtZTcwMDUzMjE5Mg@@.jpg"
        },
        {
            "id": "nm0926235",
            "name": "James Whitmore",
            "photo_small": "http://ia.media-imdb.com/images/M/MV5BMTg5MzkxMTkxOV5BMl5BanBnXkFtZTcwNTEzNTgxMw@@._V1_UY44_CR3,0,32,44_AL_.jpg",
            "photo_big": "http://ia.media-imdb.com/images/M/MV5BMTg5MzkxMTkxOV5BMl5BanBnXkFtZTcwNTEzNTgxMw@@.jpg"
        },
        {
            "id": "nm0218810",
            "name": "Jeffrey DeMunn",
            "photo_small": "http://ia.media-imdb.com/images/M/MV5BMTQ0Mjc3NDA1OV5BMl5BanBnXkFtZTcwMTg3MDEyOA@@._V1_UY44_CR1,0,32,44_AL_.jpg",
            "photo_big": "http://ia.media-imdb.com/images/M/MV5BMTQ0Mjc3NDA1OV5BMl5BanBnXkFtZTcwMTg3MDEyOA@@.jpg"
        },
        {
            "id": "nm0104594",
            "name": "Larry Brandenburg",
            "photo_small": "http://ia.media-imdb.com/images/M/MV5BMjI0Mzc0MzY5Ml5BMl5BanBnXkFtZTcwNDA1NTU4Nw@@._V1_UY44_CR3,0,32,44_AL_.jpg",
            "photo_big": "http://ia.media-imdb.com/images/M/MV5BMjI0Mzc0MzY5Ml5BMl5BanBnXkFtZTcwNDA1NTU4Nw@@.jpg"
        },
        {
            "id": "nm0321358",
            "name": "Neil Giuntoli",
            "photo_small": "http://ia.media-imdb.com/images/M/MV5BMjI0OTUxNjIyNF5BMl5BanBnXkFtZTcwNDE0MDcwOA@@._V1_UY44_CR0,0,32,44_AL_.jpg",
            "photo_big": "http://ia.media-imdb.com/images/M/MV5BMjI0OTUxNjIyNF5BMl5BanBnXkFtZTcwNDE0MDcwOA@@.jpg"
        },
        {
            "id": "nm0508742",
            "name": "Brian Libby",
            "photo_small": "http://ia.media-imdb.com/images/M/MV5BMjI2NDYwNzU0NV5BMl5BanBnXkFtZTcwMjYwMTcwOA@@._V1_UX32_CR0,0,32,44_AL_.jpg",
            "photo_big": "http://ia.media-imdb.com/images/M/MV5BMjI2NDYwNzU0NV5BMl5BanBnXkFtZTcwMjYwMTcwOA@@.jpg"
        },
        {
            "id": "nm0698998",
            "name": "David Proval",
            "photo_small": "http://ia.media-imdb.com/images/M/MV5BMjAxNjc4NDg2MF5BMl5BanBnXkFtZTcwNTA1NzE3MQ@@._V1_UY44_CR1,0,32,44_AL_.jpg",
            "photo_big": "http://ia.media-imdb.com/images/M/MV5BMjAxNjc4NDg2MF5BMl5BanBnXkFtZTcwNTA1NzE3MQ@@.jpg"
        },
        {
            "id": "nm0706554",
            "name": "Joseph Ragno",
            "photo_small": "http://ia.media-imdb.com/images/M/MV5BOTMzNzc1NzY3NF5BMl5BanBnXkFtZTgwNjMxMzA2NTE@._V1_UY44_CR1,0,32,44_AL_.jpg",
            "photo_big": "http://ia.media-imdb.com/images/M/MV5BOTMzNzc1NzY3NF5BMl5BanBnXkFtZTgwNjMxMzA2NTE@.jpg"
        },
        {
            "id": "nm0161980",
            "name": "Jude Ciccolella",
            "photo_small": "http://ia.media-imdb.com/images/M/MV5BMjA4NzY2NzAzOV5BMl5BanBnXkFtZTcwMDc2OTkyMQ@@._V1_UX32_CR0,0,32,44_AL_.jpg",
            "photo_big": "http://ia.media-imdb.com/images/M/MV5BMjA4NzY2NzAzOV5BMl5BanBnXkFtZTcwMDc2OTkyMQ@@.jpg"
        }
    ],
    "storyline": "Chronicles the experiences of a formerly successful banker as a prisoner in the gloomy jailhouse of Shawshank after being found guilty of a crime he claims he did not commit. The film portrays the man's unique way of dealing with his new, torturous life; along the way he befriends a number of fellow prisoners, most notably a wise long-term inmate named Red.",
    "taglines": "Fear can hold you prisoner. Hope can set you free.",
    "genres": [
        "Crime",
        "Drama"
    ],
    "details": {
        "country": "USA",
        "language": "English",
        "release_date": "14 October 1994 (USA)"
    },
    "box_office": {
        "budget": "$25,000,000",
        "gross": "&pound;2,344,349"
    },
    "production": {
        "id": "co0040620",
        "name": "Castle Rock Entertainment"
    },
    "technical_specs": {
        "duration": "142 min",
        "sound_mix": "dolby_digital",
        "color": "color",
        "aspect_ratio": "1.85 : 1"
    }
}
```

