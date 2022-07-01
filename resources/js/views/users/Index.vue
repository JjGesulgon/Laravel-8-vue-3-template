<template>
    <div>
        <breadcrumbs :routePrefixName="routePrefixName" :action="action" :singularName="singularName" :pluralName="pluralName" :useName="useName"></breadcrumbs>
        <div class="card">
            <div class="card-body">
                <form-title :routePrefixName="routePrefixName" :title="title" :singularName="singularName" :pluralName="pluralName" v-bind:showRightSide="true" :parameters="getParameters()"></form-title>
                <br>
                <data-table :pluralName="pluralName">
                    <template v-slot:thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Options</th>
                        </tr>
                    </template>
                    <template v-slot:tbody v-bind:data="$data">
                        <tr :id="user.id" v-for="user in data.users" :key="user.id">
                            <td>{{ user.name }}</td>
                            <td>{{ user.email }}</td>
                            <td><data-table-row-action :routePrefixName="routePrefixName" :object="user"></data-table-row-action></td>
                        </tr>
                    </template>
                </data-table>
            </div>
        </div>

        <br>

        <pagination v-if="data" :routeName="routeName" :meta="data.meta" :links="data.links" :parameters="getParameters()"></pagination>

        <search-modal v-if="data" :routeName="routeName" idAttribute="search-modal" ref="searchModal" :title="singularName" :parameters="getParameters()" @ClearParameters="clearParameters()">
            <template v-bind:data="$data">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="name">Name</label>
                        <input id="name" type="text" class="form-control" v-model="$data.name" autocomplete="off" minlength="2" maxlength="255">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="email">Email Address</label>
                        <input id="email" type="email" class="form-control" v-model="$data.email" autocomplete="off" minlength="2" maxlength="255">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="mobile-number">Mobile Number</label>
                        <input id="mobile-number" type="text" class="form-control" v-model="$data.mobile_number" autocomplete="off" minlength="11" maxlength="11">
                    </div>
                </div>
            </template>
        </search-modal>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                data:            null,
                action:          'View',
                title:           'Users',
                pluralName:      'Users',
                singularName:    'User',
                apiPath:         '/api/users',
                routeName:       'users.index',
                routePrefixName: 'users',
                useName:         'plural',
                name:            null,
                email:           null,
                order_by:        'desc',
                error:           null,
                showProgress:    false,
                pageNumbers:     [],
            };
        },

        beforeRouteEnter(to, from, next) {
            if (to.query.per_page == null) {
                next(vm => vm.getData({
                    page:     to.query.page,
                    per_page: 15,
                    name:     to.query.name,
                    email:    to.query.email,
                    order_by: to.query.order_by
                }));
            } else {
                next(vm => vm.getData({
                    page:     to.query.page,
                    per_page: to.query.per_page,
                    name:     to.query.name,
                    email:    to.query.email,
                    order_by: to.query.order_by
                }));
            }
        },

        beforeRouteUpdate(to, from) {
            this.getData({
                page:     to.query.page,
                per_page: this.data.meta.per_page,
                name:     this.name,
                email:    this.email,
                order_by: this.order_by
            });
        },

        methods: {
            getData(params) {
                this.showProgress = true;
                this.name         = params.name;
                this.email        = params.email;
                this.order_by     = params.order_by;

                (typeof params.order_by === 'undefined' || params.order_by == 'desc') ? this.order_by = 'desc' : this.order_by = 'asc';

                axios.get(this.apiPath, { params }).then(res => {
                    this.data         = res.data;
                    this.data.data    = res.data.data;
                    this.showProgress = false;
                }).catch(error => {
                    if (error.response.status == 401) {
                        location.reload();
                    }

                    if (error.response.status == 500) {
                        alert('Kindly report this issue to the devs.');
                    }
                });
            },
            getParameters() {
                return {
                    name:     this.name,
                    email:    this.email,
                    order_by: this.order_by
                };
            },
            clearParameters() {
                this.name     = '';
                this.email    = '';
                this.order_by = 'desc';
            }
        }
    }
</script>
