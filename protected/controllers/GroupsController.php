<?php

class GroupsController extends ApiController
{

    public function actionTweetCounts($groups, $from, $to)
    {
        $tweetsOfGroups = array();
        $groups = explode(',', $groups);
        $from = strtotime($from);
        $to = strtotime($to);
        date_default_timezone_set('UTC');
        $sql = 'SELECT COUNT( tweet.id ) AS tweet_count, created_at + 2 * 60 * 60 AS time
                FROM  `tweet` 
                LEFT JOIN search_phrase ON search_phrase.id = tweet.search_phrase_id
                LEFT JOIN  `group` ON group.id = search_phrase.group_id
                WHERE  `group`.name = :group
                AND tweet.created_at > :from
                GROUP BY YEAR(FROM_UNIXTIME(created_at )), MONTH(FROM_UNIXTIME(created_at)), DAY(FROM_UNIXTIME(created_at))
                ORDER BY created_at ASC';
        foreach ($groups as $group) {
            $command = Yii::app()->db->createCommand($sql);
            $command->execute(array(
                ':group' => $group,
                ':from' => $from,
            ));
            $tweets = $command->queryAll();
            $tweets = $this->valuesToJson($tweets, $from, $to, 'time', 'tweet_count');
            $tweetsOfGroups[] = array(
                'group' => $group,
                'tweets' => $tweets,
            );
        }
        $this->outputJson($tweetsOfGroups);
    }

    public function actionTopTweets($group, $from, $to, $number)
    {
        $number = intval($number);
        if (!$number) {
            throw new CHttpException('Number has to be integer');
        }

        $from = strtotime($from);
        $to = strtotime($to);

        date_default_timezone_set('UTC');

        $sql = 'SELECT tweet.*, created_at AS created_at
                FROM  `tweet` 
                LEFT JOIN search_phrase ON search_phrase.id = tweet.search_phrase_id
                LEFT JOIN  `group` ON group.id = search_phrase.group_id
                WHERE  `group`.name = :group
                AND tweet.created_at > :from
                AND tweet.retweet = 0
                ORDER BY retweet_count + favorite_count DESC
                LIMIT ' . $number;
        $command = Yii::app()->db->createCommand($sql);
        $command->execute(array(
            ':group' => $group,
            ':from' => $from,
        ));
        $tweets = $command->queryAll();
        usort($tweets, function($a, $b) {
                    if ($a['created_at'] == $b['created_at']) {
                        return 0;
                    }
                    return ($a['created_at'] < $b['created_at']) ? 1 : -1;
                });
        foreach ($tweets as &$tweet) {
            $tweet = Tweet::toReadable($tweet);
        }
        $this->outputJson(array(
            'group' => $group,
            'tweets' => $tweets,
        ));
    }

}