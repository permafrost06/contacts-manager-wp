<script setup>
import { ref } from "vue";
import { useRoute } from "vue-router";
import { getAJAX, postAJAX } from "../composable";
import { ElMessage } from "element-plus";
import "element-plus/es/components/message/style/css";
import ContactForm from "../components/ContactFormComponent.vue";

const route = useRoute();

const oldContact = ref({});

await getAJAX("get_contact", { id: route.params.id }, ({ success, data }) => {
  if (success) {
    oldContact.value = data.contact;
  }
});

const onSubmit = (contact) => {
  postAJAX("update_contact", contact, ({ success, data }) => {
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
  <div>
    <h2>Edit Contact</h2>
    <ContactForm
      :contact="oldContact"
      button-text="Update Contact"
      @form-submit="onSubmit"
    />
  </div>
</template>
