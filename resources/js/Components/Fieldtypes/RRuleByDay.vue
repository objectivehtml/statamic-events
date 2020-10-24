<template>
    <div>
        <div class="inline-grid gap-1 grid-cols-7">
            <button
                v-for="label in labels"
                :key="label"
                :disabled="!!disabled"
                type="button"
                class="w-12 h-12 active:bg-grey-40 active:bg-gray-400"
                :class="{
                    'bg-grey-50 bg-gray-600 text-gray-100': !disabled && each.indexOf(label) > -1,
                    'bg-grey-10 bg-gray-100 text-gray-600': !disabled && each.indexOf(label) === -1,
                    'text-grey-40 bg-gray-200 text-gray-400': !!disabled
                }"
                @click="onClick(label)">
                {{ label }}
            </button>
        </div>
    </div>
</template>

<script>
export default {

    props: {
        disabled: Boolean,
        labels: {
            type: Array,
            default: () => ['SU', 'MO', 'TU', 'WE', 'TH', 'FR', 'SA']
        },
        value: [Number, Array],
    },

    data() {
        const value = Array.isArray(this.value) ? this.value : (
            this.value ? [this.value] : []
        );

        return {
            each: [...value]
        };
    },

    methods: {
        onClick(i) {
            const index = this.each.indexOf(i);

            if(index === -1) {
                this.each.push(i);
            }
            else {
                this.each.splice(index, 1);
            }

            this.$emit('input', this.each);
        }
    }
};
</script>
