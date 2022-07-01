<template>
    <div>
        <div class="card">
            <div class="card-header">
                Admins / Assign Roles
            </div>
            <div class="card-body">
                <div v-if="ifReady">
                    <fieldset disabled>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" v-model="admin.name">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" class="form-control" v-model="admin.email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li v-for="(role, index) in roles" class="list-group-item" :key="index">
                                        <div class="row clearfix">
                                            <div class="col float-left">
                                                {{ role.display_name }}
                                            </div>
                                            <div class="col float-right">
                                                <div v-show="role.requesting">
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                                    </div>
                                                </div>
                                                <div v-show="! role.requesting">
                                                    <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                                                        <label class="btn btn-outline-danger btn-sm" @click="toggleRole(index)" :class="{ active: ! role.isAssigned }">
                                                            <input type="radio" autocomplete="off"> Unassigned
                                                        </label>
                                                        <label class="btn btn-outline-success btn-sm" @click="toggleRole(index)" :class="{ active: role.isAssigned }">
                                                            <input type="radio" autocomplete="off"> Assigned
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </fieldset>

                    <button type="button" class="btn btn-info btn-sm" @click.prevent.default="viewAdmins">Back</button>
                </div>

                <div v-else>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                ifReady: false,
                roles: '',
                assignedRoles: '',
                admin: '',
                test: false
            };
        },

        mounted() {
            let retrieveRolesPromise = new Promise((resolve, reject) => {
                axios.get('/api/roles/retrieve-all-roles').then(res => {
                    this.roles = res.data.roles;
                    resolve();
                }).catch(err => {
                    console.log(err);
                });
            });

            let retrieveAssignedRoles = new Promise((resolve, reject) => {
                axios.get('/api/admins/' + this.$route.params.id + '/assigned-roles').then(res => {
                    this.assignedRoles = res.data.assignedRoles;
                    resolve();
                }).catch(err => {
                    console.log(err);
                });
            });

            let retrieveAdminPromise = new Promise((resolve, reject) => {
                axios.get('/api/admins/' + this.$route.params.id).then(res => {
                    this.admin = res.data.admin;
                    resolve();
                }).catch(err => {
                    console.log(err);
                });
            });

            Promise.all([retrieveRolesPromise, retrieveAssignedRoles, retrieveAdminPromise]).then(() => {
                this.roles.find(role => {
                    if (_.some(this.assignedRoles, { id: role.id })) {
                        role.isAssigned = true;
                    } else {
                        role.isAssigned = false;
                    }

                    role.requesting = false;
                });

                this.ifReady = true;
            });
        },

        methods: {
            viewAdmins() {
                this.$router.push({
                    name: 'admins.index'
                });
            },
            isRequesting(index, value) {
                this.roles[index].display_name = this.roles[index].display_name + ' ';
                this.roles[index].requesting = value;
                this.roles[index].display_name.trim();
            },
            toggleRole(index) {
                this.isRequesting(index, true);

                let data = {
                    admin_id: this.$route.params.id,
                    role_id: this.roles[index].id
                };

                axios.post('/api/admins/toggle-role', data).then(res => {
                    this.isRequesting(index, false);
                }).catch(err => {
                    console.log(err);
                });
            }
        }
    }
</script>

<style>
.row .col-md-6:first-child {
    border-right: 1px solid rgba(0, 0, 0, 0.125);
}
</style>
