<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { sendAJAX } from "../composable";
import { ElMessage } from "element-plus";
import "element-plus/es/components/message/style/css";

const contacts = ref([]);
const deleteID = ref("");
const router = useRouter();
const dialogVisible = ref(false);
const loading = ref(true);

const getAllContacts = () => {
  sendAJAX("get_all_contacts", {}, ({ success, data }) => {
    if (success) {
      contacts.value = data.contacts;
      loading.value = false;
    } else {
      ElMessage({
        message: "Could not get contacts - " + data.error,
        type: "error",
      });
    }
  });
};

getAllContacts();

const handleAddNew = () => {
  router.push({ name: "Add New Contact" });
};

const handleEdit = (id) => {
  router.push({ name: "Edit Contact", params: { id } });
};

const handleDelete = (id) => {
  deleteID.value = id;
  dialogVisible.value = true;
};

const confirmDelete = () => {
  sendAJAX("delete_contact", { id: deleteID.value }, ({ success, data }) => {
    if (success) {
      ElMessage({
        message: "Contact Deleted",
        type: "success",
      });
      getAllContacts();
    } else {
      ElMessage({
        message: "Could not delete contact - " + data.error,
        type: "error",
      });
    }
  });

  dialogVisible.value = false;
};
</script>

<template>
  <el-row>
    <p class="message">
      Use shortcode <pre>[contact-manager]</pre> to render contacts table. Specify ID with
      <pre>id</pre> attribute to render a specific contact card. Example:
      <pre>[contact-manager id="7"]</pre> to render contact card with ID 7.
    </p>
  </el-row>
  <el-row>
    <p class="message">
      Use shortcode <pre>[contact-form]</pre> to render a contact form where visitors can
      add new contacts.
    </p>
  </el-row>
  <el-row>
    <h2 class="space-after">Contacts List</h2>
    <el-col class="button_center" :span="6">
      <el-button type="primary" @click="handleAddNew">Add new contact</el-button>
    </el-col>
  </el-row>
  <el-row>
    <el-col>
      <el-table v-loading="loading" :data="contacts" style="width: 100%">
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
  <el-dialog v-model="dialogVisible" title="Tips" width="30%">
    <span>Are you sure you want to delete contact with ID {{ deleteID }}?</span>
    <template #footer>
      <span class="dialog-footer">
        <el-button @click="dialogVisible = false">Cancel</el-button>
        <el-button type="danger" @click="confirmDelete"> Confirm </el-button>
      </span>
    </template>
  </el-dialog>
</template>

<style>
.space-after {
  margin-right: 1rem;
}

.button_center {
  align-self: center;
}

.message {
  line-height: .1rem;
}

.message pre {
  display: inline-block;
}
</style>
