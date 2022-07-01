<template>
    <form v-on:submit.prevent="create">
        <div v-if="$parent.ifReady">
            <slot></slot>
        </div>
        <div v-else>
            else
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
            </div>
        </div>

        <br>

        <button type="button" class="btn btn-outline-secondary" @click="backToIndex">
            <i class="fas fa-chevron-left"></i>&nbsp; Back
        </button>

        <button v-if="$parent.ifReady" type="submit" class="btn btn-success ml-2">
            <i class="fas fa-plus"></i>&nbsp; Save {{ singularName }}
        </button>
        
        <slot name="other-buttons"></slot>
    </form>
</template>

<script>
    export default {
        props: {
            apiPath: String,
            routePrefixName: String,
            singularName: String,
            fieldColumns: null,
            toastMessage: String,
            isDisabled: {
                type: Boolean,
                required: false,
                default: false
            },
            returnHere: {
                type: Boolean,
                required: false,
                default: false
            },
            isFormData: {
                type: Boolean,
                required: false,
                default: false
            }
        },

        watch: {
            fieldColumns(newValue, oldValue) {
                //
            },
            isDisabled(newValue, oldValue) {
                //
            }
        },

        data() {
            return {
                redirectTo: null
            };
        },

        mounted() {
            this.redirectTo = this.$route.query.redirect_to;
        },

        methods: {
            backToIndex() {
                this.$router.push({ name: `${this.routePrefixName}.index` });
            },
            create() {
                this.$parent.ifReady = false;

                axios.post(`${this.apiPath}`, this.fieldColumns).then(() => {
                    Broadcast.$emit('ToastMessage', {
                        toastMessage: `${this.toastMessage} created successfully.`
                    });

                    if (typeof this.redirectTo != 'undefined' && this.redirectTo != '') {
                        this.$router.options.routes.forEach(route => {
                            if (route.name == this.$route.query.redirect_to) {
                                this.$router.push({ name: this.$route.query.redirect_to });
                            }
                        });
                    } else if (this.returnHere) {
                        this.$parent.clearFields();
                        this.$parent.ifReady = true;
                    } else {
                        this.$router.push({ name: `${this.routePrefixName}.index` });
                    }
                }).catch(err => {
                    console.log(err);
                    this.$parent.ifReady = true;
                });
            }
        }
    }
</script>
