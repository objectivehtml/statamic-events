<template>
    <div class="flex">
        <div class="input-group">
            <input type="text" :disabled="isReadOnly" :value="value" class="input-text" @input="$emit('input', $event.target.value)">
        </div>
    </div>
</template>
<script>
export default {
    props: {
        isReadOnly: Boolean,
        value: [String, Number],
    }
};
</script>