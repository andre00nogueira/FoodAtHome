<template>
  <div>
    <navbar />
    <h2>Employees List</h2>
    <table id="employee" class="table table-striped">
      <thead>
        <tr>
          <th>Id</th>
          <th />
          <th>Name</th>
          <th>Status</th>
          <th>Started Shift at</th>
          <th>Current order ID</th>
          <th>Order Start Time</th>
          <th>Order</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user of employees" :key="user.id">
          <td>{{ user.id }}</td>
          <td>
            <img
              id="userPhoto"
              :src="`storage/fotos/${user.photo_url || 'default_avatar.jpg'}`"
            />
          </td>
          <td>{{ user.name }}</td>
          <td>{{ getStatus(user) }}</td>
          <td>{{ user.logged_at ? user.logged_at : "-" }}</td>
          <td>
            {{ user.currentOrder ? user.currentOrder.id : "-" }}
          </td>
          <td>
            {{ user.currentOrder ? user.currentOrder.current_status_at : "-" }}
          </td>
          <td>
            <router-link
              v-if="user.currentOrder"
              :to="`/orders/${user.currentOrder.id}`"
              >Details</router-link
            >
          </td>
        </tr>
      </tbody>
    </table>
    <pagination
      v-if="employees.length > 0"
      :data="employeesData"
      @pagination-change-page="getResults"
    ></pagination>
    <h2>Active Orders</h2>
    <table id="activeOrders" class="table table-striped">
      <thead>
        <tr>
          <th>Id</th>
          <th>Status</th>
          <th>Employee Name</th>
          <th>Time Current Status</th>
          <th>Details</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="order of activeOrders" :key="order.id">
          <td>{{ order.id }}</td>
          <td>{{ order.status }}</td>
          <td>
            {{ order.delivered_by ? order.delivered_by : order.prepared_by }}
          </td>
          <td>{{ order.current_status_at }}</td>
          <td>
            <router-link :to="`/orders/${order.id}`">Details</router-link>
          </td>
        </tr>
      </tbody>
    </table>
    <pagination
      v-if="activeOrders.length > 0"
      :data="activeOrdersData"
      @pagination-change-page="getResultsOrders"
    ></pagination>
  </div>
</template>

<script>
import navbar from "./navbar.vue";
export default {
  data() {
    return {
      employees: [],
      employeesData: {},
      activeOrders: [],
      activeOrdersData: {},
    };
  },
  created() {
    axios.get("api/employees").then((response) => {
      if (response) {
        this.employeesData = response.data;
        this.employees = this.employeesData.data;
        axios.get("api/orders/active").then((response) => {
          if (response) {
            this.activeOrdersData = response.data;
            this.activeOrders = this.activeOrdersData.data;
            this.refreshEmployeeData();
          }
        });
      }
    });
  },
  methods: {
    getStatus(user) {
      if (!user.logged_at) {
        return "Offline";
      }
      if (user.available_at) {
        return "Available";
      }
      if ((user.type = "ED")) {
        return "Delivering Order";
      }
      return "Preparing Order";
    },
    getResults(page = 1) {
      let url = `api/employees`;
      if (page != 0) {
        this.page = page;
        url += `?page=${page}`;
        axios.get(url).then((response) => {
          this.employeesData = response.data;
          this.employees = this.employeesData.data;
          this.refreshEmployeeData();
        });
      }
    },
    getResultsOrders(page = 1) {
      let url = `api/orders/active`;
      if (page != 0) {
        this.page = page;
        url += `?page=${page}`;
        axios.get(url).then((response) => {
          this.activeOrdersData = response.data;
          this.activeOrders = this.activeOrdersData.data;
        });
      }
    },
    getCurrentOrder(employee) {
      let order = undefined;
      if (employee.type == "EC") {
        console.log("EC");
        order = this.activeOrders.find(
          (order) => order.prepared_by == employee.id
        );
      }
      if (employee.type == "ED") {
        console.log("ED");
        order = this.activeOrders.find(
          (order) => order.delivered_by == employee.id
        );
        console.log(order);
      }

      if (!order) {
        axios
          .get(`api/employee/${employee.id}/currentOrder`)
          .then((response) => {
            console.log("axios");
            order = response.data.data;
            console.log(order);
          });
      }
      employee.currentOrder = order;
    },
    refreshEmployeeData() {
      let employeesWithActiveOrders = this.employees.filter(
        (e) => e.logged_at && !e.available_at
      );
      if (employeesWithActiveOrders) {
        employeesWithActiveOrders.forEach((employee) => {
          this.getCurrentOrder(employee);
        });
      }
    },
  } /*,
  computed: {
    user() {
      return this.$store.state.user;
    },
  }*/,
  sockets: {
    update_user_status(user) {
      console.log(user);
      let employeeIndex = this.employees.findIndex((e) => e.id == user.id);
      console.log(employeeIndex);
      if (employeeIndex != -1) {
        this.employees[employeeIndex].logged_at = user.logged_at;
        this.employees[employeeIndex].available_at = user.available_at;
      }
      console.log(this.employees);
    },
  },
  components: { navbar },
};
</script>

<style>
</style>