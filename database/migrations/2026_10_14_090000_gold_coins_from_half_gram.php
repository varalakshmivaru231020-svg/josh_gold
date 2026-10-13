<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

// Gold coins start at 0.5 g. Only touches the row if it still has the original seeded values,
// so anything already edited in Admin → Collections is left alone.
return new class extends Migration
{
    public function up(): void
    {
        $this->swap(
            ['1,2,5,8,10,20,50,100', '1 g to 100 g, priced live', '24K and 22K gold coins from 1 gram to 100 grams, priced at our latest published gold rate.'],
            ['0.5,1,2,5,8,10,20,50,100', '0.5 g to 100 g, priced live', '24K and 22K gold coins from 0.5 gram to 100 grams, priced at our latest published gold rate.']
        );
    }

    public function down(): void
    {
        $this->swap(
            ['0.5,1,2,5,8,10,20,50,100', '0.5 g to 100 g, priced live', '24K and 22K gold coins from 0.5 gram to 100 grams, priced at our latest published gold rate.'],
            ['1,2,5,8,10,20,50,100', '1 g to 100 g, priced live', '24K and 22K gold coins from 1 gram to 100 grams, priced at our latest published gold rate.']
        );
    }

    private function swap(array $from, array $to): void
    {
        $row = DB::table('collections')->where('slug', 'gold-coins')->first();
        if (! $row) {
            return;
        }

        $changes = [];
        foreach (['weights', 'tagline', 'intro'] as $i => $column) {
            if ($row->{$column} === $from[$i]) {
                $changes[$column] = $to[$i];
            }
        }
        if ($changes) {
            DB::table('collections')->where('id', $row->id)->update($changes + ['updated_at' => now()]);
        }

        Cache::forget('collections.active');
    }
};
