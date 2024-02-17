<?php

use App\Util\Colors;
use App\Util\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use function App\enum_values;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documentations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->text('slug')->unique();
            $table->enum('status', enum_values(Status::cases()))->default('draft');
            $table->enum('theme_color', enum_values(Colors::cases()))->default('neutral');
            $table->text('name')->unique();
            $table->text('logo')->nullable();
            $table->json('domains')->default('[]');
            $table->text('password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentations');
    }
};
