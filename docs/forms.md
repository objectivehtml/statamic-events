# Forms
Events is [100% compatible with Forms](https://statamic.dev/forms#content). This is a basic example using TailwindCSS and Vue. This example assumes you have a form setup with the name `event_form`.

1. Be sure to load `/vendor/statamic-events/js/forms.js` 
2. Register the components.


### `event_form.antlers.html`

``` html
<html>
    <head>
        <title>Event Form</title>
        <script src="https://unpkg.com/vue/dist/vue.js"></script>
        <script src="/vendor/statamic-events/js/forms.js"></script>

        <script>
            Vue.component('rrule', window.RRule);
            Vue.component('date-time-field', window.DateTimeField);

            new Vue({
                el: '#app'
            });
        </script>
    </head>
    <body>
        <div id="app">
        {{ form:event_form redirect="#success" }}
            {{ if success }}
                <div class="mt-mt-2 bg-green-600 text-white p-4 rounded">
                    Your message has been sent! We will get back to you as soon as possible, usually within 24 hours.
                </div>
            {{ else }}
                {{ if errors }}
                    <div class="bg-red-600 text-white p-3 mb-3 rounded">
                        <h3 class="mb-3">Errors</h3>
                        {{ errors }}
                            {{ value }}<br>
                        {{ /errors }}
                    </div>
                {{ /if }}
                                
                <div class="-mx-1 flex flex-wrap flex-1">
                {{ fields }}
                    <div class="px-1 mb-2" style="width: {{ width ? width : 50) }}%">
                        <label class="block">{{ display }}</label>
                        {{ if datetime }}
                            <date-time-field name="{{ handle }}" value="{{ old }}" />
                        {{ else }}
                            {{ field }}
                        {{ /if }}
                    </div>
                {{ /fields }}
                </div>

                <button type="submit" class="btn btn-lg btn-primary btn-block mt-4 flex items-center justify-center">
                    Submit Event
                </button>
            {{ /if }}
        {{ /form:event_form }}
        </div>
    </body>
</html>
```

### `resources/forms/event_form.yaml`
``` yaml
title: 'Event Form'
```

### `resources/blueprints/forms/event_form.yaml`

``` yaml
sections:
  main:
    display: Main
    fields:
      -
        handle: name
        field:
          input_type: text
          display: 'Event Name'
          type: text
          icon: text
          listable: hidden
          validate:
            - required
      -
        handle: content
        field:
          display: Description
          type: textarea
          icon: textarea
          listable: hidden
      -
        handle: email
        field:
          input_type: text
          display: 'Event Email'
          type: text
          icon: text
          width: 50
          listable: hidden
          validate:
            - email
      -
        handle: phone
        field:
          input_type: text
          display: 'Event Phone'
          type: text
          icon: text
          instructions: 'The contact phone number to be listed for this event.'
          width: 50
          listable: hidden
      -
        handle: address
        field:
          input_type: text
          display: 'Event Address'
          type: text
          icon: text
          listable: hidden
      -
        handle: website_url
        field:
          input_type: text
          display: 'Event Website Url'
          type: text
          icon: text
          listable: hidden
      -
        handle: start_date
        field:
          input_type: text
          display: 'Start Date'
          type: text
          icon: text
          listable: hidden
          datetime: true
          validate:
            - required
      -
        handle: end_date
        field:
          input_type: text
          display: 'End Date'
          type: text
          icon: text
          listable: hidden
          datetime: true
          validate:
            - required
      -
        handle: all_day
        field:
          options:
            - 'No'
            - 'Yes'
          inline: false
          display: 'All Day'
          type: radio
          icon: radio
          listable: hidden
      -
        handle: recurrence_rule
        field:
          display: 'Recurrence Rule'
          type: rrule
          listable: hidden
``