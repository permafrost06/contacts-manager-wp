<script setup>
import { ref } from "vue";
import { sendAJAX } from "../composable";

const message = ref("");
const input = ref("");

const sendAjaxRequest = () => {
  sendAJAX(
    "ajax_test",
    { message: input.value },
    (data) => {
      if (data.success) {
        message.value = "AJAX sent success";
      } else {
        message.value = "AJAX sent error";
      }
    },
    () => {
      message.value = "AJAX request failed";
    }
  );
};
</script>

<template>
  <el-row>
    <el-col>
      <el-button type="success">element-plus is working</el-button>
    </el-col>
  </el-row>
  <el-row>
    <el-col :span="6">
      <el-input v-model="input" placeholder="test will return success" />
    </el-col>
  </el-row>
  <el-row>
    <el-col :span="6">{{ message }}</el-col>
    <el-col @click="sendAjaxRequest"
      ><el-button type="primary">Test AJAX</el-button></el-col
    >
  </el-row>
</template>
