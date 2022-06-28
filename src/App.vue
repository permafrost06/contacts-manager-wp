<script setup></script>

<template>
  <div class="common-layout">
    <el-header>
      <el-row>
        <el-col><h1>Vue.js is working</h1></el-col>
      </el-row>
    </el-header>
    <el-main>
      <router-view></router-view>
    </el-main>
  </div>
</template>

<style>
.el-row {
  margin-bottom: 20px;
}
</style>
