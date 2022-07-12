<template>
    <section class="navbar">
        <nav>
            <a href="/" class="logo">
            <img src="images/ethicodex.jpg" width="80px" alt="Ethicodex Logo">
            </a>
            <h1>Ethicodex Database</h1>
            <ul class="nav-links">
                <li class="nav-item"><Link href="/">Home</Link></li>
                <li v-if="loggedin" class="nav-item"><Link href="/codices">Codices</Link></li>              
                <li v-if="loggedin" class="nav-item"><Link href="/purchases">Purchases</Link></li>                
                <li v-if="loggedin" class="nav-item"><Link href="/purchase_parties">Purchase Parties</Link></li>
                <li v-if="loggedin" class="nav-item"><Link href="/modern_collections">Modern Collections</Link></li>                 
                <li class="nav-item"><Link href="/about">About</Link></li>
                <li v-if="!loggedin" class="nav-item"><Link href="/user_register">Register</Link></li>
                <li v-if="!loggedin" class="nav-item"><Link href="/login">Log in</Link></li>

                <li v-if="loggedin" class="nav-item"><Link href="/logout" method="post">Log out</Link></li>                
<!--                <li v-if="loggedin" class="nav-item">({{ name }}, {{ role ? role.name : "" }})</li> -->
            </ul>
            <div v-if="loggedin" style="float: right">
                ({{ name }}, {{ role ? role.name : "" }})
            </div>
        </nav>
    </section>
    <section>
        <div class="content">
            <slot />
        </div>
    </section>
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
html {
  box-sizing: border-box;
  font-size: 16px;
}

*, *::before, *::after {
  box-sizing: inherit;
  margin: 0;
  padding: 0;
}

body {
  width: 1280px;
  margin: auto;
  background-color: #777;
  font-family: Helvetica;
}

table {
  width: 100%;
}
.navbar {
    font-family: sans-serif;
    border-bottom-right-radius: 10px;
    border-bottom-left-radius: 10px;
    overflow: hidden;
}

.content {
    font-family: sans-serif;
}

.navbar h1 {
    font-size: 18px;
}

.navbar nav {
  width: 100%;
  background-color: rgb(60, 63, 60);
  color: #fff;
  padding-left: 30px;
  padding-right: 10px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo {
  display: inline-block;
  padding-top: 10px;
  padding-bottom: 10px;
}

.nav-links {
  list-style: none;
  display: flex;
}

.nav-item a {
  display: inline-block;
  padding: 15px;
  text-decoration: none;
  color: rgb(231, 173, 173);
  font-size: 16px;
}

.nav-item:hover {
  background-color: rgb(161, 143, 143);
}

.nav-item:hover a {
  color: rgb(2, 41, 2);
}

.logo img {
  width: 80px;
  vertical-align: middle;
}
</style>