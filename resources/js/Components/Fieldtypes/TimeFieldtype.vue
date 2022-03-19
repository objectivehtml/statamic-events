<template>
    <div class="time-fieldtype">
        <div class="time-fieldtype-container">
            <div class="input-group">
                <div class="input-group-prepend flex items-center">
                    <svg-icon name="time" class="w-4 h-4" />
                </div>
                <div class="input-text flex items-center px-2 w-auto" :class="{ 'read-only': isReadOnly }">
                    <input
                        ref="hour"
                        v-model="hour"
                        class="input-time input-hour"
                        type="text"
                        pattern="[0-5][0-9]"
                        min="00"
                        max="23"
                        placeholder="00"
                        :readonly="isReadOnly"
                        tabindex="0"
                        @keydown.up.prevent="incrementHour(1)"
                        @keydown.down.prevent="incrementHour(-1)"
                        @keydown.esc="clear"
                        @keydown.186.prevent="focusMinute"
                        @keydown.190.prevent="focusMinute"
                        @focus="$emit('focus')"
                        @blur="$emit('blur')"
                        @input="e => onInput(e, 23)">
                    <span class="colon">:</span>
                    <input
                        ref="minute"
                        v-model="minute"
                        class="input-time input-minute"
                        type="text"
                        pattern="[0-5][0-9]"
                        min="00"
                        max="59"
                        placeholder="00"
                        :readonly="isReadOnly"
                        tabindex="0"
                        @keydown.up.prevent="incrementMinute(1)"
                        @keydown.down.prevent="incrementMinute(-1)"
                        @keydown.esc="clear"
                        @focus="$emit('focus')"
                        @blur="$emit('blur')"
                        @input="e => onInput(e, 59)">
                </div>
            </div>
            <button
                v-if="!required && !isReadOnly && !!data"
                class="btn-close ml-sm"
                tabindex="0"
                @click.prevent="clear"
                @keyup.enter.space="clear">
                &times;
            </button>
        </div>
    </div>
</template>

<script>
import FormFieldType from '../FormFieldType';

export default {

    mixins: [FormFieldType],

    props: {
        required: Boolean
    },

    data() {
        return {
            data: this.value || null
        };
    },

    computed: {
        hour: {
            set: function(val) {
                this.ensureTime();
                
                var time = this.data.split(':');
                var hour = parseInt(val);

                if(isNaN(hour)) {
                    hour = 0;
                }

                // ensure you cant go beyond the range
                hour = (hour > 23) ? 23 : hour;
                hour = (hour < 0) ? 0 : hour;

                time[0] = this.pad(hour);

                this.data = time.join(':');
            },
            get: function() {
                return (this.hasTime) ? this.pad(this.data.split(':')[0]) : '';
            }
        },

        minute: {
            set: function(val) {
                this.ensureTime();
                var time = this.data.split(':');
                var minute = parseInt(val);

                if(isNaN(minute)) {
                    minute = 0;
                }

                // ensure you cant go beyond the range
                minute = (minute > 59) ? 59 : minute;
                minute = (minute < 0) ? 0 : minute;

                time[1] = this.pad(minute);
                this.data = time.join(':');
            },
            get: function() {
                return (this.hasTime) ? this.pad(this.data.split(':')[1]) : '';
            }
        },

        hasTime: function() {
            return this.required || this.data !== null;
        },
    },

    watch: {

        hour() {
            this.changed();
        },

        minute() {
            this.changed();
        },

        data(value) {
            this.update(value);
        }

    },

    methods: {
        changed() {
            this.$emit('input', this.hour && this.minute && `${this.hour}:${this.minute}`);
        },

        pad: function(val) {
            return ('00' + val).substr(-2, 2);
        },

        ensureTime: function() {
            if(! this.hasTime) {
                this.initializeTime();
            }
        },

        initializeTime: function() {
            this.data = '00:00';
        },

        clear: function() {
            this.data = null;
        },

        incrementHour: function(val) {
            this.ensureTime();

            var hour = parseInt(this.hour) + val;

            // enable wrapping
            hour = (hour === 24) ? 0 : hour;
            hour = (hour === -1) ? 23 : hour;

            this.hour = hour;
        },

        incrementMinute: function(val) {
            this.ensureTime();

            var minute = parseInt(this.minute) + val;

            // enable wrapping
            minute = (minute === 60) ? 0 : minute;
            minute = (minute === -1) ? 59 : minute;

            this.minute = minute;
        },

        focusMinute: function() {
            $(this.$refs.minute).focus().select();
        },

        focus() {
            this.$refs.hour.focus();
        },

        onInput(e, max) {
            var hour = parseInt(e.target.value);

            if(isNaN(hour)) {
                hour = 0;
            }

            if(hour > max) {
                e.target.value = max;
            }
        }
    }
};
</script>
