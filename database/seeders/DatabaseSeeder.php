<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    // Images Unsplash (Tech, Finance, Musique, et Général)
    private array $images = [
        // Tech
        'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800', // Circuit
        'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=800', // Code laptop
        'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=800', // Cyber
        // Finance
        'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800', // Trading
        'https://images.unsplash.com/photo-1579621970588-a35dce9bc8e5?w=800', // Money
        'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800', // Stock market
        // Musique
        'https://images.unsplash.com/photo-1511671782633-d11bd9a4f458?w=800', // Concert
        'https://images.unsplash.com/photo-1514320291840-2e0a9ca66ce7?w=800', // Vinyl
        'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=800', // Headphones
        // Général / Lifestyle / Nature
        'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800', // Stars
        'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800', // Forest
        'https://images.unsplash.com/photo-1472214103451-9374bd1c798e?w=800', // Landscape
        'https://images.unsplash.com/photo-1519681393784-d120267933ba?w=800', // Mountains
        'https://images.unsplash.com/photo-1501854140801-50d01698950b?w=800', // Sunset
        'https://images.unsplash.com/photo-1465146344425-f00d5f5c8f07?w=800', // Ocean
    ];

    // Textes courts et variés (Tech, Finance, Musique, Lifestyle)
    private array $introTexts = [
        "L'intelligence artificielle transforme notre quotidien à une vitesse folle. Focus sur les dernières avancées qui redessinent le futur.",
        "Le développement web ne cesse d'évoluer. Voici quelques frameworks et outils qui ont retenu mon attention cette semaine.",
        "Comprendre les marchés financiers n'a jamais été aussi crucial. Décryptage des tendances économiques actuelles.",
        "Les cryptomonnaies et la finance décentralisée (DeFi) bouleversent les codes traditionnels. Faut-il y voir une opportunité ?",
        "La musique a ce pouvoir unique de nous transporter instantanément. Retour sur les albums qui ont marqué mon année.",
        "L'évolution de la production musicale avec les nouveaux outils numériques est fascinante. Exploration d'un monde en pleine mutation.",
        "Je voulais vous parler d'un sujet qui me passionne depuis toujours. La nature humaine est fascinante dans toute sa complexité.",
        "Aujourd'hui je vous partage une réflexion qui me tient à cœur depuis longtemps. La vie nous réserve parfois de belles surprises.",
        "Une promenade, un livre, une conversation... Parfois les meilleures idées naissent des moments les plus simples.",
        "Le monde change vite, très vite. Voici comment j'essaie de garder le cap face aux turbulences du quotidien.",
    ];

    private array $bodyTexts = [
        "Que l'on soit développeur ou simple utilisateur, rester à jour sur ces technologies est devenu indispensable pour comprendre le monde de demain.",
        "Il est fascinant de voir à quel point l'open-source a accéléré l'innovation. La collaboration mondiale est la clé de ces succès.",
        "Diversifier ses investissements reste la règle d'or. N'oubliez jamais de faire vos propres recherches avant de prendre une décision financière.",
        "L'économie mondiale est interconnectée. Un événement à l'autre bout du monde peut avoir des répercussions directes sur notre portefeuille.",
        "Et vous, quels sont les morceaux qui tournent en boucle dans vos écouteurs en ce moment ? Partagez vos découvertes en commentaire !",
        "La scène indépendante regorge de pépites qui méritent d'être mises en lumière. Soutenons les artistes locaux.",
        "Ce que j'ai appris de cette expérience, c'est que la patience est la clé de presque tout. Rien de grand ne se construit en un jour.",
        "Je terminerai par cette pensée : la gratitude transforme ce que nous avons en suffisance, et plus encore. Prenez soin de vous.",
        "En fin de compte, ce sont les petites choses qui comptent le plus. Un sourire, un mot d'encouragement, un moment de silence partagé.",
        "J'espère que ces mots vous auront touché d'une façon ou d'une autre. N'hésitez pas à partager vos propres réflexions en commentaire.",
    ];

    private array $titles = [
        "Le futur de l'Intelligence Artificielle",
        "L'évolution du développement Web",
        "Cybersécurité : les nouveaux enjeux",
        "Pourquoi l'open-source a gagné",
        "Investir en 2026 : les pièges à éviter",
        "Comprendre la blockchain simplement",
        "L'inflation et notre pouvoir d'achat",
        "L'essor de la finance décentralisée",
        "Les albums incontournables de l'année",
        "Comment la tech change la musique",
        "Le retour en force du vinyle",
        "À la découverte de la scène indépendante",
        "Réflexions du matin",
        "L'art de ralentir",
        "Fragments de vie",
        "Le goût des petites choses",
        "Instants suspendus",
        "Entre deux saisons",
        "Carnets de route",
        "Lumière sur l'essentiel",
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
                'user_id'      => $adminUser->id,
                'title'        => $titlePool[($i - 1) % count($titlePool)] . " #{$i}",
                'status'       => 'published',
                'published_at' => now()->subDays(rand(0, 60)),
                'created_at'   => now()->subDays(rand(0, 60)),
                'updated_at'   => now()->subDays(rand(0, 10)),
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
