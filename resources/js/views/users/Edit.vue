<template>
    <div>
        <breadcrumbs :routePrefixName="routePrefixName" :action="action" :singularName="singularName" :pluralName="pluralName" :useName="useName"></breadcrumbs>

        <div class="card">
            <div class="card-body">
                <form-title :routePrefixName="routePrefixName" :title="title" v-bind:showRightSide="false"></form-title>
                <br>
                <form-edit :apiPath="apiPath" :routePrefixName="routePrefixName" :singularName="singularName" :toastMessage="toastMessage" :fieldColumns="getFieldColumns()">
                    <template v-slot:default v-bind:data="$data">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="name">Name</label>
                                <input id="name" type="text" class="form-control" v-model="$data.name" autocomplete="off" minlength="2" maxlength="255" required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="email">Email Address</label>
                                <input id="email" type="email" class="form-control" v-model="$data.email" autocomplete="off" minlength="2" maxlength="255">
                            </div>
                        </div>
                    </template>
                </form-edit>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                ifReady:              false,
                action:               'Edit',
                title:                'Edit User',
                singularName:         'User',
                pluralName:           'Users',
                apiPath:              '/api/users',
                routePrefixName:      'users',
                useName:              'singular',
                selectedProperty:     'name',
                toastMessage:         'User',
                name:                 null,
                email:                null,
                originalEmail:        null,
            };
        },

        mounted() {
            let promise = new Promise((resolve, reject) => {
                axios.get(this.apiPath + '/' + this.$route.params.id).then(res => {
                    this.name                 = res.data.user.name;
                    this.email                = res.data.user.email;
                    this.originalEmail        = res.data.user.email;

                    resolve();
                }).catch(err => {
                    reject();
                });
            });

            promise.then(() => {
                this.ifReady = true;
            });
        },

        methods: {
            getFieldColumns() {
                let data = {
                    name: this.name
                };

                if (this.originalEmail != this.email) {
                    data.email = this.email;
                }

                return data;
            }
        }
    }
</script>
