<template>
    <div class="modal fade" :id="idAttribute" :ref="refAttribute" tabindex="-1" role="dialog" :aria-labelledby="getLabelAttribute()" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" :id="getLabelAttribute()">Search {{ title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <slot></slot>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Order By</label>
                            <select class="form-control" v-model="$parent.order_by">
                                <option value="desc">Newest</option>
                                <option value="asc">Oldest</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer clearfix">
                    <button type="button" class="btn btn-danger" @click.prevent="clearParameters()">Clear</button>
                    <button type="button" class="btn btn-success" @click.prevent="search()">Search</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            routeName: String,
            idAttribute: String,
            refAttribute: String,
            title: String,
            parameters: null
        },

        watch: {
            parameters: function(newValue, oldValue) {
                //
            }
        },

        methods: {
            search() {
                $(`#${this.idAttribute}`).modal('hide');
                
                this.showProgress = true;

                this.$router.push({
                    name: this.routeName,
                    query: {
                        page: 1,
                        per_page: this.$parent.data.meta.per_page,
                        ...this.parameters
                    }
                });
            },
            clearParameters() {
                this.$emit('ClearParameters');
            },
            openSearchModal() {
                $(`#${this.idAttribute}`).modal('show');
            },
            getLabelAttribute() {
                return `${this.idAttribute}-title`;
            }
        }
    }
</script>
