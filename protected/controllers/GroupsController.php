<?php

class GroupsController extends ApiController
{

    public function actionTweets($group, $from, $to)
    {
        $from = strtotime($from);
        $to = strtotime($to);

        date_default_timezone_set('UTC');

        $sql = 'SELECT COUNT( tweet.id ) AS tweet_count, created_at + 2 * 60 * 60 AS time
                FROM  `tweet` 
                LEFT JOIN search_phrase ON search_phrase.id = tweet.search_phrase_id
                LEFT JOIN  `group` ON group.id = search_phrase.group_id
                WHERE  `group`.name = :group
                AND tweet.created_at > :from
                GROUP BY YEAR( FROM_UNIXTIME( created_at ) ) , DAY( FROM_UNIXTIME( created_at ) )
                ORDER BY created_at ASC';
        $command = Yii::app()->db->createCommand($sql);
        $command->execute(array(
            ':group' => $group,
            ':from' => $from,
        ));
        $tweets = $command->queryAll();
        $tweets = $this->valuesToJson($tweets, $from, $to, 'time', 'tweet_count');
        $this->outputJson(array(
            'group' => $group,
            'tweets' => $tweets,
        ));
    }

}