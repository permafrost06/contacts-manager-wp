<script setup>
import { ref } from "vue";
import { useRoute } from "vue-router";
import { sendAJAX } from "../composable";
import { ElMessage } from "element-plus";
import "element-plus/es/components/message/style/css";
import ContactForm from "../components/ContactFormComponent.vue";

const route = useRoute();

sendAJAX("get_contact", { id: route.params.id }, ({ success, data }) => {
  if (success) {
    oldContact.value = data.contact;
  }
});

const oldContact = ref({});

const onSubmit = (contact) => {
  sendAJAX("update_contact", contact, ({ success, data }) => {
    if (success) {
      ElMessage({
        message: "Contact updated",
        type: "success",
      });
    } else {
      ElMessage({
        message: "Could not update contact - " + data.error,
        type: "error",
      });
    }
  });
};
</script>

<template>
  <h2>Edit Contact</h2>
  <ContactForm
    :contact="oldContact"
    button-text="Update Contact"
    @form-submit="onSubmit"
  />
</template>
