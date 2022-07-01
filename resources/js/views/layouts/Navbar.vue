<template>
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel fixed-top">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">{{ appName }}</span>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-supported-content" aria-controls="navbar-supported-content" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbar-supported-content">
                <ul class="navbar-nav flex-row ml-md-auto d-none d-md-flex">
                    <li class="nav-item dropdown">
                        <a class="nav-item nav-link dropdown-toggle mr-md-2" href="#" role="button" id="navbar-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ user.name }} <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbar-dropdown">
                            <a class="dropdown-item" href="#" v-on:click.stop.prevent="logout">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        data() {
            return {
                appName: window.appName,
                user: ''
            };
        },

        mounted() {
            axios.get('/api/auth/user').then(res => {
                this.user = res.data.user;
                localStorage.setItem('user', JSON.stringify(res.data.user));
            });
        },
        methods: {
            logout() {
                axios.post('/logout').then(res => {
                    localStorage.clear();
                    location.reload();
                }).catch(function (error) {
                    localStorage.clear();
                    location.reload();
                });
            }
        }
    }
</script>
