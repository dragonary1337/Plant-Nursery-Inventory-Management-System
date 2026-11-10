<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            $table->decimal('unit_price', 10, 2)->unsigned()->change();
        });

        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $constraints = DB::select(
            "SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_TYPE = 'CHECK'",
            ['plants'],
        );
        $names = array_column($constraints, 'CONSTRAINT_NAME');

        if (in_array('plants_unit_price_positive', $names, true)) {
            DB::statement('ALTER TABLE plants DROP CONSTRAINT plants_unit_price_positive');
        }

        if (! in_array('plants_unit_price_nonnegative', $names, true)) {
            DB::statement(
                'ALTER TABLE plants ADD CONSTRAINT plants_unit_price_nonnegative CHECK (unit_price >= 0)'
            );
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE plants DROP CONSTRAINT plants_unit_price_nonnegative');
        }

        Schema::table('plants', function (Blueprint $table) {
            $table->double('unit_price', 8, 2)->change();
        });
    }
};
