<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    // Images Unsplash fixes (pas de requête API, URLs directes)
    private array $images = [
        'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800',
        'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800',
        'https://images.unsplash.com/photo-1472214103451-9374bd1c798e?w=800',
        'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800',
        'https://images.unsplash.com/photo-1519681393784-d120267933ba?w=800',
        'https://images.unsplash.com/photo-1501854140801-50d01698950b?w=800',
        'https://images.unsplash.com/photo-1465146344425-f00d5f5c8f07?w=800',
        'https://images.unsplash.com/photo-1490730141103-6cac27aaab94?w=800',
        'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?w=800',
        'https://images.unsplash.com/photo-1511884642898-4c92249e20b6?w=800',
        'https://images.unsplash.com/photo-1470770841072-f978cf4d019e?w=800',
        'https://images.unsplash.com/photo-1418065460487-3e41a6c84dc5?w=800',
        'https://images.unsplash.com/photo-1475924156734-496f6cac6ec1?w=800',
        'https://images.unsplash.com/photo-1433086966358-54859d0ed716?w=800',
        'https://images.unsplash.com/photo-1448375240586-882707db888b?w=800',
    ];

    // Textes courts et fixes (pas de fake()->realText() qui consomme trop de mémoire)
    private array $introTexts = [
        "Aujourd'hui je vous partage une réflexion qui me tient à cœur depuis longtemps. La vie nous réserve parfois de belles surprises quand on sait ouvrir les yeux.",
        "Voici quelques pensées sur ce qui fait la beauté de notre quotidien. Chaque journée est une nouvelle occasion de s'émerveiller.",
        "Je voulais vous parler d'un sujet qui me passionne depuis toujours. La nature humaine est fascinante dans toute sa complexité.",
        "Une expérience récente m'a beaucoup appris. Je la partage avec vous en espérant qu'elle vous inspirera autant qu'elle m'a touché.",
        "Quelques mots sur un sujet qui me tient à cœur. La bienveillance est une force trop souvent sous-estimée dans notre société.",
        "Je reviens avec un nouveau billet après quelques jours de réflexion. Les mots ont parfois du mal à venir, mais ils finissent toujours par trouver leur chemin.",
        "Une promenade, un livre, une conversation... Parfois les meilleures idées naissent des moments les plus simples.",
        "Aujourd'hui je vous emmène dans un voyage intérieur. La méditation et la pleine conscience ont transformé ma façon de voir les choses.",
        "Le monde change vite, très vite. Voici comment j'essaie de garder le cap face aux turbulences du quotidien.",
        "La créativité est un muscle qui se travaille chaque jour. Voici mes réflexions sur ce sujet qui m'accompagne depuis l'enfance.",
    ];

    private array $bodyTexts = [
        "Ce que j'ai appris de cette expérience, c'est que la patience est la clé de presque tout. Rien de grand ne se construit en un jour, et c'est peut-être là toute la beauté de la chose.",
        "Je terminerai par cette pensée : la gratitude transforme ce que nous avons en suffisance, et plus encore. Prenez soin de vous et des autres.",
        "En fin de compte, ce sont les petites choses qui comptent le plus. Un sourire, un mot d'encouragement, un moment de silence partagé.",
        "J'espère que ces mots vous auront touché d'une façon ou d'une autre. N'hésitez pas à partager vos propres réflexions en commentaire.",
        "La route est longue mais le paysage est beau. C'est tout ce que j'ai besoin de savoir pour continuer d'avancer chaque matin.",
        "Merci de m'avoir lu jusqu'ici. Vos retours et commentaires sont toujours une source d'inspiration précieuse pour moi.",
        "Le voyage intérieur est sans doute le plus périlleux et le plus récompensant de tous. Bonne route à chacun d'entre vous.",
        "Je vous laisse avec cette question : qu'est-ce qui vous rend vraiment vivant ? Prenez le temps d'y répondre honnêtement.",
        "La simplicité est une forme de sophistication que peu de gens maîtrisent vraiment. J'y travaille chaque jour.",
        "À bientôt pour de nouvelles réflexions. La vie est trop courte pour ne pas partager ce qui nous fait vibrer.",
    ];

    private array $titles = [
        "Réflexions du matin",
        "Ce que la nature m'a appris",
        "Petites victoires quotidiennes",
        "Sur le chemin de la sérénité",
        "Quand les mots manquent",
        "L'art de ralentir",
        "Fragments de vie",
        "Entre deux saisons",
        "La force du silence",
        "Vers quelque chose de nouveau",
        "Histoires du soir",
        "Ce qui me fait avancer",
        "Lumière sur l'essentiel",
        "Un pas après l'autre",
        "Les choses qui comptent",
        "Instants suspendus",
        "À contre-courant",
        "Le goût des petites choses",
        "Carnets de route",
        "Dans le creux de la vague",
    ];

    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@graceblog.test');
        $adminPassword = env('ADMIN_PASSWORD', 'password');
        $adminName = env('ADMIN_NAME', 'Administrateur');
        $appName = config('app.name');

        // ── Admin ──────────────────────────────────────────────────
        $adminUser = User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name'               => $adminName,
                'username'           => 'admin',
                'password'           => Hash::make($adminPassword),
                'role'               => User::ROLE_ADMIN,
                'email_verified_at'  => now(),
                'bio'                => "Administrateur de {$appName}. Passionné de partage et de discussions.",
            ]
        );

        // ── Pages statiques ────────────────────────────────────────
        foreach ([
            [
                'slug'  => 'a-propos',
                'title' => 'À propos',
                'body'  => "{$appName} est un espace de partage où vous pouvez lire des articles, commenter et réagir.\n\nNotre mission : créer une communauté bienveillante autour de contenus de qualité.",
            ],
            [
                'slug'  => 'mentions-legales',
                'title' => 'Mentions légales',
                'body'  => "Éditeur : {$appName}\nHébergement : Railway\n\nLes contenus publiés par les utilisateurs engagent leur responsabilité.",
            ],
        ] as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], $page);
        }

        // ── 20 Utilisateurs ────────────────────────────────────────
        $this->command->info('Creating 20 users...');
        $userNames = [
            'Sophie Martin', 'Lucas Bernard', 'Emma Dubois', 'Noah Thomas',
            'Léa Robert', 'Ethan Richard', 'Chloé Petit', 'Hugo Moreau',
            'Camille Simon', 'Arthur Laurent', 'Inès Michel', 'Louis Garcia',
            'Jade Martinez', 'Raphaël David', 'Manon Wilson', 'Tom Leroy',
            'Zoé Anderson', 'Nathan Taylor', 'Lucie Moore', 'Maxime Jackson',
        ];

        $users = collect();
        foreach ($userNames as $index => $name) {
            $slug = \Illuminate\Support\Str::slug($name);
            $user = User::updateOrCreate(
                ['email' => "{$slug}@example.com"],
                [
                    'name'              => $name,
                    'username'          => str_replace('-', '_', $slug),
                    'password'          => Hash::make('password'),
                    'role'              => User::ROLE_USER,
                    'email_verified_at' => now(),
                    'bio'               => null,
                ]
            );
            $users->push($user);
        }
        $users->push($adminUser);

        // ── 50 Publications ────────────────────────────────────────
        $this->command->info('Creating 50 publications with blocks...');
        $publications = collect();
        $titlePool    = $this->titles;
        shuffle($titlePool);

        foreach (range(1, 50) as $i) {
            $publication = \App\Models\Publication::create([
                'user_id'    => $adminUser->id,
                'title'      => $titlePool[($i - 1) % count($titlePool)] . " #{$i}",
                'created_at' => now()->subDays(rand(0, 60)),
                'updated_at' => now()->subDays(rand(0, 10)),
            ]);

            // Bloc 1 : texte intro
            \App\Models\PublicationBlock::create([
                'publication_id' => $publication->id,
                'type'           => \App\Models\PublicationBlock::TYPE_TEXT,
                'content'        => ['text' => $this->introTexts[$i % count($this->introTexts)]],
                'sort_order'     => 1,
            ]);

            // Bloc 2 : image (30% de chance) — URL directe Unsplash, pas d'upload
            if (($i % 3) === 0) {
                \App\Models\PublicationBlock::create([
                    'publication_id' => $publication->id,
                    'type'           => \App\Models\PublicationBlock::TYPE_EMBED,
                    'content'        => ['url' => $this->images[$i % count($this->images)]],
                    'sort_order'     => 2,
                ]);
            }

            // Bloc 3 : texte de fin
            \App\Models\PublicationBlock::create([
                'publication_id' => $publication->id,
                'type'           => \App\Models\PublicationBlock::TYPE_TEXT,
                'content'        => ['text' => $this->bodyTexts[$i % count($this->bodyTexts)]],
                'sort_order'     => 3,
            ]);

            $publications->push($publication);
        }

        // ── Réactions sur publications ─────────────────────────────
        $this->command->info('Creating reactions for publications...');
        foreach ($publications as $pub) {
            $likers = $users->random(rand(0, 8));
            foreach ($likers as $liker) {
                \App\Models\PublicationReaction::firstOrCreate(
                    ['publication_id' => $pub->id, 'user_id' => $liker->id],
                    ['type' => 'like']
                );
            }
        }

        // ── 150 Commentaires ───────────────────────────────────────
        $this->command->info('Creating 150 comments...');
        $commentTexts = [
            "Très beau billet, merci pour ce partage !",
            "Ça me parle beaucoup, merci.",
            "Je partage tout à fait cette vision.",
            "Magnifique, continuez comme ça !",
            "Très inspirant, merci pour ces mots.",
            "C'est exactement ce dont j'avais besoin de lire aujourd'hui.",
            "Superbe réflexion, bravo !",
            "Merci pour cette belle perspective.",
            "Tellement vrai, merci du partage.",
            "Je reviendrai lire ceci quand j'en aurai besoin.",
        ];

        $comments = collect();
        foreach (range(1, 150) as $i) {
            $comment = \App\Models\Comment::create([
                'publication_id' => $publications->random()->id,
                'user_id'        => $users->random()->id,
                'parent_id'      => null,
                'body'           => $commentTexts[$i % count($commentTexts)],
                'created_at'     => now()->subDays(rand(0, 30)),
            ]);
            $comments->push($comment);
        }

        // ── 50 Réponses ────────────────────────────────────────────
        $this->command->info('Creating 50 replies...');
        $replyTexts = [
            "Tout à fait d'accord avec toi !",
            "Merci pour ce retour bienveillant.",
            "Je suis content que ça t'ait plu !",
            "C'est sympa de te lire ici.",
            "À bientôt pour de nouveaux billets !",
        ];

        foreach (range(1, 50) as $i) {
            $parent = $comments->random();
            $reply  = \App\Models\Comment::create([
                'publication_id' => $parent->publication_id,
                'user_id'        => $users->random()->id,
                'parent_id'      => $parent->id,
                'body'           => $replyTexts[$i % count($replyTexts)],
                'created_at'     => now()->subDays(rand(0, 15)),
            ]);
            $comments->push($reply);
        }

        // ── Réactions sur commentaires ─────────────────────────────
        $this->command->info('Creating reactions for comments...');
        foreach ($comments->random(min(100, $comments->count())) as $comment) {
            $likers = $users->random(rand(1, 4));
            foreach ($likers as $liker) {
                \App\Models\CommentReaction::firstOrCreate(
                    ['comment_id' => $comment->id, 'user_id' => $liker->id],
                    ['type' => 'like']
                );
            }
        }

        $this->command->info('✅ Seed completed successfully!');
    }
}
