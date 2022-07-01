<template>
    <div>
        <slot>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><router-link :to="{ name: `${routePrefixName}.index` }">{{ pluralName }}</router-link></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ getCurrentAction() }}</li>
                </ol>
            </nav>
        </slot>
    </div>
</template>

<script>
    export default {
        props: {
            routePrefixName:{
                type: String,
                required: true
            },
            action: {
                type: String,
                required: true
            },
            showOnlyAction: {
                type: Boolean,
                required: false
            },
            useName: {
                type: String,
                required: false
            },
            singularName: {
                type: String,
                required: false
            },
            pluralName: {
                type: String,
                required: false
            }
        },

        methods: {
            getCurrentAction() {
                if (this.showOnlyAction) {
                    return this.action;
                }

                if (this.useName == 'singular') {
                    return `${this.action} ${this.singularName}`;
                }

                if (this.useName == 'plural') {
                    return `${this.action} ${this.pluralName}`;
                }
            }
        }
    }
</script>
