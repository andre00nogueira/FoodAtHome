<template>
  <div v-if="customer">
    <div class="form-group">
      <label for="address">Address</label>
      <input
        type="text"
        class="form-control"
        name="address"
        id="address"
        v-model="customer.address"
      />
      <div v-if="errors && errors.address" class="text-danger">
        {{ errors.address[0] }}
      </div>
    </div>

    <div class="form-group">
      <label for="phone">Phone</label>
      <input
        type="text"
        class="form-control"
        name="phone"
        id="phone"
        v-model="customer.phone"
      />
      <div v-if="errors && errors.phone" class="text-danger">
        {{ errors.phone[0] }}
      </div>
    </div>

    <div class="form-group">
      <label for="nif">NIF</label>
      <input
        type="text"
        class="form-control"
        name="nif"
        id="nif"
        v-model="customer.nif"
      />
      <div v-if="errors && errors.nif" class="text-danger">
        {{ errors.nif[0] }}
      </div>
    </div>

  </div>
</template>
<script>
export default {
  props: ['customer', 'errors'],
};
</script>
<style>
</style>