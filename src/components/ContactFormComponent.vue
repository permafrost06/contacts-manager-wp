<script setup>
import { reactive, ref } from "vue";
import { ElMessage } from "element-plus";
import { getAJAX } from "../composable";

const emit = defineEmits(["form-submit"]);

const formEl = ref();

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
  formEl.value.validate((valid) => {
    if (valid) {
      emit("form-submit", props.contact);
    } else {
      ElMessage({
        message: "Please fix the errors to submit the form",
        type: "error",
      });
    }
  });
};

const validateNumber = (rule, value, callback) => {
  if (/^[-+ ()\d]+$/.test(value)) {
    callback();
  } else {
    callback(new Error("Please enter a valid phone number"));
  }
};

const checkEmailExists = (rule, email, callback) => {
  getAJAX("check_email_exists", { email }, ({ success, data }) => {
    if (data) {
      callback(new Error("Email already exists"));
    } else {
      callback();
    }
  });
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
    {
      validator: checkEmailExists,
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
      min: 5,
      max: 20,
      message: "Phone number length must be between 5 and 20",
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
    ref="formEl"
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
      <el-button native-type="submit" type="primary">
        {{ props.buttonText }}
      </el-button>
      <router-link :to="{ name: 'Contacts Table' }">
        <el-button>Cancel</el-button>
      </router-link>
    </el-form-item>
  </el-form>
</template>
