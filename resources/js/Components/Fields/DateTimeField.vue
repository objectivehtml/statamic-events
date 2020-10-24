<template>
    <div class="flex">
        <div class="input-group mr-2">
            <div class="input-group-prepend flex items-center">
                <div class="svg-icon using-svg w-4 h-4">
                    <svg-icon name="calendar" />
                </div>
            </div>
            <v-date-picker v-model="dateObj" :popover="{ visibility: 'focus' }" formats="MM/DD/YYYY" class="input-text flex-1">
                <input      
                    slot-scope="{ inputProps, inputEvents }"              
                    :value="date"
                    class="leading-none w-full focus:outline-none"
                    v-bind="inputProps"
                    style="background: transparent;"
                    v-on="inputEvents">
            </v-date-picker>
        </div>
        <div>
            <time-fieldtype v-model="time" :name="name" :value="time" />
        </div>
        <input type="hidden" :name="name" :value="formattedDateTime">
    </div>
</template>

<script>
import FormFieldType from '../FormFieldType';
import TimeFieldtype from '../Fieldtypes/TimeFieldtype';

export default {
    components: {
        TimeFieldtype
    },
    mixins: [
        FormFieldType
    ],
    data() {
        return {
            dateObj: this.value ? new Date(this.value) : null,
            date: null,
            time: null
        };
    },
    computed: {
        formattedDateTime() {
            if(this.date) {
                const date = new Date(this.date);

                const [ hours, minutes ] = (this.time || '').split(':');

                date.setHours(hours || 0, minutes || 0);

                return date.toISOString();
            }

            return null;
        }
    },
    watch: {
        dateObj(value) {
            if(value) {
                this.breakdownDateTime();
            }    
        }
    },
    created() {
        this.breakdownDateTime();
    },
    methods: {
        breakdownDateTime() {
            const value = this.dateObj;

            if(value) {
                this.date = ((value.getMonth() > 8) ? (value.getMonth() + 1) : ('0' + (value.getMonth() + 1))) + '/' + ((value.getDate() > 9) ? value.getDate() : ('0' + value.getDate())) + '/' + value.getFullYear();
                this.time = value.toTimeString().split(':').slice(0, 2).join(':');
            }
        }   
    }
};
</script>