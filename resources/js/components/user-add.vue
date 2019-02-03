<template>
    <section class="section">
        <div class="container">
            <h1 class="title">Add User</h1>
            <template v-for="(item,index) in user" v-if="index!=='currency'">
                <b-field :label="index.toUpperCase()"
                         :key="index"
                         :type="hasError(index)?'is-danger':''"
                         :message="getError(index)"
                >
                    <b-input required v-model="user[index]"></b-input>
                </b-field>
            </template>
            <b-field label="Currency" :type="hasError('currency')?'is-danger':''"
                     :message="getError('currency')">
                <b-select placeholder="Check currency" v-model="user.currency">
                    <option
                            v-for="c in currencies"
                            :value="c"
                            :key="c">
                        {{ c }}
                    </option>
                </b-select>
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
    name: 'user-add',
    mounted(){
        this.getCurrencies();
    },
    data: () => {
      return {
        user: {
          name: '',
          country: '',
          city: '',
          currency:'',
        },
        currencies:[],
        errors: null
      }
    },
    methods: {
      submit (e) {
        let self = this
        self.errors = null;
        axios.post('/api/v1/user', self.user).then(({data}) => {

          data= data.data;

          if (data.name === self.user.name && data.id) {
            self.messageSuccess(`User has been created with id=${data.id}`)
            self.user = {name: '', country: '', city: ''}
          }
        }).catch((error) => {
          console.log(error);
          if (error.response && error.response.status === 422 && error.response.data) {
            let data = error.response.data

            if (data.errors) {
              self.errors = error.response.data.errors
            }

            if (data.message) {
              self.messageError(data.message)
            }
          }
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
      getCurrencies() {
        axios.get('/api/v1/currencies').then(({data}) => {
          this.currencies = data.data;
        }).catch((error)=>{
          this.messageError("Can't load currency list!");
          throw error;
        });
      }
    }
  }
</script>

<style scoped>

</style>
