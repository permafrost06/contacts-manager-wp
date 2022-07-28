<script setup></script>

<template>
  <div class="common-layout">
    <el-header>
      <el-row>
        <el-col><h1>Contacts Manager</h1></el-col>
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
