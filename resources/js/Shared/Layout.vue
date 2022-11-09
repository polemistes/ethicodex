<template>
  <div class="grid-container">
    <a class="grid-logo" href="/">
      <img src="images/ethicodex.jpg" alt="Ethicodex Logo">
    </a>
    <div class="grid-header">
      <h1>The Early History of the Codex</h1>
      <h2>Database of Early Codices</h2>
    </div>

    <div class="grid-user">          
      <span v-if="loggedin">
        Username: {{ name }}, {{ role ? role.name : "" }}
      </span>
      <span v-else>
        Not logged in.
      </span>
    </div>

    <nav class="grid-navigation">
      <ul>
        <li 
          :class="{active: (isActive == 'Login' && loggedin)  || isActive == 'Home'}"
        >
          <Link href="/" @click="isActive='Home'">Home</Link>
        </li>
        
        <li 
          :class="{active: isActive == 'Codices'}"
        >
          <Link href="/codices" @click="isActive='Codices'">Codices</Link>
        </li>              
        
        <li 
          v-if="editor" 
          :class="{active: isActive == 'Transactions'}"
        >
          <Link href="/purchases" @click="isActive='Transactions'">Transactions</Link>
        </li>                
        
        <li 
          v-if="editor" 
          :class="{active: isActive == 'Parties'}"
        >
          <Link href="/purchase_parties" @click="isActive='Parties'">Transaction Parties</Link>
        </li>
<!--        
        <li 
          v-if="editor" 
          :class="{active: isActive == 'Collections'}"
        >
          <Link href="/modern_collections" @click="isActive='Collections'">Modern Collections</Link>
        </li>                 
-->        
        <li 
          v-if="!loggedin" 
          :class="{active: isActive == 'Register'}" 
          style="float:right"
        >
          <Link href="/user_register" @click="isActive='Register'">Register</Link>
        </li>
        
        <li 
          v-if="!loggedin" 
          :class="{active: isActive == 'Login'}" 
          style="float:right"
        >
          <Link href="/login" @click="isActive='Login'">Log in</Link>
        </li>
        
        <li 
          v-if="loggedin" 
          style="float:right"
        >
          <Link href="/logout" method="post" @click="isActive='Home'">Log out</Link>
        </li>                
        
        <li 
          :class="{active: isActive == 'About'}" 
          style="float:right"
        >
          <Link href="/about" @click="isActive='About'">About</Link>
        </li>
      </ul>
    </nav>

    <section class="grid-content">
      <slot />
    </section>

    <footer class="grid-bottom">
      <p>A New Methodology and Ethics for Manuscript Studies (EthiCodex)<span style="float: right">v0.5</span></p>
    </footer>
  </div>
</template>

<script>

export default {

computed: {
  loggedin() {
    return (this.$page.props.auth == null) ? 0 : 1;
  },
  editor() {
    return (this.$page.props.auth ? this.$page.props.auth.user.role.id >= 2 : 0)
  },
  name() {
    return (this.$page.props.auth == null) ? "" : this.$page.props.auth.user.name;
  },
  role() {
    return (this.$page.props.auth == null) ? null : this.$page.props.auth.user.role;
  }
}
};

</script>

<script setup>
  import { ref } from "vue";

  let isActive = ref("Home");

  const props = defineProps({
  auth: Object,
});
</script>


<style>
@import url('https://fonts.googleapis.com/css?family=Open+Sans');

* {
  font-family: "Open Sans", sans-serif;
}

*, *::before, *::after {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

html, body {
  width: 90%;
  min-width: 1024px;
  height: 100%;
  margin: auto;
  background-color: #ddd;
  color: black;
}

.grid-container {
  padding-top: 0.5em;
  background-color: #fff;
  min-height: 100vh;
  display: grid;

  grid-template-rows: auto auto 1fr auto;
  grid-template-columns: auto 1fr auto;

  grid-template-areas:  'logo header user'
                        'logo navigation navigation'
                        'content content content'
                        'bottom bottom bottom';
}


.grid-logo {
  grid-area: logo;
  align-self: start;
  justify-self: start;
  padding-right: 1em;
}
.grid-logo img {
  width: 134px;
  height: 130px;
  object-fit: contain;
}

.grid-header {
  grid-area: header;
  align-self: center;
  justify-self: start;
  margin-left: 0em;
}

.grid-user {
  grid-area: user;
  align-self: center;
  margin-right: 1em;
}

.grid-navigation {
  grid-area: navigation;
}

.grid-navigation ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: black;
}

.grid-navigation li {
  float: left;
}

.grid-navigation li a {
  display: block;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

.grid-navigation li a:hover:not(.active) {
  background-color: rgb(194, 181, 166);
}

.active a {
  background-color: rgb(140, 150, 129);
}

.active a:hover {
  background-color: rgb(119, 128, 111);
}

.grid-content {
  grid-area: content;
  background-color: white;
  padding-bottom: 0.5em;
}

.grid-bottom {
  grid-area: bottom;
  background-color: #000;
  color: #fff;
  text-align: center;
  width: 100%;
}

</style>