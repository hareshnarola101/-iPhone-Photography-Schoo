<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;

class AchievementTest extends TestCase
{
    // use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    /** @test */
    public function user_unlocks_first_lesson_watched_achievement()
    {

        $user = User::factory()->create();

        $lesson = Lesson::first();

        
        // Simulate a user watching a lesson by making an HTTP request
        $response = $this->actingAs($user) // Log in the user
            ->post('/watch-lesson', ['lesson_id' => $lesson->id]);
        
        $response->assertSuccessful();
        
        $user = $user->refresh();
        
        $this->assertTrue($user->achievements->contains('achievement_name', 'First Lesson Watched'));

    }

    /** @test */
    public function user_unlocks_first_comment_written_achievement()
    {
        // Create a user (if not already created)
        $user = User::factory()->create();

        $lesson = Lesson::first();

        $response = $this->actingAs($user) // Log in the user
                ->post('/write-comment', ['lesson_id' => $lesson->id, 'comment' => 'This is a comment']);

        $response->assertSuccessful();
        
        //fetch the user from the database to ensure achievements are updated
        $user = $user->fresh();

        // Assert that the user has unlocked the expected achievements
        $this->assertTrue($user->achievements->contains('achievement_name', 'First Comment Written'));

    }

    /** @test */
    public function user_unlocks_multiple_achievements_and_badges()
    {
        // Create a user
        $user = User::factory()->create();

        $lesson = Lesson::first();

        // retrieve the user from the database again to get the updated achievements
        $user = $user->fresh();
        
        
        $watch_lesson = $this->actingAs($user) // Log in the user
        ->post('/watch-lesson', ['lesson_id' => $lesson->id]);
        $watch_lesson->assertSuccessful();

        $write_comment = $this->actingAs($user) // Log in the user
                ->post('/write-comment', ['lesson_id' => $lesson->id, 'comment' => 'This is a comment']);
        $write_comment->assertSuccessful();

        // Assert that the user has unlocked multiple achievements
        $this->assertTrue($user->achievements->contains('achievement_name', 'First Lesson Watched'));
        $this->assertTrue($user->achievements->contains('achievement_name', 'First Comment Written'));
        // Add more assertions for other unlocked achievements

        
    }

    /** @test */
    public function user_unlocks_next_available_achievements()
    {
        // Create a user
        $user = User::factory()->create();

        

        //  retrieve the user from the database again to get the updated achievements
        $user = $user->fresh();

        // Calculate the next available achievements based on the unlocked ones
        $nextAvailableAchievements = $this->calculateNextAvailableAchievements($user->achievements->pluck('name')->toArray());

        // Assert that the next available achievements match the expected ones
        $this->assertContains('5 Lessons Watched', $nextAvailableAchievements);
        $this->assertContains('3 Comments Written', $nextAvailableAchievements);
        // Add more assertions for other next available achievements
    }

    /** @test */
    public function user_has_remaining_achievements_to_unlock_next_badge()
    {
        // Create a user who has unlocked some achievements but not enough for the next badge
        $user = User::factory()->create();
        

        // retrieve the user from the database again to get the updated achievements
        $user = $user->fresh();

        // Calculate the number of remaining achievements required for the next badge
        list($nextBadge, $remainingToUnlockNextBadge) = $this->calculateNextBadge($user->achievements->pluck('name')->toArray());

        // Assert that the user's next badge and remaining achievements are as expected
        $this->assertEquals('Intermediate', $nextBadge);
        $this->assertEquals(4, $remainingToUnlockNextBadge);
        // Add more assertions for other badge levels and remaining achievements
    }

    
    private function calculateNextAvailableAchievements($unlockedAchievements)
    {
        // Define an array of all available achievements and their required counts
        $availableAchievements = [
            'First Lesson Watched' => 1,
            '5 Lessons Watched' => 5,
            '10 Lessons Watched' => 10,
            '25 Lessons Watched' => 25,
            '50 Lessons Watched' => 50,
            'First Comment Written' => 1,
            '3 Comments Written' => 3,
            '5 Comments Written' => 5,
            '10 Comments Written' => 10,
            '20 Comments Written' => 20,
        ];

        // Filter out the achievements that the user has already unlocked
        $nextAvailableAchievements = array_diff_key($availableAchievements, array_flip($unlockedAchievements));

        return array_keys($nextAvailableAchievements);
    }

    private function calculateNextBadge($unlockedAchievements)
    {
        // Define badge criteria and their corresponding achievement counts
        $badgeCriteria = [
            'Beginner' => 0,
            'Intermediate' => 4,
            'Advanced' => 8,
            'Master' => 10,
        ];

        // Calculate the user's current badge
        $currentBadge = 'Beginner';
        foreach ($badgeCriteria as $badge => $achievementCount) {
            if (count($unlockedAchievements) >= $achievementCount) {
                $currentBadge = $badge;
            } else {
                break; // Exit loop when the user's count no longer matches the badge criteria
            }
        }

        // Calculate the next badge and remaining achievements to unlock it
        $nextBadge = null;
        $remainingToUnlockNextBadge = 0;
        foreach ($badgeCriteria as $badge => $achievementCount) {
            if (count($unlockedAchievements) < $achievementCount) {
                $nextBadge = $badge;
                $remainingToUnlockNextBadge = $achievementCount - count($unlockedAchievements);
                break; // Found the next badge
            }
        }

        return [$nextBadge, $remainingToUnlockNextBadge];
    }



}
