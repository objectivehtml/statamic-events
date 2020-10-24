<template>
    <div>
        <div class="inline-grid gap-1 grid-cols-7">
            <button
                v-for="n in 31"
                :key="n"
                :disabled="!!disabled"
                type="button"
                class="w-12 h-12 active:bg-grey-40 active:bg-gray-400"
                :class="{
                    'bg-grey-50 bg-gray-600 text-gray-100': !disabled && each.indexOf(n) > -1,
                    'bg-grey-10 bg-gray-100 text-gray-600': !disabled && each.indexOf(n) === -1,
                    'text-grey-40 bg-gray-200 text-gray-400': !!disabled
                }"
                @click="onClick(n)">
                {{ n }}
            </button>
        </div>
    </div>
</template>

<script>
export default {

    props: {
        disabled: Boolean,
        value: [Array, String]
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
