<?php

use Illuminate\Support\Facades\Route;

use Objectivehtml\Events\Tags\Events;

Route::get('feed', function() {
    // Instantiate a tag and mock the request as it would be through a template.
    $tag = new Events();
    $tag->setContext([]);
    $tag->setParameters(array_filter([
        'start' => request()->start,
        'end' => request()->end,
        'ttl' => request()->ttl
    ]));
    
    // Map the entries into arrays.
    $events = $tag->index()->map(function($entry) {
        return array_merge($entry->data()->toArray(), $entry->supplements()->toArray(), [
            'locale' => $entry->locale(),
            'url' => $entry->url()
        ]);
    });

    // Return events as JSON
    return response()->json($events);
});