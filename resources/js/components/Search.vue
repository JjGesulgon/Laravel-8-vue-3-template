<template>
    <vue-select :id="idAttribute" :class="styleClass" :label="labelAttribute" :filterable="false" v-model="option" @input="select" :options="options" @search="onSearch" :placeholder="placeholder">
        <!-- <template #search="{attributes, events}">
            <input class="vs__search" :required="! option" v-bind="attributes" v-on="events"/>
        </template> -->
        <template slot="no-options">
            {{ placeholder }}
        </template>
        <template slot="option" slot-scope="item">
            <div class="d-center">
                {{ item[labelAttribute] }}
            </div>
        </template>
        <template slot="selected-option" slot-scope="item">
            <div class="selected d-center">
                {{ item[labelAttribute] }}
            </div>
        </template>
    </vue-select>
</template>

<script>
    export default {
        props: {
            apiPath:              String,
            idAttribute:          [String, Number],
            styleClass:           String,
            labelAttribute:       String,
            placeholder:          String,
            searchColumn:         [String, Array],
            additionalParameters: null,
            selectedProperty:     null,
            selectedObject:       null
        },

        data() {
            return {
                options: [],
                option: this.selectedObject
            }
        },

        watch: {
            selectedObject: function(newValue, oldValue) {
                this.option = newValue;
            },
            additionalParameters: function(newValue, oldValue) {
                this.options = [];
            }
        },

        methods: {
            onSearch(value, loading) {
                loading(true);

                let parameters                = {};
                parameters[this.searchColumn] = value;
                parameters                    = { ...parameters, ...this.additionalParameters };

                this.search(loading, parameters, this);
            },
            search: _.debounce((loading, parameters, vm) => {
                axios.get(vm.apiPath, {
                    params: parameters
                }).then(res => {
                    vm.options = res.data;
                    loading(false);
                });
            }, 350),
            select() {
                if (this.selectedProperty != null) {
                    this.$emit('SelectOption', this.option[this.selectedProperty]);
                } else {
                    this.$emit('SelectOption', this.option);
                }
            }
        }
    }
</script>
