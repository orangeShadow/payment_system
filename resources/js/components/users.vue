<template>
    <section class="section">
        <div class="container">
            <h1 class="title">User List</h1>

            <b-table :data="data"
                     paginated
                     backend-pagination
                     :total="total"
                     :per-page="perPage"
                     @page-change="onPageChange"
            >
                <template slot-scope="props">
                    <b-table-column field="id" label="ID" centered>
                        {{props.row.id}}
                    </b-table-column>
                    <b-table-column field="name" label="Name" centered>
                        {{props.row.name}}
                    </b-table-column>
                    <b-table-column field="country" label="Country" centered>
                        {{props.row.country}}
                    </b-table-column>
                    <b-table-column field="city" label="City" centered>
                        {{props.row.city}}
                    </b-table-column>
                    <b-table-column field="purses" label="Purses" centered>
                        <span v-if="props.row.purse">{{props.row.purse.amount}} {{props.row.purse.currency}}</span>
                    </b-table-column>
                </template>
            </b-table>
        </div>
    </section>
</template>

<script>
  export default {
    name: 'users',
    mounted () {
      this.loadAsyncData()
    },
    data () {
      return {
        data: [],
        total: null,
        perPage: null,
        loading: false,
        page: 1
      }
    },
    methods: {
      loadAsyncData () {
        const params = [
          `page=${this.page}`
        ].join('&')

        this.loading = true
        axios.get(`/api/v1/user?${params}`).then(({data}) => {
          this.data = data.data
          this.total = data.meta.total
          this.perPage = data.meta.per_page

          this.loading = false
        }).catch((error) => {
          this.data = []
          this.total = 0
          this.loading = false
          this.$toast.open({
            message: 'Sorry.It was an error on load!',
            type: 'is-danger'
          })
          throw error
        })
      },
      /*
       * Handle page-change event
       */
      onPageChange (page) {
        this.page = page
        this.loadAsyncData()
      },

    }
  }
</script>

<style scoped>

</style>
