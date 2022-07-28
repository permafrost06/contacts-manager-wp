<script setup>
import { ElMessage } from "element-plus";
import { ref, onBeforeMount } from "vue";
import { getSetting, updateSetting } from "../composable";

const settings = ref({
  table_limit: null,
  table_order_by: "",
  background_color: "",
});

onBeforeMount(async () => {
  for (let setting in settings.value) {
    settings.value[setting] = await getSetting(setting);
  }
});

const onSubmit = () => {
  for (let setting in settings.value) {
    if (!updateSetting(setting, settings.value[setting])) {
      ElMessage({
        message: "Settings could not be updated",
        type: "error",
      });

      return;
    }
  }

  ElMessage({
    message: "Settings updated successfully",
    type: "success",
  });
};

const options = ref([
  { label: "ID", value: "id" },
  { label: "Name", value: "name" },
  { label: "Email", value: "email" },
  { label: "Phone", value: "phone" },
  { label: "Address", value: "address" },
]);
</script>

<template>
  <el-form @submit.prevent="onSubmit" :model="settings" label-width="300px">
    <el-form-item prop="table_limit" label="Table items to show in one page">
      <el-col :span="3">
        <el-input-number v-model="settings.table_limit" :min="5" :max="20" />
      </el-col>
    </el-form-item>
    <el-form-item prop="table_order_by" label="Order table items by">
      <el-col :span="3">
        <el-select v-model="settings.table_order_by">
          <el-option
            v-for="item in options"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
      </el-col>
    </el-form-item>
    <el-form-item
      prop="background_color"
      label="Background color of table and contact card"
    >
      <el-col :span="3">
        <el-color-picker v-model="settings.background_color" />
      </el-col>
    </el-form-item>
    <el-form-item>
      <el-button native-type="submit" type="primary">Save settings</el-button>
      <router-link :to="{ name: 'Contacts Table' }">
        <el-button>Go back</el-button>
      </router-link>
    </el-form-item>
  </el-form>
</template>
