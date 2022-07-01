<template>
    <div class="sticky-top" style="top: 63px;">
        <ul class="nav flex-column background py-1 mt-3 sidebar-nav">
            <li class="nav-item pt-2 pb-1">
                <span class="main-title">Dashboards</span>
            </li>

            <li class="nav-item">
                <router-link class="nav-link font-weight-normal" :to="{ name: 'index' }">Home</router-link>
            </li>

            <div class="accordion" id="sidebar-nav-accordion">
                <li class="nav-item mt-1">
                    <span class="nav-link font-weight-normal" type="button" data-toggle="collapse" data-target="#collapse-operations" aria-expanded="false" aria-controls="collapse-operations">
                        Users <i class="float-right fas fa-caret-down"></i>
                    </span>
                </li>
                <div id="collapse-operations" class="collapse show pl-3" aria-labelledby="operations" data-parent="#sidebar-nav-accordion">
                    <li class="nav-item font-weight-light">
                        <router-link class="nav-link" :to="{ name: 'users.index' }">Users</router-link>
                    </li>
                </div>

                <li class="nav-item mt-1">
                    <span class="nav-link font-weight-normal" type="button" data-toggle="collapse" data-target="#collapse-about" aria-expanded="false" aria-controls="collapse-about">
                        About <i class="float-right fas fa-caret-down"></i>
                    </span>
                </li>
                <div id="collapse-about" class="collapse pl-3" aria-labelledby="settings" data-parent="#sidebar-nav-accordion">
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'users.index' }">Users</router-link>
                    </li>
                </div>
            </div>
        </ul>
    </div>
</template>
