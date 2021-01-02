<template>
  <div>
    <navbar />
    <h2>Employees List</h2>
    <div class="text-right">
      <select
        v-model="employeeType"
        class="custom-select"
        id="employeeTypeFilter"
      >
        <option value="">Choose Type...</option>
        <option v-for="(type, index) in types" :key="index" :value="type.value">
          {{ type.name }}
        </option>
      </select>
    </div>
    <div v-if="filteredEmployees.length">
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
          <tr v-for="user of filteredEmployees" :key="user.id">
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
              {{
                user.currentOrder ? user.currentOrder.current_status_at : "-"
              }}
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
    </div>
    <h5 v-else>No Employees</h5>
    <h2>Active Orders</h2>
    <div class="text-right">
      <select v-model="orderStatus" class="custom-select" id="orderTypeFilter">
        <option value="">Choose Status...</option>
        <option
          v-for="(status, index) in statusList"
          :key="index"
          :value="status.value"
        >
          {{ status.name }}
        </option>
      </select>
    </div>
    <div v-if="filteredOrders.length">
      <table id="activeOrders" class="table table-striped">
        <thead>
          <tr>
            <th>Id</th>
            <th>Status</th>
            <th>Current Employee Name</th>
            <th>Time Current Status</th>
            <th>Details</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="order of filteredOrders" :key="order.id">
            <td>{{ order.id }}</td>
            <td>{{ order.status }}</td>
            <td v-if="order.status != 'D' && order.status != 'R'">
              {{ order.currentEmployee ? order.currentEmployee.name : "-" }}
            </td>
            <td v-else>-</td>
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
    <h5 v-else>No Orders</h5>
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
      types: [
        { name: "Cook", value: "EC" },
        { name: "Deliveryman", value: "ED" },
      ],
      statusList: [
        { name: "Holding", value: "H" },
        { name: "Preparing", value: "P" },
        { name: "Ready", value: "R" },
        { name: "in Transit", value: "T" },
      ],
      employeeType: "",
      orderStatus: "",
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
            this.refreshOrderData();
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
      if (user.type == "ED") {
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
        order = this.activeOrders.find(
          (order) => order.prepared_by == employee.id
        );
        employee.currentOrder = order;
      }
      if (employee.type == "ED") {
        order = this.activeOrders.find(
          (order) => order.delivered_by == employee.id
        );
        employee.currentOrder = order;
      }

      if (!order) {
        axios
          .get(`api/employee/${employee.id}/currentOrder`)
          .then((response) => {
            employee.currentOrder = response.data;
            console.log(employee);
          });
      }
    },
    refreshEmployeeData() {
      let employeesWithActiveOrders = this.employees.filter(
        (e) => e.logged_at && !e.available_at
      );
      console.log(employeesWithActiveOrders);
      if (employeesWithActiveOrders) {
        employeesWithActiveOrders.forEach((employee) => {
          this.getCurrentOrder(employee);
        });
      }
    },
    refreshOrderData() {
      this.activeOrders.forEach((order) => {
        let employee = undefined;
        if (order.delivered_by) {
          employee = this.employees.find(
            (employee) => order.delivered_by == employee.id
          );
          if (!employee) {
            axios.get(`api/users/${order.delivered_by}`).then((response) => {
              employee = response.data.data;
            });
          }
        } else {
          employee = this.employees.find(
            (employee) => order.prepared_by == employee.id
          );
          if (!employee) {
            axios.get(`api/users/${order.prepared_by}`).then((response) => {
              employee = response.data.data;
            });
          }
        }
        order.currentEmployee = employee;
      });
    },
  },
  computed: {
    filteredEmployees() {
      return this.employees.filter((employee) => {
        return employee.type == this.employeeType || this.employeeType == "";
      });
    },
    filteredOrders() {
      return this.activeOrders.filter((order) => {
        return order.status == this.orderStatus || this.orderStatus == "";
      });
    },
  },
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