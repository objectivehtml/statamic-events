<?php

namespace Objectivehtml\Events\Fieldtypes;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Statamic\Fields\Fieldtype;

class RRule extends Fieldtype
{
    protected static $handle = 'rrule';

    protected static $title = 'Recurrence Rule';

    public function view()
    {
        return 'objectivehtml/statamic-events::rrule';
    }

    /**
     * The blank/default value.
     *
     * @return array
     */
    public function defaultValue()
    {
        return null;
    }

    /**
     * Pre-process the data before it gets sent to the publish page.
     *
     * @param mixed $data
     * @return array|mixed
     */
    public function preProcess($data)
    {
        $beginsAt = Arr::get($data, 'begins_at');
        $endsAt = Arr::get($data, 'ends_at');

        return array_merge($data ?: [], [
            'begins_at' => [
                'date' => $beginsAt ? Carbon::parse($beginsAt)->format('c') : null,
                'time' => $beginsAt ? Carbon::parse($beginsAt)->format('H:i') : null,
            ],
            'ends_at' => [
                'date' => $endsAt ? Carbon::parse($endsAt)->format('c') : null,
                'time' => $endsAt ? Carbon::parse($endsAt)->format('H:i') : null,
            ]
        ]);
    }

    /**
     * Process the data before it gets saved.
     *
     * @param mixed $data
     * @return array|mixed
     */
    public function process($data)
    {
        $beginsAtDate = Arr::get($data, 'begins_at.date');
        $beginsAtTime = Arr::get($data, 'begins_at.time');

        $beginsAt = $beginsAtDate
            ? Carbon::parse($beginsAtDate)->setTimeFromTimeString($beginsAtTime)
            : null;

        $endsAtDate = Arr::get($data, 'ends_at.date');
        $endsAtTime = Arr::get($data, 'ends_at.time');

        $endsAt = $endsAtDate || $beginsAtDate
            ? Carbon::parse($endsAtDate ?? $beginsAtDate)
                ->setTimeFromTimeString($endsAtTime ?? $beginsAtTime)
            : $beginsAt;

        return array_merge($data, [
            'begins_at' => $beginsAt,
            'ends_at' => $endsAt,
            'single_day' => $beginsAt->diffInDays($endsAt) == 0
        ]);
    }

    /**
     * Augment the data.
     *
     * @param mixed $data
     * @return array|mixed
     */
    public function augment($data)
    {
        return $data;
    }
}
