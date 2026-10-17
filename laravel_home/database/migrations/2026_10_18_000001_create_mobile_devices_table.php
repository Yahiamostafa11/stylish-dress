<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
| Push-notification registry for the mobile app: one row per installed app (FCM token).
| `user_id` is the WordPress user the device is currently signed in as (wp_users.ID), so a
| logout simply deletes the row. `token_hash` keeps the unique index short — FCM tokens are
| ~160+ chars and can grow.
*/
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('mobile_devices')) {
            return;
        }

        Schema::create('mobile_devices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->char('token_hash', 64)->unique();
            $table->text('token');
            $table->string('platform', 10);          // android | ios
            $table->string('locale', 5)->default('ar');
            $table->string('app_version', 20)->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_devices');
    }
};
