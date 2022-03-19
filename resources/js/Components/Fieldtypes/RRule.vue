<template>
    <div class="rrule">
        <div class="flex flex-col">
            <div class="mb-3">
                <label>Begins At</label>
                
                <div class="flex">
                    <div
                        class="date-time-container mr-2"
                        :class="{ 'narrow': containerWidth <= 260 }">
                        <div class="flex-1 date-container input-group">
                            <div class="input-group-prepend flex items-center">
                                <svg-icon name="calendar" class="w-4 h-4" />
                            </div>
                            <v-date-picker
                                v-model="begins_at.date"
                                class="input-text flex border border-grey-50 border-l-0"
                                formats="MM/DD/YYYY"
                                :popover="{ visibility: 'click' }"
                                :locale="$config.get('locale')"
                                :is-required="true"
                                @input="onInput">
                                <template v-slot="{ inputValue, inputEvents }">
                                    <input
                                        class="bg-transparent leading-none w-full focus:outline-none"
                                        :is-read-only="!recurring"
                                        :value="inputValue"
                                        v-on="inputEvents">
                                </template>
                            </v-date-picker>
                        </div>
                    </div>
                    <div>
                        <time-fieldtype v-model="begins_at.time" @input="onInput" />
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label>Ends At</label>
                
                <div class="flex">
                    <div
                        class="date-time-container mr-2"
                        :class="{ 'narrow': containerWidth <= 260 }">
                        <div class="flex-1 date-container input-group">
                            <div class="input-group-prepend flex items-center">
                                <svg-icon name="calendar" class="w-4 h-4" />
                            </div>
                            <v-date-picker
                                v-model="ends_at.date"
                                class="input-text flex border border-grey-50 border-l-0"
                                formats="MM/DD/YYYY"
                                :popover="{ visibility: 'click' }"
                                :locale="$config.get('locale')"
                                :is-required="true"
                                @input="onInput">
                                <template v-slot="{ inputValue, inputEvents }">
                                    <input
                                        class="bg-transparent leading-none w-full focus:outline-none"
                                        :is-read-only="!recurring"
                                        :value="inputValue"
                                        v-on="inputEvents">
                                </template>
                            </v-date-picker>
                        </div>
                    </div>
                    <div>
                        <time-fieldtype v-model="ends_at.time" @input="onInput" />
                    </div>
                </div>
            </div>
        </div>
            
        <div class="mb-3">
            <label class="flex items-center">
                <input v-model="recurring" type="checkbox" class="mr-1" @input="onInput"> Recurring Event
            </label>
        </div>
            
        <div class="mb-2">
            <label>Frequency</label>
            <select-input
                v-model="freq"
                :is-read-only="!recurring"
                :options="[
                    {value: null, label: 'Select One'},
                    {value: 'HOURLY', label: 'Hourly'},
                    {value: 'DAILY', label: 'Daily'},
                    {value: 'WEEKLY', label: 'Weekly'},
                    {value: 'MONTHLY', label: 'Monthly'},
                    {value: 'YEARLY', label: 'Yearly'}
                ]"
                @input="onInput" />
        </div>

        <div class="mb-2">
            <label v-if="freq === 'HOURLY'" class="my-1 flex items-center">
                Every <text-input v-model="interval" class="w-10 mx-1" :is-read-only="!recurring" @input="onInput" /> hours(s)...
            </label>
            <label v-else-if="freq === 'DAILY'" class="my-1 flex items-center">
                Every <text-input v-model="interval" class="w-10 mx-1" :is-read-only="!recurring" @input="onInput" /> day(s)...
            </label>
            <div v-else-if="freq === 'WEEKLY'">
                <label class="my-1 flex items-center">
                    Every <text-input v-model="interval" class="w-10 mx-1" :is-read-only="!recurring" @input="onInput" /> weeks(s)...
                </label>
                <by-day v-model="byday" :value="byday" :disabled="!recurring" @input="onInput" />
            </div>
            <div v-else-if="freq === 'MONTHLY'">
                <label class="my-1 flex items-center">
                    Every <text-input v-model="interval" class="w-10 mx-1" :is-read-only="!recurring" @input="onInput" /> months(s)...
                </label>
                <by-month-day v-model="bymonthday" :disabled="!recurring" @input="onInput" />
                <label class="my-1 flex items-center mb-1">
                    <input v-model="enable_bysetpos" type="checkbox" value="1" class="mr-1" @change="onInput"> on the
                </label>
                <set-position :value="{ byday, bysetpos }" :disabled="!enable_bysetpos" @input="onSetPosition" />
            </div>
            <div v-else-if="freq === 'YEARLY'">
                <label class="my-1 flex items-center">
                    Every <text-input v-model="interval" class="w-10 mx-1" :is-read-only="!recurring" @input="onInput" /> year(s)...
                </label>
                <by-month v-model="bymonth" :disabled="!recurring" class="mb-2" @input="onInput" />
                <by-month-day v-model="bymonthday" :disabled="!recurring" class="mb-1" @input="onInput" />
                <label class="my-1 flex items-center mb-1">
                    <input v-model="enable_bysetpos" type="checkbox" value="1" class="mr-1" @change="onInput"> on the
                </label>
                <set-position :value="{ byday, bysetpos }" :disabled="!enable_bysetpos" @input="onSetPosition" />
            </div>
        </div>

        <div class="mb-2">
            <label>End After</label>
           
            <div class="flex">
                <select-input
                    v-model="end_after"
                    :is-read-only="!recurring"
                    :options="[
                        {value: null, label: 'Never'},
                        {value: 'count', label: 'After'},
                        {value: 'until', label: 'On Date'},
                    ]"
                    :placeholder="false"
                    @input="onInput" />

                <label v-if="end_after === 'count'" class="flex items-center">
                    <text-input
                        v-model="count"
                        type="text"
                        class="w-8 mx-1"
                        :is-read-only="!recurring"
                        @input="onInput" /> Time(s)
                </label>

                <div v-else-if="end_after === 'until'" class="flex items-center">
                    <div
                        class="date-time-container ml-1"
                        :class="{ 'narrow': containerWidth <= 260 }">
                        <div class="flex-1 date-container input-group">
                            <div class="input-group-prepend flex items-center">
                                <svg-icon name="calendar" class="w-4 h-4" />
                            </div>
                            <v-date-picker
                                v-model="until"
                                class="input-text border border-grey-50 border-l-0"
                                formats="MM/DD/YYYY"
                                :popover="{ visibility: 'click' }"
                                :locale="$config.get('locale')"
                                :is-required="true"
                                @input="onInput">
                                <template v-slot="{ inputValue, inputEvents }">
                                    <input
                                        class="bg-transparent leading-none w-full focus:outline-none"
                                        :is-read-only="!recurring"
                                        :value="inputValue"
                                        v-on="inputEvents">
                                </template>
                            </v-date-picker>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{rrule}}
        <template v-if="rrule">
            <label>RRULE as per <a href="http://www.kanzaki.com/docs/ical/rrule.html" class="text-underline text-blue-500">iCalendar RFC</a>.</label>
            <pre><code>{{ rrule }}</code></pre>
        </template>
        <input type="hidden" :name="name" :value="stringify">
    </div>
</template>
<script>
// import FormFieldType from '../FormFieldType';
import ByDay from './RRuleByDay';
import ByMonth from './RRuleByMonth';
import ByMonthDay from './RRuleByMonthDay';
import SetPosition from './RRuleSetPosition';
import TimeFieldtype from './TimeFieldtype';

// window.Fieldtype = window.Fieldtype || FormFieldType;

export default {
    
    components: {
        ByDay,
        ByMonth,
        ByMonthDay,
        SetPosition,
        TimeFieldtype
    },

    mixins: [
        Fieldtype
    ],

    props: {
        value: [Object, String],
        formatters: {
            type: Object,
            default() {
                return {
                    after: () => {
                        return [
                            this.end_after === 'until' && this.until ? `UNTIL=${this.until.toISOString().replace(/.\d+Z$/g, "Z").replace(/[-:]/g, '')}` : null,
                            this.end_after === 'count' && this.count ? `COUNT=${this.count}` : null,
                        ].filter(value => !!value).join(';');
                    },
                    bysetpos: () => {
                        return [
                            this.enable_bysetpos && !!this.bysetpos && this.bysetpos !== 'any' ? `BYSETPOS=${this.bysetpos}` : null,
                            this.byday ? `BYDAY=${(Array.isArray(this.byday) ? this.byday : [this.byday]).join(',')}` : null,
                        ].filter(value => !!value).join(';');
                    }
                };
            }
        }
    },

    data() {
        let value = this.value || {};

        if(typeof value === 'string') {
            value = JSON.parse(this.value);
        }

        return {
            begins_at: {
                date: value.begins_at && value.begins_at.date,
                time: value.begins_at && value.begins_at.time
            },
            ends_at: {
                date: value.ends_at && value.ends_at.date,
                time: value.ends_at && value.ends_at.time
            },
            byday: value.byday,
            bymonth: value.bymonth,
            bymonthday: value.bymonthday,
            bysetpos: value.bysetpos,
            count: value.count || '1',
            recurring: value.recurring,
            enable_bysetpos: value.enable_bysetpos || false,
            end_after: value.end_after,
            freq: value.freq,
            interval: value.interval || '1',
            monthly_pos: value.monthly_pos,
            until: value.until ? new Date(value.until) : undefined,
            yearly_pos: value.yearly_pos,
            rrule: value.rrule
        };
    },

    computed: {
        stringify() {
            return JSON.stringify(this.$data);
        }
    },

    mounted() {
        const rrule = this.toString();

        if(this.rrule !== rrule) {
            this.rrule = rrule;
            this.update(this.$data);
        }
    },

    methods: {
        formatParts(parts) {
            return parts[this.freq] ? parts[this.freq]
                .map(key => {
                    if(this.formatters[key]) {
                        return this.formatters[key]();
                    }

                    if(Array.isArray(this[key])) {
                        return this[key].length && `${key.toUpperCase()}=${this[key].join(',')}`;
                    }
                    
                    if(this[key]) {
                        return `${key.toUpperCase()}=${this[key]}`;
                    }
                })
                .filter(value => !!value)
                .join(';') : undefined;
        },
        toString() {
            const parts = {
                'HOURLY': ['freq', 'interval', 'after'],
                'DAILY': ['freq', 'interval', 'after'],
                'WEEKLY': ['freq', 'interval', 'byday', 'after'],
                'MONTHLY': ['freq', 'interval', 'bymonthday', 'bysetpos', 'after'],
                'YEARLY': ['freq', 'interval', 'bymonth', 'bymonthday', 'bysetpos', 'after']
            };

            return this.formatParts(parts);
        },
        onSetPosition({ byday, bysetpos }) {
            this.byday = byday;
            this.bysetpos = bysetpos || null;
            this.onInput();
        },
        onInput() {
            this.rrule = this.toString();
            this.update(this.$data);
        }
    }
};
</script>