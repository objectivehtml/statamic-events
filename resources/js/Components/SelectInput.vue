<template>
    <div class="flex">
        <div class="select-input-container">
            <select :disabled="isReadOnly" :value="value" class="select-input" @input="$emit('input', $event.target.value)">
                <option v-for="option in options" :key="option.value" :value="option.value">
                    {{ option.label }}
                </option>
            </select>
            <div class="select-input-toggle">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" /></svg>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        isReadOnly: Boolean,
        options: Array,
        value: [String, Number],
    }
};
</script>