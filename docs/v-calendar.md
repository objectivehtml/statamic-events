# v-calendar

We provide native [v-calendar support](https://vcalendar.io/). Just like the [events tag](./tags/events.md), the `<events>` Vue component is an asnychronous event loader that makes working with `v-calendar` as easy as it gets.


``` html
<html>
    <head>
        <title>Basic Example</title>
        <script src="https://unpkg.com/vue/dist/vue.js"></script>
        <script src="https://unpkg.com/v-calendar"></script>
        <script src="/vendor/statamic-events/js/events.js"></script>

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
                    <div class="absolute left-0 z-10 w-full">
                        <div class="flex align-top justify-center">
                            <div class="bg-red-600 text-white p-1 rounded">
                                Loading...
                            </div>
                        </div>
                    </div>
                </template>
                <template v-slot:default="{ attributes, events, fromDate, fromPage, toDate, toPage, fetch }">
                    <v-calendar
                        class="custom-calendar max-w-full"
                        :masks="{
                            weekdays: 'WWW',
                        }"
                        :attributes="attributes"
                        :from-date.sync="fromDate"
                        :to-date.sync="toDate"
                        disable-page-swipe
                        is-expanded
                        @update:from-page="fromPage"
                        @update:to-page="toPage"
                        @transition-start="fetch">
                    </v-calendar>
                </template>
            </events>
        </div>
    </body>
</html>
```