<template>
    <div>
        <breadcrumbs :routePrefixName="routePrefixName" :action="action" :singularName="singularName" :pluralName="pluralName" :useName="useName"></breadcrumbs>
        <div class="card">
            <div class="card-body">
                <form-title :routePrefixName="routePrefixName" :title="title" v-bind:showRightSide="false"></form-title>
                <br>
                <form-create :apiPath="apiPath" :routePrefixName="routePrefixName" :singularName="singularName" :toastMessage="toastMessage" :fieldColumns="getFieldColumns()">
                    <template v-slot:default v-bind:data="$data">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="name">Name <small class="text-danger">* Required</small></label>
                                <input id="name" type="text" class="form-control" v-model="$data.name" autocomplete="off" minlength="2" maxlength="255" required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="email">Email Address <small class="text-danger">* Required</small></label>
                                <input id="email" type="email" class="form-control" v-model="$data.email" autocomplete="off" minlength="2" maxlength="255">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="password">Password <small class="text-danger">* Required</small></label>
                                <input id="password" type="password" class="form-control" v-model="$data.password" autocomplete="off" minlength="2" maxlength="255" required>
                                <p v-if="v$.password_confirmation.$error">Password Does Not Match Password Confirmation</p>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="password-confirmation">Password Confirmation <small class="text-danger">* Required</small></label>
                                <input id="password-confirmation" type="password" @blur="v$.password_confirmation.$touch" class="form-control" v-model="$data.password_confirmation" required>
                                <p v-if="v$.password_confirmation.$error">Password Confirmation Does Not Match Password</p>
                            </div>
                        </div>
                    </template>
                </form-create>
            </div>
        </div>
    </div>
</template>

<script>
    import useVuelidate from '@vuelidate/core';
    import { required, email, sameAs } from '@vuelidate/validators';

    export default {
        setup () {
            return { v$: useVuelidate() }
        },

        data() {
            return {
                ifReady:               true,
                action:                'Create New',
                title:                 'Create New User',
                singularName:          'User',
                pluralName:            'Users',
                apiPath:               '/api/users',
                routePrefixName:       'users',
                useName:               'singular',
                toastMessage:          'User',
                name:                  '',
                email:                 '',
                password:              '',
                password_confirmation: ''
            };
        },

        methods: {
            getFieldColumns() {
                return {
                    name:                  this.name,
                    email:                 this.email,
                    password:              this.password,
                    password_confirmation: this.password_confirmation
                };
            }
        },

        validations () {
            return {
                name: { required },
                email: { required, email },
                password: { required },
                password_confirmation: sameAs(this.password)
            };
        }
    }
</script>
