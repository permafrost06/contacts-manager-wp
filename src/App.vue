<script setup>
import { onMounted, ref } from "vue";
import { sendAJAX } from "./composable";

const message = ref("");
const input = ref("");

const sendAjaxRequest = () => {
  sendAJAX(
    "ajax_test_invalid",
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
  <div class="common-layout">
    <el-header>
      <el-row>
        <el-col><h1>Vue.js is working</h1></el-col>
      </el-row>
    </el-header>
    <el-main>
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
    </el-main>
  </div>
</template>

<style>
.el-row {
  margin-bottom: 20px;
}
</style>
