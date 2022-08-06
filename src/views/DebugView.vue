<script setup>
import { ref } from "vue";
import { faker } from "@faker-js/faker";
import { postAJAX } from "../composable/index.js";

const createRandomContact = () => {
  return {
    name: faker.name.firstName() + " " + faker.name.lastName(),
    email: faker.internet.email(),
    phone: faker.phone.number("+# (###) ###-####"),
    address: faker.address.streetAddress(),
  };
};

const contactsNum = ref(10);

const numMinus = () => {
  if (contactsNum.value > 1) contactsNum.value -= 1;
};

const numPlus = () => {
  contactsNum.value += 1;
};

const createContacts = async () => {
  Array.from({ length: contactsNum.value }).forEach(async () => {
    await postAJAX(
      "add_contact",
      createRandomContact(),
      ({ success, data }) => {
        if (!success) {
          ElMessage({
            message: "Could not add contact - " + data.error,
            type: "error",
          });
        }
      }
    );
  });
  ElMessage({
    message: `Added ${contactsNum.value} contacts`,
    type: "success",
  });
};
</script>

<template>
  <el-button @click="numMinus">-</el-button>
  <el-button @click="createContacts"
    >Generate {{ contactsNum }} contacts</el-button
  >
  <el-button @click="numPlus">+</el-button>
</template>
