<?php

namespace Objectivehtml\Events\Fieldtypes;

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
        return $data;
    }

    /**
     * Process the data before it gets saved.
     *
     * @param mixed $data
     * @return array|mixed
     */
    public function process($data)
    {
        return $data;
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
