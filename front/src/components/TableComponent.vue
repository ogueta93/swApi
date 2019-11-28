<template>
    <b-container fluid>

    <b-table striped hover
        show-empty
        stacked="md"
        :head-variant="'dark'"
        :items="myProvider"
        :busy.sync="isBusy"
        :fields="fields"
        :current-page="currentPage"
        :per-page="perPage"
    >

    <template v-slot:table-busy>
        <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Loading...</strong>
        </div>
    </template>
    </b-table>

    <b-row>
        <b-col md="6">
            <b-pagination
                v-model="currentPage"
                :total-rows="totalRows"
                :per-page="perPage">
            </b-pagination>
        </b-col>
    </b-row>
    </b-container>  
</template>

<script>
export default {
    name: 'tableComponent',
    data() {
        return {
            urlService: process.env.VUE_APP_BACKEND_HOST,
            isBusy: false,
            fields: [
                { key: 'name', label: 'Name', sortable: false, class: 'text-center' },
                { key: 'height', label: 'Height', sortable: false, class: 'text-center' },
                { key: 'mass', label: 'Mass', sortable: false, class: 'text-center' },
                { key: 'hair_color', label: 'Hair Color', sortable: false, class: 'text-center' },
                { key: 'birth_year', label: 'Birth Year', sortable: false, class: 'text-center' },
                { key: 'gender', label: 'Gender', sortable: false, class: 'text-center' }
            ],
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
        }
    },
    methods: {
        myProvider(ctx) {
            var that = this;
            that.isBusy = true;

            console.log(this.urlService);
            var promise = this.axios.post(this.urlService, {page: ctx.currentPage});

            return promise.then((response) => {
                console.log('hecho', ctx.currentPage);
                that.isBusy = false;
                that.totalRows = response.data.count;
                return response.data.results;
            }).catch(error => {
                that.isBusy = false;
                throw "Unknow error";
                return [];
            })
        }
    }
}
</script>

<style scoped>
.table-name {
    text-transform: capitalize;  
}
</style>