
# `{{ events:by_date }}`

The tag allows you to group events by date. For example, you can display a list of events broken down by month, week, or by any date format. You may use any of the parameters available in the `collection` tags.

For more information in the available date formats, refer to [PHP Date Formatting](https://www.php.net/manual/en/datetime.format.php).

## Params

| Name     | Type   | Default    | Required |
|----------|--------|------------|----------|
| group_by | string | start_date | yes      |
| format   | string | F          | yes      |

## Examples

Group the events by the full month name, ie `January`.

``` html
{{ events:by_date }}
    <h3>{{ group_by }}</h3>

    {{ entries }}
        <div class="event">{{ title }}</div>
    {{ /entries }}
{{ /events:by_date }}
```

Group the events by week number, ie `42`.

``` html
{{ events:by_date format="W" }}
    <h3>{{ group_by }}</h3>

    {{ entries }}
        <div class="event">{{ title }}</div>
    {{ /entries }}
{{ /events:by_date }}
```