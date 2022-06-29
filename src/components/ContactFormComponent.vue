<script setup>
import { reactive } from "vue";

const emit = defineEmits(["form-submit"]);

const props = defineProps({
  contact: {
    default: reactive({
      id: "",
      name: "",
      email: "",
      phone: "",
      address: "",
    }),
  },
});

const onSubmit = () => {
  emit("form-submit", props.contact);
};
</script>

<template>
  <el-form @submit.prevent :model="props.contact" label-width="100px">
    <el-form-item label="Name">
      <el-col :span="8">
        <el-input v-model="props.contact.name" />
      </el-col>
    </el-form-item>
    <el-form-item label="Email">
      <el-col :span="8">
        <el-input v-model="props.contact.email" />
      </el-col>
    </el-form-item>
    <el-form-item label="Phone no.">
      <el-col :span="8">
        <el-input v-model="props.contact.phone" />
      </el-col>
    </el-form-item>
    <el-form-item label="Address">
      <el-col :span="8">
        <el-input v-model="props.contact.address" type="textarea" />
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
