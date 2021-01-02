<template>
  <div v-if="user">
    <navbar />

    <h2>User List</h2>
    <router-link
      v-if="user.type == 'EM'"
      class="btn btn-primary"
      :to="`users/create`"
      >Create User</router-link
    >

    <div class="content">
      <div id="filterArea">
        <input
          class="form-control"
          type="text"
          v-model="searchQuery"
          placeholder="Search for a user..."
        />

        <select
          @change="getUsersByType($event)"
          class="custom-select"
          id="userTypeFilter"
        >
          <option value="">Choose Type...</option>
          <option
            v-for="(type, index) in types"
            :key="index"
            :value="type.name"
          >
            {{ type.name }}
          </option>
        </select>
      </div>
      <table id="tableUser" class="table table-striped">
        <thead>
          <tr>
            <th />
            <th>Name</th>
            <th>Email</th>
            <th>Type</th>
            <th />
          </tr>
        </thead>
        <tbody>
          <tr v-for="user of filterUsers" :key="user.id">
            <td>
              <img
                id="userPhoto"
                :src="`storage/fotos/${user.photo_url || 'default_avatar.jpg'}`"
              />
            </td>
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.type }}</td>
            <td>
              <div style="display: flex">
                <router-link
                  v-if="user.type != 'C'"
                  class="btn btn-primary"
                  :to="`users/${user.id}/edit`"
                  >✏️</router-link
                >
                <button
                  class="btn btn-danger"
                  style="margin-left: 2%"
                  v-on:click="deleteUser(user)"
                >
                  🗑️
                </button>
                <button
                  v-if="user.blocked"
                  class="btn btn-success"
                  style="margin-left: 2%"
                  v-on:click="unblockUser(user)"
                >
                  Unblock
                </button>
                <button
                  v-else
                  class="btn btn-danger"
                  style="margin-left: 2%"
                  v-on:click="blockUser(user)"
                >
                  Block
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <pagination
        :data="usersData"
        :limit="2"
        @pagination-change-page="getUsers"
      ></pagination>
    </div>
  </div>
</template>

<script>
import navbar from "./navbar.vue";
export default {
  data() {
    return {
      users: [],
      usersData: {},
      types: [],
      searchQuery: "",
      selectedTypeValue: "",
    };
  },
  mounted() {
    this.getUsers();
    this.getTypes();
  },
  computed: {
    filterUsers() {
      if (!this.searchQuery) {
        return this.users;
      } else {
        if (this.selectedTypeValue == "") {
          if (this.users.length == 0) {
            this.getUsers(0);
          }
          return this.users.filter((users) => {
            return users.name
              .toLowerCase()
              .includes(this.searchQuery.toLowerCase());
          });
        } else {
          return this.users.filter((users) => {
            return users.name
              .toLowerCase()
              .includes(this.searchQuery.toLowerCase());
          });
        }
      }
    },
    user() {
      return this.$store.state.user;
    },
  },
  methods: {
    async getUsers(page = 1) {
      let url = `api/users`;
      if (page != 0) {
        url += `?page=${page}`;
      }
      await axios.get(url).then((response) => {
        this.users = response.data.data;
        let myIndex = this.users.findIndex((u) => u.id == this.user.id);
        this.users.splice(myIndex, 1);
        this.usersData = response.data;
      });
    },

    //#region GET USERS BY TYPE
    getUsersByType(event) {
      this.selectedTypeValue = event.target.value;
      if (this.selectedTypeValue == "") {
        this.getUsers();
      } else {
        let url = `api/users/types/${this.selectedTypeValue}`;
        axios.get(url).then((response) => {
          this.users = response.data.data;
          this.usersData = response.data;
        });
      }
    },
    //#endregion

    //#region GET FOOD TYPES
    getTypes() {
      let url = `api/users/types`;
      axios.get(url).then((response) => {
        this.types = response.data.data;
      });
    },
    //#endregion

    deleteUser(user) {
      axios
        .delete(`api/users/${user.id}`)
        .then((response) => {
          let removedUserIndex = this.users.findIndex((u) => u.id == user.id);
          this.users.splice(removedUserIndex, 1);
          this.$toasted
            .show(`User ${user.name} removed successfully!`, {
              type: "success",
            })
            .goAway(3500);
        })
        .catch((error) => {
          console.log(error);
          this.$toasted
            .show(`There was an error while removing user #${user.name}!`, {
              type: "error",
            })
            .goAway(3500);
        });
    },
    blockUser(user) {
      axios
        .patch(`api/users/${user.id}`, {
          blocked: 1,
        })
        .then((response) => {
          console.log(response.data);
          user.blocked = response.data.data.blocked;
          user.logged_at = response.data.data.logged_at;
          this.$toasted
            .show(`User: ${user.name} blocked successfully!`, {
              type: "success",
            })
            .goAway(3500);
          if (user.logged_at) {
            this.$socket.emit("blocked", user.id);
          }
        });
    },
    unblockUser(user) {
      axios
        .patch(`api/users/${user.id}`, {
          blocked: 0,
        })
        .then((response) => {
          user.blocked = response.data.data.blocked;
          this.$toasted
            .show(`User: ${user.name} unblocked successfully!`, {
              type: "success",
            })
            .goAway(3500);
        });
    },
  },
  components: { navbar },
};
</script>

<style>
#userPhoto {
  width: 50px;
  height: 50px;
}

#tableUsers {
  margin-top: 2%;
  margin-bottom: 3%;
}

#filterArea {
  margin-top: 3%;
  display: flex;
}

#userTypeFilter {
  width: 20%;
}
</style>