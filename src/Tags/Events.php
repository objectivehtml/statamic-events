<?php

namespace Objectivehtml\Events\Tags;

use Carbon\Carbon;
use DeepCopy\DeepCopy;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use RRule\RRule;
use Statamic\Entries\EntryCollection;
use Statamic\Facades\Collection as CollectionAPI;
use Statamic\Tags\Collection\Collection;

class Events extends Collection
{
    /**
     * The {{ events }} tag.
     *
     * @return Collection
     */
    public function index()
    {
        if(!$this->params->get('collection')) {
            throw new InvalidArgumentException(
                'The "collection" parameter is required for the {{ events }} tag.'
            );
        }

        if(!$field = $this->params->get('field')) {
            throw new InvalidArgumentException(
                'The "field" parameter is required for the {{ events }} tag.'
            );
        }
        
        $beginsAtField = sprintf('%s_begins_at', $field);
        $endsAtField = sprintf('%s_ends_at', $field);

        $results = parent::index();

        $events = is_array($results)
            ? $results[$this->params->get('as')]
            : $results;

        $events = $events->map(
            $this->mapRecurrenceRule($field, $beginsAtField, $endsAtField)
        );

        $events = $this->mergeOccurrences($events, $field)
            ->filter($this->filterFuture($beginsAtField, $endsAtField))
            ->filter($this->filterByTtlParam($endsAtField))
            ->filter($this->filterByStartParam($beginsAtField))
            ->filter($this->filterByEndParam($endsAtField))
            ->sortBy($this->sortByDate($beginsAtField))
            ->splice(
                $this->params->get('page', 1) - 1,
                $this->params->get('total', $this->params->get('limit', 100))
            )
            ->values();
        
        if(is_array($results)) {
            return array_merge($results, [
                $this->params->get('as') => $events,
                'total_results' => $events->count()
            ]);
        }

        return $events;
    }
    

    public function byDate()
    {
        return $this->index()
            ->groupBy(function($entry) {
                $beginsAtField = $this->params->get('start_field', 'start_date');
                
                $group_by = $this->params->get('group_by', $beginsAtField);

                $value = $entry->supplements()->get($group_by) ?: $entry->get($group_by);

                return Carbon::parse($value)->format($this->params->get('format', 'F'));
            })
            ->map(function($entries, $i) {
                return [
                    'group_by' => $i,
                    'entries' => $entries
                ];
            })
            ->values();
    }

    public function details()
    {
        $field = $this->params->get('field', 'recurrence_rule');
        
        $collection = CollectionAPI::find($this->params->get('collection', 'events'));

        $query = $collection->queryEntries();

        if($id = $this->params->get('id')) {
            $query->where('id', $id);
        }

        if($slug = $this->params->get('slug')) {
            $query->where('slug', $slug);
        }

        if($entry = $query->first()) {
            $beginsAtField = $this->params->get('start_field', 'start_date');
            $endsAtField = $this->params->get('end_field', 'end_date');
    
            return $this->mapRecurrenceRule($beginsAtField, $endsAtField, $field)($entry);
        }
    }

    public function next()
    {
        if(!$this->params->get('start')) {
            $this->params->put('start', 'now');
        }
        
        return $this->index()->slice(0, $this->params->get('total', 1));
    }

    // protected function setOccurrenceDate(Entry $entry, array $occurrence, string $field, string $beginsAtField, string $endsAtField)
    // {
    //     $data = $entry->$field;

    //     Arr::set($data, 'begins_at.date', $occurrence[$beginsAtField]);
    //     Arr::set($data, 'ends_at.date', $occurrence[$endsAtField]);

    //     $entry->set($field, $data);

    //     dd($entry);
    // }

    protected function mergeOccurrences(EntryCollection $events, string $field)
    {
        $copier = new DeepCopy();

        return $events->reduce(function($carry, $event) use ($field, $copier) {
            return collect(
                $event->supplements()->get(sprintf('%s_occurrences', $field))
            )->filter(function($occurrence) use ($event) {
                return $event->supplements()
                    ->only(array_keys($occurrence))
                    ->diff($occurrence)
                    ->count();
            })->reduce(function($carry, $occurrence) use ($event, $copier) {
                $clone = $copier->copy($event);

                foreach($occurrence as $key => $value) {
                    $clone->supplements()->put($key, $value);
                }

                return $carry->merge([$clone]);
            }, $carry);
        }, $events);
    }

    protected function sortByDate(string $dateField)
    {
        return function($entry) use ($dateField) {
            return $entry->supplements()->get($dateField)->timestamp;
        };
    }
    
    protected function mapRecurrenceRule(string $field, string $beginsAtField, string $endsAtField)
    {
        return function($entry) use ($field, $beginsAtField, $endsAtField) {
            // Get the recurrence field.
            if(!$data = $entry->get($field)) {
                return $entry;
            }

            $beginsAt = Carbon::parse(Arr::get($data, 'begins_at.date'));

            if($beginsAtTime = Arr::get($data, 'begins_at.time')) {
                $beginsAt->setTimeFromTimeString($beginsAtTime);
            }

            $entry->supplements()->put($beginsAtField, $beginsAt);

            $endsAt = Carbon::parse(
                Arr::get($data, 'ends_at.date') ?: $beginsAt
            );

            if($endsAtTime = Arr::get($data, 'ends_at.time')) {
                $endsAt->setTimeFromTimeString($endsAtTime);
            }
            
            $entry->supplements()->put($endsAtField, $endsAt);

            $entry->supplements()->put(
                sprintf('%s_same_day', $field), $beginsAt->format('Ymd') == $endsAt->format('Ymd')
            );
            
            // Get the diff in seconds from the start and end date
            $diff = $beginsAt->diffInSeconds($endsAt);

            $entry->supplements()->put(sprintf('%s_diff_seconds', $field), $diff);
            
            if(Arr::get($data, 'recurring')) {
                $rrule = new RRule(Arr::get($data, 'rrule'), $beginsAt);

                // Map the recurrence rules into an RRule instance.
                $entry->supplements()->put($field, $rrule);

                // Get the next
                $occurrences = $rrule->getOccurrencesAfter(now(), false, $this->params->get('total_occurrences', 10));

                // If event has future occurrences, supplement the data.
                if(count($occurrences)) {
                    $beginsAt = Carbon::parse($occurrences[0]);

                    $entry->supplements()->put($beginsAtField, $beginsAt);
                    $entry->supplements()->put($endsAtField, $beginsAt->clone()->addSeconds($diff));

                    $occurrences = collect($occurrences)->map(function($occurrence) use ($beginsAtField, $endsAtField, $diff) {
                        return [
                            $beginsAtField => $beginsAt = Carbon::parse($occurrence),
                            $endsAtField => $beginsAt->clone()->addSeconds($diff)
                        ];
                    });

                    $entry->supplements()->put(
                        sprintf('%s_occurrences', $field), $occurrences->all()
                    );
                }
            }

            // Return the transformed entry
            return $entry;
        };
    }
    
    protected function filterFuture(string $beginsAtField, string $endsAtField) {
        return function($entry) use ($beginsAtField, $endsAtField) {
            $future = $this->params->get('future');

            if($future === true) {
                return $entry->supplements()->get($endsAtField) >= now();
            }
            else if($future === false) {
                return $entry->supplements()->get($beginsAtField) < now();
            }

            return true;
        };
    }
    
    protected function filterByTtlParam(string $dateField)
    {
        return function($entry) use ($dateField) {
            if($ttl = $this->params->get('ttl')) {
                return $entry->get($dateField) < now()->add($ttl);
            }
            
            return true;
        };
    }

    protected function filterByStartParam(string $beginsAtField)
    {
        return function($entry) use ($beginsAtField) {
            if($start = $this->params->get('start')) {
                return $entry->supplements()
                    ->get($beginsAtField)
                    ->isAfter(Carbon::parse($start));
            }

            return true;
        };
    }

    protected function filterByEndParam(string $endsAtField)
    {
        return function($entry) use ($endsAtField) {
            if($end = $this->params->get('end')) {
                return $entry->supplements()
                    ->get($endsAtField)
                    ->isBefore(Carbon::parse($end));
            }

            return true;
        };
    }

    /*
    protected function filterByDate(string $dateField, string $recurrenceField)
    {
        return function($entry) use ($dateField, $recurrenceField) {
            if($rrule = $entry->supplements()->get($recurrenceField)) {
                return !!$rrule->getNthOccurrenceAfter(now(), 1);
            }

            return !Carbon::parse($entry->get($dateField))->isPast();
        };
    }
    */
}
