<template>
    <panel-item :field="field">
        <template slot="value">
            <span class="inline-block rounded-full w-2 h-2 mr-1" :class="{'bg-success': field.hasCoordinates, 'bg-danger': !field.hasCoordinates}"></span> {{ label }}
        </template>
    </panel-item>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    
    computed: {
        label() {
            return this.field.hasCoordinates ? 'Oui' : 'Non'
        }
    }
}
</script>