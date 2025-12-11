<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fishes', function (Blueprint $table) {
            // 1. id: BIGINT UNSIGNED, PRIMARY KEY, AUTO_INCREMENT
            $table->id();

            // 2. name: VARCHAR(100), NOT NULL
            $table->string('name', 100);

            // 3. rarity: ENUM, NOT NULL (7 tingkat)
            $table->enum('rarity', [
                'Common', 'Uncommon', 'Rare', 'Epic', 'Legendary', 'Mythic', 'Secret'
            ]);

            // 4. base_weight_min: DECIMAL(8,2), NOT NULL
            $table->decimal('base_weight_min', 8, 2);

            // 5. base_weight_max: DECIMAL(8,2), NOT NULL
            $table->decimal('base_weight_max', 8, 2);

            // 6. sell_price_per_kg: INTEGER, NOT NULL
            $table->integer('sell_price_per_kg');

            // 7. catch_probability: DECIMAL(5,2), NOT NULL
            $table->decimal('catch_probability', 5, 2);

            // 8. description: TEXT, NULLABLE
            $table->text('description')->nullable();

            // 9 & 10. created_at, updated_at: TIMESTAMP
            $table->timestamps();
        });
    }
};
