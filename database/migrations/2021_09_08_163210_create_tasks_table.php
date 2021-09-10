<?php

use App\Models\State;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            // @link https://laravel.com/docs/8.x/migrations#foreign-key-constraints
            $table->foreignId('parent_id')->index()->nullable()->constrained('tasks', 'id')->cascadeOnDelete();
            $table->string('title');
            $table->text('body');
            $table->foreignIdFor(State::class)->index();
            $table->integer('sort_order')->nullable();
            $table->foreignIdFor(User::class)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
