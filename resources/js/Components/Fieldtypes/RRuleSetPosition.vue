<template>
    <div class="flex items-center">
        <div class="mr-2">
            <select-input
                v-model="bysetpos"
                name="bysetpos"
                :is-read-only="!!disabled"
                :options="[
                    {value: null, label: 'Select One'},
                    {value: 'any', label: 'Any'},
                    {value: 1, label: 'first'},
                    {value: 2, label: 'second'},
                    {value: 3, label: 'third'},
                    {value: 4, label: 'fourth'},
                    {value: -1, label: 'last'},
                ]"
                @input="onInput" />
        </div>
        <by-day
            v-model="byday" 
            :value="byday"
            :disabled="disabled || !bysetpos"
            @input="onInput" />
    </div>
</template>

<script>
import ByDay from './RRuleByDay';
import SelectInputField from '../SelectInput';

const SelectInput = window.SelectInput || SelectInputField;

export default {

    components: {
        ByDay,
        SelectInput,
    },

    props: {
        disabled: Boolean,
        value: Object
    },

    data() {
        const value = this.value || {};

        return {
            byday: value.byday,
            bysetpos: value.bysetpos
        };
    },

    methods: {
        onInput() {
            this.$emit('input', this.$data);
        }
    }
};
</script>
