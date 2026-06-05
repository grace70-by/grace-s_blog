<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            if (Schema::hasColumn('publications', 'slug')) {
                $table->dropColumn('slug');
            }
            if (Schema::hasColumn('publications', 'excerpt')) {
                $table->dropColumn('excerpt');
            }
        });
    }

    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            if (! Schema::hasColumn('publications', 'slug')) {
                $table->string('slug')->unique()->after('title');
            }
            if (! Schema::hasColumn('publications', 'excerpt')) {
                $table->text('excerpt')->nullable()->after('slug');
            }
        });
    }
};
