<template>
  <div>
    <navbar />
    <h2>User List</h2>

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
        <option v-for="(type, index) in types" :key="index" :value="type.name">
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
              <button
                class="btn btn-danger"
                style="margin-left: 2%"
                v-on:click="deleteUser(user)"
              >
                🗑️
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
</template>

<script>
import navbar from "./navbar.vue";
export default {
  data() {
    return {
      //allUsers: [],
      //allUsersData: {},
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
  },
  methods: {
    getUsers(page = 1) {
      let url = `api/users`;
      if (page != 0) {
        url += `?page=${page}`;
      }
      axios.get(url).then((response) => {
        this.users = response.data.data;
        this.usersData = response.data;
      });
    },

    //#region GET FOOD BY TYPE
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
          console.log("ENTRIU");
          let removedUserIndex = this.allUsers.findIndex(
            (u) => u.id == user.id
          );
          if (removedUserIndex == -1) {
            removedUserIndex = this.users.findIndex((u) => u.id == user.id);
            this.users.splice(removedUserIndex, 1);
            return;
          }
          this.allUsers.splice(removedUserIndex, 1);
        })
        .catch((error) => {});
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