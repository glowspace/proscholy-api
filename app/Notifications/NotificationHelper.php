<?php


namespace App\Notifications;


class NotificationHelper
{
    /**
     * Generate random emoji for slack.
     */
    public function getRandomEmoji()
    {
        $exclamations = [
            ':tada:',
            ':sunglasses:',
            ':smiley_cat:',
            ':blush:',
            ':relaxed:',
            ':innocent:',
            ':smile:',
            ':koala:',
            ':herb:',
            ':hedgehog:',
            ':ram:',
            ':sheep:',
            ':fountain:',
        ];

        $collection = collect($exclamations);

        return $collection->random(1)[0];
    }
}
