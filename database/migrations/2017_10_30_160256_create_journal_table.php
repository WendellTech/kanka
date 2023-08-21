<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJournalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->notNull();
            $table->string('slug');
            $table->unsignedInteger('journal_id')->nullable();
            $table->string('type')->nullable();
            $table->string('image', 255)->nullable();
            $table->date('date')->nullable();
            $table->unsignedInteger('campaign_id')->notNull();
            $table->unsignedInteger('location_id')->nullable();
            $table->unsignedInteger('author_id')->nullable();

            // Overview
            $table->longText('entry')->nullable();

            $table->unsignedInteger('_lft')->default(0);
            $table->unsignedInteger('_rgt')->default(0);
            $table->timestamps();

            // Privacy
            $table->boolean('is_private')->default(false);
            $table->index(['is_private']);

            // Foreign
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('entities')->nullOnDelete();
            $table->foreign('location_id')->references('id')->on('locations')->nullOnDelete();
            $table->foreign('journal_id')->references('id')->on('journals')->onDelete('set null');

            // Index
            $table->index(['name', 'slug', 'type', 'date']);
            $table->index(['_lft', '_rgt']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('journals');
    }
}
