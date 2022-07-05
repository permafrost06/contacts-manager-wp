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
  buttonText: {
    type: String,
    default: "Add Contact",
  },
});

const onSubmit = () => {
  emit("form-submit", props.contact);
};

const validateNumber = (rule, value, callback) => {
  if (/^[-+ ()\d]+$/.test(value)) {
    callback();
  } else {
    callback(new Error("Please enter a valid phone number"));
  }
};

const formRules = reactive({
  name: [
    {
      required: true,
      message: "Please enter a name",
      trigger: "blur",
    },
  ],
  email: [
    {
      required: true,
      message: "Please enter an email address",
      trigger: "blur",
    },
    {
      type: "email",
      message: "Please enter a valid email address",
      trigger: ["blur", "change"],
    },
  ],
  phone: [
    {
      required: true,
      message: "Please enter a phone number",
      trigger: "blur",
    },
    {
      validator: validateNumber,
      trigger: ["blur", "change"],
    },
  ],
  address: [
    {
      required: true,
      message: "Please enter an address",
      trigger: "blur",
    },
  ],
});
</script>

<template>
  <el-form
    @submit.prevent="onSubmit"
    :model="props.contact"
    :rules="formRules"
    label-width="100px"
  >
    <el-form-item prop="name" label="Name">
      <el-col :span="8">
        <el-input v-model="props.contact.name" />
      </el-col>
    </el-form-item>
    <el-form-item prop="email" label="Email">
      <el-col :span="8">
        <el-input type="email" v-model="props.contact.email" />
      </el-col>
    </el-form-item>
    <el-form-item prop="phone" label="Phone no.">
      <el-col :span="8">
        <el-input v-model="props.contact.phone" />
      </el-col>
    </el-form-item>
    <el-form-item prop="address" label="Address">
      <el-col :span="8">
        <el-input v-model="props.contact.address" type="textarea" />
      </el-col>
    </el-form-item>
    <el-form-item>
      <el-button native-type="submit" type="primary">{{
        props.buttonText
      }}</el-button>
      <router-link :to="{ name: 'Contacts Table' }">
        <el-button>Cancel</el-button>
      </router-link>
    </el-form-item>
  </el-form>
</template>
