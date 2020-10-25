
# `{{ events:details }}`

Get a single event and its occurrence by a given `id` or `slug`. All your entry data is available in the tag context.

## Params

| Name        | Type   | Default         | Required |
|-------------|--------|-----------------|----------|
| collection  | string | events          | no       |
| end_field   | string | end_date        | no       |
| field       | string | recurrence_rule | no       |
| id          | number |                 | no       |
| slug        | string |                 | no       |
| start_field | string | start_date      | no       |

## Examples

Get a single event by `slug`.

```
{{ events:details slug="some-slug-goes-here" }}
    {{ title }}
    {{ start_date }}
    {{ end_date }}
    {{ diff_seconds }}
    {{ occurrences }}
        {{ start_date }}
        {{ end_date }}
    {{ /occurrences }}
{{ /events:next }}
```

Or provide an `id`...

```
{{ events:next id="1" }}
    {{ title }}
{{ /events:next }}
```