# Events

Events is the easiest and most flexible way to turn your entries into a calendar. Events will transform simple entries into singular or robust recurring events. Recurrences are defined using the [Recurrence Rule Fieldtype](https://github.com/objectivehtml/statamic-events/blob/master/docs/fieldtype.md), which is fully compliant with [iCalendar RFC5545](https://tools.ietf.org/html/rfc5545#section-3.3.10).

## Features

What exactly is included with this addon?

1. [Recurrence Rule Fieldtype](./fieldtype.md)
2. [Events Tag](./tags/events.md)
3. [100% compatible with Forms](https://statamic.dev/forms#content)
4. [Event Import Widget for .cal files](import.md)
5. Fully Compliant with [RFC5545](https://tools.ietf.org/html/rfc5545#section-3.3.10)
6. [REST Endpoint](./rest-endpoint.md) (for JavaScript applications)
7. [Vue Events Loader](./vue-events-loader.md)

## Tags

We provides a number of tags to extact event data in various ways.

1. [events](./tags/events.md) :: Just like the [collection tag]([collection](https://statamic.dev/tags/collection#content)) but for event.
2. [events:next](./tags/events-next.md) :: The events after a specified date.
3. [events:by_date](./tags/events-by-date.md) :: Group the events by a specified date format.
4. [events:details](./tags/events-details.md) :: Get a single event by `id` or `slug`.

## Fieldtype

We provide an elegant fieldtype that is loved by both users and developers. [Learn More](./fieldtype.md)

![Preview of the Recurrence Rule Fieldtype](https://cdn.jsdelivr.net/gh/objectivehtml/statamic-events/docs/screenshots/fieldtype-preview.gif)

## Import Widget

We also provide a simple widget to upload .cal files. [Learn More](./widgets/import-event.md)

![Import Events Widget Preview](https://cdn.jsdelivr.net/gh/objectivehtml/statamic-events/docs/screenshots/fieldtype-daily-preview.gif)
