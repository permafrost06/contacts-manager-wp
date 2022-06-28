<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { sendAJAX } from "../composable";

const contacts = ref([]);
const error = ref("");
const router = useRouter();

sendAJAX("get_all_contacts", {}, (res) => {
  if (res.success) {
    contacts.value = res.data.contacts;
  } else {
    error.value = res.data.error;
  }
});

const handleAddNew = () => {
  router.push({ name: "Add New Contact" });
};

const handleEdit = (id) => {
  console.log(id);
};

const handleDelete = (id) => {
  console.log(id);
};
</script>

<template>
  <el-row>
    <el-col :span="3">
      <h2>Contacts List</h2>
    </el-col>
    <el-col class="button_center" :span="6">
      <el-button @click="handleAddNew">Add new contact</el-button>
    </el-col>
    <el-col>
      <el-table :data="contacts" style="width: 100%">
        <el-table-column prop="id" label="id" width="40" />
        <el-table-column prop="name" label="Name" width="200" />
        <el-table-column prop="email" label="Email" />
        <el-table-column prop="phone" label="Phone no." />
        <el-table-column prop="address" label="Address" />
        <el-table-column label="Operations">
          <template #default="{ row }">
            <el-button size="small" @click="handleEdit(row.id)">Edit</el-button>
            <el-button size="small" type="danger" @click="handleDelete(row.id)"
              >Delete</el-button
            >
          </template>
        </el-table-column>
      </el-table>
    </el-col>
  </el-row>
</template>

<style>
.button_center {
  align-self: center;
}
</style>
