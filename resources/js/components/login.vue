<template>
  <div class="jumbotron">
    <h2>Login</h2>
    <div class="form-group">
      <label for="inputEmail">Email</label>
      <input
        type="email"
        class="form-control"
        v-model="credentials.email"
        name="email"
        id="inputEmail"
        placeholder="Email address"
      />
    </div>
    <div class="form-group">
      <label for="inputPassword">Password</label>
      <input
        type="password"
        class="form-control"
        v-model="credentials.password"
        name="password"
        id="inputPassword"
        placeholder="Password"
      />
    </div>
    <div class="form-group">
      <a class="btn btn-primary" v-on:click.prevent="login">Login</a>
    </div>
  </div>
</template>
<script>
export default {
  data: function () {
    return {
      credentials: {
        email: "",
        password: "",
      },
    };
  },
  methods: {
    login() {
      this.$store.commit("clearUser");
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/login", this.credentials)
          .then((response) => {
            axios
              .patch(`/api/users/${response.data.id}`, {
                loggedin: new Boolean(true),
              })
              .then((response) => {
                let user = response.data
                console.log("PATCH LOGGEDIN RESPONSE = " + user.type);
                switch (user.type) {
                  case "EC":
                    console.log("ENTERED EC");
                    this.getCurrentOrder(user,`api/cook/${user.id}/currentOrder`);
                    break;
                  case "ED":
                    console.log("ENTERED ED");
                    this.getCurrentOrder(user,`api/deliverymen/${user.id}/order`);
                    break;
                  default:
                    console.log("ENTERED C");
                    this.saveUserAndRedirect(user);
                    break;
                }
              })
              .catch((error) => {
                console.log(error);
              });
          })
          .catch((error) => {
            console.log("Invalid Authentication");
          });
      });
    },
    getCurrentOrder(user,url) {
      console.log("CURRENT ORDER ID USER = " + user);
      axios
        .get(url)
        .then((response) => {
          let orderId = response.data;
          console.log("CURRENT ORDER ORDER ID = " + orderId);
          // No order
          if (orderId == "") {
            console.log("NO ORDER, Worker IS AVAILABLE");
            this.setAvailable(user);
          } else {
            console.log("THERE IS AN ORDER ALREADY");
            this.saveUserAndRedirect(user);
          }
        });
    },
    setAvailable(user) {
      axios
        .patch(`api/users/${user.id}`, {
          available: new Boolean(true),
        })
        .then((response) => {
          let user = response.data;
          console.log("NO ORDER, WE SET WORKER AVAILABLE = " + user);
          this.saveUserAndRedirect(user);
        });
    },
    saveUserAndRedirect(user) {
      this.$store.commit("setUser", user);
      this.$router.push("/");
    },
  },
};
</script>