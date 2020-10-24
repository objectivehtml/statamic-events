<template>
    <div class="card p-2">
        <div class="flex justify-between items-center">
            <h2>{{ title }}</h2>
            <div class="relative">
                <a href="#" class="text-blue hover:text-blue-dark text-sm">
                    Upload iCal File
                </a>
                <input type="file" class="opacity-0 absolute top-0 left-0 w-full h-full" @change="onChange">
            </div>
        </div>
        <div v-if="events && events.length" class="mt-2">
            <div class="text-sm mb-1">
                Importing {{ events.length }} event(s)...
            </div>
           
            <table class="data-table">
                <tbody tabindex="0">
                    <tr v-for="(event, i) in events" :key="i" class="sortable-row outline-none" tabindex="0">
                        <td>
                            <div
                                class="flex items-center"
                                :class="{
                                    'text-red': !!event.error,
                                    'text-green': !event.error && !!event.uploaded
                                }">
                                <div
                                    class="little-dot mr-1"
                                    :class="{
                                        'bg-green': !!event.uploaded,
                                        'bg-grey': !event.uploaded && !event.error,
                                        'bg-red': !!event.error,
                                    }" />
                                {{ event.title }}
                                <b v-if="event.error" class="pl-1">{{ event.error }}</b>
                                <b v-else-if="event.uploaded" class="pl-1">Uploaded!</b>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import IcalExpander from 'ical-expander';

export default {
    props: {
        title: String,
        collection: String,
        startDate: String,
        endDate: String,
        allDay: String,
        recurrenceRule: String,
    },
    data: () => ({
        events: []
    }),
    methods: {
        async upload(events) {
            this.events = events;
                    
            for(let i in events) {
                try {
                    const payload = Object.assign({
                        map: {
                            collection: this.collection,
                            start_date: this.startDate,
                            end_date: this.endDate,
                            all_day: this.allDay,
                            recurrence_rule: this.recurrence_rule
                        }
                    }, events[i]);

                    const { data } = await this.$axios.post('import-events', payload);

                    this.$set(events[i], 'uploaded', true);
                }
                catch(e) {
                    this.$set(events[i], 'uploaded', false);
                    this.$set(events[i], 'error', e);
                }
            }

            this.$toast.success('Import Complete!');
        },
        transform(event) {
            const rrule = event.component.getFirstPropertyValue('rrule');

            return Object.fromEntries(Object.entries({
                title: event.summary,
                start_date: event.startDate.toJSDate(),
                end_date: event.endDate.toJSDate(),
                all_day: event.startDate.isDate,
                recurrence_rule: event.isRecurring() ? Object.assign({
                    enable: true,
                    rrule: rrule.toString()
                }, rrule.toJSON()) : undefined
            }).filter(([key, value]) => !!value));
        },
        onChange(e) {
            const file = e.target.files.item(0);
            
            if(file) {
                const reader = new FileReader();

                reader.onload = e => {
                    try {
                        const expander = new IcalExpander({
                            ics: e.target.result
                        });
                        
                        this.upload(expander.events.map(this.transform));
                    }
                    catch(e) {
                        this.$toast.error(e.message);
                    }
                };

                reader.readAsText(file);
            }
        }
    }
};
</script>