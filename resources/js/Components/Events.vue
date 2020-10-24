<template>
    <div>
        <template v-if="activity">
            <slot name="loading" />
        </template>
        <slot
            :attributes="attributes"
            :events="events"
            :activity="activity"
            :fetch="fetch"
            :from-date="fromDate"
            :from-page="fromPage"
            :to-page="toPage" />
    </div>
</template>
<script>
import axios from 'axios';

export default {
    inheritAttrs: false,
    props: {
        transform: {
            type: Function,
            default(value) {
                return {
                    // An optional key can be used for retrieving this attribute later,
                    // and will most likely be derived from your data object
                    key: value.id,
                    content: value.title,
                    customData: value,
                    dates: new Date(value.start_date)
                };
            }
        }
    },
    data() {
        return {
            activity: false,
            attributes: [],
            events: [],
            fromDate: this.$attrs.start && new Date(this.$attrs.start),
            toDate: this.$attrs.end && new Date(this.$attrs.end),
        };
    },
    mounted() {
        this.fetch();
    },
    methods: {
        fromPage({ month, year }) {
            this.$attrs.start = `${year}-${month}-01`;
        },
        toPage({ month, year }) {
            this.$attrs.end = `${year}-${month}-31`;
        },
        fetch() {
            this.activity = true;

            axios.get('/!/statamic-events/feed', {
                params: this.$attrs
            }).then(({ data }) => {
                this.activity = false;
                this.events = data; 
                this.attributes = data.map(value => this.transform(value));
            });
        }
    }
};
</script>