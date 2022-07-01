<template>
    <div>
        <div v-if="$parent.ifReady">
            <fieldset disabled>
                <slot></slot>
            </fieldset>
        </div>
        <div v-else>
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
            </div>
        </div>

        <br>

        <button class="btn btn-outline-secondary" @click.prevent="backToIndex()">
            <i class="fas fa-chevron-left"></i>&nbsp; Back
        </button>

        <button v-if="$parent.ifReady && ! disableEdit" class="btn btn-primary ml-2" @click.prevent="edit()">
            <i class="fas fa-edit"></i>&nbsp; Edit {{ singularName }}
        </button>

        <button v-if="$parent.ifReady && ! disableDelete" type="button" class="btn btn-danger ml-2" @click.prevent="openDeleteModal()">
            <i class="fas fa-trash-alt"></i>&nbsp; Delete {{ singularName }}
        </button>

        <slot name="other-buttons"></slot>

        <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="delete-modal-title">You're about to delete this.</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete {{ singularName }} {{ object[selectedProperty] }}?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" @click.prevent="deleteItem()">Confirm Delete</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            apiPath: String,
            routePrefixName: String,
            singularName: String,
            toastMessage: String,
            object: Object,
            selectedProperty: String,
            disableEdit: {
                type: Boolean,
                required: false,
                default: false
            },
            disableDelete: {
                type: Boolean,
                required: false,
                default: false
            }
        },

        methods: {
            openDeleteModal() {
                $('#delete-modal').modal('show');
            },
            backToIndex() {
                this.$router.push({ name: `${this.routePrefixName}.index` });
            },
            edit() {
                this.$router.push({ name: `${this.routePrefixName}.edit`, params: { id: this.$route.params.id } });
            },
            deleteItem() {
                $('#delete-modal').modal('hide');

                this.$parent.ifReady = false;

                axios.delete(`${this.apiPath}` + '/' + this.$route.params.id).then(() => {
                    Broadcast.$emit('ToastMessage', {
                        toastMessage: `${this.toastMessage} deleted successfully.`
                    });

                    this.$router.push({ name: `${this.routePrefixName}.index` });
                }).catch(err => {
                    console.log(err);
                    this.$parent.ifReady = true;
                });
            }
        }
    }
</script>
