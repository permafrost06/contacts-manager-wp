<script setup>
import { ref, watch } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();

watch(
  () => route.name,
  (routeName) => {
    if (routeName == "Settings") heading.value = "Contacts Manager Settings";
    else heading.value = "Contacts Manager";
  }
);

const heading = ref("Contacts Manager");
</script>

<template>
  <div class="common-layout">
    <el-header>
      <el-row>
        <el-col
          ><h1>{{ heading }}</h1></el-col
        >
      </el-row>
    </el-header>
    <el-main>
      <router-view></router-view>
    </el-main>
  </div>
</template>

<style>
.el-input > * > input {
  border: none;
  background: none;
  padding: 0;
}

.el-input > * > input:focus {
  box-shadow: none;
  outline: none;
}

.el-input > * > input:focus-visible {
  outline: none;
}
</style>
