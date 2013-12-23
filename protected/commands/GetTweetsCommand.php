<?php

class GetTweetsCommand extends CConsoleCommand
{

    public function run()
    {
        $settings = array(
            'oauth_access_token' => Yii::app()->params['twitter']['accessToken'],
            'oauth_access_token_secret' => Yii::app()->params['twitter']['accessTokenSecret'],
            'consumer_key' => Yii::app()->params['twitter']['consumerKey'],
            'consumer_secret' => Yii::app()->params['twitter']['consumerSecret'],
        );

        $searchPhrases = SearchPhrase::model()->findAll();
        foreach ($searchPhrases as $searchPhrase) {

            $url = 'https://api.twitter.com/1.1/search/tweets.json';
            $getField = '?q=' . urlencode($searchPhrase->search_phrase) . '&count=100';
            $requestMethod = 'GET';

            $getMore = true;

            while ($getMore) {
                var_dump($getField);

                $twitter = new TwitterAPIExchange($settings);
                $tweets = $twitter->setGetfield($getField)
                        ->buildOauth($url, $requestMethod)
                        ->performRequest();

                $tweetsJson = json_decode($tweets, true);

                if (isset($tweetsJson['statuses'])) {
                    foreach ($tweetsJson['statuses'] as $tweetJson) {
                        $tweet = Tweet::model()->findByPk($tweetJson['id']);
                        if (!$tweet) {
                            $getMore = true;
                            $tweet = new Tweet();
                            $tweet->getDataFromJson($tweetJson);
                            $tweet->search_phrase_id = $searchPhrase->id;
                            $tweet->save();
                        } else {
                            $getMore = false;
                        }
                    }
                }

                if (isset($tweetsJson['search_metadata']['next_results'])) {
                    $getField = $tweetsJson['search_metadata']['next_results'];
                    sleep(1);
                } else {
                    $getMore = false;
                }
            }
            sleep(1);
        }
    }

}