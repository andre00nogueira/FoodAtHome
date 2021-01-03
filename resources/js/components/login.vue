<template>
  <div>
    <navbar />
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
  </div>
</template>
<script>
import navbar from "./navbar.vue";
export default {
  components: { navbar },
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
                let user = response.data.data;
                if (user.blocked) {
                  this.logout(user);
                  this.$toasted
                    .show(`You've been blocked by a manager!`, {
                      type: "error",
                    })
                    .goAway(3500);
                  return;
                }
                switch (user.type) {
                  case "EC":
                    this.getCurrentOrder(user);
                    break;
                  case "ED":
                    this.getCurrentOrder(user);
                    break;
                  default:
                    this.saveUserAndRedirect(user);
                    break;
                }
              })
              .catch((error) => {
                console.log(error);
              });
          })
          .catch((error) => {
            this.$toasted
              .show(`Invalid Authentication`, {
                type: "error",
              })
              .goAway(3500);
          });
      });
    },
    getCurrentOrder(user) {
      //console.log("CURRENT ORDER ID USER = " + user);
      console.log(user);
      axios.get(`api/employee/${user.id}/currentOrder`).then((response) => {
        // Already have order
        if (response.data.data) {
          console.log(response.data.data.id);
          this.saveUserAndRedirect(user);
        } else {
          axios.get("api/orders/preparation/queue").then((response) => {
            console.log(response.data);
            if (response.data != "") {
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
          let user = response.data.data;
          console.log(user);
          this.saveUserAndRedirect(user);
        });
    },
    saveUserAndRedirect(user) {
      this.$store.commit("setUser", user)
      this.$router.push("/");
    },
    logout(user) {
      axios
        .post("/api/logout")
        .then((response) => {
          console.log("User has logged out");
          // This updates the store
          // And sets current user to NULL
          this.$store.commit("clearCart");
          axios
            .patch(`/api/users/${user.id}`, {
              loggedin: new Boolean(false),
              available: new Boolean(false),
            })
            .then((response) => {
              console.log(response.data);
            })
            .catch((error) => {
              console.log(error);
            });
          this.$store.commit("clearUser");
          this.$router.push("/");
        })
        .catch((error) => {
          console.log(`Invalid Logout ${error}`);
        });
    },
  },
};
</script>