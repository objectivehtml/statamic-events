# Vue Event Loader

We love Vue, so we wanted to provide a way for Vue users to get their events using a Vue component that has relatively the same syntax as the `{{ events }}` counter-part. 

We provide an `<events>` tag. the parameters are the same, but for Vue.

The core concept is simple. The `<events>` tag provides two slots, `default` and `loading`. The `default` slot scope provides the returned data, and some more methods to fetch and manipulate the request.

### Required Scripts

``` html
<html>
    <head>
        <title>Basic Example</title>
        <script src="https://unpkg.com/vue/dist/vue.js" defer></script>
        <script src="/vendor/statamic-events/js/events.js" defer></script>

        <script>
        // app.js
        Vue.component('events', window.Events);

        new Vue({
            el: '#app'
        });
        </script>
    </head>

    <body>
        <div id="app">
            <events start="01/01/2020" end="01/31/2020">
                <template v-slot:loading>
                    <div>Loading...</div>
                </template>
                <template v-slot:default="{ events, fetch }">
                    <div for="event in events" :key="event.id">
                        {{ event }}
                    </div>
                    <a href="#" @click.prevent="fetch">Reload</a>
                </template>
            </events>
        </div>
    </body>
</html>
```

[Advanced Example Using v-calendar](./v-calendar.md)