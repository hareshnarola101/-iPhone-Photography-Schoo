<?php

namespace App\Listeners;

use App\Events\LessonWatched;
use App\Events\AchievementUnlocked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LessonWatchedListener
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
    public function handle(LessonWatched $event)
    {
        
       
        $user = $event->user;
        
        $lessonsWatched = $user->watched()->count();
        
        // Implement logic to unlock achievements based on $lessonsWatched
        if ($lessonsWatched == 1) {
            // Unlock "First Lesson Watched" achievement
            // Create and dispatch an AchievementUnlocked event
            event(new AchievementUnlocked('First Lesson Watched', $user));
        } else if ($lessonsWatched == 5) {
            // Unlock "5 Lessons Watched" achievement
            event(new AchievementUnlocked('5 Lessons Watched', $user));
        } else if ($lessonsWatched == 10) {
            // Unlock "10 Lessons Watched" achievement
            event(new AchievementUnlocked('10 Lessons Watched', $user));
        } else if ($lessonsWatched == 25) {
            
            // Unlock "25 Lessons Watched" achievement
            event(new AchievementUnlocked('25 Lessons Watched', $user));
        } else if ($lessonsWatched == 50) {
            // Unlock "50 Lessons Watched" achievement
            event(new AchievementUnlocked('50 Lessons Watched', $user));
        }

        

    }
}
