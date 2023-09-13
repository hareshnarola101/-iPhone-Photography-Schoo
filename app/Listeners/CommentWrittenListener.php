<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use App\Events\AchievementUnlocked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CommentWrittenListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CommentWritten $event): void
    {
        $user = $event->user;


        $commentsWritten = $user->comments()->count();

        if ($commentsWritten == 1) {
            // Unlock "First Comment Written" achievement
            // Create and dispatch an AchievementUnlocked event
            event(new AchievementUnlocked('First Comment Written', $user));
        } elseif ($commentsWritten == 3) {
            // Unlock "3 Comments Written" achievement
            event(new AchievementUnlocked('3 Comments Written', $user));
        } elseif ($commentsWritten == 5) {
            // Unlock "5 Comments Written" achievement
            event(new AchievementUnlocked('5 Comments Written', $user));
        } elseif ($commentsWritten == 10) {
            // Unlock "10 Comments Written" achievement
            event(new AchievementUnlocked('10 Comments Written', $user));
        } elseif ($commentsWritten == 20) {
            // Unlock "20 Comments Written" achievement
            event(new AchievementUnlocked('20 Comments Written', $user));
        }

    }
}
