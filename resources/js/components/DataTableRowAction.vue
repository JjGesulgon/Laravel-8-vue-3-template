<template>
    <div>
        <router-link v-if="! disableView" class="text-secondary" :to="{ name: `${routePrefixName}.view`, params: { id: object.id }}">
            <i class="fas fa-eye"></i>
            &nbsp;
            <label class="font-weight-normal">View</label>
        </router-link>
        <span v-if="! disableView && ! disableEdit">&nbsp; | &nbsp;</span>
        <router-link v-if="! disableEdit" class="text-secondary" :to="{ name: `${routePrefixName}.edit`, params: { id: object.id }}">
            <i class="fas fa-edit"></i>
            &nbsp;
            <label class="font-weight-normal">Edit</label>
        </router-link>
        
        <slot></slot>
    </div>
</template>

<script>
    export default {
        props: {
            routePrefixName: String,
            object: Object,
            disableView: {
                type: Boolean,
                required: false,
                default: false
            },
            disableEdit: {
                type: Boolean,
                required: false,
                default: false
            }
        }
    }
</script>
