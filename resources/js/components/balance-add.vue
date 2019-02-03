<template>
    <section class="section">
        <div class="container">
            <h1 class="title">Add balance</h1>
            <b-field label="User" :type="hasError('user_id')?'is-danger':''"
                     :message="getError('user_id')">
                <b-select placeholder="Check user" v-model="balance.user_id">
                    <option
                            v-for="u in users"
                            :value="u.id"
                            :key="u.id">
                        {{ u.name }} <span v-if="u.purse.currency">{{u.purse.currency}}</span>
                    </option>
                </b-select>
            </b-field>
            <b-field label="Amount"
                     :type="hasError('amount')?'is-danger':''"
                     :message="getError('amount')"
            >
                <b-input required v-model="balance.amount"></b-input>
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
  export default {
    name: 'balance-add',
    mounted () {
      this.getUsers()
    },
    data: () => {
      return {
        balance: {
          amount: 0,
          user_id: null
        },
        users: [],
        errors: null
      }
    },
    methods: {
      submit (e) {
        let self = this
        self.errors = null
        axios.post('/api/v1/add-balance', self.balance).then(({data}) => {

          data = data.data

          if (data.purse.amount) {
            self.messageSuccess(`Balance has been added ${self.balance.amount}`)
            self.balance = {amount: 0, user_id: null}
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
      }
    }
  }
</script>

<style scoped>

</style>
