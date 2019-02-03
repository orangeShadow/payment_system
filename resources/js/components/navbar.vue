<template>
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item" href="https://bulma.io">
                <img src="https://bulma.io/images/bulma-logo.png" width="112" height="28">
            </a>

            <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div  class="navbar-menu">
            <div class="navbar-start">
                <router-link class="navbar-item" to="/">User list</router-link>
                <router-link class="navbar-item" to="/rate-add">Add rate</router-link>
                <router-link class="navbar-item" to="/user-add">Add user</router-link>
                <router-link class="navbar-item" to="/balance-add">Add balance</router-link>
                <router-link class="navbar-item" to="/remittance">Send money</router-link>
            </div>

            <div class="navbar-end">

            </div>
        </div>
    </nav>
</template>

<script>
  export default {
    name: 'navbar'
  }
</script>
