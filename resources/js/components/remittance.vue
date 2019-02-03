<template>
    <section class="section">
        <div class="container">
            <h1 class="title">Send money</h1>
            <div v-if="users && currencies">
                <b-field label="User From" :type="hasError('user_from')?'is-danger':''"
                         :message="getError('user_from')">
                    <b-select placeholder="Check user" v-model="remittance.user_from">
                        <option
                                v-for="u in users"
                                :value="u.id"
                                :key="u.id">
                            {{ u.name }} <span v-if="u.purse.currency">{{u.purse.currency}}</span>
                        </option>
                    </b-select>
                </b-field>
                <b-field label="User To" :type="hasError('user_to')?'is-danger':''"
                         :message="getError('user_to')">
                    <b-select placeholder="Check user" v-model="remittance.user_to">
                        <option
                                v-for="u in users"
                                :value="u.id"
                                :key="u.id">
                            {{ u.name }} <span v-if="u.purse.currency">{{u.purse.currency}}</span>
                        </option>
                    </b-select>
                </b-field>

                <b-field label="Currency" :type="hasError('currency')?'is-danger':''"
                         :message="getError('currency')">
                    <b-select placeholder="Check currency" v-model="remittance.currency">
                        <option
                                v-for="c in currencies"
                                :value="c"
                                :key="c">
                            {{ c }}
                        </option>
                    </b-select>
                </b-field>

                <b-field label="Amount"
                         :type="hasError('amount')?'is-danger':''"
                         :message="getError('amount')"
                >
                    <b-input required v-model="remittance.amount"></b-input>
                </b-field>

                <div class="field is-grouped">
                    <div class="control">
                        <button @click="submit" class="button is-link">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
  export default {
    name: 'remittance',
    mounted () {
      this.getUsers();
      this.getCurrencies();
    },
    data: () => {
      return {
        remittance: {
          user_from: null,
          user_to: null,
          amount: 0,
          currency: null
        },
        users: [],
        currencies: [],
        errors: null
      }
    },
    methods: {
      submit (e) {
        let self = this
        self.errors = null
        axios.post('/api/v1/remittance', self.remittance).then(({data}) => {
          if (data.id) {
            self.messageSuccess(`Operation complete, operation order is ${data.id}`)
            // self.remittance = {
            //   user_from: null,
            //   user_to: null,
            //   amount: 0,
            //   currency: null
            // }
          }
        }).catch((error) => {
          if (error.response && error.response.status === 422 && error.response.data) {
            let data = error.response.data

            if (data.errors) {
              self.errors = error.response.data.errors
            }

            if (data.message) {
              self.messageError(data.message)
            }
          }

          throw error
        })
      },
      hasError (item) {
        return this.errors && typeof this.errors[item] !== 'undefined'
      },
      getError (item) {
        if (!this.hasError(item)) return ''
        return this.errors[item]
      },
      messageError (message) {
        this.$toast.open({
          message: message,
          type: 'is-danger'
        })
      },
      messageSuccess (message) {
        this.$toast.open({
          duration: 5000,
          message: message,
          type: 'is-success'
        })
      },
      getUsers () {
        axios.get('/api/v1/user?all=1').then(({data}) => {
          this.users = data.data
        }).catch((error) => {
          this.messageError('Can\'t load user list!')
          throw error
        })
      },
      getCurrencies () {
        axios.get('/api/v1/currencies').then(({data}) => {
          this.currencies = data.data
        }).catch((error) => {
          this.messageError('Can\'t load currency list!')
          throw error
        })
      }
    }
  }
</script>

<style scoped>

</style>
