<template>
    <div
        class="svg-icon using-svg"
        v-html="html" />
</template>


<script>
export default {

    props: {
        name: String
    },

    computed: {

        html() {
            return require(`!!html-loader!./../../svg/${this.name}.svg`);
        }

    }

};
</script>
