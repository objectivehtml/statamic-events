<?php

use Carbon\Carbon;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Statamic\Facades\Entry;
use Statamic\Facades\Collection;

Route::post('import-events', function() {   
    $collection = Collection::findByHandle(
        $handle = request()->input('map.collection', 'events')
    );

    $query = $collection->queryEntries()
        ->where(request()->input('map.title', 'title'), request()->title)
        ->where(request()->input('map.start_date', 'start_date'), Carbon::parse(request()->start_date)->format('Y-m-d H:i'))
        ->where(request()->input('map.end_date', 'end_date'), Carbon::parse(request()->end_date)->format('Y-m-d H:i'));
        
    if(!$query->count()) {
        $entry = Entry::make()
            ->collection($handle)
            ->slug(Str::slug(request()->title));
                
        $entry->set(request()->input('map.title', 'title'), request()->title);
        $entry->set(request()->input('map.start_date', 'start_date'), Carbon::parse(request()->start_date)->format('Y-m-d H:i'));
        $entry->set(request()->input('map.end_date', 'end_date'), Carbon::parse(request()->end_date)->format('Y-m-d H:i'));
        $entry->set(request()->input('map.all_day', 'all_day'), request()->all_day);
        
        $recurrence_rule = request()->recurrence_rule;

        if(is_string($byday = Arr::get($recurrence_rule, 'byday'))) {
            preg_match('/^[-\d]+/', $byday, $matches);

            if(isset($matches[0])) {
                $recurrence_rule['enable_bysetpos'] = true;
                $recurrence_rule['bysetpos'] = (int) $matches[0];
                $recurrence_rule['byday'] = preg_replace('/^([-\d]+)(.+)/', '$2', $byday);
            }
        }

        if(Arr::get($recurrence_rule, 'count')) {
            $recurrence_rule['end_after'] = 'count';
        }
        else if(Arr::get($recurrence_rule, 'until')) {
            $recurrence_rule['end_after'] = 'until';
        }

        $entry->set(request()->input('map.recurrence_rule', 'recurrence_rule'), $recurrence_rule);
        $entry->save();
    }
    else {
        return response()->json('Duplicate', 409);
    }
});