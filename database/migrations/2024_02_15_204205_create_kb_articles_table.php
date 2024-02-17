<?php

use App\Models\Documentation;
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
        Schema::create('kb_articles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->foreignIdFor(Documentation::class);
            $table->text('slug');
            $table->enum('status', enum_values(Status::cases()))->default('draft');
            $table->text('title');
            $table->text('subtitle')->nullable();
            $table->text('icon_mode')->nullable();
            $table->text('icon')->nullable();
            $table->text('header_image')->nullable();
            $table->longText('content');

            $table->unique(['slug', 'documentation_id']);
            $table->unique(['title', 'documentation_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kb_articles');
    }
};
