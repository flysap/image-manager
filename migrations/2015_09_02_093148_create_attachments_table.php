<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (Schema::hasTable('attachments')) {
            Schema::create('attachments', function(Blueprint $table) {
                $table->integer('imageable_id');
                $table->string('imageable_type');
                $table->string('title');
                $table->string('path');
                $table->string('full_path');
                $table->string('extension');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        if (Schema::hasTable('attachments')) {
            Schema::drop('attachments');
        }
    }
}
