<template>
    <section class="section">
        <div class="container">
            <h1 class="title">Add Currency Rate</h1>
            <b-field label="Currency" :type="hasError('currency')?'is-danger':''"
                     :message="getError('currency')">
                <b-select placeholder="Check currency" v-model="rate.currency">
                    <option
                            v-for="c in currencies"
                            :value="c"
                            :key="c">
                        {{ c }}
                    </option>
                </b-select>
            </b-field>
            <b-field label="Rate"
                     :type="hasError('rate')?'is-danger':''"
                     :message="getError('rate')"
            >
                <b-input required v-model="rate.rate"></b-input>
            </b-field>

            <b-field label="Date"
                     :type="hasError('rate')?'is-danger':''"
                     :message="getError('rate')"
            >
                <b-input required v-model="rate.date" placeholder="2028-01-01"></b-input>
            </b-field>

            <div class="field is-grouped">
                <div class="control">
                    <button @click="submit" class="button is-link">Submit</button>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
  import moment from 'moment';
  export default {
    name: 'rate-add',
    mounted () {
      this.getCurrencies()
      this.rate.date = moment().format('Y-MM-DD');
    },
    data: () => {
      return {
        rate: {
          currency: null,
          rate: 0,
          date: '',
        },
        currencies: [],
        errors: null
      }
    },
    methods: {
      submit (e) {
        let self = this
        self.errors = null
        axios.post('/api/v1/add-rate', self.rate).then(({data}) => {

          data = data.data

          if (data.purse.amount) {
            self.messageSuccess(`Rate has been added ${self.balance.amount}`)
            self.rate = {
              currency: null,
              rate: 0,
              date: '',
            }
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
