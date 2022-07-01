import { createRouter, createWebHistory } from 'vue-router'

const Index = () => import('./views/Index');

/**
 * Users
 * 
 */
const UsersIndex       = () => import('./views/users/Index');
const UsersCreate      = () => import('./views/users/Create');
const UsersView        = () => import('./views/users/View');
const UsersEdit        = () => import('./views/users/Edit');
const UsersAssignRoles = () => import('./views/users/AssignRoles');

const router = createRouter({
    // mode: 'history',
    history: createWebHistory(),
    routes: [
        // Index
        { path: '/', name: 'index', component: Index },

        // Users
        { path: '/users', name: 'users.index', component: UsersIndex },
        { path: '/users/create', name: 'users.create', component: UsersCreate },
        { path: '/users/:id', name: 'users.view', component: UsersView },
        { path: '/users/:id/edit', name: 'users.edit', component: UsersEdit }
    ]
});

export default router;
