/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')

var Vue = require('vue')
window.Vue = Vue

import VueRouter from 'vue-router'

Vue.use(VueRouter)

import Buefy from 'buefy'

Vue.use(Buefy)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('top-navbar', require('./components/navbar').default)

import Users from './components/users.vue'
import UserAdd from './components/user-add.vue'
import BalanceAdd from './components/balance-add.vue'
import RateAdd from './components/rate-add.vue'
import Remittance from './components/remittance.vue'

const routes = [
  {path: '/', component: Users},
  {path: '/rate-add', component: RateAdd},
  {path: '/user-add', component: UserAdd},
  {path: '/balance-add', component: BalanceAdd},
  {path: '/remittance', component: Remittance},
]

const router = new VueRouter({
  routes // сокращённая запись для `routes: routes`
})

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
  el: '#app',
  router
})
