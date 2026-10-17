<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Outgoing WhatsApp messages (what was sent, to whom, and whether the provider accepted it).
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('whatsapp_messages')) {
            return;
        }

        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('phone', 20)->index();
            $table->string('template', 60);
            $table->string('lang', 2);
            $table->text('params')->nullable();
            $table->string('status', 10);          // sent | failed | logged
            $table->text('detail')->nullable();    // provider error, if any
            $table->timestamp('created_at')->useCurrent()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
    }
};
