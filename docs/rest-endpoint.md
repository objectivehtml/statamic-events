# REST Endpoint

We provide a REST API for Events to make it easy to entegrate your backend with your frontend JavaSccript applications. The endpoint makes available the same parameters available to [events tag](./tags/events.md), except by passing them as query parameters.

## Base URI

`GET /!/statamic-events/events`

## Example Request

`GET /!/statamic-events/feed?start=2020-11-01&end=2020-11-30`

## Example Response

```
[
   {
      "title":"Veterans' Day",
      "start_date":"2020-11-11T14:00:00.000000Z",
      "end_date":"2020-11-12T14:00:00.000000Z",
      "all_day":true,
      "diff_seconds":86400,
      "locale":"default",
      "url":"\/events\/veterans-day"
   },
   {
      "title":"Thanksgiving Day",
      "start_date":"2020-11-26T14:00:00.000000Z",
      "end_date":"2020-11-27T14:00:00.000000Z",
      "all_day":true,
      "diff_seconds":86400,
      "locale":"default",
      "url":"\/events\/thanksgiving-day"
   }
]
```

## Basic Javascript Example

``` js
import axios from 'axios';

axios.get('/!/statamic-events/events', {
    params: {
        start: '2020-11-01',
        end: '2020-11-30',
    }
})
    .then(({ data }) => {
        console.log(events);
    }, e => {
        console.warn(events);
    });
```