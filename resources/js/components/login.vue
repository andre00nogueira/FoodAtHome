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
                let user = response.data;
                console.log("PATCH LOGGEDIN RESPONSE = " + user.type);
                switch (user.type) {
                  case "EC":
                    console.log("ENTERED EC");
                    this.getCurrentOrder(user);
                    break;
                  case "ED":
                    console.log("ENTERED ED");
                    this.getCurrentOrder(user);
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
    getCurrentOrder(user) {
      //console.log("CURRENT ORDER ID USER = " + user);
      console.log(user);
      axios.get(`api/employee/${user.id}/currentOrder`).then((response) => {
        // Already have order
        if (response.data.data) {
          console.log("THERE IS AN ORDER ALREADY");
          console.log(response.data.data.id);
          this.saveUserAndRedirect(user);
        } else {
          console.log("NO ORDER, EMPLOYEE IS AVAILABLE");
          axios.get("api/orders/preparation/queue").then((response) => {
            console.log(response.data);
            if (response.data != "") {
              console.log("entrou");
              let order = response.data.data;
              axios
                .patch(`api/orders/${order.id}`, {
                  prepared_by: user.id,
                })
                .then((response) => {
                  this.setAvailable(user, false);
                });
              this.$toasted
                .show(`You've been assigned with a new order (${order.id})`, {
                  type: "info",
                })
                .goAway(3500);
            } else {
              console.log("else");
              this.setAvailable(user, true);
            }
          });
        }
      });
    },
    setAvailable(user, value) {
      axios
        .patch(`api/users/${user.id}`, {
          available: new Boolean(value),
        })
        .then((response) => {
          let user = response.data;
          console.log(user);
          console.log("NO ORDER, WE SET EMPLOYEE AVAILABLE = " + user);
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