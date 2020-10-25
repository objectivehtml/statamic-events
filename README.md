# Events

Events is a Statamic addon that converts entries into single or recurring
events. Recurrences are defined using "recurrence rules" and are fully compliant with [RFC5545](https://tools.ietf.org/html/rfc5545#section-3.3.10). This addons built-in integration with [V-calendar](https://vcalendar.io/), and makes it easy to integrate with any JavaScript plugin.

## Features

What exactly is included with this addon?

1. [Recurrence Rule Fieldtype](./fieldtype.md)
2. [Template Tags](./docs/tags/events.md)
3. Events Tag REST Endpoint (for JavaScript applications)
4. [100% compatible with Forms](https://statamic.dev/forms).
5. Import Widget that allows imports from .cal files

## Template Tags

We provides a number of tags to extract events in various ways.

1. [events](#events) :: The as the collection tag, but for events. 
2. [events:next](#events:next) :: Get the next X events after a specific point.
4. [events:details](#events:events) :: Get a single event and its occurrences.
5. [events:by_date](#events:by_date) :: Get the events, but grouped by a date.

## Fieldtype

The Recurrence Rule fieldtype is the heart of the plugin. This fieldtype allows
you to define 1 entry for "every second Tuesday of the month", for example. We provide an elegant UI that is fully compliant to the iCalendar spec. [RFC5545](https://tools.ietf.org/html/rfc5545#section-3.3.10)

1. Define Hourly Events

2. Define Weekly Events

3. Define Monthly Events

4. Define Yearly Events

## Import iCalendar Files
