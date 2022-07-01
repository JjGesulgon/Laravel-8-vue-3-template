<template>
    <div>
        <navbar></navbar>
        <div class="container-fluid my-5">
            <div class="row">
                <div class="col-md-2" style="padding-right: 0px;">
                    <sidebar></sidebar>
                </div>
                <div class="col-md-10">
                    <div class="mt-3">
                        <router-view v-slot="{ Component }">
                            <transition name="fade">
                                <component :is="Component" />
                            </transition>
                        </router-view>
                    </div>
                </div>
            </div>
        </div>

        <div style="position: fixed; bottom: 0; right: 0; margin-bottom: 30px; margin-right: 30px; z-index: 1000;">
            <div id="toast-message" role="alert" aria-live="assertive" aria-atomic="true" class="toast" data-autohide="false">
                <div class="toast-header">
                    <!-- <img class="rounded mr-2 px-1 py-1" src="/storage/photos/logo.png" width="30" height="30" alt="" loading="lazy"> -->
                    <strong class="mr-auto custom-color">4G-Shoes</strong>
                    <small class="text-muted">Now</small>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    <span style="font-size: 16px;">{{ toastMessage }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                toastMessage: ''
            }
        },

        beforeCreate() {
            //
        },

        created() {
            // Echo.connector.socket.on('disconnect', (reason) => {
            //     alert('No Internet Connection');
            // });

            // Echo.connector.socket.on('reconnect', (reason) => {
            //     alert('Connected');
            // });
        },

        mounted() {
            Broadcast.$on('ToastMessage', (event) => {
                this.toastMessage = event.toastMessage;
                
                $('#toast-message').toast('show');

                setTimeout(() => {
                    $('#toast-message').toast('hide');
                }, 5000);
            });
        }
    }
</script>

<style>
.sticky-toast {
    position: fixed;
    bottom: 0;
    right: 0;
    margin-right: 30px;
    margin-bottom: 30px;
}
</style>
