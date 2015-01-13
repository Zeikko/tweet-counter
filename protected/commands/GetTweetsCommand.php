<?php

class GetTweetsCommand extends CConsoleCommand
{

    public function run($args)
    {
        $settings = array(
            'oauth_access_token' => Yii::app()->params['twitter']['accessToken'],
            'oauth_access_token_secret' => Yii::app()->params['twitter']['accessTokenSecret'],
            'consumer_key' => Yii::app()->params['twitter']['consumerKey'],
            'consumer_secret' => Yii::app()->params['twitter']['consumerSecret'],
        );

        $searchPhrases = SearchPhrase::model()->findAll();
        foreach ($searchPhrases as $searchPhrase) {
            if ($searchPhrase->group->active) {
                /*
                 * @TODO
                 * Check the latest Tweet for the current search phrase and set since_id to the ID of that Tweet for the Twitter request
                 * Create many to many relationship between search phrases and tweets since one tweet can match many search phrases
                 */
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

                    $newTweets = 0;
                    if (isset($tweetsJson['statuses'])) {
                        foreach ($tweetsJson['statuses'] as $tweetJson) {
                            $tweet = Tweet::model()->findByPk($tweetJson['id']);
                            //Save tweet
                            if (!$tweet) {
                                $getMore = true;
                                $tweet = new Tweet();
                                $tweet->getDataFromJson($tweetJson);
                                $tweet->search_phrase_id = $searchPhrase->id;
                                $tweet->retweet = 0;
                                $newTweets++;
                            } else {
                                $getMore = false;
                            }
                            //Update retweets of the original tweet
                            if (isset($tweetJson['retweeted_status'])) {
                                $originalTweet = Tweet::model()->findByPk($tweetJson['retweeted_status']['id']);
                                if ($originalTweet) {
                                    $originalTweet->retweet_count = $tweetJson['retweet_count'];
                                }
                                $tweet->retweet = 1;
                            }
                            $tweet->save();

                            $searchPhraseInTweet = SearchPhraseInTweet::model()->findByAttributes(array(
                                'tweet_id' => $tweet->id,
                                'search_phrase_id' => $searchPhrase->id
                            ));
                            if(!$searchPhraseInTweet) {
                                $searchPhraseInTweet = new SearchPhraseInTweet();
                                $searchPhraseInTweet->tweet_id = $tweet->id;
                                $searchPhraseInTweet->search_phrase_id = $searchPhrase->id;
                                $searchPhraseInTweet->save();
                            }
                        }
                        var_dump('Found ' . count($tweetsJson['statuses']) . ' tweets of which ' . $newTweets . ' were new.');
                    } else {
                        var_dump($tweetsJson);
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

}