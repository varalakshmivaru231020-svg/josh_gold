<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Support\RateBook;
use Illuminate\Database\QueryException;

// Public collection pages (/collections/{slug}): prices by weight for bullion, a gallery and rates for jewellery
class CollectionController extends Controller
{
    public function show(string $slug)
    {
        try {
            $collection = Collection::active()->where('slug', $slug)->first();
        } catch (QueryException $e) {
            // The collections table does not exist yet (deployed before "php artisan migrate")
            report($e);
            $collection = null;
        }

        abort_unless($collection, 404);

        return view('collections.show', [
            'collection' => $collection,
            'rate'       => RateBook::latest(),
            'others'     => Collection::activeCached()->reject(fn (Collection $c) => $c->id === $collection->id)->values(),
        ]);
    }
}
