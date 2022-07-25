<script setup>
import { computed, ref } from "vue";
import { useRouter } from "vue-router";
import { getAJAX, postAJAX } from "../composable";
import { ElMessage } from "element-plus";
import "element-plus/es/components/message/style/css";
import CopyIcon from "../components/CopyIconComponent.vue";

const contacts = ref([]);
const deleteID = ref("");
const router = useRouter();
const dialogVisible = ref(false);
const loading = ref(true);

const getAllContacts = () => {
  getAJAX("get_all_contacts", {}, ({ success, data }) => {
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
  postAJAX("delete_contact", { id: deleteID.value }, ({ success, data }) => {
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

const currentPage = ref(1);
const pageSize = ref(10);

const contactsPage = computed(() => {
  return contacts.value.slice(
    pageSize.value * (currentPage.value - 1),
    pageSize.value * currentPage.value
  );
});

const copyShortcode = async (id) => {
  const shortcode = `[contacts-manager id="${id}"]`;
  await navigator.clipboard.writeText(shortcode);

  ElMessage({
    message: "Shortcode copied to clipboard",
    type: "success",
  });
};
</script>

<template>
  <el-row>
    <p class="message">
      Use shortcode <code>[contacts-manager]</code> to render contacts table.
      Specify ID with <code>id</code> attribute to render a specific contact
      card. Example: <code>[contacts-manager id="7"]</code> to render contact
      card with ID 7.
    </p>
  </el-row>
  <el-row>
    <p class="message">
      Use shortcode <code>[contact-form]</code> to render a contact form where
      visitors can add new contacts.
    </p>
  </el-row>
  <el-row justify="space-between">
    <el-col class="flex-align-center" :span="9">
      <h2 class="inline space-after">Contacts List</h2>
      <el-button type="primary" @click="handleAddNew"
        >Add new contact</el-button
      >
    </el-col>
    <el-pagination
      v-model:currentPage="currentPage"
      v-model:page-size="pageSize"
      :page-sizes="[10, 20, 30, 40]"
      background
      layout="sizes, total, prev, pager, next"
      :total="contacts.length"
    />
  </el-row>
  <el-row>
    <el-col>
      <el-table v-loading="loading" :data="contactsPage" style="width: 100%">
        <el-table-column prop="id" label="id" width="40" />
        <el-table-column prop="name" label="Name" min-width="16" />
        <el-table-column prop="email" label="Email" min-width="18" />
        <el-table-column prop="phone" label="Phone no." min-width="13" />
        <el-table-column prop="address" label="Address" min-width="14" />
        <el-table-column label="Operations" min-width="13">
          <template #default="{ row }">
            <el-button size="small" @click="handleEdit(row.id)">Edit</el-button>
            <el-button size="small" type="danger" @click="handleDelete(row.id)">
              Delete
            </el-button>
          </template>
        </el-table-column>
        <el-table-column label="Shortcode" min-width="22">
          <template #default="{ row }">
            <code>[contacts-manager id="{{ row.id }}"]</code>
            <el-button
              class="no-padding"
              size="small"
              @click="copyShortcode(row.id)"
            >
              <CopyIcon class="svg-button" />
            </el-button>
          </template>
        </el-table-column>
      </el-table>
    </el-col>
  </el-row>
  <el-row justify="end">
    <el-pagination
      v-model:currentPage="currentPage"
      v-model:page-size="pageSize"
      :page-sizes="[10, 20, 30, 40]"
      background
      layout="sizes, total, prev, pager, next"
      :total="contacts.length"
    />
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

.inline {
  display: inline-block;
}

.flex-align-center {
  display: flex;
  align-items: center;
}

.no-padding {
  padding: 0;
}
</style>
