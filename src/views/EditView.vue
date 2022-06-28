<script setup>
import { ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import { sendAJAX } from "../composable";

const router = useRouter();
const route = useRoute();

sendAJAX("get_contact", { id: route.params.id }, ({ success, data }) => {
  if (success) {
    newContact.value = data.contact;
  }
});

const newContact = ref({
  id: "",
  name: "",
  email: "",
  phone: "",
  address: "",
});

const onSubmit = () => {
  sendAJAX("update_contact", newContact.value, ({ success }) => {
    if (success) {
      router.push({ name: "Contacts Table" });
    }
  });
};
</script>

<template>
  <h2>Edit Contact</h2>
  <el-form @submit.prevent :model="newContact" label-width="100px">
    <el-form-item label="Name">
      <el-col :span="8">
        <el-input v-model="newContact.name" />
      </el-col>
    </el-form-item>
    <el-form-item label="Email">
      <el-col :span="8">
        <el-input v-model="newContact.email" />
      </el-col>
    </el-form-item>
    <el-form-item label="Phone no.">
      <el-col :span="8">
        <el-input v-model="newContact.phone" />
      </el-col>
    </el-form-item>
    <el-form-item label="Address">
      <el-col :span="8">
        <el-input v-model="newContact.address" type="textarea" />
      </el-col>
    </el-form-item>
    <el-form-item>
      <el-button type="primary" @click="onSubmit">Add Contact</el-button>
      <router-link :to="{ name: 'Contacts Table' }">
        <el-button>Cancel</el-button>
      </router-link>
    </el-form-item>
  </el-form>
</template>

<style>
.el-input > * > input[type="text"] {
  border: none;
  padding: 0;
}

.el-input > * > input[type="text"]:focus {
  box-shadow: none;
  outline: none;
}
</style>
