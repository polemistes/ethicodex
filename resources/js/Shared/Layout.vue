<template>
  <div class="grid-container">
    <a class="grid-logo" href="/">
      <img src="images/ethicodex.jpg" width="132" alt="Ethicodex Logo">
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
        <li class="active"><Link href="/">Home</Link></li>
        <li v-if="loggedin"><Link href="/codices">Codices</Link></li>              
        <li v-if="loggedin"><Link href="/purchases">Purchases</Link></li>                
        <li v-if="loggedin"><Link href="/purchase_parties">Purchase Parties</Link></li>
        <li v-if="loggedin"><Link href="/modern_collections">Modern Collections</Link></li>                 
        <li v-if="!loggedin" style="float:right"><Link href="/user_register">Register</Link></li>
        <li v-if="!loggedin" style="float:right"><Link href="/login">Log in</Link></li>
        <li v-if="loggedin" style="float:right"><Link href="/logout" method="post">Log out</Link></li>                
        <li style="float:right"><Link href="/about">About</Link></li>
      </ul>
    </nav>

    <section class="grid-content">
      <slot />
    </section>

    <footer class="grid-bottom">
      This is the bottom of the page.
    </footer>
  </div>
</template>

<script>

export default {

computed: {
  loggedin() {
    return (this.$page.props.auth == null) ? 0 : 1;
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
  height: 100%;
  margin: auto;
  background-color: rgb(255, 255, 255);
  color: black;
}

.grid-container {
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
  padding-right: 2em;
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
}

.grid-bottom {
  grid-area: bottom;
}

</style>