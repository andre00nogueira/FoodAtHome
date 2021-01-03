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
            <th>Shift Start</th>
            <th>Current order ID</th>
            <th>Started Preparaton/Delivery</th>
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
              {{ user.current_order ? user.current_order.id : "-" }}
            </td>
            <td>
              {{
                user.current_order ? user.current_order.current_status_at : "-"
              }}
            </td>
            <td>
              <router-link
                v-if="user.current_order.id"
                :to="`/orders/${user.current_order.id}`"
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
    <div v-if="filteredOrders.length">
      <div class="text-right">
        <select
          v-model="orderStatus"
          class="custom-select"
          id="orderTypeFilter"
        >
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
      <table id="activeOrders" class="table table-striped">
        <thead>
          <tr>
            <th>Id</th>
            <th>Status</th>
            <th>Current Employee Name</th>
            <th>Time Current Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="order of filteredOrders" :key="order.id">
            <td>{{ order.id }}</td>
            <td>{{ order.status }}</td>
            <td v-if="order.status != 'D' && order.status != 'R'">
              {{ order.current_employee ? order.current_employee.name : "-" }}
            </td>
            <td v-else>-</td>
            <td>{{ order.current_status_at }}</td>
            <td>
              <router-link :to="`/orders/${order.id}`">Details</router-link>
              <button class="btn btn-link" @click="cancelOrder(order.id)">
                Cancel Order
              </button>
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
      employeesPage: 1,
      ordersPage: 1,
    };
  },
  created() {
    axios.get("api/employees").then((response) => {
      if (response) {
        this.employeesData = response.data;
        this.employees = this.employeesData.data;
        console.log(this.employees);
        axios.get("api/orders/active").then((response) => {
          if (response) {
            this.activeOrdersData = response.data;
            this.activeOrders = this.activeOrdersData.data;
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
      this.employeesPage = page;
      let url = `api/employees`;
      if (page != 0) {
        this.page = page;
        url += `?page=${page}`;
        axios.get(url).then((response) => {
          this.employeesData = response.data;
          this.employees = this.employeesData.data;
        });
      }
    },
    getResultsOrders(page = 1) {
      this.ordersPage = page;
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
    cancelOrder(orderID) {
      let orderIndex = this.activeOrders.findIndex((o) => o.id == orderID);
      if (orderIndex != -1) {
        this.getResultsOrders(this.ordersPage);
      }
      axios
        .patch(`api/orders/${orderID}`, {
          status: "C",
        })
        .then((response) => {
          let order = response.data.data;
          let payload = {
            userId: order.customer_id,
            status: "C",
            orderId: order.id,
          };
          this.$socket.emit("order_status", payload);
          let payloadToCancel = {
            employeeId: "",
            employeeType: "",
            orderId: order.id,
          };
          if (order.delivered_by) {
            payloadToCancel.employeeId = order.delivered_by;
            payloadToCancel.employeeType = "ED";
            this.$socket.emit("order_cancelled", payloadToCancel);
          } else if (order.prepared_by) {
            payloadToCancel.employeeId = order.prepared_by;
            payloadToCancel.employeeType = "EC";
            this.$socket.emit("order_cancelled", payloadToCancel);
          }
          //atualizar lista de employees se necessário
          let employeeIndex = this.employees.findIndex((e) => {
            if (e.current_order) {
              return e.current_order.id == orderID;
            }
            return false;
          });
          if (employeeIndex != -1) {
            this.getResults(this.employeesPage);
          }
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
    new_order(orderID) {
      this.getResultsOrders(this.ordersPage);
      this.getResults(this.employeesPage);
    },
    order_status(orderID) {
      console.log("order status");
      console.log(orderID);
      let orderIndex = this.activeOrders.findIndex((o) => o.id == orderID);
      if (orderIndex != -1) {
        this.getResultsOrders(this.ordersPage);
      }
      let employeeIndex = this.employees.findIndex((e) => {
        if (e.current_order) {
          return e.current_order.id == orderID;
        }
        return false;
      });
      if (employeeIndex != -1) {
        this.getResults(this.employeesPage);
      }
    },
    refresh_user(userID) {
      let employeeIndex = this.employees.findIndex((e) => {
        console.log(e);
        return e.id == userID;
      });
      console.log(employeeIndex);
      if (employeeIndex != -1) {
        this.getResults(this.employeesPage);
      }
    },
  },
  components: { navbar },
};
</script>

<style>
</style>