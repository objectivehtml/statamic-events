# Events

Events is the easiest and most flexible way to turn your entries into a calendar. Period. Events can transform simple entries into singular or robust recurring events. Recurrences are defined using the [Recurrence Rule Fieldtype](https://github.com/objectivehtml/statamic-events/blob/master/docs/fieldtype.md), which is fully compliant with [iCalendar RFC5545](https://tools.ietf.org/html/rfc5545#section-3.3.10).

## Beta

This addon is currently free while in beta. Bear with us if you run into a bug. [Report any issues](https://github.com/objectivehtml/statamic-events/issues) you find, and we will fix as fast as we can. Feel free to report any suggestions or feature requests.

## Features

What exactly is included with this addon?

1. [Recurrence Rule Fieldtype](https://github.com/objectivehtml/statamic-events/blob/master/docs/fieldtype.md)
2. [Events Tag](https://github.com/objectivehtml/statamic-events/blob/master/docs/tags/events.md)
3. [100% compatible with Forms](https://github.com/objectivehtml/statamic-events/blob/master/docs/forms.md)
4. [Import widget for .cal files](https://github.com/objectivehtml/statamic-events/blob/master/docs/import.md)
5. Fully Compliant with [RFC5545](https://tools.ietf.org/html/rfc5545#section-3.3.10)
6. [REST Endpoint](https://github.com/objectivehtml/statamic-events/blob/master/docs/rest-endpoint.md) (for JavaScript applications)
7. [Vue Events Loader](https://github.com/objectivehtml/statamic-events/blob/master/docs/vue-events-loader.md)

## Tags

We provides a number of tags to extact event data in various ways.

1. [events](https://github.com/objectivehtml/statamic-events/blob/master/docs/tags/events.md) :: Just like the [collection tag]([collection](https://statamic.dev/tags/collection#content)) but for event.
2. [events:next](https://github.com/objectivehtml/statamic-events/blob/master/docs/tags/events-next.md) :: The events after a specified date.
3. [events:by_date](https://github.com/objectivehtml/statamic-events/blob/master/docs/tags/events-by-date.md) :: Group the events by a specified date format.
4. [events:details](https://github.com/objectivehtml/statamic-events/blob/master/docs/tags/events-details.md) :: Get a single event by `id` or `slug`.

### Basic Example

``` html
<div class="list">
{{ events ttl="+9 months" }}
    <div class="list-item">
        {{ start_date }} - {{ end_date }}

        {{ diff_seconds }}

        {{ occurrences }}
            {{ start_date }} - {{ end_date }}
        {{ /occurrences }}
    </div>
{{ /events }}
</div>
```

## Fieldtype

We provide an elegant fieldtype that is loved by both users and developers. [Learn More](https://github.com/objectivehtml/statamic-events/blob/master/docs/fieldtype.md)

![Preview of the Recurrence Rule Fieldtype](https://cdn.jsdelivr.net/gh/objectivehtml/statamic-events/docs/screenshots/fieldtype-preview.gif)

## Import Widget

We also provide a simple widget to upload .cal files. [Learn More](https://github.com/objectivehtml/statamic-events/blob/master/docs/widgets/import-event.md)

![Import Events Widget Preview](https://cdn.jsdelivr.net/gh/objectivehtml/statamic-events/docs/screenshots/import-widget-preview.gif)
