<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStayNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stay_notes', function (Blueprint $table) {
            $table->id();
            $table->string('ref_id', 48)->unique();
            $table->foreignId('stay_id')->constrained('stays')->onDelete('cascade');
            $table->string('type', 16)->index();
            $table->string('note');
            $table->dateTime('date_note')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stay_notes');
    }
}
