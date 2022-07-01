/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import { createApp } from 'vue';
import store from './store';
import router from './router';
import mitt from 'mitt';
import VueSelect from 'vue-select';

window.Broadcast = new mitt();
Broadcast.$emit = Broadcast.emit;
Broadcast.$on = Broadcast.on;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import App from './views/layouts/App.vue';

const app = createApp(App).use(store).use(router);

// Components
app.component('navbar', require('./views/layouts/Navbar.vue').default);
app.component('sidebar', require('./views/layouts/Sidebar.vue').default);

app.component('vue-select', VueSelect);
app.component('search', require('./components/Search.vue').default);
app.component('pagination', require('./components/Pagination.vue').default);
app.component('search-modal', require('./components/SearchModal.vue').default);
app.component('data-table', require('./components/DataTable.vue').default);
app.component('data-table-row-action', require('./components/DataTableRowAction.vue').default);
app.component('form-title', require('./components/FormTitle.vue').default);
app.component('form-create', require('./components/FormCreate.vue').default);
app.component('form-view', require('./components/FormView.vue').default);
app.component('form-edit', require('./components/FormEdit.vue').default);
app.component('breadcrumbs', require('./components/Breadcrumbs.vue').default);

app.mount('#app');
