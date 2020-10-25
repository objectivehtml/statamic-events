<?php

namespace Objectivehtml\Events\Tags;

use Carbon\Carbon;
use DateTime;
use Exception;
use RRule\RRule;
use Statamic\Facades\Collection as CollectionAPI;
use Statamic\Tags\Collection\Collection;

class Events extends Collection
{
    const EXCLUDED_ATTRIBUTES = [
        'enable', 'enable_bysetpos', 'end_after', 'rrule'
    ];

    /**
     * The {{ events }} tag.
     *
     * @return Collection
     */
    public function index()
    {
        $field = $this->params->get('field', 'recurrence_rule');
        
        if(!$this->params->get('collection')) {
            $this->params->put('collection', 'events');
        }

        $startField = $this->params->get('start_field', 'start_date');
        $endField = $this->params->get('end_field', 'end_date');

        return parent::index()
            ->map($this->mapRecurrenceRule($startField, $endField, $field))
            ->filter($this->filterFuture($startField, $endField))
            ->filter($this->filterByTtlParam($endField))
            // ->filter($this->filterByDate($endField, $field))
            ->filter($this->filterByStartParam($startField))
            ->filter($this->filterByEndParam($endField))
            ->sortBy($this->sortByDate($startField))
            ->splice(
                $this->params->get('page', 1) - 1,
                $this->params->get('total', 100)
            )
            ->values();
    }

    public function byDate()
    {
        return $this->index()
            ->groupBy(function($entry) {
                $startField = $this->params->get('start_field', 'start_date');
                
                $group_by = $this->params->get('group_by', $startField);

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
            $startField = $this->params->get('start_field', 'start_date');
            $endField = $this->params->get('end_field', 'end_date');
    
            return $this->mapRecurrenceRule($startField, $endField, $field)($entry);
        }
    }

    public function next()
    {
        if(!$this->params->get('start')) {
            $this->params->put('start', 'now');
        }
        
        return $this->index()->slice(0, $this->params->get('total', 1));
    }

    protected function sortByDate(string $dateField)
    {
        return function($entry) use ($dateField) {
            return $entry->supplements()->get($dateField);
        };
    }
    
    protected function mapRecurrenceRule(string $startField, string $endField, string $recurrenceField)
    {
        return function($entry) use ($startField, $endField, $recurrenceField) {
            $startDate = Carbon::parse($entry->get($startField));

            $entry->supplements()->put($startField, $startDate);

            $endDate = Carbon::parse($entry->get($endField, $entry->get($startField)));

            $entry->supplements()->put($endField, $endDate);

            // Get the diff in seconds from the start and end date
            $diff = $startDate->diffInSeconds($endDate);

            $entry->supplements()->put('diff_seconds', $diff);
            
            // Get the recurrence field.
            if($rrule = $entry->get($recurrenceField)) {
                // Remove the excluded keys and merge the dtstart value.
                $attrs = collect($rrule)->except(static::EXCLUDED_ATTRIBUTES);

                if($attrs->count()) {
                    $attrs = array_merge($attrs->all(), [
                        'dtstart' => Carbon::parse($entry->get($startField))
                    ]);
                    
                    // Map the recurrence rules into an RRule instance.
                    $entry->supplements()->put($recurrenceField, $rrule = new RRule($attrs));

                    // Get the next
                    $occurrences = $rrule->getOccurrencesAfter(now(), false, $this->params->get('total_occurrences', 10));

                    // If event has future occurrences, supplement the data.
                    if(count($occurrences)) {
                        $startDate = Carbon::parse($occurrences[0]);

                        $entry->supplements()->put($startField, $startDate);
                        $entry->supplements()->put($endField, $endDate = $startDate->clone()->addSeconds($diff));

                        $occurrences = collect($occurrences)->map(function($occurrence) use ($startField, $endField, $diff) {
                            return [
                                $startField => $startDate = Carbon::parse($occurrence),
                                $endField => $startDate->clone()->addSeconds($diff)
                            ];
                        });

                        $entry->supplements()->put('occurrences', $occurrences->all());
                    }        
                }
            }
            
            // Return the potentially transformed entry
            return $entry;
        };
    }
    
    protected function filterFuture(string $startField, string $endField) {
        return function($entry) use ($startField, $endField) {
            $future = $this->params->get('future');

            if($future === true) {
                return $entry->supplements()->get($endField) >= now();
            }
            else if($future === false) {
                return $entry->supplements()->get($startField) < now();
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

    protected function filterByStartParam(string $startField)
    {
        return function($entry) use ($startField) {
            if($start = $this->params->get('start')) {
                return $entry->supplements()
                    ->get($startField)
                    ->isAfter(Carbon::parse($start));
            }

            return true;
        };
    }

    protected function filterByEndParam(string $endField)
    {
        return function($entry) use ($endField) {
            if($end = $this->params->get('end')) {
                return $entry->supplements()
                    ->get($endField)
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
