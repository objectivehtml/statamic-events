
# `{{ events }}`

The events tag should be extremely familiar to use, because it extends Statamic's native `{{ collection }}` tag. So anything you can do with a `{{ collection }}` tag, you can tag with an `{{ events }}` tag. There are a few notable differences of course.

**Date Formats** \
Unless specifically noted, most date formats will work. Meaning, all common US and EU date formats work interchangeably.

### Params

| Name            | Type    | Default         | Required |
|-----------------|---------|-----------------|----------|
| collection      | string  | events          | yes      |
| end             | date    |                 | no       |
| end_field       | string  | end_date        | yes      |
| field           | string  | recurrence_rule | yes      |
| future          | boolean |                 | no       |
| max_occurrences | number  | 10              | yes      |
| page            | number  | 1               | yes      |
| start           | date    |                 | no       |
| start_field     | string  | start_date      | yes      |
| total           | number  | 100             | yes      |
| ttl             | string  |                 | no       |


### Collection

The name of the collection.

```
{{ events collection="events" }}
    {{ title }}
{{ /events }}
```

### End

Get all occurrences before a defined date.

```
{{ events end="01/01/2020" }}
    {{ title }}
{{ /events }}
```

### End Field

The name of the end field.

```
{{ events end_field="end_date" }}
    {{ title }}
{{ /events }}
```

### Field

The name of the recurrence field.

```
{{ events field="recurrence_field" }}
    {{ title }}
{{ /events }}
```

### Future

Get only occurrences in the future.

```
{{ events future="true" }}
    {{ title }}
{{ /events }}
```

Get only occurrences in the past.

```
{{ events future="false" }}
    {{ title }}
{{ /events }}
```

### Max Occurrences

Defines the maximum number of occurrences to include in the context payload. More occurrences will result in lower performance.

```
{{ events max_occurrences="5" }}
    {{ title }}
{{ /events }}
```

### Page

The page of occurrences to view. This param works work `total`.

```
{{ events page="2" }}
    {{ title }}
{{ /events }}
```

Get only occurrences in the past.

```
{{ events future="false" }}
    {{ title }}
{{ /events }}
```

### Start

Get all occurrences after a defined date.

```
{{ events start="01/01/2020" }}
    {{ title }}
{{ /events }}
```

### Start Field

The name of the start field.

```
{{ events start_field="start_date" }}
    {{ title }}
{{ /events }}
```

### TTL

TTL, or time to live, allows you filter based on a length of time. For example, "+9 months" would get all occurrences within the next 9 months.

```
{{ events ttl="+9 months" }}
    {{ title }}
{{ /events }}
```

Likewise, this would get all occurrences this month and the next.

```
{{ events ttl="next month" }}
    {{ title }}
{{ /events }}
```

### Total

The difference between `limit` and `total` is, `limit` just factors in the raw number of entries returns. `total` factors in the total number of occurrences.

```
{{ events total="50" }}
    {{ title }}
{{ /events }}
```

## Context

The events tag supplements some event specific context to your entry.

`{{ start_date }}` and `{{ end_date }}` will always represent the next available or most recently available occurrence.

`{{ diff_seconds }}` returns the difference from the `end_date` and `start_date` in seconds.

`{{ occurrences }}` loops through all occurrences available.

```
{{ events ttl="next month" }}
    {{ start_date }}

    {{ end_date }}

    {{ diff_seconds }}

    {{ occurrences }}
        {{ start_date }}
        {{ end_date }}
    {{ /occurrences }}
{{ /events }}
```