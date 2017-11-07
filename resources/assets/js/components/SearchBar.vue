<template>
    <div class="padd-5">
        <input type="text" class="form-control" placeholder="Search for burger name ..." v-bind:value="value"
        v-on:input="launchSearch($event.target.value)">
    </div>
</template>

<script>
    export default {
        props: ['value'],
        methods: {
            launchSearch(value) {
                this.$emit('input', value);
            }
        }
    }
</script>