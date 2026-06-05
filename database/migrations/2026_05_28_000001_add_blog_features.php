<?php

use App\Models\Publication;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            if (! Schema::hasColumn('publications', 'slug')) {
                $table->string('slug')->nullable()->after('title');
            }
            if (! Schema::hasColumn('publications', 'meta_description')) {
                $table->string('meta_description', 500)->nullable()->after('status');
            }
            if (! Schema::hasColumn('publications', 'views_count')) {
                $table->unsignedBigInteger('views_count')->default(0)->after('published_at');
            }
            if (! Schema::hasColumn('publications', 'reading_time_minutes')) {
                $table->unsignedSmallInteger('reading_time_minutes')->nullable()->after('views_count');
            }
        });

        Publication::query()->whereNull('slug')->orWhere('slug', '')->each(function (Publication $publication) {
            $base = Str::slug($publication->title) ?: 'article-'.$publication->id;
            $slug = $base;
            $counter = 1;

            while (Publication::where('slug', $slug)->where('id', '!=', $publication->id)->exists()) {
                $slug = $base.'-'.$counter;
                $counter++;
            }

            $publication->update(['slug' => $slug]);
        });

        Schema::table('publications', function (Blueprint $table) {
            $table->unique('slug');
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('publication_tag', function (Blueprint $table) {
            $table->foreignId('publication_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['publication_id', 'tag_id']);
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('body');
            $table->timestamps();
        });

        Schema::create('media_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('original_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('role');
            }
        });

        Schema::table('comments', function (Blueprint $table) {
            if (! Schema::hasColumn('comments', 'edited_at')) {
                $table->timestamp('edited_at')->nullable()->after('body');
            }
        });

        if (! Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('media_files');
        Schema::dropIfExists('publication_tag');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('pages');

        Schema::table('comments', function (Blueprint $table) {
            if (Schema::hasColumn('comments', 'edited_at')) {
                $table->dropColumn('edited_at');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'bio')) {
                $table->dropColumn('bio');
            }
        });

        Schema::table('publications', function (Blueprint $table) {
            $columns = ['slug', 'meta_description', 'views_count', 'reading_time_minutes'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('publications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
