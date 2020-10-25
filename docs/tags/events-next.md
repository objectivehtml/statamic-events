
# `{{ events:next }}`

Get the next available event. This tag has all the available parameters as the [Events](./events.md). Any parameter listed overrides the default

## Params

| Name  | Type   | Default | Required |
|-------|--------|---------|----------|
| start | date   | now     | no       |
| total | number | 1       | yes      |

## Examples

Get the next events starting `now`.

```
{{ events:next }}
    {{ title }}
{{ /events:next }}
```

Get the next events starting `01/01/2020`.

```
{{ events:next start="01/01/2020" }}
    {{ title }}
{{ /events:next }}
```

Get the next 2 events starting `now`.

```
{{ events:next total="2" }}
    {{ title }}
{{ /events:next }}
```