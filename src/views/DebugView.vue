<script setup>
import { ref } from "vue";
import { faker } from "@faker-js/faker";
import { getAJAX, postAJAX } from "../composable/index.js";
import { ElMessage } from "element-plus";

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

const genPages = async () => {
  await getAJAX("debug_create_example_pages", {}, ({ success, data }) => {
    if (success) {
      ElMessage({
        message: "Pages created",
        type: "success",
      });
    } else {
      ElMessage({
        message: "Could not create pages",
        type: "error",
      });
    }
  });
};

const dropTable = async () => {
  await getAJAX("debug_drop_table", {}, ({ success, data }) => {
    if (success) {
      ElMessage({
        message: "Table dropped",
        type: "success",
      });
    } else {
      ElMessage({
        message: "Could not drop table",
        type: "error",
      });
    }
  });
};

const createTable = async () => {
  await getAJAX("debug_create_table", {}, ({ success, data }) => {
    if (success) {
      ElMessage({
        message: "Table creation query run",
        type: "success",
      });
    } else {
      ElMessage({
        message: "Could not execute query",
        type: "error",
      });
    }
  });
};
</script>

<template>
  <el-row style="margin-bottom: 20px">
    <el-button @click="numMinus">-</el-button>
    <el-button @click="createContacts"
      >Generate {{ contactsNum }} contacts</el-button
    >
    <el-button @click="numPlus">+</el-button>
  </el-row>

  <el-row style="margin-bottom: 20px">
    <el-button @click="genPages">Generate example pages</el-button>
  </el-row>

  <el-row>
    <el-button type="danger" @click="dropTable">Drop table</el-button>
    <el-button @click="createTable">Create table if not exists</el-button>
  </el-row>
</template>
