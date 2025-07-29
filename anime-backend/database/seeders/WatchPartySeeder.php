<?php

namespace Database\Seeders;

use AnimeSite\Enums\WatchPartyStatus;
use AnimeSite\Models\Anime;
use AnimeSite\Models\Episode;
use AnimeSite\Models\User;
use AnimeSite\Models\WatchParty;
use AnimeSite\Models\WatchPartyMessage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WatchPartySeeder extends Seeder
{
    public function run(): void
    {
        // Create users
        $users = User::factory()->count(10)->create();

        $episodes = Episode::all();

        if ($users->isEmpty() || $episodes->isEmpty()) {
            $this->command->warn('Немає користувачів або епізодів для створення кімнат. Спочатку запустіть UserSeeder та EpisodeSeeder.');
            return;
        }

        // Create 50 watch parties
        $parties = [];
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $episode = $episodes->random();
            $isPrivate = rand(0, 1) === 1;
            $maxViewers = rand(5, 20);

            // Determine room status
            $status = WatchPartyStatus::cases()[array_rand(WatchPartyStatus::cases())];

            // Set dates based on status
            $startedAt = null;
            $endedAt = null;

            if ($status === WatchPartyStatus::ACTIVE) {
                $startedAt = now()->subHours(rand(1, 24));
            } elseif ($status === WatchPartyStatus::ENDED) {
                $startedAt = now()->subDays(rand(1, 30));
                $endedAt = now()->subHours(rand(1, 12));
            }

            $party = WatchParty::create([
                'id' => Str::ulid(),
                'name' => "Кімната {$user->name} - {$episode->name}",
                'slug' => "party-{$i}-" . Str::random(6),
                'user_id' => $user->id,
                'episode_id' => $episode->id,
                'watch_party_status' => $status,
                'is_private' => $isPrivate,
                'password' => $isPrivate ? bcrypt('password') : null,
                'max_viewers' => $maxViewers,
                'started_at' => $startedAt,
                'ended_at' => $endedAt,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $parties[] = $party;

            // Add viewers to the watch party
            $viewersCount = rand(1, min($maxViewers - 1, 5));
            $viewers = $users->random($viewersCount);

            foreach ($viewers as $viewer) {
                if ($viewer->id === $user->id) {
                    continue;
                }

                $joinedAt = null;
                $leftAt = null;

                if ($status === WatchPartyStatus::ACTIVE) {
                    $joinedAt = now()->subMinutes(rand(5, 120));
                    $leftAt = rand(0, 1) === 1 ? now()->subMinutes(rand(1, 5)) : null;
                } elseif ($status === WatchPartyStatus::ENDED && $startedAt && $endedAt) {
                    $joinedAt = (clone $startedAt)->addMinutes(rand(1, 30));
                    $leftAt = (clone $endedAt)->subMinutes(rand(1, 30));

                    if ($leftAt <= $joinedAt) {
                        $leftAt = (clone $joinedAt)->addMinutes(rand(5, 60));
                    }
                }

                if ($joinedAt) {
                    $party->viewers()->attach($viewer->id, [
                        'joined_at' => $joinedAt,
                        'left_at' => $leftAt,
                    ]);
                }

                // Create 1-5 messages per viewer (or host) for active or ended parties
                if ($status !== WatchPartyStatus::WAITING) {
                    $messageCount = rand(1, 5);
                    $possibleSenders = collect([$user, $viewer]); // Host and viewer can send messages
                    for ($j = 0; $j < $messageCount; $j++) {
                        $sender = $possibleSenders->random();
                        WatchPartyMessage::create([
                            'id' => Str::ulid(),
                            'watch_party_id' => $party->id,
                            'user_id' => $sender->id,
                            'message' => $this->faker()->sentence(),
                            'created_at' => $joinedAt ? (clone $joinedAt)->addMinutes(rand(1, 60)) : now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        $this->command->info('Створено ' . count($parties) . ' тестових кімнат з глядачами та повідомленнями.');
    }

    protected function faker()
    {
        return \Faker\Factory::create();
    }

}
