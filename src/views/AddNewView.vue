<script setup>
import { useRouter } from "vue-router";
import { sendAJAX } from "../composable";
import { ElMessage } from "element-plus";
import "element-plus/es/components/message/style/css";
import ContactForm from "../components/ContactFormComponent.vue";

const router = useRouter();

const onSubmit = (contact) => {
  sendAJAX("add_contact", contact, ({ success, data }) => {
    if (success) {
      ElMessage({
        message: "Contact added",
        type: "success",
      });
      router.push({ name: "Contacts Table" });
    } else {
      ElMessage({
        message: "Could not add contact - " + data.error,
        type: "error",
      });
    }
  });
};
</script>

<template>
  <h2>Add New Contact</h2>
  <ContactForm @form-submit="onSubmit" />
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
