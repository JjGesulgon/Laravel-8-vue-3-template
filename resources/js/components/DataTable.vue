<template>
    <div>
        <table class="table table-hover table-sm">
            <caption>
                <div class="row">
                    <div class="col-md-9">
                        List of {{ pluralName }} <span v-if="$parent.data">- <strong>Total Items {{ $parent.data.meta.total }}</strong></span>
                    </div>
                    <div class="col-md-3">
                        <div class="progress" height="30px;" v-show="$parent.showProgress || ! $parent.data">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </caption>

            <thead v-if="$parent.data" class="thead-light">
                <slot name="thead"></slot>
            </thead>

            <tbody v-if="$parent.data">
                <slot name="tbody"></slot>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: {
            pluralName: String
        }
    }
</script>
