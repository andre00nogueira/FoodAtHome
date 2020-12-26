<template>
    <div>
    <table id="tableItems" class="table table-striped">
          <thead>
            <tr>
              <th />
              <th>Name</th>
              <th>Quantity</th>
              <th>Sub Total</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item of items" :key="item.id">
              <td>
                <img
                  id="itemPhoto"
                  :src="'storage/products/' + item.photo_url"
                />
              </td>
              <td>{{ item.name }}</td>
              <td>{{ item.quantity }}</td>
              <td>{{ item.sub_total_price }}€</td>
            </tr>
          </tbody>
    </table>
    </div>
</template>

<script>
export default {
    props:['items']
}
</script>

<style>

</style>