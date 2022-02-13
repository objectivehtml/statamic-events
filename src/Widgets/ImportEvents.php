<?php

namespace Objectivehtml\Events\Widgets;

use Statamic\Widgets\Widget;

class ImportEvents extends Widget
{
    /**
     * The HTML that should be shown in the widget.
     *
     * @return string|\Illuminate\View\View
     */
    public function html()
    {
        return view('objectivehtml/statamic-events::widgets.import-events', [
            'title' => $this->config('title', 'Import Events'),
            'collection' => $this->config('collection', 'events'),
            'start_date' => $this->config('start_date', 'start_date'),
            'end_date' => $this->config('end_date', 'end_date'),
            'all_day' => $this->config('all_day', 'all_day'),
            'recurrence_rule' => $this->config('recurrence_rule', 'recurrence_rule'),
        ]);
    }
}
