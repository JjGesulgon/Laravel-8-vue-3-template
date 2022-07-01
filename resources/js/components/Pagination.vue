<template>
    <div class="clearfix">
        <div v-if="pageCount">
            <nav class="float-left">
                <ul class="pagination">
                    <li class="page-item" v-bind:class="isPrevDisabled">
                        <a class="page-link font-weight-light" href="#" @click.prevent="goToPreviousPage()" disabled><strong>Previous</strong></a>
                    </li>
                    <li class="page-item">
                        <a class="page-link font-weight-light" href="#" @click.prevent="goToFirstPage()"><strong>First</strong></a>
                    </li>
                    <li class="page-item" :key="index" v-for="(pageNumber, index) in pageNumbers" v-bind:class="isPageActive(pageNumber)">
                        <a class="page-link font-weight-light" href="#" @click.prevent="goToPage(pageNumber)"><strong>{{ pageNumber }}</strong></a>
                    </li>
                    <li class="page-item" v-bind:class="isNextDisabled">
                        <a class="page-link font-weight-light" href="#" @click.prevent="goToLastPage()"><strong>Last</strong></a>
                    </li>
                    <li class="page-item" v-bind:class="isNextDisabled">
                        <a class="page-link font-weight-light" href="#" @click.prevent="goToNextPage()"><strong>Next</strong></a>
                    </li>
                </ul>
            </nav>
        </div>
        <div v-else>
            <nav class="float-left">
                <ul class="pagination">
                    <li class="page-item" v-bind:class="isPrevDisabled">
                        <a class="page-link font-weight-light" href="#" @click.prevent="goToPreviousPage()" disabled><strong>Previous</strong></a>
                    </li>
                    <li class="page-item">
                        <a class="page-link font-weight-light" href="#" @click.prevent="goToFirstPage()"><strong>First</strong></a>
                    </li>
                    <li class="page-item" :key="index" v-for="(pageNumber, index) in pageNumbers" v-bind:class="isPageActive(pageNumber)">
                        <a class="page-link font-weight-light" href="#" @click.prevent="goToPage(pageNumber)"><strong>{{ pageNumber }}</strong></a>
                    </li>
                    <li class="page-item" v-bind:class="isNextDisabled">
                        <a class="page-link font-weight-light" href="#" @click.prevent="goToLastPage()"><strong>Last</strong></a>
                    </li>
                    <li class="page-item" v-bind:class="isNextDisabled">
                        <a class="page-link font-weight-light" href="#" @click.prevent="goToNextPage()"><strong>Next</strong></a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="float-right">
            <form class="form-inline">
                <label class="sr-only" for="number-of-items">Number of Items</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Items per page</div>
                    </div>
                    <select class="custom-select" id="number-of-items" v-model="meta.per_page" v-on:change="changePerPage()">
                        <option value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="75">75</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            routeName: String,
            meta: Object,
            links: Object,
            parameters: null
        },

        data() {
            return {
                pageNumbers: []
            };
        },

        mounted() {
            this.populatePages();
        },

        watch: {
            meta: function(newValue, oldValue) {
                this.populatePages();
            }
        },

        computed: {
            nextPage() {
                return this.meta.current_page + 1;
            },
            prevPage() {
                return this.meta.current_page - 1;
            },
            paginatonCount() {
                if (! this.meta) {
                    return;
                }

                const { current_page, last_page } = this.meta;

                return `${current_page} of ${last_page}`;
            },
            pageCount() {
                if (this.meta.last_page > 10) {
                    return false;
                }

                return true;
            },
            isPrevDisabled() {
                if (this.links.prev == null) {
                    return 'disabled';
                }

                return;
            },
            isNextDisabled() {
                if (this.links.next == null) {
                    return 'disabled';
                }

                return;
            }
        },

        methods: {
            goToFirstPage() {
                this.$router.push({
                    name: this.routeName,
                    query: {
                        page: 1,
                        per_page: this.meta.per_page,
                        ...this.parameters
                    }
                });
            },
            goToPage(page = null) {
                this.$router.push({
                    name: this.routeName,
                    query: {
                        page,
                        per_page: this.meta.per_page,
                        ...this.parameters
                    },
                });
            },
            goToLastPage() {
                this.$router.push({
                    name: this.routeName,
                    query: {
                        page: this.meta.last_page,
                        per_page: this.meta.per_page,
                        ...this.parameters
                    }
                });
            },
            goToNextPage() {
                this.$router.push({
                    name: this.routeName,
                    query: {
                        page: this.nextPage,
                        per_page: this.meta.per_page,
                        ...this.parameters
                    }
                });
            },
            goToPreviousPage() {
                this.$router.push({
                    name: this.routeName,
                    query: {
                        page: this.prevPage,
                        per_page: this.meta.per_page,
                        ...this.parameters
                    }
                });
            },
            populatePages() {
                this.pageNumbers = [];

                if (this.pageCount) {
                    for (let i = 1; i <= this.meta.last_page; i++) {
                        this.pageNumbers.push(i);
                    }
                } else if (this.meta.current_page <= 6) {
                    let page = 1;
                    this.pageNumbers.push(page);
                    this.pageNumbers.push(page + 1);
                    this.pageNumbers.push(page + 2);
                    this.pageNumbers.push(page + 3);
                    this.pageNumbers.push(page + 4);
                    this.pageNumbers.push(page + 5);
                    this.pageNumbers.push(page + 6);
                    this.pageNumbers.push('...');
                    this.pageNumbers.push(this.meta.last_page - 1);
                    this.pageNumbers.push(this.meta.last_page);
                } else if ((this.meta.current_page + 6) >= this.meta.last_page) {
                    this.pageNumbers.push(1);
                    this.pageNumbers.push(2);
                    this.pageNumbers.push('...');
                    this.pageNumbers.push(this.meta.last_page - 7);
                    this.pageNumbers.push(this.meta.last_page - 6);
                    this.pageNumbers.push(this.meta.last_page - 5);
                    this.pageNumbers.push(this.meta.last_page - 4);
                    this.pageNumbers.push(this.meta.last_page - 3);
                    this.pageNumbers.push(this.meta.last_page - 2);
                    this.pageNumbers.push(this.meta.last_page - 1);
                    this.pageNumbers.push(this.meta.last_page);
                } else {
                    this.pageNumbers.push(1);
                    this.pageNumbers.push(2);
                    this.pageNumbers.push('...');
                    this.pageNumbers.push(this.meta.current_page - 2);
                    this.pageNumbers.push(this.meta.current_page - 1);
                    this.pageNumbers.push(this.meta.current_page);
                    this.pageNumbers.push(this.meta.current_page + 1);
                    this.pageNumbers.push(this.meta.current_page + 2);
                    this.pageNumbers.push('...');
                    this.pageNumbers.push(this.meta.last_page - 1);
                    this.pageNumbers.push(this.meta.last_page);
                }
            },
            isPageActive(page) {
                if (page == this.$route.query.page || (page == 1 && this.$route.query.page == null)) {
                    return 'active';
                }

                return;
            },
            changePerPage() {
                this.$router.push({
                    name: this.routeName,
                    query: {
                        page: 1,
                        per_page: this.meta.per_page,
                        ...this.parameters
                    }
                });
            }
        }
    }
</script>
