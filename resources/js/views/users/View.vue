<template>
    <div>
        <breadcrumbs :routePrefixName="routePrefixName" :action="action" :singularName="singularName" :pluralName="pluralName" :useName="useName"></breadcrumbs>

        <div class="card">
            <div class="card-body">
                <form-title :routePrefixName="routePrefixName" :title="title" v-bind:showRightSide="false"></form-title>
                <br>
                <form-view :apiPath="apiPath" :routePrefixName="routePrefixName" :singularName="singularName" :object="user" :selectedProperty="selectedProperty" :toastMessage="toastMessage">
                    <template v-slot:default v-bind:user="user">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="name">Name</label>
                                <input id="name" type="text" class="form-control" v-model="user.name" autocomplete="off" minlength="2" maxlength="255">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="email">Email Address</label>
                                <input id="email" type="email" class="form-control" v-model="user.email" autocomplete="off" minlength="2" maxlength="255">
                            </div>
                        </div>
                    </template>
                </form-view>
            </div>
        </div> 

    </div>
</template>

<script>
    export default {
        data() {
            return {
                ifReady:          false,
                action:           'View',
                title:            'View User',
                singularName:     'User',
                pluralName:       'Users',
                apiPath:          '/api/users',
                routePrefixName:  'users',
                useName:          'singular',
                selectedProperty: 'name',
                toastMessage:     'User',
                user:             {}
            };
        },

        mounted() {
            let promise = new Promise((resolve, reject) => {
                axios.get(this.apiPath + '/' + this.$route.params.id).then(res => {
                    this.user = res.data.user;
                    resolve();
                }).catch(err => {
                    reject();
                });
            });

            promise.then(() => {
                this.ifReady = true;
            });
        }
    }
</script>
