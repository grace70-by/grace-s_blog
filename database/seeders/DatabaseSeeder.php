<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@graceblog.test');
        $adminPassword = env('ADMIN_PASSWORD', 'password');
        $adminName = env('ADMIN_NAME', 'Administrateur');

        $appName = config('app.name');

        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => $adminName,
                'username' => 'admin',
                'password' => Hash::make($adminPassword),
                'role' => User::ROLE_ADMIN,
                'email_verified_at' => now(),
                'bio' => "Administrateur de {$appName}. Passionné de partage et de discussions.",
            ]
        );

        $pages = [
            [
                'slug' => 'a-propos',
                'title' => 'À propos',
                'body' => "{$appName} est un espace de partage où vous pouvez lire des articles, commenter et réagir comme sur un réseau social.\n\nNotre mission : créer une communauté bienveillante autour de contenus de qualité.",
            ],
            [
                'slug' => 'mentions-legales',
                'title' => 'Mentions légales',
                'body' => "Éditeur : {$appName}\nHébergement : à compléter\n\nLes contenus publiés par les utilisateurs engagent leur responsabilité.",
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], $page);
        }

        $this->command->info('Creating 20 users...');
        $users = User::factory(20)->create();
        $adminUser = User::where('email', $adminEmail)->first();
        if ($adminUser && !$users->contains('id', $adminUser->id)) {
            $users->push($adminUser);
        }

        $this->command->info('Creating 50 publications with blocks...');
        $publications = collect();
        foreach (range(1, 50) as $i) {
            $publication = \App\Models\Publication::factory()->create([
                'user_id' => $adminUser->id,
            ]);

            // Block 1: Intro Text
            \App\Models\PublicationBlock::factory()->create([
                'publication_id' => $publication->id,
                'sort_order' => 1,
            ]);

            // Block 2: Optional Embed (Video)
            if (fake()->boolean(30)) {
                 \App\Models\PublicationBlock::factory()->create([
                     'publication_id' => $publication->id,
                     'type' => \App\Models\PublicationBlock::TYPE_EMBED,
                     'sort_order' => 2,
                     'content' => ['url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
                 ]);
            }

            // Block 3: More Text
            \App\Models\PublicationBlock::factory()->create([
                'publication_id' => $publication->id,
                'sort_order' => 3,
            ]);

            $publications->push($publication);
        }

        $this->command->info('Creating reactions for publications...');
        foreach ($publications as $pub) {
            $likers = $users->random(rand(0, 10));
            foreach ($likers as $liker) {
                \App\Models\PublicationReaction::firstOrCreate([
                    'publication_id' => $pub->id,
                    'user_id' => $liker->id,
                ], [
                    'type' => 'like',
                ]);
            }
        }

        $this->command->info('Creating 150 top-level comments...');
        $comments = collect();
        foreach (range(1, 150) as $i) {
            $comment = \App\Models\Comment::factory()->create([
                'publication_id' => $publications->random()->id,
                'user_id' => $users->random()->id,
                'parent_id' => null,
            ]);
            $comments->push($comment);
        }

        $this->command->info('Creating 50 nested replies...');
        foreach (range(1, 50) as $i) {
            $parentComment = $comments->random();
            $reply = \App\Models\Comment::factory()->create([
                'publication_id' => $parentComment->publication_id,
                'user_id' => $users->random()->id,
                'parent_id' => $parentComment->id,
            ]);
            $comments->push($reply);
        }

        $this->command->info('Creating reactions for comments...');
        foreach ($comments->random(100) as $comment) {
            $likers = $users->random(rand(1, 5));
            foreach ($likers as $liker) {
                \App\Models\CommentReaction::firstOrCreate([
                    'comment_id' => $comment->id,
                    'user_id' => $liker->id,
                ], [
                    'type' => 'like',
                ]);
            }
        }

        $this->command->info('Seed data generated successfully!');
    }
}
