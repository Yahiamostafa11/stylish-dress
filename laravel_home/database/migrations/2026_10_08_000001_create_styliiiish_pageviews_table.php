<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
| First-party, cookie-less visit counter for the storefront. `visitor` is a daily-salted
| hash (IP + user agent + day + app key): it can't be reversed to a person and it changes
| every day, so nobody can be followed across days. Read by the owner dashboard (Statistics).
*/
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('styliiiish_pageviews')) {
            return;
        }

        Schema::create('styliiiish_pageviews', function (Blueprint $table) {
            $table->id();
            $table->date('day');               // Cairo-local calendar day
            $table->char('visitor', 16);       // salted daily hash
            $table->string('path', 190);       // path only — no query string
            $table->timestamp('created_at')->useCurrent();

            $table->index(['day', 'visitor']);
            $table->index(['day', 'path']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('styliiiish_pageviews');
    }
};
