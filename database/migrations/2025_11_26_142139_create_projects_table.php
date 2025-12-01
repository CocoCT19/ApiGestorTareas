<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
   public function up()
{
    Schema::create('projects', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('name');
        $table->text('description')->nullable();
        $table->integer('priority')->default(1); // 1 a 3
        $table->boolean('is_archived')->default(false);
        $table->timestamps();
    });
}


    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
